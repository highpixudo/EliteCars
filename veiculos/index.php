<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/veiculos.css">
    <link rel="stylesheet" href="../css/nav_bar.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../js/scripts.js"></script>
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
    <div class="veiculos">
        <div class="pesquisa">
            <input type="text" id="pesquisaTermo" name="pesquisaTermo" placeholder="O que procura?">
            <button type="button" id="pesquisa-button"><i class='bx bx-search-alt-2'></i></button>
        </div>


        <div class="categoria">
            <div class="form-group" id="editableInputnacionalidade">
                <label>Categoria</label>
                <div class="flex-container">
                    <select name="nacionalidade" id="nacionalidade-select">
                        <option value="carros">Carros</option>
                        <option value="motas">Motas</option>
                    </select>
                </div>
            </div>
            <div class="form-group-1" id="editableInputPreco">
                <label>Preço</label>
                <div class="flex-container">
                    <div>
                        <input type="text" name="preco_de" id="preco-de-input" placeholder="De">
                        <input type="text" name="preco_ate" id="preco-ate-input" placeholder="Até">
                    </div>
                </div>
            </div>
        </div>

        <hr class="line-vehicles">
        <div class="marca-modelo">
            <div class="form-group" id="editableInputmarca">
                <label>Marca</label>
                <div class="flex-container">
                    <?php
                    $sql = "SELECT DISTINCT marca FROM carros ORDER BY marca ASC";
                    $result = $conn->query($sql);
                    if ($result) {
                        echo '<select name="marca" id="marca-select">';
                        echo '<option value="mostrar_tudo">Mostrar Tudo</option>';

                        while ($linha = mysqli_fetch_assoc($result)) {
                            $marca = $linha['marca'];
                            echo '<option value="' . $marca . '">' . $marca . '</option>';
                        }
                        echo '</select>';
                    }
                    ?>
                </div>
            </div>
            <div class="form-group" id="editableInputmodelo">
                <label>Modelo</label>
                <div class="flex-container">
                    <select name="modelo" id="modelo-select">
                        <option value="mostrar_tudo1">Mostrar Tudo</option>
                    </select>
                </div>
            </div>
            <script>
                $(document).ready(function () {
                    $('#modelo-select').prop('disabled', true);
                    $('#submodelo-select').prop('disabled', true);

                    $('#marca-select, #modelo-select, #submodelo-select').change(function () {
                        updateVeiculos();
                    });

                    $('#pesquisa-button').click(function () {
                        updateVeiculos();
                    });

                    $('#favoritos').click(function () {
                        updateVeiculos();
                    })

                    $('#pesquisaTermo').keypress(function (e) {
                        if (e.which === 13) {
                            updateVeiculos();
                        }
                    });

                    updateVeiculos();

                    $('#marca-select').change(function () {
                        var marcaSelecionada = $(this).val();

                        if (marcaSelecionada === 'mostrar_tudo') {
                            $('#modelo-select').prop('disabled', true).val('mostrar_tudo1');
                            $('#submodelo-select').prop('disabled', true).val('mostrar_tudo2');
                        } else {
                            console.log("chamado")
                            $('#modelo-select').prop('disabled', false);
                            $('#modelo-select').val('mostrar_tudo1');
                            $('#submodelo-select').prop('disabled', true).val('mostrar_tudo2');

                            $.ajax({
                                type: 'POST',
                                url: 'get_modelos.php',
                                data: { marca: marcaSelecionada },
                                dataType: 'json',
                                success: function (modelos) {
                                    var modeloSelect = $('#modelo-select');
                                    modeloSelect.empty();

                                    modeloSelect.append('<option value="mostrar_tudo1">Mostrar Tudo</option>');

                                    $.each(modelos, function (index, modelo) {
                                        modeloSelect.append('<option value="' + modelo + '">' + modelo + '</option>');
                                    });
                                },
                                error: function (xhr, status, error) {
                                    console.error('Erro na requisição AJAX: ' + xhr.responseText);
                                }
                            });
                        }
                    });

                    $('#modelo-select').change(function () {
                        var marcaSelecionada = $('#marca-select').val();
                        var modeloSelecionado = $(this).val();

                        if (modeloSelecionado === 'mostrar_tudo1') {
                            $('#submodelo-select').prop('disabled', true).val('mostrar_tudo2');
                        } else {
                            $('#submodelo-select').prop('disabled', true).val('mostrar_tudo2');

                            $.ajax({
                                type: 'POST',
                                url: 'get_submodelos.php',
                                data: { marca: marcaSelecionada, modelo: modeloSelecionado },
                                dataType: 'json',
                                success: function (submodelos) {
                                    var submodeloSelect = $('#submodelo-select');
                                    submodeloSelect.empty();

                                    submodeloSelect.append('<option value="mostrar_tudo2">Mostrar Tudo</option>');

                                    $.each(submodelos, function (index, submodelo) {
                                        if (submodelo != '') {
                                            submodeloSelect.prop('disabled', false);
                                            submodeloSelect.append('<option value="' + submodelo + '">' + submodelo + '</option>');
                                        }
                                    });
                                },
                                error: function (xhr, status, error) {
                                    console.error('Erro na requisição AJAX: ' + xhr.responseText);
                                }
                            });
                        }
                    });
                });
            </script>
            <div class="form-group" id="editableInputsubmodelo">
                <label>Sub-modelo</label>
                <div class="flex-container">
                    <select name="submodelo" id="submodelo-select">
                        <option value="mostrar_tudo2">Mostrar Tudo</option>
                    </select>
                </div>
            </div>

        </div>

        <div>
            <input type="checkbox" id="favoritos" name="favoritos" />
            <label for="favoritos">Apenas mostrar favoritos</label>
        </div>

        <div class="items-container"></div>

        <script>
            $(document).ready(function () {
                $('#marca-select, #modelo-select, #submodelo-select').change(function () {
                    updateVeiculos();
                });
            });

            function updateVeiculos() {
                var marcaSelecionada = $('#marca-select').val();
                var modeloSelecionado = $('#modelo-select').val();
                var submodeloSelecionado = $('#submodelo-select').val();
                var pesquisaTermo = $('#pesquisaTermo').val();
                var favorito = $('#favoritos').is(':checked') ? 1 : 0; 

                $.ajax({
                    type: 'POST',
                    url: 'atualizar_veiculos.php',
                    data: {
                        marca: marcaSelecionada,
                        modelo: modeloSelecionado,
                        submodelo: submodeloSelecionado,
                        pesquisaTermo: pesquisaTermo,
                        favoritos: favorito  
                    },
                    dataType: 'html',
                    success: function (html) {
                        $('.items-container').html(html);
                    },
                    error: function (xhr, status, error) {
                        console.error('Erro na requisição AJAX: ' + xhr.responseText);
                    }
                });
            }

        </script>


    </div>

</body>

</html>