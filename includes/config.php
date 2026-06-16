<?php
require_once __DIR__ . '/env.php';

$geminiApiKey = ctc_env('GEMINI_API_KEY', '');

define('GEMINI_API_KEY', $geminiApiKey);
define('APP_URL', rtrim(ctc_env('APP_URL', 'https://www.cloudtechnologycomputing.com'), '/'));
// Canonical public host. Use this in any user-facing URL (OG, Twitter, canonical,
// JSON-LD) so we always emit `www.` and never the naked apex.
define('SITE_URL', APP_URL);
