<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/conta.css">
    <link rel="stylesheet" href="css/nav_bar.css">
    <link rel="stylesheet" href="">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="js/scripts.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>EliteCars - Conta</title>
</head>

<?php
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
$current_section = isset($_GET['section']) ? $_GET['section'] : 'home';
?>

<body>
    <div class="topnav" id="myTopnav">
        <a href="./" class="logo">ELITE</a>
        <div class="nav-buttons">
            <?php
            if (isset($_SESSION["username"])) {
                echo '<a href="./" class="register" id="home">Início</a>';
                echo '<a href="" class="register" id="cars">Carros</a>';
                echo '<a href="" class="register" id="about">Sobre</a>';
                echo '<a href="conta.php" class="register" id="account">Conta</a>';
            } else {
                echo '<a href="signup.php" class="register">Criar conta</a>';
                echo '<a href="login.php" class="login">Entrar</a>';
            }
            ?>
        </div>
    </div>

    <?php
    if (!isset($_SESSION["username"])) {
        echo '<h1 style="text-align: center;">Não tem sessão iniciada, por favor <a href="login.php">inicie sessão</a></h1>';
        return;
    }
    ?>

    <div id="menu">
        <ul>
            <li><a href="?section=home" id="texto-main-conta" <?php if ($current_section === 'home')
                echo 'class="active" id="texto-main-conta"'; ?>><i class='bx bxs-home'></i>Página inicial</a></li>
            <li><a href="?section=account" id="texto-info-conta" <?php if ($current_section === 'account')
                echo 'class="active"'; ?>><i class='bx bxs-user-account'></i>Informações da conta</a></li>
            <li><a href="?section=language" id="texto-linguagem" <?php if ($current_section === 'language')
                echo 'class="active"'; ?>><i class='bx bx-globe'></i>Mudar linguagem</a></li>
            <form id="encerrarForm" action="encerrar_sessão.php" method="post">
                <li><a href="#" id="texto-sessao" onclick="document.getElementById('encerrarForm').submit();"><i
                            class='bx bx-log-out'></i>Encerrar sessão</a></li>
            </form>

        </ul>
    </div>

    <?php

    switch ($current_section) {
        case 'home':
            ?>
            <div class="form-container">
                <form id="uploadForm" action="processar_envio.php" method="post" enctype="multipart/form-data">
                    <div class="image-upload-container">
                        <?php
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "elitecars";

                        $conn = new mysqli($servername, $username, $password, $dbname);

                        if ($conn->connect_error) {
                            die("Erro na conexão com a base de dados: " . $conn->connect_error);
                        }

                        $sql = 'SELECT foto FROM utilizadores WHERE user = \'' . $_SESSION['username'] . '\'';
                        $result = $conn->query($sql);

                        if ($result) {
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $imagePath = $row['foto'];
                                echo "<img id='previewImage' src='$imagePath' onclick='openFileInput()'>";
                            }
                        } else {
                            echo "Erro na consulta: " . $conn->error;
                        }

                        $conn->close();
                        ?>
                        <input type="file" name="file" id="fileInput" style="display: none;" onchange="previewImage()">
                    </div>

                    <div class="submit-container" id="submitContainer">
                        <button type="button" name="acao" id="submit-zezoca" class="hidden-submit"
                            onclick="enviar()">Enviar</button>
                        <button type="button" id="submit-cancelar" class="hidden-submit" onclick="cancelar()">Cancelar</button>
                    </div>
                </form>
                <h2 class="welcome-text" data-username="<?php echo $_SESSION["username"]; ?>">Bem-vindo,
                    <span class="nome_utilizador">
                        <?php echo $_SESSION["username"]; ?>
                    </span>
                </h2>

                <h3 class="text-details-home">Faça a gestão das suas informações, da privacidade e da segurança nos serviços
                    <span class="elite">Elite</span><span class="cars">Cars</span>.</h3>

                <div class="container-infos">
                    <div class="informacoes">
                        <h3>Privacidade e personalização</h3>
                        <h4>Consulte os dados na sua Conta EliteCars e escolha a atividade que quer guardar para personalizar a
                            sua
                            experiência EliteCars</h4>
                        <hr>
                        <a href="?section=account">Gerir dados e privacidade</a>
                    </div>

                    <div class="seguranca">
                        <h3>Linguagem e região</h3>
                        <h4>Consulte e altere a região da sua Conta e dos serviços EliteCars, bem como a linguagem em que
                            visualiza o mesmo.</h4>
                        <hr>
                        <a href="?section=language">Gerir linguagem e região</a>
                    </div>
                </div>
            </div>

            <script>
                function openFileInput() {
                    var submitButton = document.getElementById('submit-zezoca');
                    var cancelButton = document.getElementById('submit-cancelar');

                    document.getElementById('fileInput').click();
                    submitButton.classList.remove('hidden-submit');
                    cancelButton.classList.remove('hidden-submit');
                }

                function enviar() {
                    var form = document.getElementById('uploadForm');
                    var formData = new FormData(form);

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'processar_envio.php', true);

                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                document.getElementById('previewImage').src = 'uploads/' + response.username + '/' + response.filename;
                            } else {
                                console.error('Erro no envio do arquivo');
                            }
                        }
                    };

                    xhr.send(formData);

                    cancelar();
                }


                function cancelar() {
                    var submitButton = document.getElementById('submit-zezoca');
                    var cancelButton = document.getElementById('submit-cancelar');

                    submitButton.classList.add('hidden-submit');
                    cancelButton.classList.add('hidden-submit');
                    return false;
                }
            </script>

            <?php
            break;
        case 'language':
            ?>
            

            <?php
            break;
        default:
            break;
    }
    ?>

</body>

</html>