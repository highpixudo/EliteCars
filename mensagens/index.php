<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/mensagens.css">
    <link rel="stylesheet" href="../css/nav_bar.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../js/scripts.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>EliteCars - Veículos</title>
</head>

<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "elitecars";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão com a base de dados: " . $conn->connect_error);
}

$secretKey = bin2hex(random_bytes(32));

session_start();

$sessionLifetime = 1800; // 30 minutos em segundos

// ajustar o tempo de vida da sessão se a checkbox "lembrar-me" estiver marcada
if (isset($_COOKIE['remember_me']) && !empty($_COOKIE['remember_me'])) {
    $sessionLifetime = 60 * 60 * 24 * 30; // 30 dias em segundos
}

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $sessionLifetime)) {
    // a sessão expirou
    session_unset();
    session_destroy();
}

$_SESSION['last_activity'] = time();
?>

<body>
    <div class="topnav" id="myTopnav">
        <a href="../" class="logo">ELITE</a>
        <div class="nav-buttons">
            <?php
            if (isset($_SESSION["username"])) {
                echo '<a href="../" class="register" id="home">Início</a>';
                echo '<a href="../veiculos" class="register" id="cars">Veículos</a>';
                echo '<a href="../mensagens" class="register" id="about">Mensagens</a>';
                echo '<a href="../conta" class="register" id="account">Conta</a>';
            } else {
                echo '<a href="../signup" class="register">Criar conta</a>';
                echo '<a href="../login" class="login">Entrar</a>';
            }
            ?>
        </div>
    </div>

    <?php
    if (!isset($_SESSION["username"])) {
        echo '<h1 style="text-align: center;">Não tem sessão iniciada, por favor <a href="../login">inicie sessão</a></h1>';
        return;
    }
    ?>

    <?php

    $username = $_SESSION["username"];

    $sql = "SELECT DISTINCT destinatario as contato FROM mensagens WHERE remetente = '$username'
        UNION
        SELECT DISTINCT remetente as contato FROM mensagens WHERE destinatario = '$username'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $contatos = array();

        while ($row = $result->fetch_assoc()) {
            $contatos[] = $row['contato'];
        }
    } else {
        $contatos = array();
    }
    ?>

    <div class="menu-lateral">
        <ul>
            <?php
            foreach ($contatos as $contato) {
                echo "<li><a href='?chat=$contato'>$contato</a></li>";
            }
            ?>
        </ul>
    </div>


    <div class="chat-content">
        <div class="messages-container">

            <?php
            $contato = isset($_GET['chat']) ? $_GET['chat'] : '';

            if (!empty($contato)) {
                $sqlValidContact = "SELECT * FROM mensagens WHERE (remetente = '$username' AND destinatario = '$contato') OR (remetente = '$contato' AND destinatario = '$username')";
                $resultValidContact = $conn->query($sqlValidContact);

                if ($resultValidContact->num_rows > 0) {
                    $sqlLoadMessages = "SELECT * FROM mensagens WHERE (remetente = '$username' AND destinatario = '$contato') OR (remetente = '$contato' AND destinatario = '$username')";
                    $resultLoadMessages = $conn->query($sqlLoadMessages);

                    $messages = array();

                    while ($row = $resultLoadMessages->fetch_assoc()) {
                        $messages[] = $row;
                    }
                } else {
                    echo "Contacto inválido.";
                    exit;
                }
            } else {
                echo "Selecione um contacto para começar o chat.";
                exit;
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mensagem'])) {
                $mensagem = $_POST["mensagem"];

                if (!empty($mensagem)) {
                    $sqlInsertMessage = "INSERT INTO mensagens (remetente, destinatario, mensagem) VALUES ('$username', '$contato', '$mensagem')";
                    $conn->query($sqlInsertMessage);

                    $resultLoadMessages = $conn->query($sqlLoadMessages);

                    $messages = array();

                    while ($row = $resultLoadMessages->fetch_assoc()) {
                        $messages[] = $row;
                    }
                }
            }

            $conn->close();
            ?>

            <?php
            foreach ($messages as $message) {
                $sender = $message['remetente'];
                $messageText = $message['mensagem'];
                echo "<div class='message'><strong>$sender:</strong> $messageText</div>";
            }
            ?>
        </div>

        <form method="post" action="">
            <div>
                <textarea name="mensagem" placeholder="Escreva a sua mensagem"></textarea>
            </div>
            <div>
                <button type="submit">Enviar</button>
            </div>
        </form>
    </div>

</body>