<?php
$cardNumber = $_POST['card_number'];

if (!isValidCardNumber($cardNumber)) {
    http_response_code(400); 
    exit('Número de cartão inválido'); 
}

echo 'Pagamento processado com sucesso';
exit();

function isValidCardNumber($cardNumber) {
    $cardNumber = preg_replace('/\D/', '', $cardNumber);

    if (strlen($cardNumber) != 16) {
        return false;
    }

    // algoritmo de Luhn
    $sum = 0;
    for ($i = 0; $i < 16; $i++) {
        $digit = (int)$cardNumber[$i];
        if ($i % 2 == 0) {
            $digit *= 2;
            if ($digit > 9) {
                $digit -= 9;
            }
        }
        $sum += $digit;
    }

    return $sum % 10 == 0;
}

?>
