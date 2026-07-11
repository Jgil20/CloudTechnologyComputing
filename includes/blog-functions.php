<?php

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function ctc_blog_truncate(string $value, int $maxLength): string
{
    return function_exists('mb_substr')
        ? mb_substr($value, 0, $maxLength, 'UTF-8')
        : substr($value, 0, $maxLength);
}

function getPostBySlugOrId(PDO $pdo): ?array
{
    $slug = $_GET['slug'] ?? null;
    $id = $_GET['id'] ?? null;

    if ($slug) {
        $stmt = $pdo->prepare("
            SELECT 
                p.*,
                c.name AS category_name,
                c.slug AS category_slug
            FROM blog_posts p
            LEFT JOIN blog_categories c ON p.category_id = c.id
            WHERE p.slug = :slug
              AND p.status = 'published'
            LIMIT 1
        ");
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetch() ?: null;
    }

    if ($id && ctype_digit((string) $id)) {
        $stmt = $pdo->prepare("
            SELECT 
                p.*,
                c.name AS category_name,
                c.slug AS category_slug
            FROM blog_posts p
            LEFT JOIN blog_categories c ON p.category_id = c.id
            WHERE p.id = :id
              AND p.status = 'published'
            LIMIT 1
        ");
        $stmt->execute([':id' => (int) $id]);
        return $stmt->fetch() ?: null;
    }

    return null;
}

function incrementPostViews(PDO $pdo, int $postId): int
{
    $stmt = $pdo->prepare("
        INSERT INTO blog_post_views (post_id, views)
        VALUES (:post_id, 1)
        ON DUPLICATE KEY UPDATE views = views + 1
    ");
    $stmt->execute([':post_id' => $postId]);

    $stmt = $pdo->prepare("SELECT views FROM blog_post_views WHERE post_id = :post_id");
    $stmt->execute([':post_id' => $postId]);

    return (int) $stmt->fetchColumn();
}

function getPostTags(PDO $pdo, int $postId): array
{
    $stmt = $pdo->prepare("
        SELECT t.name, t.slug
        FROM blog_tags t
        INNER JOIN blog_post_tags pt ON t.id = pt.tag_id
        WHERE pt.post_id = :post_id
        ORDER BY t.name ASC
    ");
    $stmt->execute([':post_id' => $postId]);

    return $stmt->fetchAll();
}

function getRecentPosts(PDO $pdo, int $limit = 3): array
{
    $stmt = $pdo->prepare("
        SELECT id, title, slug, featured_image, post_date
        FROM blog_posts
        WHERE status = 'published'
        ORDER BY post_date DESC
        LIMIT :limit
    ");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}

function getCategoriesWithCounts(PDO $pdo): array
{
    $stmt = $pdo->query("
        SELECT 
            c.name,
            c.slug,
            COUNT(p.id) AS post_count
        FROM blog_categories c
        LEFT JOIN blog_posts p 
            ON c.id = p.category_id 
           AND p.status = 'published'
        GROUP BY c.id, c.name, c.slug
        ORDER BY c.name ASC
    ");

    return $stmt->fetchAll();
}

function getAllTags(PDO $pdo): array
{
    $stmt = $pdo->query("
        SELECT t.name, t.slug
        FROM blog_tags t
        ORDER BY t.name ASC
    ");

    return $stmt->fetchAll();
}

function getApprovedComments(PDO $pdo, int $postId): array
{
    $stmt = $pdo->prepare("
        SELECT name, message, created_at
        FROM blog_comments
        WHERE post_id = :post_id
          AND status = 'approved'
        ORDER BY created_at DESC
    ");
    $stmt->execute([':post_id' => $postId]);

    return $stmt->fetchAll();
}

function saveComment(PDO $pdo, int $postId): bool
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return false;
    }

    if (function_exists('csrf_check') && !csrf_check()) {
        return false;
    }

    // Honeypot field. Real visitors will not fill this hidden field.
    if (!empty($_POST['website'] ?? '')) {
        return false;
    }

    $lastComment = (int) ($_SESSION['last_blog_comment'] ?? 0);
    if ($lastComment > 0 && (time() - $lastComment) < 15) {
        return false;
    }

    $name = trim((string) ($_POST['name'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $subject = trim((string) ($_POST['subject'] ?? ''));
    $message = trim((string) ($_POST['message'] ?? ''));

    if ($name === '' || $email === '' || $message === '') {
        return false;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $name = ctc_blog_truncate($name, 100);
    $email = ctc_blog_truncate($email, 190);
    $subject = ctc_blog_truncate($subject, 160);
    $message = ctc_blog_truncate($message, 3000);

    $stmt = $pdo->prepare("
        INSERT INTO blog_comments 
        (post_id, name, email, subject, message, status)
        VALUES 
        (:post_id, :name, :email, :subject, :message, 'pending')
    ");

    $stmt->execute([
        ':post_id' => $postId,
        ':name' => $name,
        ':email' => $email,
        ':subject' => $subject,
        ':message' => $message,
    ]);

    $_SESSION['last_blog_comment'] = time();
    unset($_SESSION['csrf']);

    return true;
}

function getRelatedPosts(PDO $pdo, int $postId, ?int $categoryId, int $limit = 3): array
{
    if (!$categoryId) {
        return getRecentPosts($pdo, $limit);
    }

    $stmt = $pdo->prepare("
        SELECT id, title, slug, excerpt, featured_image, post_date
        FROM blog_posts
        WHERE status = 'published'
          AND category_id = :category_id
          AND id <> :post_id
        ORDER BY post_date DESC, id DESC
        LIMIT :limit
    ");

    $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
    $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    $posts = $stmt->fetchAll();

    if (count($posts) < $limit) {
        $fallback = getRecentPosts($pdo, $limit);
        foreach ($fallback as $post) {
            if ((int) $post['id'] !== $postId) {
                $posts[] = $post;
            }
            if (count($posts) >= $limit) {
                break;
            }
        }
    }

    return array_slice($posts, 0, $limit);
}

function getPostAffiliateLinks(PDO $pdo, int $postId): array
{
    $stmt = $pdo->prepare("
        SELECT 
            a.name,
            a.slug,
            a.category,
            a.description,
            a.url,
            a.cta,
            a.disclosure
        FROM affiliate_links a
        INNER JOIN blog_post_affiliate_links pa 
            ON a.id = pa.affiliate_link_id
        WHERE pa.post_id = :post_id
          AND a.is_active = 1
        ORDER BY pa.sort_order ASC, a.name ASC
    ");

    $stmt->execute([':post_id' => $postId]);

    return $stmt->fetchAll();
}