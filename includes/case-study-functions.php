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
            return '/assets/img/inner-pages/AzureHostingDesktop.avif';
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

function getCaseStudyBySlugOrId(PDO $pdo): ?array
{
    $slug = $_GET['slug'] ?? null;
    $id = $_GET['id'] ?? null;

    if ($slug) {
        $stmt = $pdo->prepare("
            SELECT *
            FROM case_studies
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
            FROM case_studies
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

function getCaseStudySections(PDO $pdo, int $caseStudyId): array
{
    $stmt = $pdo->prepare("
        SELECT *
        FROM case_study_sections
        WHERE case_study_id = :case_study_id
        ORDER BY sort_order ASC, id ASC
    ");

    $stmt->execute([
        ':case_study_id' => $caseStudyId
    ]);

    return $stmt->fetchAll();
}

function getCaseStudyProcessSteps(PDO $pdo, int $caseStudyId): array
{
    $stmt = $pdo->prepare("
        SELECT *
        FROM case_study_process_steps
        WHERE case_study_id = :case_study_id
        ORDER BY sort_order ASC, id ASC
    ");

    $stmt->execute([
        ':case_study_id' => $caseStudyId
    ]);

    return $stmt->fetchAll();
}

function getCaseStudyGallery(PDO $pdo, int $caseStudyId): array
{
    $stmt = $pdo->prepare("
        SELECT *
        FROM case_study_gallery
        WHERE case_study_id = :case_study_id
        ORDER BY sort_order ASC, id ASC
    ");

    $stmt->execute([
        ':case_study_id' => $caseStudyId
    ]);

    return $stmt->fetchAll();
}