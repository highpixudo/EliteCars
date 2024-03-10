<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "elitecars";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão com a base de dados: " . $conn->connect_error);
}

session_start();

$id_anuncio = $_POST['id_anuncio'];
$user = $_POST['user'];

$sql_check_favorite = "SELECT COUNT(*) AS favorito FROM favoritos WHERE user = ? AND id_anuncio = ?";
$stmt_check_favorite = $conn->prepare($sql_check_favorite);
$stmt_check_favorite->bind_param("ss", $user, $id_anuncio);
$stmt_check_favorite->execute();
$result_check_favorite = $stmt_check_favorite->get_result();
$row_check_favorite = $result_check_favorite->fetch_assoc();
$is_favorite = $row_check_favorite['favorito'] > 0;

if ($is_favorite) {
    $sql_remove_favorite = "DELETE FROM favoritos WHERE user = ? AND id_anuncio = ?";
    $stmt_remove_favorite = $conn->prepare($sql_remove_favorite);
    $stmt_remove_favorite->bind_param("ss", $user, $id_anuncio);

    if ($stmt_remove_favorite->execute()) {
        echo "Carro removido dos favoritos com sucesso!";
    } else {
        echo "Erro ao remover o carro dos favoritos: " . $stmt_remove_favorite->error;
    }

    $stmt_remove_favorite->close();
} else {
    $sql_add_favorite = "INSERT INTO favoritos (user, id_anuncio) VALUES (?, ?)";
    $stmt_add_favorite = $conn->prepare($sql_add_favorite);
    $stmt_add_favorite->bind_param("ss", $user, $id_anuncio);

    if ($stmt_add_favorite->execute()) {
        echo "Carro adicionado aos favoritos com sucesso!";
    } else {
        echo "Erro ao adicionar o carro aos favoritos: " . $stmt_add_favorite->error;
    }

    $stmt_add_favorite->close();
}

$stmt_check_favorite->close();
$conn->close();
?>
