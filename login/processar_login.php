<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "elitecars";
$secretKey = bin2hex(random_bytes(32));

$conn = new mysqli($servername, $username, $password, $dbname);

session_start();

// verificar se o cookie está setado
if (isset($_COOKIE['remember_me'])) {
    // extrair login do utilizador que tem este token
    list($user_id, $token) = explode(':', $_COOKIE['remember_me']);

    // validar o "user_id" e o token
    if (is_numeric($user_id)) {
        $token_esperado = hash('sha256', $secretKey . $user_id);

        if (hash_equals($token_esperado, $token)) {
            $_SESSION['user_id'] = $user_id; // marcar o utilizador como logado
            header("Location: index.php");
            exit();
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $passwordInput = $_POST["password"];

    // Use prepared statements para evitar injeção SQL
    $stmt = $conn->prepare("SELECT id, user, pass FROM utilizadores WHERE (user = ? OR email = ?)");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $stmt->store_result();

    // verificar se existe utilizador com o login
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $user, $hashed_password);
        $stmt->fetch();

        // verificar password com hash
        if (password_verify($passwordInput, $hashed_password)) {
            $_SESSION["user_id"] = $user_id;
            $_SESSION["username"] = $user;

            // verificar se a opção "Lembrar-me" está marcada
            if (isset($_POST['remember_me']) && $_POST['remember_me'] == 'on') {
                // cria o cookie
                $token = password_hash($secretKey . $user_id, PASSWORD_DEFAULT);
                setcookie('remember_me', $user_id . ':' . $token, time() + 60 * 60 * 24 * 30); // expira em 30 dias
            } else {
                setcookie('remember_me', '', time() - 3600);
            }

            // Configuração do tempo de vida da sessão (tempo de vida da sessão padrão: até o navegador ser fechado)
            $sessionLifetime = 0; // 0 significa até o navegador ser fechado
            ini_set('session.cookie_lifetime', $sessionLifetime);
            ini_set('session.gc_maxlifetime', $sessionLifetime);

            header("Location: index.php");
            exit();
        }
    }

    $stmt->close();
}

$conn->close();
?>