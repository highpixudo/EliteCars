<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "elitecars";

    $conn = new mysqli($servername, $username, $password, $dbname);

    $email = $_POST["email"];

    // Use prepared statement para evitar injeção SQL
    $stmt = $conn->prepare("SELECT * FROM utilizadores WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // gerar um token único para a redefinição de senha
        $token = bin2hex(random_bytes(16));

        date_default_timezone_set('Europe/Lisbon');
        $expiryTimestamp = strtotime('+1 hour');
        $formattedExpiry = date('Y-m-d H:i:s', $expiryTimestamp);

        // Use prepared statement para evitar injeção SQL
        $stmt_insert = $conn->prepare("INSERT INTO reset_tokens (email, token, tempo_expirar) VALUES (?, ?, ?)");
        $stmt_insert->bind_param("sss", $email, $token, $formattedExpiry);
        $stmt_insert->execute();

        // construir o link de redefinição
        $resetLink = "http://localhost/EliteCars/reset.php?token=$token";

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'guilhermecatarino8@gmail.com';
            $mail->Password = 'bejnrumfegbzdwno';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom('guilhermecatarino8@gmail.com');
            $mail->addAddress($email);
            $mail->Subject = 'Redefinição de Credenciais - EliteCars';
            $mail->Body = "Clique no seguinte link para redefinir a sua palavra-passe: $resetLink";
            $mail->send();

            header('Location: index.php');
            exit;
        } catch (Exception $e) {
            echo "Erro ao enviar o email: {$mail->ErrorInfo}";
            echo "<br>";
            echo "Exception Message: {$e->getMessage()}";
            echo "<br>";
            echo "SMTP Debug Output:<pre>" . $mail->SMTPDebug . "</pre>";
        } finally {
            $stmt_insert->close();
        }
    }

    $stmt->close();
    $conn->close();
}
?>