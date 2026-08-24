<?php
include 'includes/header.php';
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!csrf_verify($_POST['csrf'] ?? '')) $msg='Invalid token';
    else{
        $title=trim($_POST['title']); $desc=trim($_POST['description']); $icon=trim($_POST['icon'] ?? 'fa-helmet-safety'); $status=(int)($_POST['status'] ?? 1); $order=(int)($_POST['sort_order'] ?? 0); $id=$_POST['id'] ?? '';
        $image=null;
        if(!empty($_FILES['image']['name'])){
            [$ok,$res]=secure_upload($_FILES['image'], __DIR__.'/../uploads/services');
            if(!$ok) $msg=$res; else $image=$res;
        }
        if(!$msg){
            if($id){
                if($image) $pdo->prepare("UPDATE services SET title=?,description=?,icon=?,image=?,status=?,sort_order=? WHERE id=?")->execute([$title,$desc,$icon,$image,$status,$order,$id]);
                else $pdo->prepare("UPDATE services SET title=?,description=?,icon=?,status=?,sort_order=? WHERE id=?")->execute([$title,$desc,$icon,$status,$order,$id]);
                $msg='Updated';
            } else {
                $pdo->prepare("INSERT INTO services(title,description,icon,image,status,sort_order) VALUES(?,?,?,?,?,?)")->execute([$title,$desc,$icon,$image,$status,$order]);
                $msg='Created';
            }
        }
    }
}
if(isset($_GET['delete']) && csrf_verify($_GET['csrf'] ?? '')){
    $pdo->prepare("DELETE FROM services WHERE id=?")->execute([(int)$_GET['delete']]); header('Location: services.php'); exit;
}
$edit=null; if(isset($_GET['edit'])){ $s=$pdo->prepare("SELECT * FROM services WHERE id=?"); $s->execute([(int)$_GET['edit']]); $edit=$s->fetch(); }
$services=$pdo->query("SELECT * FROM services ORDER BY sort_order, id")->fetchAll();
?>
<h4>Services</h4>
<?php if($msg): ?><div class="alert alert-success py-2"><?= e($msg) ?></div><?php endif; ?>
<div class="card p-3 mt-3">
<form method="post" enctype="multipart/form-data" class="row g-3">
<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
<input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>">
<div class="col-md-4"><label class="form-label">Title</label><input name="title" required class="form-control" value="<?= e($edit['title'] ?? '') ?>"></div>
<div class="col-md-2"><label class="form-label">Icon (fa-*)</label><input name="icon" class="form-control" value="<?= e($edit['icon'] ?? 'fa-helmet-safety') ?>"></div>
<div class="col-md-2"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-control" value="<?= e($edit['sort_order'] ?? 0) ?>"></div>
<div class="col-md-2"><label class="form-label">Status</label><select name="status" class="form-select"><option value="1" <?= ($edit['status']??1)==1?'selected':'' ?>>Enabled</option><option value="0" <?= ($edit['status']??1)==0?'selected':'' ?>>Disabled</option></select></div>
<div class="col-md-2"><label class="form-label">Image</label><input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" class="form-control"></div>
<div class="col-12"><label class="form-label">Description</label><textarea name="description" rows="2" required class="form-control"><?= e($edit['description'] ?? '') ?></textarea></div>
<div class="col-12"><button class="btn btn-primary"><?= $edit?'Update':'Add Service' ?></button> <?php if($edit): ?><a href="services.php" class="btn btn-secondary">Cancel</a><?php endif; ?></div>
</form>
</div>
<table class="table table-bordered mt-3 bg-white">
<thead><tr><th>#</th><th>Title</th><th>Icon</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead>
<tbody><?php foreach($services as $s): ?><tr><td><?= (int)$s['id'] ?></td><td><?= e($s['title']) ?></td><td><i class="fa-solid <?= e($s['icon']) ?>"></i> <?= e($s['icon']) ?></td><td><?= $s['status']?'Enabled':'Disabled' ?></td><td><?= (int)$s['sort_order'] ?></td><td><a href="?edit=<?= (int)$s['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a> <a href="?delete=<?= (int)$s['id'] ?>&csrf=<?= e(csrf_token()) ?>" onclick="return confirm('Delete?')" class="btn btn-sm btn-outline-danger">Delete</a></td></tr><?php endforeach; ?></tbody>
</table>
<?php include 'includes/footer.php'; ?>
