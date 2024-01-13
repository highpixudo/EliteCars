<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title> EliteCars - Login </title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/nav_bar.css">
    <link rel="icon" href="assets/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
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
    // A sessão expirou
    session_unset();
    session_destroy();
}

$_SESSION['last_activity'] = time();
?>

<body style="background-color: #D9D9D9;">
    <div class="topnav" id="myTopnav">
        <a href="./" class="logo">ELITE</a>
        <div class="nav-buttons">
            <?php
            if (isset($_SESSION["username"])) {
                echo '<a href="./" class="register" id="home">Início</a>';
                echo '<a href="veiculos.php" class="register" id="cars">Veículos</a>';
                echo '<a href="" class="register" id="about">Sobre</a>';
                echo '<a href="conta.php" class="register" id="account">Conta</a>';
            } else {
                echo '<a href="signup.php" class="register">Criar conta</a>';
                echo '<a href="login.php" class="login">Entrar</a>';
            }
            ?>
        </div>
    </div>

    <div class="main_div">
        <div class="title">Iniciar Sessão</div>
        <form action="processar_login.php" method="post">
            <div class="input_box">
                <input type="text" name="username" placeholder="Email ou nome de utilizador" required>
                <div class="icon"><i class="fas fa-user"></i></div>
            </div>
            <div class="input_box">
                <input type="password" name="password" placeholder="Palavra-passe" required>
                <div class="icon"><i class="fas fa-lock"></i></div>
            </div>
            <div class="option_div">
                <div class="check_box">
                    <input type="checkbox" name="remember_me">
                    <span>Lembrar-me</span>
                </div>
                <div class="forget_div">
                    <a href="reset.php">Esqueceu-se da senha?</a>
                </div>
            </div>
            <div class="input_box button">
                <input type="submit" value="Entrar">
            </div>
            <div class="sign_up">
                Não tem uma conta? <a href="signup.php">Criar conta</a>
            </div>
        </form>
    </div>
</body>

</html>