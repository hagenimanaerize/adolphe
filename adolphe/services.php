<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
$pageTitle='Services'; $pageDesc='Civil engineering services in Muganza, Rwanda - structural design, supervision, roads and more.';
include 'includes/header.php';
$services=$pdo->query("SELECT * FROM services WHERE status=1 ORDER BY sort_order, id")->fetchAll();
?>
<section class="section" style="padding-top:110px">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <h1 class="section-title">OUR <span>SERVICES</span></h1>
      <p class="text-white-50">Professional engineering solutions in Muganza, Rwanda</p>
    </div>
    <div class="row g-4">
      <?php foreach($services as $s): ?>
      <div class="col-md-6 col-lg-3" data-aos="fade-up">
        <div class="glass-card p-4 h-100 text-center">
          <?php if($s['image']): ?><img src="uploads/services/<?= e($s['image']) ?>" alt="" class="w-100 rounded-3 mb-3" style="height:160px;object-fit:cover"><?php endif; ?>
          <div class="icon-box mx-auto mb-3"><i class="fa-solid <?= e($s['icon']) ?>"></i></div>
          <h5><?= e($s['title']) ?></h5>
          <p class="small text-white-50"><?= nl2br(e($s['description'])) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
