<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/elitecars/css/detalhes_carro.css">
    <link rel="stylesheet" href="/elitecars/css/nav_bar.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="/elitecars/js/scripts.js"></script>
    <script src="/elitecars/atualizar_online.js"></script>
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
        <a href="/elitecars/" class="logo">ELITE</a>
        <div class="nav-buttons">
            <?php
            if (isset ($_SESSION["username"])) {
                echo '<a href="/elitecars/" class="register" id="home">Início</a>';
                echo '<a href="/elitecars/veiculos" class="register" id="cars">Veículos</a>';
                echo '<a href="/elitecars/mensagens" class="register" id="about">Mensagens</a>';
                echo '<a href="/elitecars/conta" class="register" id="account">Conta</a>';
            } else {
                echo '<a href="/elitecars/signup" class="register">Criar conta</a>';
                echo '<a href="/elitecars/login" class="login">Entrar</a>';
            }
            ?>
        </div>
    </div>

    <?php
    if (!isset ($_SESSION["username"])) {
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

                    <div class="description">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quos cupiditate
                        autem
                        earum nostrum asperiores soluta laudantium, illum dolorem, molestiae error quam! Excepturi est
                        quia
                        iusto distinctio nam aperiam repellendus ratione.</div>


                    <div class="icons">
                        <div class="icon-container">
                            <i class='bx bx-tachometer'></i>
                            <?php echo '<span class="tooltip">' . $row_detalhes['km'] . ' km</span>'; ?>
                        </div>
                        <div class="icon-container">
                            <i class='bx bx-gas-pump'></i>
                            <?php echo '<span class="tooltip">' . $row_detalhes['combustivel'] . '</span>'; ?>
                        </div>
                        <div class="icon-container">
                            <i class='bx bx-cog'></i>
                            <?php echo '<span class="tooltip">' . $row_detalhes['cv'] . ' cv</span>'; ?>
                        </div>
                        <div class="icon-container">
                            <i class='bx bx-calendar'></i>
                            <?php echo '<span class="tooltip">' . $row_detalhes['datafabricacao'] . '</span>'; ?>
                        </div>
                    </div>

                    <?php
                    $car_id = $_GET['id'];

                    $sql_check_favorite = "SELECT COUNT(*) AS favorito FROM favoritos WHERE user = ? AND id_anuncio = ?";
                    $stmt_check_favorite = $conn->prepare($sql_check_favorite);
                    $stmt_check_favorite->bind_param("ss", $_SESSION['username'], $car_id);
                    $stmt_check_favorite->execute();
                    $result_check_favorite = $stmt_check_favorite->get_result();
                    $row_check_favorite = $result_check_favorite->fetch_assoc();
                    $is_favorite = $row_check_favorite['favorito'] > 0;
                    ?>

                    <div class="buttons">
                        <button>Test Drive</button>
                        <a href="/elitecars/checkout?id=<?php echo $car_id; ?>" class="button-link">Comprar Agora
                            <span>
                                <svg class="" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 18 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 15a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm0 0h8m-8 0-1-4m9 4a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-9-4h10l2-7H3m2 7L3 4m0 0-.792-3H1" />
                                </svg>
                            </span>
                        </a>
                    </div>


                    <div class="favorito">
                        <a href="#" class="add-to-favorites" data-car-id="<?php echo $car_id; ?>">
                            <div class="icon-container">
                                <?php
                                if ($is_favorite) {
                                    echo '<i class="bx bx-heart"></i>';
                                    echo '<span class="tooltip"> Remover dos favoritos</span>';
                                } else {
                                    echo '<i class="bx bx-heart"></i>';
                                    echo '<span class="tooltip"> Adicionar aos favoritos</span>';
                                }
                                ?>
                            </div>
                        </a>
                    </div>

                    <script>
                        $(document).ready(function () {
                            $(".add-to-favorites").on("click", function (e) {
                                e.preventDefault();

                                var carId = obterIdAnuncioDaURL();

                                $.ajax({
                                    type: "POST",
                                    url: "adicionar_fav.php",
                                    data: { id_anuncio: carId, user: "<?php echo $_SESSION['username']; ?>" },
                                    success: function (response) {
                                        alert(response);
                                    },
                                    error: function (error) {
                                        console.error("Erro na solicitação AJAX:", error);
                                    }
                                });
                            });
                        });

                        function obterIdAnuncioDaURL() {
                            var url = window.location.href;
                            var urlParams = new URLSearchParams(url.split('?')[1]);
                            var idAnuncio = urlParams.get('id');

                            return idAnuncio;
                        }
                    </script>


                </div>
            </div>
            <div class="contact-vendor">
                <div class="contact-image">
                    <?php
                    $sql_foto_user = "SELECT foto FROM utilizadores WHERE user = ?";
                    $stmt_foto_user = $conn->prepare($sql_foto_user);
                    $stmt_foto_user->bind_param("s", $row_detalhes["anunciante"]);
                    $stmt_foto_user->execute();
                    $stmt_foto_user->bind_result($foto_user);
                    $stmt_foto_user->fetch();
                    $stmt_foto_user->close();
                    ?>

                    <?php
                    echo '<img src="/elitecars/conta/' . $foto_user . '"';
                    ?>

                    <img src="https://cdn-icons-png.flaticon.com/512/2815/2815428.png" alt="Foto do Vendedor">
                </div>
                <div class="contact-info">
                    <?php
                    echo '<h2>@' . $row_detalhes["anunciante"] . '</h2>';
                    $anunciante = $row_detalhes["anunciante"];
                    $sql_last_activity = "SELECT ultima_atividade FROM utilizadores WHERE user = ?";
                    $stmt_last_activity = $conn->prepare($sql_last_activity);
                    $stmt_last_activity->bind_param("s", $anunciante);
                    $stmt_last_activity->execute();
                    $stmt_last_activity->store_result();

                    if ($stmt_last_activity->num_rows > 0) {
                        $stmt_last_activity->bind_result($ultima_atividade);
                        $stmt_last_activity->fetch();

                        $ultima_atividade = new DateTime($ultima_atividade);
                        $ultima_atividade_formatada = $ultima_atividade->format('d/m/Y');
                        echo '<p>Última vez online em ' . $ultima_atividade_formatada . '</p>';
                    } else {
                        echo '<p>Informação de atividade não disponível</p>';
                    }
                    $stmt_last_activity->close();
                    ?>

                </div>
                <div class="botao-contact">
                    <form action="/elitecars/mensagens?chat=<?php echo urlencode($row_detalhes["anunciante"]); ?>"
                        method="post">
                        <button type="submit">Contactar</button>
                    </form>

                </div>
            </div>
            <div class="comments-section">
                <h2>Comentários</h2>

                <form id="comment-form" method="post" action="">
                    <div class="comment-input-container">
                        <textarea id="comment" name="comment" rows="4"
                            placeholder="Adicione um comentário público..."></textarea>
                    </div>
                    <button type="submit" name="submit_comment">Comentar</button>
                </form>


                <ul class="comments-list">
                    <?php
                    if (isset ($_POST['submit_comment'])) {
                        $commentText = mysqli_real_escape_string($conn, $_POST['comment']);
                        $anuncio_id = $_GET['id'];

                        $sql_insert_comment = "INSERT INTO comentarios (user, comentario, id_anuncio) VALUES (?, ?, ?)";
                        $stmt_insert_comment = $conn->prepare($sql_insert_comment);
                        $stmt_insert_comment->bind_param("sss", $_SESSION["username"], $commentText, $anuncio_id);

                        if ($stmt_insert_comment->execute()) {
                            // comentário adicionado com sucesso
                        } else {
                            // erro ao adicionar o comentário
                            echo "Erro ao adicionar o comentário: " . $stmt_insert_comment->error;
                        }

                        $stmt_insert_comment->close();
                    }

                    $anuncio_id = $_GET['id'];
                    $sql_get_comments = "SELECT comentarios.*, utilizadores.foto AS user_photo FROM comentarios
                    INNER JOIN utilizadores ON comentarios.user = utilizadores.user
                    WHERE comentarios.id_anuncio = ? ORDER BY comentarios.data_comentario DESC";

                    $stmt_get_comments = $conn->prepare($sql_get_comments);
                    $stmt_get_comments->bind_param("s", $anuncio_id);
                    $stmt_get_comments->execute();
                    $result_get_comments = $stmt_get_comments->get_result();

                    if ($result_get_comments->num_rows > 0) {
                        while ($row_comment = $result_get_comments->fetch_assoc()) {
                            echo '<div class="teste">';
                            echo '<li>';
                            echo '<img src="' . htmlspecialchars("/elitecars/conta/" . $row_comment['user_photo']) . '" alt="User Photo" style="width:50px;height:50px;">';

                            echo '<div class="user-info">';
                            echo '<strong>@' . htmlspecialchars($row_comment['user']) . '</strong>';

                            echo '<p class="comment-text">' . htmlspecialchars($row_comment['comentario']) . '</p>';
                            echo '</div>';

                            echo '</li>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>Sem comentários ainda.</p>';
                        echo '</div>';
                    }

                    $stmt_get_comments->close();
                    ?>


                </ul>

            </div>
        </div>


        <?php
    }
    ?>

</body>

</html>