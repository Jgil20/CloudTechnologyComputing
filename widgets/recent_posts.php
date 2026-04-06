<?php
// widgets/recent_posts.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/db.php';

$res = $mysqli->query("SELECT id, title, created_at FROM posts ORDER BY created_at DESC LIMIT 5");

if ($res && $res->num_rows > 0) {
  while ($row = $res->fetch_assoc()) {
    echo '<div class="widget-cnt">';
$image = $row['image'] ?? 'assets/img/inner-pages/CloudComping.avif';
$dateRaw = $row['post_date'] ?? ($row['created_at'] ?? null);
$dateLabel = $dateRaw ? date('M d, Y', strtotime($dateRaw)) : '';
  }
} else {
  echo "<p>No recent posts found.</p>";
}
?>
