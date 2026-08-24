<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
$pageTitle='Home'; $pageDesc='Adolphe Civil Engineering — Engineering the Future. Building with Precision. Muganza, Rwanda.';
include 'includes/header.php';

// stats editable
$stats = $pdo->query("SELECT * FROM site_stats ORDER BY id")->fetchAll();
// services preview
$services = $pdo->query("SELECT * FROM services WHERE status=1 ORDER BY sort_order LIMIT 6")->fetchAll();
$projects = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC LIMIT 6")->fetchAll();
$testimonials = $pdo->query("SELECT * FROM testimonials WHERE status='approved' ORDER BY created_at DESC LIMIT 6")->fetchAll();
$heroTitle=get_setting($pdo,'hero_title','ADOLPHE CIVIL ENGINEERING');
$heroSub=get_setting($pdo,'hero_subtitle','Engineering the Future. Building with Precision.');
$heroDesc=get_setting($pdo,'hero_description','Professional civil engineering, structural design, construction supervision and infrastructure solutions based in Muganza, Rwanda.');
$heroSideImg=get_setting($pdo,'hero_side_image','');
$heroSideTitle=get_setting($pdo,'hero_side_title','Precision Engineering');
$heroSideDesc=get_setting($pdo,'hero_side_desc','Blueprint-accurate design & supervision for lasting infrastructure.');
$heroSideSrc = $heroSideImg ? 'uploads/hero/'.e($heroSideImg) : 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=800&q=80';
?>
<section class="hero">
  <div class="hero-image"></div>
  <div class="container position-relative">
    <div class="row align-items-center">
      <div class="col-lg-7" data-aos="fade-up">
        <div class="hero-card">
          <span class="badge badge-cyan mb-3"><i class="fa-solid fa-location-dot me-1"></i> Muganza, Rwanda</span>
          <h1 class="hero-title"><?= e($heroTitle) ?></h1>
          <p class="lead text-cyan fw-semibold mt-2"><?= e($heroSub) ?></p>
          <p class="text-white-50 mt-3"><?= e($heroDesc) ?></p>
          <div class="d-flex flex-wrap gap-3 mt-4">
            <a href="contact.php" class="btn btn-cyan px-4 py-2">Request Consultation <i class="fa-solid fa-arrow-right ms-2"></i></a>
            <a href="projects.php" class="btn btn-outline-cyan px-4 py-2">View Projects</a>
          </div>
          <div class="row g-3 hero-stats">
            <?php foreach($stats as $s): ?>
            <div class="col-6 col-md-3">
              <div class="stat-card">
                <div class="stat-num" data-count="<?= (int)$s['stat_value'] ?>"><?= (int)$s['stat_value'] ?></div>
                <div class="small text-white-50"><?= e($s['stat_label']) ?></div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="col-lg-5 d-none d-lg-block" data-aos="fade-left">
        <div class="glass-card p-3 ms-lg-4">
          <img src="<?= $heroSideSrc ?>" alt="Engineering" class="w-100 rounded-4" loading="lazy" style="height:280px;object-fit:cover">
          <div class="p-3">
            <h6 class="fw-bold mb-1"><i class="fa-solid fa-compass-drafting text-cyan me-2"></i> <?= e($heroSideTitle) ?></h6>
            <p class="small text-white-50 mb-0"><?= e($heroSideDesc) ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <h2 class="section-title">OUR <span>SERVICES</span></h2>
      <p class="text-white-50">Comprehensive engineering solutions for every stage of construction</p>
    </div>
    <div class="row g-4">
      <?php foreach($services as $svc): ?>
      <div class="col-md-6 col-lg-4" data-aos="fade-up">
        <div class="glass-card p-4 h-100">
          <div class="icon-box mb-3"><i class="fa-solid <?= e($svc['icon']) ?>"></i></div>
          <h5><?= e($svc['title']) ?></h5>
          <p class="small text-white-50"><?= e(mb_strimwidth($svc['description'],0,120,'...')) ?></p>
          <a href="services.php" class="small text-cyan">Learn more <i class="fa-solid fa-arrow-right ms-1"></i></a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-4"><a href="services.php" class="btn btn-outline-cyan">View All Services</a></div>
  </div>
</section>

<section class="section pt-0">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2" data-aos="fade-up">
      <div><h2 class="section-title mb-1">FEATURED <span>PROJECTS</span></h2><p class="text-white-50 mb-0">Selected works across Muganza & Rwanda</p></div>
      <a href="projects.php" class="btn btn-cyan btn-sm">View Portfolio</a>
    </div>
    <div class="row g-4">
      <?php foreach($projects as $p): ?>
      <div class="col-md-6 col-lg-4" data-aos="fade-up">
        <div class="project-card h-100">
          <?php $img = $p['main_image'] ? 'uploads/projects/'.e($p['main_image']) : 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=600&q=80'; ?>
          <img src="<?= $img ?>" alt="<?= e($p['title']) ?>" class="w-100" loading="lazy">
          <div class="p-3">
            <span class="badge badge-cyan"><?= e($p['category']) ?></span>
            <h6 class="mt-2 mb-1"><?= e($p['title']) ?></h6>
            <p class="small text-white-50 mb-2"><i class="fa-solid fa-location-dot me-1"></i><?= e($p['location']) ?> • <?= e($p['status']) ?></p>
            <a href="project.php?id=<?= (int)$p['id'] ?>" class="small text-cyan">View details →</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php if($testimonials): ?>
<section class="section pt-0">
  <div class="container">
    <h3 class="section-title text-center mb-4" data-aos="fade-up">CLIENT <span>TESTIMONIALS</span></h3>
    <div class="row g-4">
      <?php foreach($testimonials as $t): ?>
      <div class="col-md-6 col-lg-4" data-aos="fade-up">
        <div class="glass-card p-4 h-100">
          <div class="text-warning mb-2"><?= str_repeat('★', (int)$t['rating']) ?><?= str_repeat('☆',5-(int)$t['rating']) ?></div>
          <p class="small">"<?= e($t['review']) ?>"</p>
          <strong class="small"><?= e($t['client_name']) ?></strong>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section">
  <div class="container">
    <div class="glass-card p-4 p-lg-5 d-flex flex-wrap justify-content-between align-items-center gap-3" data-aos="zoom-in">
      <div><h3 class="fw-bold mb-1">Ready to build with precision?</h3><p class="text-white-50 mb-0">Based in Muganza, serving all Rwanda. Let's discuss your project.</p></div>
      <a href="contact.php" class="btn btn-cyan px-4">Start Your Project</a>
    </div>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
