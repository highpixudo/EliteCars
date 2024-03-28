<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title> EliteCars - Criar Conta </title>
    <link rel="stylesheet" href="../css/signup.css">
    <link rel="stylesheet" href="../css/nav_bar.css">
    <link rel="icon" href="assets/favicon.ico">
    <script src="/elitecars/atualizar_online.js"></script>
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
    <div class="main_div">
        <div class="title">Criar Conta</div>
        <form action="processar_registro.php" method="post">
            <div class="input_box">
                <input type="text" name="username" placeholder="Nome de utilizador" required>
                <div class="icon"><i class="fas fa-user"></i></div>
            </div>
            <div class="input_box">
                <input type="email" name="email" placeholder="Email" required>
                <div class="icon"><i class="fas fa-envelope"></i></div>
            </div>
            <div class="input_box">
                <input type="password" name="password" placeholder="Palavra-passe" required>
                <div class="icon"><i class="fas fa-lock"></i></div>
            </div>
            <div class="input_box">
                <input type="password" name="confirm_password" placeholder="Confirmar Palavra-passe" required>
                <div class="icon"><i class="fas fa-lock"></i></div>
            </div>
            <div class="input_box button">
                <input type="submit" value="Criar Conta">
            </div>
            <div class="sign_in">
                Já tem uma conta? <a href="../login">Iniciar Sessão</a>
            </div>
        </form>
    </div>
</body>

</html>