<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
$pageTitle='Projects'; $pageDesc='Project portfolio - Adolphe Civil Engineering, Muganza Rwanda.';
include 'includes/header.php';
$cats=['All','Residential','Commercial','Roads','Bridges','Infrastructure','Structural','Other'];
$activeCat=$_GET['category'] ?? 'All';
$search=trim($_GET['q'] ?? '');
$where="WHERE 1"; $params=[];
if($activeCat!=='All' && in_array($activeCat,$cats)){ $where.=" AND category=?"; $params[]=$activeCat; }
if($search!==''){ $where.=" AND (title LIKE ? OR description LIKE ? OR location LIKE ?)"; $params[]="%$search%"; $params[]="%$search%"; $params[]="%$search%"; }
$stmt=$pdo->prepare("SELECT * FROM projects $where ORDER BY created_at DESC");
$stmt->execute($params);
$projects=$stmt->fetchAll();
?>
<section class="section" style="padding-top:110px">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4" data-aos="fade-up">
      <div><h1 class="section-title mb-1">PROJECT <span>PORTFOLIO</span></h1><p class="text-white-50 mb-0">Search and filter by category</p></div>
      <form class="d-flex gap-2" method="get">
        <input type="hidden" name="category" value="<?= e($activeCat) ?>">
        <input id="liveSearch" name="q" value="<?= e($search) ?>" class="form-control" placeholder="Search projects..." style="min-width:220px">
        <button class="btn btn-cyan">Search</button>
      </form>
    </div>
    <div class="d-flex flex-wrap gap-2 mb-4" data-aos="fade-up">
      <?php foreach($cats as $c): $is=($c===$activeCat); ?>
        <a href="?category=<?= e($c) ?>&q=<?= e($search) ?>" class="btn <?= $is?'btn-cyan':'btn-outline-cyan' ?> btn-sm"><?= e($c) ?></a>
      <?php endforeach; ?>
    </div>
    <div class="row g-4">
      <?php foreach($projects as $p): ?>
      <div class="col-md-6 col-lg-4 search-item" data-aos="fade-up">
        <div class="project-card h-100">
          <?php $img=$p['main_image']?'uploads/projects/'.e($p['main_image']):'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=600&q=80'; ?>
          <img src="<?= $img ?>" alt="<?= e($p['title']) ?>" class="w-100" loading="lazy">
          <div class="p-3">
            <div class="d-flex gap-2 mb-2"><span class="badge badge-cyan"><?= e($p['category']) ?></span><span class="badge bg-light text-dark"><?= e($p['status']) ?></span></div>
            <h6><?= e($p['title']) ?></h6>
            <p class="small text-white-50 mb-2"><?= e(mb_strimwidth($p['description'],0,90,'...')) ?></p>
            <p class="small text-white-50"><i class="fa-solid fa-location-dot me-1 text-cyan"></i><?= e($p['location']) ?> • <?= e($p['start_date'] ?? '') ?></p>
            <a href="project.php?id=<?= (int)$p['id'] ?>" class="btn btn-outline-cyan btn-sm w-100">View Details</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if(!$projects): ?><div class="col-12 text-center text-white-50 py-5">No projects found.</div><?php endif; ?>
    </div>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
