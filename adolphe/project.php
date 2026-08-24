<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
$id=(int)($_GET['id'] ?? 0);
$stmt=$pdo->prepare("SELECT * FROM projects WHERE id=?");
$stmt->execute([$id]);
$p=$stmt->fetch();
if(!$p){ http_response_code(404); $pageTitle='Not Found'; include 'includes/header.php'; echo '<section class="section" style="padding-top:110px"><div class="container text-center py-5"><h2>Project not found</h2><a href="projects.php" class="btn btn-cyan mt-3">Back to Projects</a></div></section>'; include 'includes/footer.php'; exit; }
$pageTitle=e($p['title']); $pageDesc=mb_strimwidth($p['description'],0,150,'...');
include 'includes/header.php';
$images=$pdo->prepare("SELECT * FROM project_images WHERE project_id=? ORDER BY id");
$images->execute([$id]); $gallery=$images->fetchAll();
?>
<section class="section" style="padding-top:100px">
  <div class="container">
    <a href="projects.php" class="btn btn-outline-cyan btn-sm mb-3"><i class="fa-solid fa-arrow-left me-2"></i>Back</a>
    <div class="row g-4">
      <div class="col-lg-8" data-aos="fade-up">
        <?php $main=$p['main_image']?'uploads/projects/'.e($p['main_image']):'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=900&q=80'; ?>
        <img src="<?= $main ?>" alt="<?= e($p['title']) ?>" class="w-100 rounded-4 mb-3" style="max-height:520px;object-fit:cover">
        <?php if($gallery): ?>
        <h5 class="mt-2">Gallery</h5>
        <div class="row g-3">
          <?php foreach($gallery as $g): ?>
          <div class="col-6 col-md-4">
            <img src="uploads/projects/<?= e($g['image']) ?>" alt="<?= e($g['caption'] ?? $p['title']) ?>" class="w-100 rounded-3 lightbox-trigger" style="height:180px;object-fit:cover;cursor:zoom-in" loading="lazy">
            <?php if($g['caption']): ?><small class="text-white-50"><?= e($g['caption']) ?></small><?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <div class="col-lg-4" data-aos="fade-left">
        <div class="glass-card p-4">
          <span class="badge badge-cyan"><?= e($p['category']) ?></span> <span class="badge bg-light text-dark"><?= e($p['status']) ?></span>
          <h2 class="h4 mt-3"><?= e($p['title']) ?></h2>
          <p class="small text-white-50"><i class="fa-solid fa-location-dot text-cyan me-1"></i><?= e($p['location']) ?></p>
          <p class="small text-white-50"><i class="fa-regular fa-calendar text-cyan me-1"></i><?= e($p['start_date'] ?? '') ?> <?php if($p['completion_date']) echo ' → '.e($p['completion_date']); ?></p>
          <hr class="border-secondary">
          <p class="small"><?= nl2br(e($p['description'])) ?></p>
          <a href="contact.php" class="btn btn-cyan w-100 mt-3">Request Similar Project</a>
        </div>
      </div>
    </div>
  </div>
</section>
<div id="lightbox" class="lightbox"><img src="" alt=""></div>
<?php include 'includes/footer.php'; ?>
