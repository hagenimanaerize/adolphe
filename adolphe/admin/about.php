<?php
include 'includes/header.php';
$about=$pdo->query("SELECT * FROM about ORDER BY id DESC LIMIT 1")->fetch();
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!csrf_verify($_POST['csrf'] ?? '')) $msg='Invalid token';
    else{
        $name=trim($_POST['engineer_name']); $title=trim($_POST['title']); $bio=trim($_POST['bio']); $exp=trim($_POST['experience']); $skills=trim($_POST['skills']); $phil=trim($_POST['philosophy']); $loc=trim($_POST['location']);
        $img=$about['profile_image'];
        if(!empty($_FILES['profile_image']['name'])){
            [$ok,$res]=secure_upload($_FILES['profile_image'], __DIR__.'/../uploads/profile');
            if(!$ok) $msg=$res; else { if($img && file_exists(__DIR__.'/../uploads/profile/'.$img)) unlink(__DIR__.'/../uploads/profile/'.$img); $img=$res; }
        }
        if(!$msg){
            $pdo->prepare("UPDATE about SET engineer_name=?,title=?,bio=?,experience=?,skills=?,philosophy=?,location=?,profile_image=? WHERE id=?")->execute([$name,$title,$bio,$exp,$skills,$phil,$loc,$img,$about['id']]);
            $msg='Updated'; $about=$pdo->query("SELECT * FROM about WHERE id={$about['id']}")->fetch();
        }
    }
}
?>
<h4>About Section</h4>
<?php if($msg): ?><div class="alert alert-success py-2"><?= e($msg) ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="card p-4 mt-3">
<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
<div class="row g-3">
  <div class="col-md-6"><label class="form-label">Engineer Name</label><input name="engineer_name" required class="form-control" value="<?= e($about['engineer_name']) ?>"></div>
  <div class="col-md-6"><label class="form-label">Professional Title</label><input name="title" required class="form-control" value="<?= e($about['title']) ?>"></div>
  <div class="col-12"><label class="form-label">Biography</label><textarea name="bio" rows="4" class="form-control"><?= e($about['bio']) ?></textarea></div>
  <div class="col-md-6"><label class="form-label">Experience</label><textarea name="experience" rows="3" class="form-control"><?= e($about['experience']) ?></textarea></div>
  <div class="col-md-6"><label class="form-label">Philosophy</label><textarea name="philosophy" rows="3" class="form-control"><?= e($about['philosophy']) ?></textarea></div>
  <div class="col-md-8"><label class="form-label">Skills (comma separated)</label><input name="skills" class="form-control" value="<?= e($about['skills']) ?>"></div>
  <div class="col-md-4"><label class="form-label">Location</label><input name="location" class="form-control" value="<?= e($about['location']) ?>"></div>
  <div class="col-md-6"><label class="form-label">Profile Image</label><?php if($about['profile_image']): ?><img src="../uploads/profile/<?= e($about['profile_image']) ?>" style="height:80px" class="d-block mb-2 rounded"><?php endif; ?><input type="file" name="profile_image" accept=".jpg,.jpeg,.png,.webp" class="form-control"></div>
  <div class="col-12"><button class="btn btn-primary">Save</button></div>
</div>
</form>
<?php include 'includes/footer.php'; ?>
