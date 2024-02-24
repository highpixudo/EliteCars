<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "elitecars";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão com a base de dados: " . $conn->connect_error);
}

if (isset($_POST['modelo'])) {
    $modelo = $_POST['modelo'];

    $sql = "SELECT DISTINCT submodelo FROM carros WHERE modelo = ? ORDER BY submodelo ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $modelo);
    $stmt->execute();
    $result = $stmt->get_result();  

    $submodelos = array();

    if ($result) {
        while ($linha = mysqli_fetch_assoc($result)) {
            $submodelos[] = $linha['submodelo'];
        }
    }

    echo json_encode($submodelos);
} else {
    echo json_encode(array('error' => 'Modelo nao especificada.'));
}
?>