<!DOCTYPE html>
<html lang="pt-pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EliteCars - Página inicial</title>
    <link rel="stylesheet" href="css/pagina_inicial.css">
    <link rel="stylesheet" href="css/blinking-arrow.css">
    <link rel="stylesheet" href="css/footer.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="assets/favicon.ico">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
?>


<body>
    <script src="js/scripts.js"></script>
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
                echo '<a href="signup.php" class="register" id="create">Criar conta</a>';
                echo '<a href="login.php" class="login" id="login">Entrar</a>';
            }
            ?>
        </div>
    </div>
    <div class="container">

        <div class="text">
            <p class="texto-main">Salve até <span class="highlight">15%</span> com o novo modelo Tesla S.
                Aproveite a próxima geração de veículos elétricos.</p>

            <div class="buttons">
                <a href="#" class="button_comprar">Comprar agora</a>
                <a href="#" class="button_testdrive">Test drive</a>
            </div>
        </div>

        <div class="image">
            <img src="assets/tesla.png" alt="Descrição da Imagem">
        </div>
    </div>

    <section id="section" class="demo">
        <a href="javascript:void(0);" onclick="scrollToProdutosDestaque();"><span></span></a>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            function scrollToProdutosDestaque() {
                var container = document.querySelector('.container');
                var produtosDestaque = document.getElementById('produtos-destaque');

                if (container && produtosDestaque) {
                    var containerPosition = container.offsetTop;
                    var produtosDestaquePosition = produtosDestaque.offsetTop + containerPosition - 120;

                    window.scrollTo({
                        top: produtosDestaquePosition,
                        behavior: 'smooth'
                    });
                }
            }

            var linkToProdutosDestaque = document.querySelector('#section a');
            if (linkToProdutosDestaque) {
                linkToProdutosDestaque.addEventListener('click', scrollToProdutosDestaque);
            }
        });
    </script>

    <div class="produtos" id="produtos-destaque">
        <h1 class="product-title">Carros em destaque</h1>
        <div class="product-section">
            <form action="checkout.php?produto=boladeagua" method="post">
                <div class="product">
                    <div class="product-img">
                        <img src="assets/ferrari.png">
                        <button type="submit" class="comprar-produto-destaque">Comprar</button>
                        <button type="submit">Test drive</button>
                    </div>
                    <div class="product-info">
                        <p class="product-name">Ferrari 488 GTB</p>
                        <p class="product-details-ferrari">
                            A Ferrari 488 GTB é um carro desportivo de alto desempenho lançado em 2015 como sucessor do
                            458 Italia. Destaca-se pelo seu motor V8 de 3.9 litros biturbo e transmissão de dupla
                            embreagem
                            de sete velocidades, proporcionando uma aceleração de 0 a 100 km/h em pouco mais de 3
                            segundos.
                        </p>
                        <div class="price">
                            <span style="--i:1">2</span>
                            <span style="--i:2">5</span>
                            <span style="--i:3">0</span>
                            <span>,</span>
                            <span style="--i:5">0</span>
                            <span style="--i:6">0</span>
                            <span style="--i:7">0</span>
                            <span>€</span>
                        </div>
                    </div>
                </div>
                <div class="product">
                    <div class="product-info">
                        <p class="product-name">Porsche Panamera</p>
                        <p class="product-details-porsche">

                            O Porsche Panamera é uma berlina desportiva de luxo que equilibra o desempenho excecional da
                            Porsche com o conforto de um sedã de quatro portas. Lançado em 2009, destaca-se por uma
                            variedade de motores potentes, transmissão automática de dupla embreagem para trocas
                            rápidas, e um design elegante. Com aceleração impressionante, capaz de atingir 0 a 100 km/h
                            em tempos rápidos, o Panamera oferece uma experiência de condução emocionante. O seu
                            interior
                            luxuoso, repleto de tecnologia, complementa o compromisso da Porsche com desempenho, estilo
                            e sofisticação.
                        </p>
                        <div class="price">
                            <span style="--i:1">1</span>
                            <span style="--i:2">3</span>
                            <span style="--i:3">4</span>
                            <span>,</span>
                            <span style="--i:5">3</span>
                            <span style="--i:6">4</span>
                            <span style="--i:7">1</span>
                            <span>€</span>
                        </div>
                    </div>
                    <div class="product-img">
                        <img src="assets/porsche-panamera.png">
                        <button type="submit" class="comprar-produto-destaque">Comprar</button>
                        <button type="submit">Test drive</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>

    </script>

    <footer>
        <div class="footer-container">
            <div id="map">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1294.9753809983342!2d-8.194707871012954!3d39.469602658830595!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd186c46a67577df%3A0xdc72caf339bdb1ac!2sCasa%20China!5e0!3m2!1sen!2spt!4v1703281512125!5m2!1sen!2spt"
                    width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="socialicons">
                <a href="https://www.instagram.com" target="_blank"><i class='bx bxl-instagram'></i></a>
                <a href="https://www.facebook.com" target="_blank"><i class='bx bxl-facebook-circle'></i></a>
                <a href="https://www.twitter.com" target="_blank"><i class='bx bxl-twitter'></i></a>

                <p>Email: suporte@elitecars.com</p>
                <p class="contacto">Contacto: +351 123 456 789</p>
                <p class="copyright">Copyright &copy; 2023, EliteCars. Todos os direitos reservados.</p>

                <script>
                    $(document).ready(function () {
                        var selectedValue = localStorage.getItem('language') || 'pt';

                        $('#language').on('change', function () {
                            var selectedLanguage = $(this).val();
                            changeLanguage(selectedLanguage, selectedValue);
                        });

                        window.changeLanguage = function (language) {
                            var username = $('.welcome-text').data('username');
                            if (language === 'en') {
                                $('.texto-main').html('Save up to <span class="highlight">15%</span> with the new Tesla Model S. Enjoy the next generation of electric vehicles.');
                                $('.button_comprar').text('Buy Now');
                                $('.button_testdrive').text('Test Drive');
                                $('.product-title').text('Featured Cars');
                                $('.product-details-ferrari').text('The Ferrari 488 GTB is a high-performance sports car launched in 2015 as a successor to the 458 Italia. It stands out for its 3.9-liter twin-turbo V8 engine and seven-speed dual-clutch transmission, providing acceleration from 0 to 100 km/h in just over 3 seconds.');
                                $('.product-details-porsche').text("The Porsche Panamera is a luxury sports saloon that balances exceptional Porsche performance with the comfort of a four-door sedan. Launched in 2009, it stands out for a variety of powerful engines, automatic dual-clutch transmission for quick shifts, and an elegant design. With impressive acceleration, capable of reaching 0 to 100 km/h in quick times, the Panamera offers an exhilarating driving experience. Its luxurious, technology-packed interior complements Porsche's commitment to performance, style and sophistication.");
                                $('.comprar-produto-destaque').text('Buy now');
                                $('#home').text('Home');
                                $('#cars').text('Cars');
                                $('#about').text('About');
                                $('#account').text('Account');
                                $('.contacto').text('Contact: +351 123 456 789');
                                $('.copyright').text('Copyright © 2023, EliteCars. All rights reserved.');
                                $('#texto-main-conta').html('<i class="bx bxs-home"></i> Home page');
                                $('#texto-info-conta').html('<i class= "bx bxs-user-account" ></i > Account information');
                                $('#texto-linguagem').html('<i class="bx bx-globe"></i>Change language');
                                $('#texto-sessao').html('<i class="bx bx-log-out"></i>Log out');
                                $('.welcome-text').html('Welcome back, <span class=\'nome_utilizador\'>' + username + '</span>');
                                $('.text-details-home').html('Manage your information, privacy and security in <span class= "elite" > Elite</span><span class="cars">Cars</span> services.');
                                $('.informacoes h3').text('Privacy and personalization');
                                $('.informacoes h4').text('View the data in your EliteCars Account and choose the activity you want to save to personalize your your EliteCars experience.');
                                $('.informacoes a').text('Manage data and privacy');
                                $('.seguranca h3').text('Language and region');
                                $('.seguranca h4').text('Consult and change the region of your Account and EliteCars services, as well as the language in which you view it.'); $('.seguranca a').text('Manage language and region');
                                $('#create').text('Sign up');
                                $('#login').text('Login');
                            } else {
                                $('.texto-main').html('Salve até <span class="highlight">15%</span> com o novo modelo Tesla S. Aproveite a próxima geração de veículos elétricos.</p>');
                                $('.button_comprar').text('Comprar agora');
                                $('.button_testdrive').text('Test drive');
                                $('.product-title').text('Carros em destaque');
                                $('.product-details-ferrari').text('A Ferrari 488 GTB é um carro desportivo de alto desempenho lançado em 2015 como sucessor do 458 Italia. Destaca-se pelo seu motor V8 de 3.9 litros biturbo e transmissão de dupla embreagem de sete velocidades, proporcionando uma aceleração de 0 a 100 km/h em pouco mais de 3 segundos.');
                                $('.product-details-porsche').text("O Porsche Panamera é uma berlina desportiva de luxo que equilibra o desempenho excecional da Porsche com o conforto de um sedã de quatro portas. Lançado em 2009, destaca-se por uma variedade de motores potentes, transmissão automática de dupla embreagem para trocas rápidas, e um design elegante. Com aceleração impressionante, capaz de atingir 0 a 100 km/h em tempos rápidos, o Panamera oferece uma experiência de condução emocionante. O seu interior luxuoso, repleto de tecnologia, complementa o compromisso da Porsche com desempenho, estilo e sofisticação.");
                                $('.comprar-produto-destaque').text('Comprar');
                                $('#home').text('Início');
                                $('#cars').text('Carros');
                                $('#about').text('Sobre');
                                $('#account').text('Conta');
                                $('.contacto').text('Contacto: +351 123 456 789');
                                $('.copyright').text('Copyright © 2023, EliteCars. Todos os direitos reservados.');
                                $('#texto-main-conta').html('<i class="bx bxs-home"></i>Página inicial');
                                $('#texto-info-conta').html('<i class= "bx bxs-user-account" ></i > Informações da conta');
                                $('#texto-linguagem').html('<i class="bx bx-globe"></i>Mudar linguagem');
                                $('#texto-sessao').html('<i class="bx bx-log-out"></i>Encerrar sessão');
                                $('.welcome-text').html('Bem vindo, <span class=\'nome_utilizador\'>' + username + '</span>');
                                $('.text-details-home').html('Faça a gestão das suas informações, da privacidade e da segurança nos serviços <span class= "elite" > Elite</span><span class="cars">Cars</span>.');
                                $('.informacoes h3').text('Privacidade e personalização');
                                $('.informacoes h4').text('Consulte os dados na sua Conta EliteCars e escolha a atividade que quer guardar para personalizar a sua experiência EliteCars.');
                                $('.informacoes a').text('Gerir dados e privacidade');
                                $('.seguranca h3').text('Linguagem e região');
                                $('.seguranca h4').text('Consulte e altere a região da sua Conta e dos serviços EliteCars, bem como a linguagem em que visualiza o mesmo.');
                                $('.seguranca a').text('Gerir linguagem e região');
                                $('#create').text('Criar conta');
                                $('#login').text('Entrar');
                            }
                        }

                        changeLanguage(selectedValue);
                    });
                </script>

                <div id="language-selector">
                    <select id="language" onchange="changeLanguage(this.value)">
                        <option value="pt" data-flag="en">Português</option>
                        <option value="en" data-flag="pt">English</option>
                    </select>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>