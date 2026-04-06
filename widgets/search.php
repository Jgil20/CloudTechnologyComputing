<?php
// widgets/search.php
// Drop-in secure search. Include this inside your search form container.

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/db.php';

$q = '';
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['s'])) {
  $q = trim((string)$_GET['s']);
  if ($q !== '') {
    $like = '%' . $q . '%';
    $stmt = $mysqli->prepare("SELECT id, title, SUBSTRING(content,1,250) as snippet FROM posts WHERE title LIKE ? OR content LIKE ? ORDER BY post_date DESC LIMIT 20");
    $stmt->bind_param('ss', $like, $like);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
      echo '<div class="search-results">';
      while ($row = $res->fetch_assoc()) {
        echo '<div class="search-item">';
        echo '<h2><a href="blog-details.php?id=' . h($row['id']) . '">' . h($row['title']) . '</a></h2>';
        echo '<p>' . h($row['snippet']) . '...</p>';
        echo '</div>';
      }
      echo '</div>';
    } else {
      echo '<p>No results for <strong>' . h($q) . '</strong>.</p>';
    }
    $stmt->close();
  }
}
?>
