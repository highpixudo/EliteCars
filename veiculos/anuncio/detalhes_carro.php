<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/elitecars/css/detalhes_carro.css">
    <link rel="stylesheet" href="/elitecars/css/nav_bar.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="/elitecars/js/scripts.js"></script>
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
$current_section = isset($_GET['section']) ? $_GET['section'] : 'home';
?>

<body>
    <div class="topnav" id="myTopnav">
        <a href="/elitecars/" class="logo">ELITE</a>
        <div class="nav-buttons">
            <?php
            if (isset($_SESSION["username"])) {
                echo '<a href="/elitecars/" class="register" id="home">Início</a>';
                echo '<a href="/elitecars/veiculos" class="register" id="cars">Veículos</a>';
                echo '<a href="" class="register" id="about">Sobre</a>';
                echo '<a href="/elitecars/conta" class="register" id="account">Conta</a>';
            } else {
                echo '<a href="/elitecars/signup" class="register">Criar conta</a>';
                echo '<a href="/elitecars/login" class="login">Entrar</a>';
            }
            ?>
        </div>
    </div>

    <?php
    if (!isset($_SESSION["username"])) {
        echo '<h1 style="text-align: center;">Não tem sessão iniciada, por favor <a href="/elitecars/login">inicie sessão</a></h1>';
        return;
    }
    ?>

    <?php
    $car_id = $_GET['id'];

    $sql_detalhes = "SELECT * FROM carros WHERE id = $car_id";
    $result_detalhes = $conn->query($sql_detalhes);

    if ($result_detalhes && $row_detalhes = mysqli_fetch_assoc($result_detalhes)) {

        ?>
        <section class="seccao" style="background-color: #D9D9D9;">
            <div class="container flex">
                <div class="left">
                    <div class="main_image">
                        <?php echo '<img class="slide" src="../veiculos_fotos/' . $row_detalhes['foto'] . '.png" >'; ?>
                    </div>
                </div>
                <div class="right">
                    <?php echo '<h3>' . $row_detalhes['nome'] . '</h3>'; ?>
                    <h4> <small>$</small>999.99 </h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
                        et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                        aliquip ex ea commodo consequat. </p>

                    <button>Add to Bag</button>
                </div>
            </div>
        </section>

        <?php
    }
    ?>

</body>

</html>