<?php
// Telegram Bot Configuration
$botToken = "8538834851:AAEUCTtUBidhjpeja2OB8yXAgWPPNmfENTs";
$chatId = "810063185";
$telegramApiUrl = "https://api.telegram.org/bot" . $botToken . "/sendMessage";

// Set headers for JSON response
header('Content-Type: application/json');

// Get form data
$name = isset($_POST['Name']) ? trim($_POST['Name']) : '';
$company = isset($_POST['Company']) ? trim($_POST['Company']) : '';
$email = isset($_POST['E-mail']) ? trim($_POST['E-mail']) : '';
$phone = isset($_POST['Phone']) ? trim($_POST['Phone']) : '';
$message = isset($_POST['Message']) ? trim($_POST['Message']) : '';

// Validate required fields
if (empty($name) || empty($email) || empty($phone) || empty($message)) {
    echo json_encode(['success' => false, 'error' => 'Please fill in all required fields.']);
    exit;
}

// Format message for Telegram
$telegramMessage = "📧 *New Contact Form Message*\n\n";
$telegramMessage .= "👤 *Name:* " . $name . "\n";
if (!empty($company)) {
    $telegramMessage .= "🏢 *Company:* " . $company . "\n";
}
$telegramMessage .= "📧 *Email:* " . $email . "\n";
$telegramMessage .= "📱 *Phone:* " . $phone . "\n";
$telegramMessage .= "\n💬 *Message:*\n" . $message;

// Prepare data for Telegram API
$postData = [
    'chat_id' => $chatId,
    'text' => $telegramMessage,
    'parse_mode' => 'Markdown'
];

// Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $telegramApiUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// Execute request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Check response
if ($httpCode == 200) {
    $responseData = json_decode($response, true);
    if ($responseData && isset($responseData['ok']) && $responseData['ok']) {
        echo json_encode(['success' => true, 'message' => 'Message sent successfully!']);
    } else {
        $errorMsg = 'Failed to send message to Telegram.';
        if ($responseData && isset($responseData['description'])) {
            $errorMsg .= ' Details: ' . $responseData['description'];
        }
        echo json_encode(['success' => false, 'error' => $errorMsg]);
    }
} else {
    $errorMsg = 'Error connecting to Telegram API. HTTP Code: ' . $httpCode;
    if ($response) {
        $errorMsg .= ' Response: ' . substr($response, 0, 200);
    }
    echo json_encode(['success' => false, 'error' => $errorMsg]);
}
?>

