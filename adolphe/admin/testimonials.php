<?php
include 'includes/header.php';
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!csrf_verify($_POST['csrf'] ?? '')) $msg='Invalid token';
    else{
        $name=trim($_POST['client_name']); $review=trim($_POST['review']); $rating=(int)$_POST['rating']; $status=$_POST['status'] ?? 'pending'; $id=$_POST['id'] ?? '';
        $photo=null;
        if(!empty($_FILES['client_photo']['name'])){
            [$ok,$res]=secure_upload($_FILES['client_photo'], __DIR__.'/../uploads/profile');
            if(!$ok) $msg=$res; else $photo=$res;
        }
        if(!$msg){
            if($id){
                if($photo) $pdo->prepare("UPDATE testimonials SET client_name=?,review=?,rating=?,status=?,client_photo=? WHERE id=?")->execute([$name,$review,$rating,$status,$photo,$id]);
                else $pdo->prepare("UPDATE testimonials SET client_name=?,review=?,rating=?,status=? WHERE id=?")->execute([$name,$review,$rating,$status,$id]);
                $msg='Updated';
            } else {
                $pdo->prepare("INSERT INTO testimonials(client_name,client_photo,review,rating,status) VALUES(?,?,?,?,?)")->execute([$name,$photo,$review,$rating,$status]);
                $msg='Created';
            }
        }
    }
}
if(isset($_GET['delete']) && csrf_verify($_GET['csrf'] ?? '')){ $pdo->prepare("DELETE FROM testimonials WHERE id=?")->execute([(int)$_GET['delete']]); header('Location: testimonials.php'); exit; }
$edit=null; if(isset($_GET['edit'])){ $s=$pdo->prepare("SELECT * FROM testimonials WHERE id=?"); $s->execute([(int)$_GET['edit']]); $edit=$s->fetch(); }
$list=$pdo->query("SELECT * FROM testimonials ORDER BY created_at DESC")->fetchAll();
?>
<h4>Testimonials</h4>
<?php if($msg): ?><div class="alert alert-success py-2"><?= e($msg) ?></div><?php endif; ?>
<div class="card p-3 mt-3">
<form method="post" enctype="multipart/form-data" class="row g-3">
<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= e($edit['id'] ?? '') ?>">
<div class="col-md-4"><label class="form-label">Client Name</label><input name="client_name" required class="form-control" value="<?= e($edit['client_name'] ?? '') ?>"></div>
<div class="col-md-2"><label class="form-label">Rating</label><select name="rating" class="form-select"><?php for($i=1;$i<=5;$i++): ?><option value="<?= $i ?>" <?= ($edit['rating']??5)==$i?'selected':'' ?>><?= $i ?></option><?php endfor; ?></select></div>
<div class="col-md-3"><label class="form-label">Status</label><select name="status" class="form-select"><option value="pending" <?= ($edit['status']??'')=='pending'?'selected':'' ?>>Pending</option><option value="approved" <?= ($edit['status']??'')=='approved'?'selected':'' ?>>Approved</option><option value="rejected" <?= ($edit['status']??'')=='rejected'?'selected':'' ?>>Rejected</option></select></div>
<div class="col-md-3"><label class="form-label">Photo</label><input type="file" name="client_photo" accept=".jpg,.jpeg,.png,.webp" class="form-control"></div>
<div class="col-12"><label class="form-label">Review</label><textarea name="review" rows="3" required class="form-control"><?= e($edit['review'] ?? '') ?></textarea></div>
<div class="col-12"><button class="btn btn-primary"><?= $edit?'Update':'Add' ?></button> <?php if($edit): ?><a href="testimonials.php" class="btn btn-secondary">Cancel</a><?php endif; ?></div>
</form>
</div>
<table class="table table-bordered mt-3 bg-white">
<thead><tr><th>Client</th><th>Rating</th><th>Status</th><th>Review</th><th>Actions</th></tr></thead>
<tbody><?php foreach($list as $t): ?><tr><td><?= e($t['client_name']) ?></td><td><?= str_repeat('★',(int)$t['rating']) ?></td><td><span class="badge bg-<?= $t['status']=='approved'?'success':($t['status']=='rejected'?'danger':'warning') ?>"><?= e($t['status']) ?></span></td><td><?= e(mb_strimwidth($t['review'],0,80,'...')) ?></td><td><a href="?edit=<?= (int)$t['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a> <a href="?delete=<?= (int)$t['id'] ?>&csrf=<?= e(csrf_token()) ?>" onclick="return confirm('Delete?')" class="btn btn-sm btn-outline-danger">Delete</a></td></tr><?php endforeach; ?></tbody>
</table>
<?php include 'includes/footer.php'; ?>
