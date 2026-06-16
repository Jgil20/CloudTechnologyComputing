<?php
require_once __DIR__ . '/../includes/db.php';

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function createSlug(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');

    return $text ?: 'blog-post-' . time();
}

$message = '';

$categoriesStmt = $pdo->query("
    SELECT id, name 
    FROM blog_categories 
    ORDER BY name ASC
");
$categories = $categoriesStmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryId = !empty($_POST['category_id']) ? (int) $_POST['category_id'] : null;
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $contentHtml = trim($_POST['content_html'] ?? '');
    $featuredImage = trim($_POST['featured_image'] ?? '');
    $featuredImageAlt = trim($_POST['featured_image_alt'] ?? '');
    $metaTitle = trim($_POST['meta_title'] ?? '');
    $metaDescription = trim($_POST['meta_description'] ?? '');
    $status = $_POST['status'] ?? 'draft';

    if ($slug === '') {
        $slug = createSlug($title);
    } else {
        $slug = createSlug($slug);
    }

    if ($title === '' || $contentHtml === '') {
        $message = 'Title and content are required.';
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO blog_posts (
                category_id,
                title,
                slug,
                excerpt,
                content_html,
                featured_image,
                featured_image_alt,
                author_name,
                meta_title,
                meta_description,
                og_image,
                status,
                post_date
            )
            VALUES (
                :category_id,
                :title,
                :slug,
                :excerpt,
                :content_html,
                :featured_image,
                :featured_image_alt,
                :author_name,
                :meta_title,
                :meta_description,
                :og_image,
                :status,
                NOW()
            )
        ");

        try {
            $stmt->execute([
                ':category_id' => $categoryId,
                ':title' => $title,
                ':slug' => $slug,
                ':excerpt' => $excerpt,
                ':content_html' => $contentHtml,
                ':featured_image' => $featuredImage,
                ':featured_image_alt' => $featuredImageAlt,
                ':author_name' => 'Jhon Arzu-Gil',
                ':meta_title' => $metaTitle ?: $title,
                ':meta_description' => $metaDescription ?: $excerpt,
                ':og_image' => $featuredImage,
                ':status' => $status,
            ]);

            $message = 'Post added successfully.';

            $newPostUrl = '/blog-details.php?slug=' . urlencode($slug);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $message = 'That slug already exists. Choose a different slug.';
            } else {
                error_log($e->getMessage());
                $message = 'Something went wrong while saving the post.';
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Blog Post</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8fafc;
            padding: 30px;
        }

        .admin-container {
            max-width: 950px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 18px;
            margin-bottom: 6px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 16px;
        }

        textarea {
            min-height: 220px;
            font-family: monospace;
        }

        button {
            margin-top: 25px;
            padding: 14px 22px;
            background: #111827;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        .message {
            padding: 14px;
            border-radius: 8px;
            background: #ecfdf5;
            border: 1px solid #10b981;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

<div class="admin-container">
    <h1>Add New Blog Post</h1>

    <?php if ($message): ?>
        <div class="message">
            <?= e($message) ?>

            <?php if (!empty($newPostUrl)): ?>
                <br>
                <a href="<?= e($newPostUrl) ?>" target="_blank">View Post</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <label for="category_id">Category</label>
        <select name="category_id" id="category_id">
            <option value="">Select Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= (int) $category['id'] ?>">
                    <?= e($category['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="title">Post Title</label>
        <input 
            type="text" 
            name="title" 
            id="title" 
            placeholder="Example: Best Cloud Hosting for Small Businesses"
            required>

        <label for="slug">Slug</label>
        <input 
            type="text" 
            name="slug" 
            id="slug" 
            placeholder="best-cloud-hosting-for-small-businesses">

        <label for="excerpt">Excerpt</label>
        <textarea 
            name="excerpt" 
            id="excerpt" 
            placeholder="Short summary of the blog post."></textarea>

        <label for="content_html">Post Content HTML</label>
        <textarea 
            name="content_html" 
            id="content_html" 
            placeholder="<h2>Introduction</h2><p>Your blog content here...</p>"
            required></textarea>

        <label for="featured_image">Featured Image Path</label>
        <input 
            type="text" 
            name="featured_image" 
            id="featured_image" 
            placeholder="assets/img/blog/cloud-hosting-small-business.jpg">

        <label for="featured_image_alt">Featured Image ALT Text</label>
        <input 
            type="text" 
            name="featured_image_alt" 
            id="featured_image_alt" 
            placeholder="Cloud hosting for small businesses">

        <label for="meta_title">SEO Meta Title</label>
        <input 
            type="text" 
            name="meta_title" 
            id="meta_title" 
            placeholder="Best Cloud Hosting for Small Businesses">

        <label for="meta_description">SEO Meta Description</label>
        <textarea 
            name="meta_description" 
            id="meta_description" 
            placeholder="Write a 150-165 character SEO description."></textarea>

        <label for="status">Status</label>
        <select name="status" id="status">
            <option value="draft">Draft</option>
            <option value="published">Published</option>
        </select>

        <button type="submit">Save Blog Post</button>

    </form>
</div>

<script>
const titleInput = document.getElementById('title');
const slugInput = document.getElementById('slug');

titleInput.addEventListener('input', function () {
    if (slugInput.value.trim() !== '') {
        return;
    }

    slugInput.value = titleInput.value
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
});
</script>

</body>
</html>