<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, max-age=0');
header('X-Content-Type-Options: nosniff');

function ctc_chat_response(bool $success, array $payload = [], int $status = 200): never
{
    http_response_code($status);
    echo json_encode(
        array_merge(['success' => $success], $payload),
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    );
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    ctc_chat_response(false, ['error' => 'Only POST requests are allowed.'], 405);
}

$contentLength = (int) ($_SERVER['CONTENT_LENGTH'] ?? 0);
if ($contentLength > 5_500_000) {
    ctc_chat_response(false, ['error' => 'The request is too large.'], 413);
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$now = time();
$windowStart = (int) ($_SESSION['chat_rate_window'] ?? 0);
$requestCount = (int) ($_SESSION['chat_rate_count'] ?? 0);

if ($windowStart === 0 || ($now - $windowStart) >= 300) {
    $windowStart = $now;
    $requestCount = 0;
}

if ($requestCount >= 20) {
    ctc_chat_response(false, ['error' => 'Too many chat requests. Please wait a few minutes and try again.'], 429);
}

$_SESSION['chat_rate_window'] = $windowStart;
$_SESSION['chat_rate_count'] = $requestCount + 1;

require_once __DIR__ . '/includes/config.php';

if (!defined('GEMINI_API_KEY') || GEMINI_API_KEY === '') {
    ctc_chat_response(false, ['error' => 'Chat is temporarily unavailable while the API key is configured.'], 503);
}

$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput ?: '', true);

if (!is_array($input) || json_last_error() !== JSON_ERROR_NONE) {
    ctc_chat_response(false, ['error' => 'Invalid JSON request.'], 400);
}

$userMessage = trim((string) ($input['message'] ?? ''));
$history = is_array($input['history'] ?? null) ? $input['history'] : [];
$file = is_array($input['file'] ?? null) ? $input['file'] : null;

if (strlen($userMessage) > 3000) {
    ctc_chat_response(false, ['error' => 'Message is too long.'], 400);
}

$businessMemory = <<<'CONTEXT'
You are the website assistant for Cloud Technology Computing Corporation, a Houston-based cloud, AI, web, mobile, data, cybersecurity, SEO, and managed IT services company.

Core services:
- AWS, Microsoft Azure, IBM Cloud, Google Cloud, hybrid-cloud, migration, hosting, monitoring, backup, and cost optimization
- Custom PHP, MySQL, HTML, CSS, JavaScript, APIs, e-commerce, and business websites
- AI chatbots, workflow automation, lead capture, and customer-support integrations
- Native Android and iOS applications, progressive web apps, and mobile publishing support
- SEO, website performance, Core Web Vitals, analytics, digital marketing, and conversion optimization
- SAP Analytics Cloud, dashboards, reporting, data analytics, and technology consulting

Current listed service prices include a $200 business consulting session, $500/month SEO optimization, $750 website speed and SEO tune-up, $950 managed cloud setup, $1,200 AI chatbot integration, and $1,500 custom business website. Final pricing depends on scope.

Answer clearly and accurately. Do not invent guarantees, certifications, project results, availability, or prices. When a visitor needs a quote, appointment, or project-specific answer, encourage them to use the consultation form or call 1-713-870-9966.
CONTEXT;

$contents = [];
$totalHistoryCharacters = 0;

foreach (array_slice($history, -10) as $item) {
    if (!is_array($item) || !isset($item['parts']) || !is_array($item['parts'])) {
        continue;
    }

    $safeParts = [];
    foreach ($item['parts'] as $part) {
        if (!is_array($part) || !isset($part['text'])) {
            continue;
        }

        $text = substr((string) $part['text'], 0, 2000);
        $totalHistoryCharacters += strlen($text);
        if ($totalHistoryCharacters > 12_000) {
            break 2;
        }

        if ($text !== '') {
            $safeParts[] = ['text' => $text];
        }
    }

    if ($safeParts !== []) {
        $contents[] = [
            'role' => (($item['role'] ?? '') === 'model') ? 'model' : 'user',
            'parts' => $safeParts,
        ];
    }
}

$currentParts = [];
if ($userMessage !== '') {
    $currentParts[] = ['text' => $userMessage];
}

if ($file !== null && !empty($file['data']) && !empty($file['mime_type'])) {
    $mimeType = (string) $file['mime_type'];
    $encodedData = (string) $file['data'];
    $allowedMimeTypes = ['image/png', 'image/jpeg', 'image/webp', 'image/gif'];

    if (!in_array($mimeType, $allowedMimeTypes, true)) {
        ctc_chat_response(false, ['error' => 'Only PNG, JPEG, WebP, and GIF images are supported.'], 400);
    }

    if (strlen($encodedData) > 4_200_000) {
        ctc_chat_response(false, ['error' => 'The uploaded image is too large.'], 413);
    }

    $decodedData = base64_decode($encodedData, true);
    if ($decodedData === false || strlen($decodedData) > 3_000_000) {
        ctc_chat_response(false, ['error' => 'The uploaded image data is invalid or too large.'], 400);
    }

    $currentParts[] = [
        'inline_data' => [
            'mime_type' => $mimeType,
            'data' => $encodedData,
        ],
    ];
}

if ($currentParts === []) {
    ctc_chat_response(false, ['error' => 'Message or image is required.'], 400);
}

$contents[] = ['role' => 'user', 'parts' => $currentParts];

$model = ctc_env('GEMINI_MODEL', 'gemini-2.5-flash');
$url = 'https://generativelanguage.googleapis.com/v1beta/models/'
    . rawurlencode($model)
    . ':generateContent';

$payload = [
    'system_instruction' => [
        'parts' => [['text' => $businessMemory]],
    ],
    'contents' => $contents,
    'generationConfig' => [
        'temperature' => 0.55,
        'maxOutputTokens' => 700,
    ],
];

$encodedPayload = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
if ($encodedPayload === false) {
    ctc_chat_response(false, ['error' => 'The chat request could not be prepared.'], 500);
}

if (!function_exists('curl_init')) {
    ctc_chat_response(false, ['error' => 'Chat is unavailable because PHP cURL is not enabled.'], 503);
}

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'x-goog-api-key: ' . GEMINI_API_KEY,
    ],
    CURLOPT_POSTFIELDS => $encodedPayload,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT => 30,
]);

$response = curl_exec($ch);
$httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($response === false || $curlError !== '') {
    error_log('Gemini connection error: ' . $curlError);
    ctc_chat_response(false, ['error' => 'The chat service could not be reached. Please try again.'], 502);
}

$data = json_decode($response, true);
if (!is_array($data)) {
    error_log('Gemini returned invalid JSON with HTTP status ' . $httpCode);
    ctc_chat_response(false, ['error' => 'The chat service returned an invalid response.'], 502);
}

if ($httpCode < 200 || $httpCode >= 300) {
    $apiMessage = (string) ($data['error']['message'] ?? 'Unknown Gemini API error');
    error_log('Gemini API error ' . $httpCode . ': ' . $apiMessage);
    ctc_chat_response(false, ['error' => 'The chat service is temporarily unavailable.'], 502);
}

$reply = trim((string) ($data['candidates'][0]['content']['parts'][0]['text'] ?? ''));
if ($reply === '') {
    ctc_chat_response(false, ['error' => 'No response was generated. Please try again.'], 502);
}

ctc_chat_response(true, ['reply' => $reply]);
