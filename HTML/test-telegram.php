<?php
// Simple test file to verify PHP and Telegram connection
header('Content-Type: application/json');

$botToken = "8538834851:AAEUCTtUBidhjpeja2OB8yXAgWPPNmfENTs";
$chatId = "810063185";
$telegramApiUrl = "https://api.telegram.org/bot" . $botToken . "/sendMessage";

$testMessage = "🧪 *Test Message*\n\nThis is a test from your portfolio contact form. PHP is working correctly!";

$postData = [
    'chat_id' => $chatId,
    'text' => $testMessage,
    'parse_mode' => 'Markdown'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $telegramApiUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    $responseData = json_decode($response, true);
    if ($responseData && isset($responseData['ok']) && $responseData['ok']) {
        echo json_encode(['success' => true, 'message' => 'Test message sent successfully! Check your Telegram.']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Telegram API error: ' . ($responseData['description'] ?? 'Unknown error'), 'response' => $response]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'HTTP Error: ' . $httpCode, 'response' => substr($response, 0, 500)]);
}
?>

