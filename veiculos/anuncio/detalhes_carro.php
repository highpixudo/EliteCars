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
        <div class="container">
            <div class="detail">
                <div class="image">
                    <?php echo '<img src="../veiculos_fotos/' . $row_detalhes['foto'] . '.png" >'; ?>
                </div>
                <div class="content">
                    <?php echo '<h1 class="name">' . $row_detalhes['nome'] . '</h1>';
                    $preco = number_format($row_detalhes['preco'], 0, ',', ' ');
                    $precoArray = str_split(str_replace(',', '', $preco));

                    echo '<div class="price">' . $preco . '€</div>'; ?>

                    <div class="description">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quos cupiditate autem
                        earum nostrum asperiores soluta laudantium, illum dolorem, molestiae error quam! Excepturi est quia
                        iusto distinctio nam aperiam repellendus ratione.</div>


                    <div class="icons">
                        <div class="icon-container">
                            <i class='bx bx-tachometer'></i>
                            <span class="tooltip">57 900 km</span>
                        </div>
                        <div class="icon-container">
                            <i class='bx bx-gas-pump'></i>
                            <span class="tooltip">Diesel</span>
                        </div>
                        <div class="icon-container">
                            <i class='bx bx-cog'></i>
                            <span class="tooltip">116 cv</span>
                        </div>
                        <div class="icon-container">
                            <i class='bx bx-calendar'></i>
                            <span class="tooltip">2022</span>
                        </div>
                    </div>

                    <div class="buttons">
                        <button>Test Drive</button>
                        <button>Comprar Agora
                            <span>
                                <svg class="" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 18 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 15a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 0h8m-8 0-1-4m9 4a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-9-4h10l2-7H3m2 7L3 4m0 0-.792-3H1" />
                                </svg>
                            </span>
                        </button>
                    </div>

                    <div class="favorito">
                        <a href="">
                            <div class="icon-container">
                                <i class='bx bx-heart'></i>
                                <span class="tooltip">Adicionar aos favoritos</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }
    ?>

</body>

</html>