<?php
include 'includes/header.php';
$counts=[
 'projects'=>$pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn(),
 'gallery'=>$pdo->query("SELECT COUNT(*) FROM gallery")->fetchColumn(),
 'services'=>$pdo->query("SELECT COUNT(*) FROM services")->fetchColumn(),
 'testimonials'=>$pdo->query("SELECT COUNT(*) FROM testimonials")->fetchColumn(),
 'messages'=>$pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn(),
];
$recentProjects=$pdo->query("SELECT * FROM projects ORDER BY created_at DESC LIMIT 5")->fetchAll();
$recentMessages=$pdo->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 5")->fetchAll();
$logs=$pdo->query("SELECT l.*, a.username FROM activity_logs l LEFT JOIN admins a ON a.id=l.admin_id ORDER BY l.created_at DESC LIMIT 8")->fetchAll();
?>
<h3 class="fw-bold">Dashboard</h3>
<p class="text-muted">Overview — Muganza, Rwanda</p>
<div class="row g-3">
  <?php foreach(['projects'=>'Projects','gallery'=>'Gallery Images','services'=>'Services','testimonials'=>'Testimonials','messages'=>'Messages'] as $k=>$label): ?>
  <div class="col-6 col-lg"><div class="card p-3 text-center"><div class="h3 fw-bold"><?= (int)$counts[$k] ?></div><small class="text-muted"><?= $label ?></small></div></div>
  <?php endforeach; ?>
</div>
<div class="row g-4 mt-1">
  <div class="col-lg-6"><div class="card p-3"><h6>Recent Projects</h6><ul class="list-group list-group-flush small"><?php foreach($recentProjects as $p): ?><li class="list-group-item d-flex justify-content-between"><span><?= e($p['title']) ?> <small class="text-muted">[<?= e($p['category']) ?>]</small></span><span><?= e($p['status']) ?></span></li><?php endforeach; ?></ul></div></div>
  <div class="col-lg-6"><div class="card p-3"><h6>Recent Messages</h6><ul class="list-group list-group-flush small"><?php foreach($recentMessages as $m): ?><li class="list-group-item d-flex justify-content-between"><span><?= e($m['name']) ?> — <?= e($m['email']) ?></span><span class="badge <?= $m['status']=='unread'?'bg-warning text-dark':'bg-success' ?>"><?= e($m['status']) ?></span></li><?php endforeach; ?><?php if(!$recentMessages): ?><li class="list-group-item text-muted">No messages</li><?php endif; ?></ul></div></div>
  <div class="col-12"><div class="card p-3"><h6>Recent Activity</h6><ul class="small text-muted mb-0"><?php foreach($logs as $l): ?><li><?= e($l['created_at']) ?> — <strong><?= e($l['username'] ?? 'system') ?></strong>: <?= e($l['action']) ?> <?= e($l['description']) ?></li><?php endforeach; ?><?php if(!$logs): ?><li>No activity yet.</li><?php endif; ?></ul></div></div>
</div>
<?php include 'includes/footer.php'; ?>
