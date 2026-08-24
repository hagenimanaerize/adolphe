<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
$about = $pdo->query("SELECT * FROM about ORDER BY id DESC LIMIT 1")->fetch();
$pageTitle='About — '.($about['engineer_name'] ?? 'Adolphe');
$pageDesc=mb_strimwidth($about['bio'] ?? '',0,150,'...');
include 'includes/header.php';
?>
<section class="section" style="padding-top:110px">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-5" data-aos="fade-right">
        <div class="glass-card p-3">
          <?php $prof = $about['profile_image'] ? 'uploads/profile/'.e($about['profile_image']) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=600&q=80'; ?>
          <img src="<?= $prof ?>" alt="<?= e($about['engineer_name']) ?>" class="w-100 rounded-4" style="height:520px;object-fit:cover">
        </div>
      </div>
      <div class="col-lg-7" data-aos="fade-left">
        <span class="badge badge-cyan mb-2">Civil Engineer • Muganza, Rwanda</span>
        <h1 class="section-title"><?= e($about['engineer_name']) ?></h1>
        <p class="text-cyan fw-semibold"><?= e($about['title']) ?></p>
        <p class="text-white-50"><?= nl2br(e($about['bio'])) ?></p>
        <div class="row g-3 mt-3">
          <div class="col-md-6"><div class="glass-card p-3"><h6><i class="fa-solid fa-award text-cyan me-2"></i>Experience</h6><p class="small text-white-50 mb-0"><?= nl2br(e($about['experience'])) ?></p></div></div>
          <div class="col-md-6"><div class="glass-card p-3"><h6><i class="fa-solid fa-lightbulb text-cyan me-2"></i>Philosophy</h6><p class="small text-white-50 mb-0"><?= nl2br(e($about['philosophy'])) ?></p></div></div>
          <div class="col-12"><div class="glass-card p-3"><h6><i class="fa-solid fa-screwdriver-wrench text-cyan me-2"></i>Skills</h6><p class="small text-white-50 mb-0"><?= e($about['skills']) ?></p></div></div>
        </div>
        <p class="mt-3 small text-white-50"><i class="fa-solid fa-location-dot text-cyan me-2"></i><?= e($about['location']) ?></p>
        <a href="contact.php" class="btn btn-cyan mt-2">Work With Adolphe</a>
      </div>
    </div>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
