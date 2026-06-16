<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Only POST requests are allowed.'
    ]);
    exit;
}

require_once __DIR__ . '/includes/config.php';

if (!defined('GEMINI_API_KEY') || GEMINI_API_KEY === '') {
    http_response_code(503);
    echo json_encode([
        'success' => false,
        'error' => 'Chat is temporarily unavailable while the API key is configured.'
    ]);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$userMessage = trim($input['message'] ?? '');
$file = $input['file'] ?? null;
$history = $input['history'] ?? [];

if ($userMessage === '' && empty($file['data'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Message is required.'
    ]);
    exit;
}

if (strlen($userMessage) > 3000) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => 'Message is too long.'
    ]);
    exit;
}

$businessMemory = "
Context:
www.arzugil.com is the personal portfolio site of Jhon A. Arzu-Gil — a certified Cloud Developer, Full-Stack Engineer, and Application Development Specialist with over 30 professional IT certifications.

Jhon specializes in:
- Web Development using HTML, CSS, JavaScript, PHP, and MySQL
- Native and hybrid mobile app development for Android and iOS
- Cloud Architecture with AWS, Azure, IBM Cloud, and multi-cloud environments
- AI and Machine Learning integrations including chatbots and automation
- SEO optimization, website speed, and performance audits
- Progressive Web Apps, responsive designs, and accessible UI
- SAP Analytics Cloud dashboards and enterprise data reporting
- Digital marketing campaigns including Amazon PPC, Google Ads, and Facebook

Cloud Technology Computing Corporation provides:
- Affordable 3-page website development starting at $1,000
- Local SEO packages starting at $500 per month
- Website optimization starting at $1,500
- Social media marketing starting at $399 per month
- PPC online advertising starting at $450 per month
- Custom software development
- Web application development
- Mobile application development
- WordPress development
- SAP consulting
- Cloud consulting
- IT consulting

The assistant should answer questions clearly, recommend relevant services, and encourage visitors to schedule an appointment or contact Cloud Technology Computing when appropriate.
";

$contents = [];

$contents[] = [
    'role' => 'user',
    'parts' => [
        [
            'text' => $businessMemory
        ]
    ]
];

if (is_array($history)) {
    foreach ($history as $item) {
        if (!isset($item['role']) || !isset($item['parts']) || !is_array($item['parts'])) {
            continue;
        }

        $safeParts = [];

        foreach ($item['parts'] as $part) {
            if (isset($part['text'])) {
                $safeParts[] = [
                    'text' => substr((string) $part['text'], 0, 3000)
                ];
            }
        }

        if (!empty($safeParts)) {
            $contents[] = [
                'role' => $item['role'] === 'model' ? 'model' : 'user',
                'parts' => $safeParts
            ];
        }
    }
}

$currentParts = [];

if ($userMessage !== '') {
    $currentParts[] = [
        'text' => $userMessage
    ];
}

if (
    is_array($file) &&
    !empty($file['data']) &&
    !empty($file['mime_type'])
) {
    $allowedMimeTypes = [
        'image/png',
        'image/jpeg',
        'image/webp',
        'image/gif'
    ];

    if (in_array($file['mime_type'], $allowedMimeTypes, true)) {
        $currentParts[] = [
            'inline_data' => [
                'mime_type' => $file['mime_type'],
                'data' => $file['data']
            ]
        ];
    }
}

$contents[] = [
    'role' => 'user',
    'parts' => $currentParts
];

$url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';

$payload = [
    'contents' => $contents,
    'generationConfig' => [
        'temperature' => 0.7,
        'maxOutputTokens' => 700
    ]
];

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'x-goog-api-key: ' . GEMINI_API_KEY
    ],
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_TIMEOUT => 30
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);

curl_close($ch);

if ($curlError) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server connection error.'
    ]);
    exit;
}

$data = json_decode($response, true);

if ($httpCode < 200 || $httpCode >= 300) {
    http_response_code($httpCode);
    echo json_encode([
        'success' => false,
        'error' => $data['error']['message'] ?? 'Gemini API error.'
    ]);
    exit;
}

$reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No response generated.';

$reply = preg_replace('/\*\*(.*?)\*\*/', '$1', $reply);
$reply = trim($reply);

echo json_encode([
    'success' => true,
    'reply' => $reply
]);