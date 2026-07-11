<?php
/**
 * Dynamic portfolio listing data.
 *
 * project.php uses this file to render the portfolio from MySQL when the
 * `projects` table exists. The static array remains as a safe fallback so the
 * page still works if the database is offline or empty.
 */

if (!function_exists('ctc_h')) {
    function ctc_h(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('ctc_asset')) {
    function ctc_asset(?string $path): string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return '/assets/img/home-6/cloudbanner.jpg';
        }

        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        return '/' . ltrim($path, '/');
    }
}

if (!function_exists('ctc_url')) {
    function ctc_url(?string $url): string
    {
        $url = trim((string) $url);

        if ($url === '') {
            return '#';
        }

        if (preg_match('~^(https?://|mailto:|tel:|/|#)~i', $url)) {
            return $url;
        }

        return '/' . ltrim($url, '/');
    }
}

if (!function_exists('ctc_absolute_url')) {
    function ctc_absolute_url(?string $path): string
    {
        $path = trim((string) $path);
        $siteUrl = defined('SITE_URL') ? rtrim(SITE_URL, '/') : 'https://www.cloudtechnologycomputing.com';

        if ($path === '') {
            return $siteUrl . '/assets/img/home-6/cloudbanner.jpg';
        }

        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        return $siteUrl . '/' . ltrim($path, '/');
    }
}

if (!function_exists('ctc_truncate_text')) {
    function ctc_truncate_text(?string $text, int $limit = 160): string
    {
        $clean = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $text)) ?? '');

        if ($clean === '') {
            return '';
        }

        $length = function_exists('mb_strlen') ? mb_strlen($clean, 'UTF-8') : strlen($clean);

        if ($length <= $limit) {
            return $clean;
        }

        $short = function_exists('mb_substr')
            ? mb_substr($clean, 0, $limit - 3, 'UTF-8')
            : substr($clean, 0, $limit - 3);

        $short = preg_replace('/\s+\S*$/u', '', $short) ?: $short;

        return rtrim($short, " \t\n\r\0\x0B,.;:-") . '...';
    }
}

if (!function_exists('ctc_portfolio_page_data')) {
    function ctc_portfolio_page_data(): array
    {
        return [
            'seo' => [
                'title' => 'Cloud, AI & Web Development Projects | CTC',
                'description' => 'View cloud, AI, website, mobile app, and SEO projects built by Cloud Technology Computing. See practical solutions that help small businesses grow.',
                'canonical' => '/project.php',
                'image' => '/assets/img/home-6/cloudbanner.jpg',
                'image_alt' => 'Cloud, AI, and web development projects by Cloud Technology Computing',
                'robots' => 'index, follow',
                'author' => 'Jhon Arzu-Gil',
                'site_name' => 'Cloud Technology Computing',
                'locale' => 'en_US',
                'type' => 'website',
                'twitter_site' => '@JhonArzuGil',
                'twitter_creator' => '@JhonArzuGil',
            ],
            'breadcrumb' => [
                ['name' => 'Home', 'url' => '/'],
                ['name' => 'Our Completed Cloud and Web Projects', 'url' => '/project.php'],
            ],
            'page' => [
                'eyebrow' => 'Projects',
                'h1' => 'Our Completed Projects',
                'hidden_h2' => 'Cloud, Web, AI, Mobile App, SEO, and Cloud Computing Project Portfolio',
                'per_page' => 6,
            ],
            'projects' => ctc_fallback_portfolio_projects(),
        ];
    }
}

if (!function_exists('ctc_fallback_portfolio_projects')) {
    function ctc_fallback_portfolio_projects(): array
    {
        return [
            [
                'title' => 'Portfolio Site',
                'category' => 'Web Development',
                'url' => 'https://www.arzugil.com',
                'detail_url' => 'https://www.arzugil.com',
                'image' => 'assets/img/home-3/CloudComputing.webp',
                'alt' => 'ArzuGil personal portfolio website designed and developed by Cloud Technology Computing',
            ],
            [
                'title' => 'Portfolio Site App',
                'category' => 'Mobile Development',
                'url' => 'https://play.google.com/store/apps/details?id=com.arzugil.com.portfoliositejhongil',
                'detail_url' => 'https://play.google.com/store/apps/details?id=com.arzugil.com.portfoliositejhongil',
                'image' => 'assets/img/home-3/CloudTechnologyComputing.avif',
                'alt' => 'ArzuGil portfolio mobile app published on Google Play by Cloud Technology Computing',
            ],
            [
                'title' => 'Cloud Technology Computing',
                'category' => 'Web Development',
                'url' => '/',
                'detail_url' => '/',
                'image' => 'assets/img/home-3/ComputerClouds.webp',
                'alt' => 'Cloud Technology Computing corporate marketing website and brand identity',
            ],
            [
                'title' => 'Cloud Technology Computing App',
                'category' => 'Mobile Development',
                'url' => 'https://apps.apple.com/us/app/cloudtechnologycomputingapp/id6751188089',
                'detail_url' => 'https://apps.apple.com/us/app/cloudtechnologycomputingapp/id6751188089',
                'image' => 'assets/img/home-3/ComputerCloudsDisplay.webp',
                'alt' => 'Cloud Technology Computing mobile app on the Apple App Store',
            ],
            [
                'title' => 'iOS App',
                'category' => 'Mobile Development',
                'url' => 'https://apps.apple.com/us/app/cloudtechnologycomputingapp/id6751188089',
                'detail_url' => 'https://apps.apple.com/us/app/cloudtechnologycomputingapp/id6751188089',
                'image' => 'assets/img/home-3/CloudSolutions.avif',
                'alt' => 'Cloud Technology Computing iOS app project',
            ],
            [
                'title' => 'Credly Certifications',
                'category' => 'Certifications',
                'url' => 'https://www.credly.com/users/jhongil',
                'detail_url' => 'https://www.credly.com/users/jhongil',
                'image' => 'assets/img/home-3/ITConulsting2.avif',
                'alt' => 'Cloud Technology Computing professional cloud, IT, analytics, and software development certifications',
            ],
        ];
    }
}

