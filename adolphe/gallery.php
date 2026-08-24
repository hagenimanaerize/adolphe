<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
$pageTitle='Gallery'; $pageDesc='Engineering and construction gallery — Adolphe Civil Engineering, Muganza Rwanda.';
include 'includes/header.php';
$cat=$_GET['category'] ?? 'all';
$q=trim($_GET['q'] ?? '');
$where="WHERE 1"; $params=[];
if($cat!=='all'){ $where.=" AND category=?"; $params[]=$cat; }
if($q!==''){ $where.=" AND (title LIKE ? OR caption LIKE ?)"; $params[]="%$q%"; $params[]="%$q%"; }
$stmt=$pdo->prepare("SELECT * FROM gallery $where ORDER BY created_at DESC");
$stmt->execute($params);
$items=$stmt->fetchAll();
$cats=$pdo->query("SELECT DISTINCT category FROM gallery WHERE category IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);
?>
<section class="section" style="padding-top:110px">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4" data-aos="fade-up">
      <div><h1 class="section-title">GALLERY</h1><p class="text-white-50 mb-0">Engineering & construction photographs</p></div>
      <form class="d-flex gap-2" method="get">
        <input type="hidden" name="category" value="<?= e($cat) ?>">
        <input name="q" value="<?= e($q) ?>" class="form-control" placeholder="Search captions...">
        <button class="btn btn-cyan">Search</button>
      </form>
    </div>
    <div class="d-flex flex-wrap gap-2 mb-4">
      <a href="?category=all&q=<?= e($q) ?>" class="btn <?= $cat==='all'?'btn-cyan':'btn-outline-cyan' ?> btn-sm">All</a>
      <?php foreach($cats as $c): ?><a href="?category=<?= e($c) ?>&q=<?= e($q) ?>" class="btn <?= $cat===$c?'btn-cyan':'btn-outline-cyan' ?> btn-sm"><?= e($c) ?></a><?php endforeach; ?>
    </div>
    <div class="row g-4">
      <?php foreach($items as $it): ?>
      <div class="col-6 col-md-4 col-lg-3">
        <div class="gallery-item">
          <img src="uploads/gallery/<?= e($it['image']) ?>" alt="<?= e($it['caption'] ?? $it['title']) ?>" class="lightbox-trigger" loading="lazy">
          <div class="p-2"><small class="text-white-50"><?= e($it['caption'] ?? $it['title']) ?></small><br><span class="badge badge-cyan mt-1"><?= e($it['category']) ?></span></div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if(!$items): ?><div class="col-12 text-center text-white-50 py-5">No images yet.</div><?php endif; ?>
    </div>
  </div>
</section>
<div id="lightbox" class="lightbox"><img src="" alt=""></div>
<?php include 'includes/footer.php'; ?>
