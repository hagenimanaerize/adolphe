<div class="sidebar">
  <h6 class="text-cyan fw-bold"><i class="fa-solid fa-helmet-safety me-2"></i>ADOLPHE ADMIN</h6>
  <small class="text-secondary"><?= e($_SESSION['admin_username'] ?? '') ?></small>
  <nav class="mt-4 d-flex flex-column gap-1">
    <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF'])==='dashboard.php'?'active':'' ?>"><i class="fa-solid fa-gauge"></i> Dashboard</a>
    <a href="projects.php" class="<?= str_contains(basename($_SERVER['PHP_SELF']),'project')?'active':'' ?>"><i class="fa-solid fa-building"></i> Projects</a>
    <a href="gallery.php" class="<?= basename($_SERVER['PHP_SELF'])==='gallery.php'?'active':'' ?>"><i class="fa-solid fa-images"></i> Gallery</a>
    <a href="services.php" class="<?= basename($_SERVER['PHP_SELF'])==='services.php'?'active':'' ?>"><i class="fa-solid fa-helmet-safety"></i> Services</a>
    <a href="testimonials.php" class="<?= basename($_SERVER['PHP_SELF'])==='testimonials.php'?'active':'' ?>"><i class="fa-solid fa-star"></i> Testimonials</a>
    <a href="messages.php" class="<?= basename($_SERVER['PHP_SELF'])==='messages.php'?'active':'' ?>"><i class="fa-solid fa-envelope"></i> Messages</a>
    <a href="about.php" class="<?= basename($_SERVER['PHP_SELF'])==='about.php'?'active':'' ?>"><i class="fa-solid fa-user-tie"></i> About</a>
    <a href="settings.php" class="<?= basename($_SERVER['PHP_SELF'])==='settings.php'?'active':'' ?>"><i class="fa-solid fa-gear"></i> Settings</a>
    <a href="logout.php" class="mt-3 text-danger"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
  </nav>
  <a href="../index.php" target="_blank" class="btn btn-outline-light btn-sm w-100 mt-3">View Site</a>
</div>
