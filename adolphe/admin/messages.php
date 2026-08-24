<?php
include 'includes/header.php';
if(isset($_GET['toggle']) && csrf_verify($_GET['csrf'] ?? '')){
    $id=(int)$_GET['toggle']; $s=$pdo->prepare("SELECT status FROM messages WHERE id=?"); $s->execute([$id]); $cur=$s->fetchColumn();
    $new=$cur==='read'?'unread':'read'; $pdo->prepare("UPDATE messages SET status=? WHERE id=?")->execute([$new,$id]); header('Location: messages.php'); exit;
}
if(isset($_GET['delete']) && csrf_verify($_GET['csrf'] ?? '')){ $pdo->prepare("DELETE FROM messages WHERE id=?")->execute([(int)$_GET['delete']]); header('Location: messages.php'); exit; }
$q=trim($_GET['q'] ?? '');
$where=''; $params=[];
if($q!==''){ $where="WHERE name LIKE ? OR email LIKE ? OR message LIKE ?"; $params=["%$q%","%$q%","%$q%"]; }
$stmt=$pdo->prepare("SELECT * FROM messages $where ORDER BY created_at DESC");
$stmt->execute($params);
$msgs=$stmt->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center">
  <h4>Messages</h4>
  <form class="d-flex gap-2"><input name="q" value="<?= e($q) ?>" placeholder="Search..." class="form-control"><button class="btn btn-primary">Search</button></form>
</div>
<div class="card mt-3 p-3 table-responsive">
<table class="table table-hover">
<thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Project Type</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
<?php foreach($msgs as $m): ?>
<tr>
  <td><?= e($m['name']) ?></td><td><?= e($m['email']) ?></td><td><?= e($m['phone']) ?></td><td><?= e($m['project_type']) ?></td><td><small><?= e($m['created_at']) ?></small></td><td><span class="badge <?= $m['status']=='unread'?'bg-warning text-dark':'bg-success' ?>"><?= e($m['status']) ?></span></td>
  <td class="d-flex gap-1">
    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#msg<?= (int)$m['id'] ?>">View</button>
    <a href="?toggle=<?= (int)$m['id'] ?>&csrf=<?= e(csrf_token()) ?>" class="btn btn-sm btn-outline-primary"><?= $m['status']=='unread'?'Mark read':'Mark unread' ?></a>
    <a href="?delete=<?= (int)$m['id'] ?>&csrf=<?= e(csrf_token()) ?>" onclick="return confirm('Delete?')" class="btn btn-sm btn-outline-danger">Delete</a>
  </td>
</tr>
<!-- Modal -->
<div class="modal fade" id="msg<?= (int)$m['id'] ?>" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h6><?= e($m['name']) ?> — <?= e($m['email']) ?></h6><button class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><p><strong>Phone:</strong> <?= e($m['phone']) ?> | <strong>Location:</strong> <?= e($m['location']) ?> | <strong>Type:</strong> <?= e($m['project_type']) ?></p><p><?= nl2br(e($m['message'])) ?></p></div></div></div></div>
<?php endforeach; ?>
</tbody>
</table>
</div>
<?php include 'includes/footer.php'; ?>
