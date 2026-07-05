<?php
// widgets/recent_posts.php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/db.php';

$res = $mysqli->query("SELECT id, title, image, post_date FROM posts ORDER BY post_date DESC LIMIT 3");
if ($res && $res->num_rows > 0) {
  while ($row = $res->fetch_assoc()) {
    echo '<div class="widget-cnt">';
    echo '<div class="wi"><a href="blog-details.php?id=' . h($row['id']) . '" aria-label="Read Cloud Technology Computing blog article"><img loading="lazy" src="' . h($row['image']) . '" alt="' . h($row['title']) . '" width="800" height="600"   >   </a></div>';
    echo '<div class="wc"><h6><a href="blog-details.php?id=' . h($row['id']) . '">' . h($row['title']) . '</a></h6>';
    echo '<a href="blog.php">' . h(date('M d, Y', strtotime($row['post_date']))) . '</a></div></div>';
  }
} else {
  echo "<p>No recent posts found.</p>";
}
?>
