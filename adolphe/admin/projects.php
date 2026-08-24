<?php
include 'includes/header.php';
$projects=$pdo->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center">
  <h4>Projects</h4><a href="project-add.php" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus me-1"></i> Add Project</a>
</div>
<?php if(isset($_GET['msg'])): ?><div class="alert alert-success py-2 small"><?= e($_GET['msg']) ?></div><?php endif; ?>
<div class="card mt-3 p-3 table-responsive">
<table class="table table-hover align-middle">
<thead><tr><th>#</th><th>Title</th><th>Category</th><th>Location</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
<?php foreach($projects as $p): ?>
<tr>
  <td><?= (int)$p['id'] ?></td><td><?= e($p['title']) ?></td><td><?= e($p['category']) ?></td><td><?= e($p['location']) ?></td><td><span class="badge bg-info"><?= e($p['status']) ?></span></td>
  <td class="d-flex gap-1">
    <a href="project-edit.php?id=<?= (int)$p['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></a>
    <a href="project-delete.php?id=<?= (int)$p['id'] ?>" onclick="return confirm('Delete this project?')" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></a>
  </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<?php include 'includes/footer.php'; ?>
