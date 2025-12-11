<?php
// widgets/comments.php
// Expects: $mysqli (from includes/db.php) and helpers (h(), csrf_field(), csrf_check()).
// If not already loaded, we fall back to loading them here.

if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

if (!isset($mysqli) || !($mysqli instanceof mysqli)) {
  require_once __DIR__ . '../includes/db.php';      // <-- correct relative path
}
require_once __DIR__ . '../includes/helpers.php';   // <-- correct relative path

$post_id = isset($_GET['id']) ? max(1, (int)$_GET['id']) : 1;
$flash = '';

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // CSRF
  if (!csrf_check()) {
    $flash = "<p class='alert alert-danger'>Your session expired. Please try again.</p>";
  }
  // Honeypot
  elseif (!empty($_POST['website'] ?? '')) {
    $flash = "<p class='alert alert-danger'>Spam detected.</p>";
  }
  else {
    $name    = trim((string)($_POST['name'] ?? ''));
    $email   = trim((string)($_POST['email'] ?? ''));
    $subject = trim((string)($_POST['subject'] ?? ''));
    $message = trim((string)($_POST['message'] ?? ''));

    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && $message) {
      $stmt = $mysqli->prepare(
        "INSERT INTO comments (post_id, name, email, subject, message) VALUES (?, ?, ?, ?, ?)"
      );
      if ($stmt) {
        $stmt->bind_param('issss', $post_id, $name, $email, $subject, $message);
        if ($stmt->execute()) {
          $flash = "<p class='alert alert-success'>Thanks! Your comment is awaiting moderation.</p>";
        } else {
          error_log('comments insert error: ' . $mysqli->error);
          $flash = "<p class='alert alert-danger'>We couldn’t save your comment (DB error).</p>";
        }
        $stmt->close();
      } else {
        error_log('prepare failed: ' . $mysqli->error);
        $flash = "<p class='alert alert-danger'>We couldn’t save your comment (prepare error).</p>";
      }
    } else {
      $flash = "<p class='alert alert-warning'>Please enter your name, a valid email, and a message.</p>";
    }
  }
}

// List comments
$stmt = $mysqli->prepare("SELECT name, message, created_at FROM comments WHERE post_id = ? AND (approved = 1 OR approved IS NULL) ORDER BY created_at DESC");
$stmt->bind_param('i', $post_id);
$stmt->execute();
$res = $stmt->get_result();
$comment_total = $res ? $res->num_rows : 0;
?>
<div class="comments-area sec-mar">
  <?php if ($flash) echo $flash; ?>
  <h3><?php echo (int)$comment_total; ?> Comment(s)</h3>
  <?php if ($comment_total > 0): ?>
    <?php while ($row = $res->fetch_assoc()): ?>
      <div class="single-comment">
        <div class="author-thumb">
          <img loading="lazy" src="/assets/img/inner-pages/hackerJhonBG.avif" alt="User avatar">
        </div>
        <div class="comment-content">
          <div class="author-post">
            <div class="author-info">
              <h4><?php echo h($row['name']); ?></h4>
              <span><?php echo h(date('d M, Y h:i a', strtotime($row['created_at']))); ?></span>
            </div>
          </div>
          <p><?php echo nl2br(h($row['message'])); ?></p>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No comments yet. Be the first to comment!</p>
  <?php endif; ?>
</div>

<div class="comment-form">
  <h3>Leave a comment</h3>
  <form action="<?php echo h($_SERVER['PHP_SELF']) . '?id=' . (int)$post_id; ?>" method="POST" novalidate>
    <?php csrf_field(); ?>
    <input type="hidden" name="post_id" value="<?php echo (int)$post_id; ?>">
    <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">
    <div class="row">
      <div class="col-md-12 mb-40">
        <div class="form-inner">
          <input type="text" name="name" placeholder="Enter your name" required>
        </div>
      </div>
      <div class="col-md-6 mb-40">
        <div class="form-inner">
          <input type="email" name="email" placeholder="Enter your email" required>
        </div>
      </div>
      <div class="col-md-6 mb-40">
        <div class="form-inner">
          <input type="text" name="subject" placeholder="Subject">
        </div>
      </div>
      <div class="col-12 mb-40">
        <div class="form-inner">
          <textarea name="message" placeholder="Your message" required></textarea>
        </div>
      </div>
      <div class="col-12">
        <div class="form-inner">
          <button class="primary-btn3" type="submit">Post a Comment</button>
        </div>
      </div>
    </div>
  </form>
</div>
<?php $stmt->close(); ?>
