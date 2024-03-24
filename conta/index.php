<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/conta.css">
    <link rel="stylesheet" href="../css/nav_bar.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../js/scripts.js"></script>
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

    <div id="menu">
        <ul>
            <li><a href="?section=home" id="texto-main-conta" <?php if ($current_section === 'home')
                echo 'class="active" id="texto-main-conta"'; ?>><i class='bx bxs-home'></i>Página inicial</a></li>
            <li><a href="?section=account" id="texto-info-conta" <?php if ($current_section === 'account')
                echo 'class="active"'; ?>><i class='bx bxs-user-account'></i>Informações da conta</a></li>
            <li><a href="?section=reset_pass" id="texto-alterar-senha" <?php if ($current_section === 'reset_pass')
                echo 'class="active" id="texto-alterar-senha"'; ?>><i class='bx bxs-key'></i>Alterar senha</a></li>

            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "elitecars";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Erro na conexão com a base de dados: " . $conn->connect_error);
            }

            $sql = "SELECT adm FROM utilizadores WHERE user = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $_SESSION['username']);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();

            if ($row['adm'] === 1) {
                ?>
                <li><a href="?section=admin" id="admin" <?php if ($current_section === 'admin')
                    echo 'class="active"'; ?>><i
                            class='bx bxs-invader'></i>Admin</a></li>
                <?php
            }
            ?>

            <form id="encerrarForm" action="encerrar_sessão.php" method="post">
                <li><a href="#" id="texto-sessao" onclick="document.getElementById('encerrarForm').submit();"><i
                            class='bx bx-log-out'></i>Encerrar sessão</a></li>
            </form>

        </ul>
    </div>

    <div id="content">

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

                            $sql = 'SELECT foto FROM utilizadores WHERE user = ?';
                            $stmt = $conn->prepare($sql);

                            if ($stmt) {
                                $stmt->bind_param('s', $_SESSION['username']);

                                $stmt->execute();

                                $result = $stmt->get_result();

                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    $imagePath = $row['foto'];
                                    echo "<img id='previewImage' src='$imagePath' onclick='openFileInput()'>";
                                }

                                $stmt->close();
                            } else {
                                echo "Erro na preparação da consulta: " . $conn->error;
                            }

                            $conn->close();
                            ?>

                            <input type="file" name="file" id="fileInput" style="display: none;" onchange="previewImage()">
                        </div>

                        <div class="submit-container" id="submitContainer">
                            <button type="button" name="acao" id="submit-zezoca" class="hidden-submit"
                                onclick="enviar()">Enviar</button>
                            <button type="button" id="submit-cancelar" class="hidden-submit"
                                onclick="cancelar()">Cancelar</button>
                        </div>
                    </form>
                    <h2 class="welcome-text" data-username="<?php echo $_SESSION["username"]; ?>">Bem-vindo,
                        <span class="nome_utilizador">
                            <?php echo $_SESSION["username"]; ?>
                        </span>
                    </h2>

                    <h3 class="text-details-home">Faça a gestão das suas informações, da privacidade e da segurança nos serviços
                        <span class="elite">Elite</span><span class="cars">Cars</span>.
                    </h3>

                    <div class="container-infos">
                        <div class="informacoes">
                            <h3>Privacidade e personalização</h3>
                            <h4>Consulte os dados na sua Conta EliteCars e escolha a atividade que quer guardar para
                                personalizar a
                                sua
                                experiência EliteCars</h4>
                            <hr>
                            <a href="?section=account">Gerir dados e privacidade</a>
                        </div>

                        <div class="seguranca">
                            <h3>Segurança da conta</h3>
                            <h4>Consulte e altere a palavra-passe da sua Conta e dos serviços EliteCars, para garantir mais
                                segurança.</h4>
                            <hr>
                            <a href="?section=reset_pass">Mudar palavra-passe</a>
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
            case 'account':

                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "elitecars";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Erro na conexão com a base de dados: " . $conn->connect_error);
                }

                $sql = "SELECT nome, sobrenome, user, email, nacionalidade, num_tel FROM utilizadores WHERE user = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $_SESSION['username']);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $stmt->close();
                ?>
                <div class="account-info">
                    <div>
                        <h2>Definições de perfil</h2>
                    </div>

                    <form action="atualizar_dados.php" method="post">
                        <div id="content-account-info" class="flex-container">
                            <div class="adjust-labels">
                                <div class="form-group" id="editableInputnome">
                                    <label for="nome">Nome</label>
                                    <input type="text" name="nome" id="nome" value="<?php echo $row['nome']; ?>" readonly>
                                    <i class='bx bxs-edit' id="nome-edit" onclick="enableEdit('nome')"></i>
                                    <i class='bx bx-check' id="nome-check"
                                        style="display: none;position: absolute;top: 50%;right: 50px;color: #888;cursor: pointer;"
                                        onclick="confirmEdit('nome')"></i>
                                    <i class='bx bx-x' id="nome-cancel" style="display: none;"
                                        onclick="cancelEdit('nome', '<?php echo $row['nome']; ?>')"></i>
                                </div>

                                <div class="form-group" id="editableInputsobrenome">
                                    <label for="sobrenome">Sobrenome</label>
                                    <input type="text" name="sobrenome" id="sobrenome" value="<?php echo $row['sobrenome']; ?>"
                                        readonly>
                                    <i class='bx bxs-edit' id="sobrenome-edit" onclick="enableEdit('sobrenome')"></i>
                                    <i class='bx bx-check' id="sobrenome-check"
                                        style="display: none;position: absolute;top: 50%;right: 50px;color: #888;cursor: pointer;"
                                        onclick="confirmEdit('sobrenome')"></i>
                                    <i class='bx bx-x' id="sobrenome-cancel" style="display: none;"
                                        onclick="cancelEdit('sobrenome', '<?php echo $row['sobrenome']; ?>')"></i>
                                </div>
                            </div>

                            <div class="adjust-labels">
                                <div class="form-group" id="editableInputuser">
                                    <label for="user">Nome de utilizador</label>
                                    <input type="text" name="user" id="user" value="<?php echo $row['user']; ?>">
                                    <i class='bx bxs-edit' id="user-edit" onclick="enableEdit('user')"></i>
                                    <i class='bx bx-check' id="user-check"
                                        style="display: none;position: absolute;top: 50%;right: 50px;color: #888;cursor: pointer;"
                                        onclick="confirmEdit('user')"></i>
                                    <i class='bx bx-x' id="user-cancel" style="display: none;"
                                        onclick="cancelEdit('user', '<?php echo $row['user']; ?>')"></i>
                                </div>

                                <div class="form-group" id="editableInputmail">
                                    <label for="mail">Endereço de email</label>
                                    <input type="text" name="mail" id="mail" value="<?php echo $row['email']; ?>">
                                    <i class='bx bxs-edit' id="mail-edit" onclick="enableEdit('mail')"></i>
                                    <i class='bx bx-check' id="mail-check"
                                        style="display: none;position: absolute;top: 50%;right: 50px;color: #888;cursor: pointer;"
                                        onclick="confirmEdit('mail')"></i>
                                    <i class='bx bx-x' id="mail-cancel" style="display: none;"
                                        onclick="cancelEdit('mail', '<?php echo $row['email']; ?>')"></i>
                                </div>
                            </div>

                            <div class="adjust-labels">
                                <div class="form-group" id="editableInputnumtele">
                                    <label for="numtele">Numero de telefone</label>
                                    <input type="text" name="numtele" id="numtele" value="<?php echo $row['num_tel']; ?>">
                                    <i class='bx bxs-edit' id="numtele-edit" onclick="enableEdit('numtele')"></i>
                                    <i class='bx bx-check' id="numtele-check"
                                        style="display: none;position: absolute;top: 50%;right: 50px;color: #888;cursor: pointer;"
                                        onclick="confirmEdit('numtele')"></i>
                                    <i class='bx bx-x' id="numtele-cancel" style="display: none;"
                                        onclick="cancelEdit('numtele', '<?php echo $row['num_tel']; ?>')"></i>
                                </div>

                                <div class="form-group" id="editableInputnacionalidade">
                                    <label for="nacionalidade">Nacionalidade</label>
                                    <select name="nacionalidade" id="nacionalidade-select"></select>
                                </div>
                            </div>

                            <div class="buttons">
                                <button class="btn-atualizar">Atualizar</button>
                            </div>
                    </form>

                    <script>
                        function enableEdit(inputId) {
                            var inputElement = document.getElementById(inputId);

                            inputElement.removeAttribute('readonly');
                            inputElement.focus();
                            document.getElementById(inputId + '-edit').style.display = 'none';
                            document.getElementById(inputId + '-check').style.display = 'inline';
                            document.getElementById(inputId + '-cancel').style.display = 'inline';
                        }
                        function cancelEdit(inputId, originalValue) {
                            var inputElement = document.getElementById(inputId);

                            inputElement.value = originalValue;
                            inputElement.setAttribute('readonly', 'true');
                            document.getElementById(inputId + '-edit').style.display = 'inline';
                            document.getElementById(inputId + '-check').style.display = 'none';
                            document.getElementById(inputId + '-cancel').style.display = 'none';
                        }

                        function confirmEdit(inputId) {
                            var inputElement = document.getElementById(inputId);
                            var inputValue = inputElement.value;

                            if (inputId === 'mail' && !validateEmail(inputValue)) {
                                alert('Endereço de email inválido. Certifique-se de incluir "@".');
                                inputElement.focus();
                                return false;
                            }

                            if (inputId === 'numtele' && (!/^\d+$/.test(inputValue) || inputValue.length > 9)) {
                                alert('Número de telefone deve conter apenas números e ter no máximo 9 caracteres.');
                                inputElement.focus();
                                return false;
                            }

                            inputElement.setAttribute('readonly', 'true');
                            document.getElementById(inputId + '-edit').style.display = 'inline';
                            document.getElementById(inputId + '-check').style.display = 'none';
                            document.getElementById(inputId + '-cancel').style.display = 'none';
                        }

                        function validateEmail(email) {
                            var emailRegex = /\S+@\S+\.\S+/;
                            return emailRegex.test(email);
                        }
                    </script>


                    <script>
                        // API REST Countries
                        fetch('https://restcountries.com/v3.1/all')
                            .then(response => response.json())
                            .then(data => {
                                const selectElement = document.getElementById('nacionalidade-select');
                                data.forEach(country => {
                                    const option = document.createElement('option');
                                    option.value = country.name.common;
                                    option.text = country.name.common;
                                    selectElement.appendChild(option);
                                });

                                const existingValue = "<?php echo $row['nacionalidade']; ?>";
                                if (existingValue) {
                                    selectElement.value = existingValue;
                                }
                            })
                            .catch(error => console.error('Erro ao obter dados da API:', error));
                    </script>
                </div>
            </div>
            <?php
            break;
            case 'reset_pass':
                ?>
            <form action="alterar_senha.php" method="post">
                <div class="account-info">
                    <div>
                        <h2>Alteração de senha</h2>
                    </div>

                    <div class="content-reset">
                        <label for="senha">Senha atual</label>
                        <input type="password" name="senha-atual" required>
                    </div>

                    <div class="content-reset">
                        <label for="nova-senha">Nova senha</label>
                        <input type="password" id="senha-nova" name="senha-nova" oninput="checkPasswordStrength()" required>
                        <div id="password-strength-bar">
                            <div id="password-strength-fill"></div>
                        </div>

                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            checkPasswordStrength();
                            document.getElementById('senha-nova').addEventListener('input', checkPasswordStrength);
                        });


                        function checkPasswordStrength() {
                            var password = document.getElementById('senha-nova').value;
                            var strengthFill = document.getElementById('password-strength-fill');
                            var strength = 0;

                            if (/[A-Z]/.test(password)) {
                                strength += 1;
                            }

                            if (/\d/.test(password)) {
                                strength += 1;
                            }

                            if (password.length >= 8) {
                                strength += 1;
                            }

                            if (/[~`!#$%\^&*+=\-\[\]\\';,/{}|\\":<>\?()@._]/.test(password)) {
                                strength += 1;
                            }

                            var color = getColorForStrength(strength);
                            strengthFill.style.width = (strength * 25) + '%';
                            strengthFill.style.backgroundColor = color;
                        }

                        function getColorForStrength(strength) {
                            if (strength === 1) {
                                return '#ff0000'; // Vermelho
                            } else if (strength === 2) {
                                return '#ff9900'; // Laranja
                            } else if (strength === 3) {
                                return '#ffc222'; // Amarelo
                            } else if (strength === 4) {
                                return '#85c734'; // Verde
                            }
                        }



                    </script>

                    <div class="content-reset">
                        <label for="nova-senha-confirm">Confirme a nova senha</label>
                        <input type="password" name="senha-nova-confirm" required>
                    </div>

                    <div class="buttons">
                        <a href="conta.php" class="btn-redf">Cancelar</a>
                        <button type="submit" class="btn-atualizar">Atualizar</button>
                    </div>
                </div>
            </form>

            <?php
            break;
            case 'admin':
                $current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'comentarios';
                ?>

            <?php
            default:
                break;
        }
        ?>

    </div>

</body>

</html>