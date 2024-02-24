<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "elitecars";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão com a base de dados: " . $conn->connect_error);
}

if (isset($_POST['marca'])) {
    $marca = $_POST['marca'];

    $sql = "SELECT DISTINCT modelo FROM carros WHERE marca = ? ORDER BY modelo ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $marca);
    $stmt->execute();
    $result = $stmt->get_result();

    $modelos = array();

    if ($result) {
        while ($linha = mysqli_fetch_assoc($result)) {
            $modelos[] = $linha['modelo'];
        }
    }

    echo json_encode($modelos);
} else {
    echo json_encode(array('error' => 'Marca nao especificada.'));
}
?>