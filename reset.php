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
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "elitecars";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Erro na conexão: " . $conn->connect_error);
    }

    if (isset($_GET['token']) && !empty($_GET['token'])) {
        $token = $_GET['token'];

        $query = "SELECT email FROM reset_tokens WHERE token = '$token' AND tempo_expirar > NOW()";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            echo '<div class="main_div">
                <div class="title">Redefinir Senha</div>
                <form action="efetuar_reset.php?token=' . $token . '" method="post">
                    <div class="input_box">
                        <input type="password" name="new_password" placeholder="Nova Senha" required>
                        <div class="icon"><i class="fas fa-lock"></i></div>
                    </div>
                    <div class="input_box">
                        <input type="password" name="confirm_password" placeholder="Confirmar Senha" required>
                        <div class="icon"><i class="fas fa-lock"></i></div>
                    </div>
                    <div class="input_box button">
                        <input type="submit" value="Redefinir Senha">
                    </div>
                </form>
            </div>';
        } else {
            echo '<h1 style="text-align: center;">O token fornecido é inválido ou já expirou.</h1>';
        }
    } else {
        echo '<div class="main_div">
                    <div class="title">Redefinição de credenciais</div>
                    <form action="reset_pass.php" method="post">
                        <div class="input_box">
                            <input type="text" name="email" placeholder="Email usado na conta" required>
                            <div class="icon"><i class="fas fa-user"></i></div>
                        </div>
                        <div class="input_box button">
                            <input type="submit" value="Redefinir palavra-passe">
                        </div>
                    </form>
                </div>';
    }
    ?>
</body>

</html>