document.addEventListener("DOMContentLoaded", function () {
  var navbar = document.getElementById("myTopnav");

  window.onscroll = function () {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
      navbar.classList.add("scroll-bg");

    } else {
      navbar.classList.remove("scroll-bg");
    }
  };
});

$(document).ready(function () {
  var selectedLanguage = localStorage.getItem('selectedLanguage') || 'pt';

  changeLanguage(selectedLanguage);

  $('#change-language').on('click', function () {
    var newLanguage = $('#language-select').val();
    changeLanguage(newLanguage);
    localStorage.setItem('selectedLanguage', newLanguage);
  });

  function changeLanguage(language) {
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
      $('.seguranca h4').text('Consult and change the region of your Account and EliteCars services, as well as the language in which you view it.');
      $('.seguranca a').text('Manage language and region');
    } else {
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
    }
  }
});