if (!function_exists('ctc_first_non_empty')) {
    function ctc_first_non_empty(array $row, array $keys, string $default = ''): string
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $row) && trim((string) $row[$key]) !== '') {
                return trim((string) $row[$key]);
            }
        }

        return $default;
    }
}

if (!function_exists('ctc_database_connection')) {
    function ctc_database_connection(): ?PDO
    {
        if (!function_exists('ctc_env') || !class_exists('PDO')) {
            return null;
        }

        if (!in_array('mysql', PDO::getAvailableDrivers(), true)) {
            return null;
        }

        $host = ctc_env('DB_HOST', '127.0.0.1');
        $port = ctc_env('DB_PORT', '3306');
        $dbname = ctc_env('DB_NAME', '');
        $username = ctc_env('DB_USERNAME', '');
        $password = ctc_env('DB_PASSWORD', '');

        if ($dbname === '' || $username === '' || $password === '') {
            return null;
        }

        try {
            return new PDO(
                "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (Throwable $e) {
            error_log('Project portfolio DB connection skipped: ' . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('ctc_table_columns')) {
    function ctc_table_columns(PDO $pdo, string $table): array
    {
        try {
            $stmt = $pdo->query('SHOW COLUMNS FROM `' . str_replace('`', '``', $table) . '`');
            $columns = [];

            foreach ($stmt->fetchAll() as $column) {
                if (!empty($column['Field'])) {
                    $columns[] = (string) $column['Field'];
                }
            }

            return $columns;
        } catch (Throwable $e) {
            return [];
        }
    }
}

if (!function_exists('ctc_portfolio_projects_from_database')) {
    /**
     * Load portfolio cards from the MySQL `projects` table in techstartup.sql.
     * Optional migration columns supported: live_url, card_cta, sort_order.
     */
    function ctc_portfolio_projects_from_database(): array
    {
        $pdo = ctc_database_connection();
        if (!$pdo) {
            return [];
        }

        $columns = ctc_table_columns($pdo, 'projects');
        if (!$columns || !in_array('title', $columns, true)) {
            return [];
        }

        $hasStatus = in_array('status', $columns, true);
        $hasSort = in_array('sort_order', $columns, true);
        $hasPublishedAt = in_array('published_at', $columns, true);
        $where = $hasStatus ? "WHERE status = 'published'" : '';
        $orderBy = $hasSort
            ? 'ORDER BY sort_order ASC, id DESC'
            : ($hasPublishedAt ? 'ORDER BY published_at DESC, id DESC' : 'ORDER BY id DESC');

        try {
            $rows = $pdo->query("SELECT * FROM projects {$where} {$orderBy} LIMIT 100")->fetchAll();
        } catch (Throwable $e) {
            error_log('Project portfolio query failed: ' . $e->getMessage());
            return [];
        }

        if (!$rows) {
            return [];
        }

        $projects = [];

        foreach ($rows as $row) {
            $title = ctc_first_non_empty($row, ['title', 'name', 'project_title'], 'Project');
            $slug = ctc_first_non_empty($row, ['slug']);
            $id = ctc_first_non_empty($row, ['id']);

            $detailUrl = $slug !== ''
                ? '/project/' . rawurlencode($slug)
                : ($id !== '' ? '/project-details.php?id=' . rawurlencode($id) : '/project.php');

            $liveUrl = ctc_first_non_empty($row, [
                'live_url',
                'project_url',
                'external_url',
                'website_url',
                'client_url',
                'url',
            ]);

            $image = ctc_first_non_empty($row, [
                'featured_image',
                'card_image',
                'thumbnail',
                'image',
                'og_image',
            ], 'assets/img/home-6/cloudbanner.jpg');

            $projects[] = [
                'title' => $title,
                'category' => ctc_first_non_empty($row, ['project_type', 'category', 'service_type', 'type', 'industry'], 'Project'),
                'excerpt' => ctc_first_non_empty($row, ['excerpt', 'subtitle', 'meta_description']),
                'url' => $liveUrl !== '' ? $liveUrl : $detailUrl,
                'detail_url' => $detailUrl,
                'image' => $image,
                'alt' => ctc_first_non_empty($row, ['featured_image_alt', 'image_alt', 'alt_text', 'meta_title'], $title . ' project by Cloud Technology Computing'),
            ];
        }

        return $projects;
    }
}
