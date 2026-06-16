<?php

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('assetPath')) {
    function assetPath(?string $path): string
    {
        if (!$path) {
            return '/assets/img/inner-pages/portfolio-dt-01.png';
        }

        if (
            str_starts_with($path, 'http://') ||
            str_starts_with($path, 'https://')
        ) {
            return $path;
        }

        return '/' . ltrim($path, '/');
    }
}

if (!function_exists('urlPath')) {
    function urlPath(string $path): string
    {
        $path = trim($path);

        if (
            str_starts_with($path, 'http://') ||
            str_starts_with($path, 'https://') ||
            str_starts_with($path, 'mailto:') ||
            str_starts_with($path, 'tel:') ||
            str_starts_with($path, '#')
        ) {
            return $path;
        }

        return '/' . ltrim($path, '/');
    }
}

if (!function_exists('projectUrl')) {
    function projectUrl(string $slug): string
    {
        return '/project/' . rawurlencode($slug);

        // Use this if clean URLs are not active:
        // return '/project-details.php?slug=' . rawurlencode($slug);
    }
}

function getProjectBySlugOrId(PDO $pdo): ?array
{
    $slug = $_GET['slug'] ?? null;
    $id = $_GET['id'] ?? null;

    if ($slug) {
        $stmt = $pdo->prepare("
            SELECT *
            FROM projects
            WHERE slug = :slug
              AND status = 'published'
            LIMIT 1
        ");

        $stmt->execute([
            ':slug' => $slug
        ]);

        return $stmt->fetch() ?: null;
    }

    if ($id && ctype_digit((string) $id)) {
        $stmt = $pdo->prepare("
            SELECT *
            FROM projects
            WHERE id = :id
              AND status = 'published'
            LIMIT 1
        ");

        $stmt->execute([
            ':id' => (int) $id
        ]);

        return $stmt->fetch() ?: null;
    }

    return null;
}

function getProjectImages(PDO $pdo, int $projectId, ?string $position = null): array
{
    if ($position) {
        $stmt = $pdo->prepare("
            SELECT *
            FROM project_images
            WHERE project_id = :project_id
              AND image_position = :position
            ORDER BY sort_order ASC, id ASC
        ");

        $stmt->execute([
            ':project_id' => $projectId,
            ':position' => $position
        ]);

        return $stmt->fetchAll();
    }

    $stmt = $pdo->prepare("
        SELECT *
        FROM project_images
        WHERE project_id = :project_id
        ORDER BY sort_order ASC, id ASC
    ");

    $stmt->execute([
        ':project_id' => $projectId
    ]);

    return $stmt->fetchAll();
}

function getProjectProcessSteps(PDO $pdo, int $projectId): array
{
    $stmt = $pdo->prepare("
        SELECT *
        FROM project_process_steps
        WHERE project_id = :project_id
        ORDER BY sort_order ASC, id ASC
    ");

    $stmt->execute([
        ':project_id' => $projectId
    ]);

    return $stmt->fetchAll();
}

function getAdjacentProject(PDO $pdo, int $projectId, string $direction): ?array
{
    if ($direction === 'previous') {
        $stmt = $pdo->prepare("
            SELECT id, title, slug, featured_image, featured_image_alt
            FROM projects
            WHERE status = 'published'
              AND id < :id
            ORDER BY id DESC
            LIMIT 1
        ");
    } else {
        $stmt = $pdo->prepare("
            SELECT id, title, slug, featured_image, featured_image_alt
            FROM projects
            WHERE status = 'published'
              AND id > :id
            ORDER BY id ASC
            LIMIT 1
        ");
    }

    $stmt->execute([
        ':id' => $projectId
    ]);

    return $stmt->fetch() ?: null;
}