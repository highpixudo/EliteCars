<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/checkout.css">
    <link rel="stylesheet" href="../css/nav_bar.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../js/scripts.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel='stylesheet'
        href='https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css'>
    <title>EliteCars - Veículos</title>
</head>

<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "elitecars";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die ("Erro na conexão com a base de dados: " . $conn->connect_error);
}

$secretKey = bin2hex(random_bytes(32));

session_start();

$sessionLifetime = 1800; // 30 minutos em segundos

// ajustar o tempo de vida da sessão se a checkbox "lembrar-me" estiver marcada
if (isset ($_COOKIE['remember_me']) && !empty ($_COOKIE['remember_me'])) {
    $sessionLifetime = 60 * 60 * 24 * 30; // 30 dias em segundos
}

if (isset ($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $sessionLifetime)) {
    // a sessão expirou
    session_unset();
    session_destroy();
}

$_SESSION['last_activity'] = time();
$current_section = isset ($_GET['section']) ? $_GET['section'] : 'home';
?>

<body>
    <div class="topnav" id="myTopnav">
        <a href="../" class="logo">ELITE</a>
        <div class="nav-buttons">
            <?php
            if (isset ($_SESSION["username"])) {
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
    if (!isset ($_SESSION["username"])) {
        echo '<h1 style="text-align: center;">Não tem sessão iniciada, por favor <a href="../login">inicie sessão</a></h1>';
        return;
    }

    if (!isset ($_GET['id'])) {
        echo "<body><h1 style='text-align: center;'>Esta página não está disponível.</h1></body>";
        exit();
    }

    $car_id = $_GET['id'];

    $sql_detalhes = "SELECT * FROM carros WHERE id = $car_id";
    $result_detalhes = $conn->query($sql_detalhes);

    if ($result_detalhes && $row_detalhes = mysqli_fetch_assoc($result_detalhes)) {

        ?>

        <header>
            <div class="container">
                <div class="navigation">
                    <div class="secure">
                        <i class="icon icon-shield"></i>
                        <span>Pagamento Seguro</span>

                    </div>
                </div>
                <div class="notification">
                    Após completar o pagamento, será necessário entrar em contato com o vendedor para agendar
                    a retirada do veículo.

                </div>
                <div class="contact-vendor">
                    <div class="contact-image">
                        <img src="https://cdn-icons-png.flaticon.com/512/2815/2815428.png" alt="Foto do Vendedor">
                    </div>
                    <div class="contact-info">
                        <?php echo '<h2>@' . $row_detalhes["anunciante"] . '</h2> '; ?>
                        <p>Ultima vez online dia 12 de Fevereiro de 2023</p>
                    </div>
                    <div class="botao-contact">
                        <form action="/elitecars/mensagens?chat=<?php echo urlencode($row_detalhes["anunciante"]); ?>"
                            method="post">
                            <button type="submit">Contactar</button>
                        </form>

                    </div>
                </div>
            </div>
        </header>
        <section class="content">

            <div class="discount"></div>

            <div class="container">
                <div class="payment">
                    <div class="payment__title">
                        Metódos de pagamento
                    </div>
                    <div class="payment__types">
                        <div class="payment__type payment__type--cc active">
                            <i class="icon icon-credit-card"></i>Cartão de Crédito
                        </div>
                        <div class="payment__type payment__type--paypal">
                            <i class="icon icon-paypal"></i>Paypal
                        </div>
                        <div class="payment__type payment__type--paypal">
                            <i class="icon icon-wallet"></i>Crypto
                        </div>
                    </div>

                    <div class="payment__info">
                        <div class="payment__cc">
                            <div class="payment__title">
                                <i class="icon icon-user"></i>Informações pessoais
                            </div>
                            <form>
                                <div class="form__cc">
                                    <div class="row">
                                        <div class="field">
                                            <div class="title">Número do cartão
                                            </div>
                                            <input type="text" class="input txt text-validated"
                                                placeholder="4542 9931 9292 2293" name="card_number" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="field small">
                                            <div class="title">Data de validade
                                            </div>
                                            <select class="input ddl" name="expiry_month">
                                                <option selected>01</option>
                                                <option>02</option>
                                                <option>03</option>
                                                <option>04</option>
                                                <option>05</option>
                                                <option>06</option>
                                                <option>07</option>
                                                <option>08</option>
                                                <option>09</option>
                                                <option>10</option>
                                                <option>11</option>
                                                <option>12</option>
                                            </select>
                                            <select class="input ddl" name="expiry_year">
                                                <option>01</option>
                                                <option>02</option>
                                                <option>03</option>
                                                <option>04</option>
                                                <option>05</option>
                                                <option>06</option>
                                                <option>07</option>
                                                <option>08</option>
                                                <option>09</option>
                                                <option>10</option>
                                                <option>11</option>
                                                <option>12</option>
                                                <option>13</option>
                                                <option>14</option>
                                                <option>15</option>
                                                <option selected>16</option>
                                                <option>17</option>
                                                <option>18</option>
                                                <option>19</option>
                                                <option>20</option>
                                                <option>21</option>
                                                <option>22</option>
                                                <option>23</option>
                                                <option>24</option>
                                                <option>25</option>
                                                <option>26</option>
                                                <option>27</option>
                                                <option>28</option>
                                                <option>29</option>
                                                <option>30</option>
                                                <option>31</option>
                                            </select>
                                        </div>
                                        <div class="field small">
                                            <div class="title">CVV
                                            </div>
                                            <input type="text" class="input txt" name="cvv" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="field">
                                            <div class="title">Nome no cartão
                                            </div>
                                            <input type="text" class="input txt" />
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                        <div class="payment__shipping">
                            <div class="payment__title">
                                <i class="icon icon-plane"></i> Detalhes do pagamento
                            </div>
                            <div class="details__user">
                                <div class="user__name">Guilherme Catarino
                                    <br> 03/04/2006
                                </div>
                                <div class="user__address">Rua 123, Abrantes
                                    <br>Portugal
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="actions">

                    <a href="" class="btn action__submit">Efetuar pagamento
                        <i class="icon icon-arrow-right-circle"></i>
                    </a>
                    <a href="/elitecars/veiculos" class="backBtn">Voltar para o catálogo</a>

                </div>
        </section>
        </div>

        <?php
    }
    ?>

    <script>
        $(document).ready(function () {
            $('.action__submit').click(function (e) {
                e.preventDefault();

                var cardNumber = $('.txt.text-validated').val();
                var cvv = $('.input[name="cvv"]').val();
                var expiryMonth = $('.ddl[name="expiry_month"]').val();
                var expiryYear = $('.ddl[name="expiry_year"]').val();

                if (!isValidCardNumber(cardNumber)) {
                    alert('Número de cartão inválido');
                    return;
                }

                if (!isValidCVV(cvv)) {
                    alert('CVV inválido');
                    return;
                }

                if (!isValidExpiryDate(expiryMonth, expiryYear)) {
                    alert('Data de validade inválida');
                    return;
                }

                $(this).addClass('processing');

                var formData = $('form').serialize();
                $.ajax({
                    type: 'POST',
                    url: 'processar_pagamento.php',
                    data: formData,
                    success: function (response) {
                        alert("Compra efetuada com sucesso.")
                        window.location.href = '/elitecars/';
                    },
                    error: function (xhr, status, error) {
                        console.error('Erro na requisição AJAX: ' + xhr.responseText);
                        alert('Ocorreu um erro ao processar o pagamento. Por favor, tente novamente.');
                        $('.action__submit').removeClass('processing');
                    }
                });
            });
        });

        function isValidCVV(cvv) {
            return /^\d{3,4}$/.test(cvv);
        }

        function isValidExpiryDate(expiryMonth, expiryYear) {
            var currentDate = new Date();
            var currentYear = currentDate.getFullYear() % 100;
            var currentMonth = currentDate.getMonth() + 1;

            var expiryMonthInt = parseInt(expiryMonth, 10);
            var expiryYearInt = parseInt(expiryYear, 10);
            return (expiryYearInt > currentYear || (expiryYearInt === currentYear && expiryMonthInt >= currentMonth));
        }


        function isValidCardNumber(cardNumber) {
            cardNumber = cardNumber.replace(/\D/g, '');

            if (cardNumber.length !== 16) {
                return false;
            }

            // algoritmo de Luhn
            var sum = 0;
            for (var i = 0; i < 16; i++) {
                var digit = parseInt(cardNumber[i]);
                if (i % 2 === 0) {
                    digit *= 2;
                    if (digit > 9) {
                        digit -= 9;
                    }
                }
                sum += digit;
            }

            return sum % 10 === 0;
        }


    </script>

</body>