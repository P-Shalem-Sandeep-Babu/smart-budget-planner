<?php
require_once '../includes/config.php';
requireLogin();

header('Content-Type: application/json');

// Only respond to POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);
$message = trim($data['message'] ?? '');

if (empty($message)) {
    http_response_code(400);
    echo json_encode(['error' => 'Message is required']);
    exit();
}

// Finance-related instructions for the AI
$financeInstructions = "You are a financial advisor assistant. Only provide advice related to:
- Personal budgeting
- Saving money strategies
- Investment recommendations (stocks, mutual funds, ETFs)
- Debt management
- Retirement planning
- Tax planning

Do NOT answer questions unrelated to personal finance. If asked about other topics, respond with:
'I'm sorry, I can only provide advice on personal finance matters. Please ask me about budgeting, saving, or investing.'";

// Prepare the prompt with finance restrictions
$prompt = $financeInstructions . "\n\nUser question: " . $message;

$url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=AIzaSyCTsAEp70sZ7Sbg_8b4Wbo6FTwlxBPfI9E';


$requestData = [
    'contents' => [
        'parts' => [
            ['text' => $prompt]
        ]
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to get response from AI']);
    exit();
}

$responseData = json_decode($response, true);
$aiResponse = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? 'Sorry, I could not process your request.';

echo json_encode(['response' => $aiResponse]);
?>