<?php
session_start();
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "elitecars";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão com a base de dados: " . $conn->connect_error);
}

$marcaSelecionada = isset($_POST['marca']) ? $_POST['marca'] : '';
$modeloSelecionado = isset($_POST['modelo']) ? $_POST['modelo'] : '';
$submodeloSelecionado = isset($_POST['submodelo']) ? $_POST['submodelo'] : '';
$pesquisaTermo = isset($_POST['pesquisaTermo']) ? $_POST['pesquisaTermo'] : '';
$favoritos = isset($_POST['favoritos']) ? $_POST['favoritos'] : 0;

$sql = "SELECT id, nome, marca, modelo, submodelo, preco, foto FROM carros WHERE 1";

$paramTypes = "";
$bindParams = array();

if ($favoritos == 1) {
    $sql = "SELECT carros.id, carros.nome, carros.marca, carros.modelo, carros.submodelo, carros.preco, carros.foto
            FROM carros
            INNER JOIN favoritos ON carros.id = favoritos.id_anuncio
            WHERE favoritos.user = ?";
    $paramTypes .= "s";
    $bindParams[] = $_SESSION["username"];
} else {
    if ($marcaSelecionada != 'mostrar_tudo') {
        $sql .= " AND marca = ?";
        $paramTypes .= "s";
        $bindParams[] = $marcaSelecionada;
    }

    if ($modeloSelecionado != 'mostrar_tudo1' && !empty($modeloSelecionado)) {
        $sql .= " AND modelo = ?";
        $paramTypes .= "s";
        $bindParams[] = $modeloSelecionado;
    }

    if ($submodeloSelecionado != 'mostrar_tudo2') {
        $sql .= " AND submodelo = ?";
        $paramTypes .= "s";
        $bindParams[] = $submodeloSelecionado;
    }

    if (!empty($pesquisaTermo)) {
        $sql = "SELECT id, nome, marca, modelo, submodelo, preco, foto FROM carros WHERE nome LIKE ?";
        $pesquisaTermoSeguro = "%$pesquisaTermo%";
    }
}

$stmt = $conn->prepare($sql);

if ($stmt) {
    if ($favoritos == 1) {
        $stmt->bind_param($paramTypes, ...$bindParams);
    } elseif (!empty($bindParams) and empty($pesquisaTermo)) {
        $stmt->bind_param($paramTypes, ...$bindParams);
    } elseif (!empty($pesquisaTermo)) {
        $stmt->bind_param("s", $pesquisaTermoSeguro);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $html = '<div class="items-container">';

        while ($row = $result->fetch_assoc()) {
            $html .= '<div class="item">';
            $html .= '<h3>' . $row['nome'] . '</h3>';
            $html .= '<img src="veiculos_fotos/' . $row['foto'] . '.png" alt="' . $row['nome'] . '" style="max-width: 25%;">';
            $html .= '<div class="price">';

            $preco = number_format($row['preco'], 0, ' ', ' ');
            $precoArray = str_split(str_replace(',', '', $preco));

            foreach ($precoArray as $key => $value) {
                if ($value == '.') {
                    $html .= '<span>,</span>';
                } else {
                    $html .= '<span style="--i:' . ($key + 1) . '">' . $value . '</span>';
                }
            }

            $html .= '<span>€</span>';
            $html .= '</div>';

            $html .= '<div class="buttons">';
            $html .= '<a href="anuncio/detalhes_carro.php?id=' . $row['id'] . '" class="button_detalhes">Ver Detalhes</a>';
            $html .= '</div>';

            $html .= '</div>';
        }

        $html .= '</div>';

        echo $html;
    } else {
        echo "Nenhum resultado encontrado.";
    }

    $stmt->close();
} else {
    echo "Erro na preparação da declaração: " . $conn->error;
}

$conn->close();
?>