<?php
http_response_code(404);
require_once 'config/database.php';
require_once 'includes/functions.php';
$pageTitle='Page Not Found';
include 'includes/header.php';
?>
<section class="section text-center" style="padding-top:120px">
  <div class="container py-5">
    <h1 class="display-1 fw-bold text-cyan">404</h1>
    <p class="lead text-white-50">Page not found.</p>
    <a href="index.php" class="btn btn-cyan mt-3">Back to Home</a>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
