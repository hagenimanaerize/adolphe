<?php
include 'includes/header.php';
$id=(int)($_GET['id'] ?? 0);
$p=$pdo->prepare("SELECT * FROM projects WHERE id=?"); $p->execute([$id]); $proj=$p->fetch();
if(!$proj){ echo '<div class="alert alert-danger">Not found</div>'; include 'includes/footer.php'; exit; }
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!csrf_verify($_POST['csrf'] ?? '')) $error='Invalid token';
    else{
        $title=trim($_POST['title']); $desc=trim($_POST['description']); $cat=$_POST['category']; $loc=trim($_POST['location']); $status=$_POST['status'];
        $start=$_POST['start_date']?:null; $end=$_POST['completion_date']?:null;
        $main=$proj['main_image'];
        if(!empty($_FILES['main_image']['name'])){
            [$ok,$res]=secure_upload($_FILES['main_image'], __DIR__.'/../uploads/projects');
            if(!$ok) $error=$res; else { if($main && file_exists(__DIR__.'/../uploads/projects/'.$main)) unlink(__DIR__.'/../uploads/projects/'.$main); $main=$res; }
        }
        if(!$error){
            $slug=slugify($title);
            // keep unique if changed
            if($slug!==$proj['slug']){
                $chk=$pdo->prepare("SELECT id FROM projects WHERE slug=? AND id<>?"); $chk->execute([$slug,$id]); if($chk->fetch()) $slug.='-'.time();
            } else $slug=$proj['slug'];
            $pdo->prepare("UPDATE projects SET title=?,slug=?,description=?,category=?,location=?,start_date=?,completion_date=?,status=?,main_image=? WHERE id=?")->execute([$title,$slug,$desc,$cat,$loc,$start,$end,$status,$main,$id]);
            if(!empty($_FILES['gallery']['name'][0])){
                foreach($_FILES['gallery']['name'] as $idx=>$name){
                    if(empty($name)) continue;
                    $file=['name'=>$name,'type'=>$_FILES['gallery']['type'][$idx],'tmp_name'=>$_FILES['gallery']['tmp_name'][$idx],'error'=>$_FILES['gallery']['error'][$idx],'size'=>$_FILES['gallery']['size'][$idx]];
                    [$ok,$res]=secure_upload($file, __DIR__.'/../uploads/projects');
                    if($ok) $pdo->prepare("INSERT INTO project_images(project_id,image) VALUES(?,?)")->execute([$id,$res]);
                }
            }
            log_activity($pdo,'update_project',"Updated $title");
            header('Location: projects.php?msg=Project+updated'); exit;
        }
    }
}
// handle gallery delete
if(isset($_GET['del_img'])){
    $imgId=(int)$_GET['del_img'];
    $row=$pdo->prepare("SELECT * FROM project_images WHERE id=? AND project_id=?"); $row->execute([$imgId,$id]); $r=$row->fetch();
    if($r && csrf_verify($_GET['csrf'] ?? '')){ if(file_exists(__DIR__.'/../uploads/projects/'.$r['image'])) unlink(__DIR__.'/../uploads/projects/'.$r['image']); $pdo->prepare("DELETE FROM project_images WHERE id=?")->execute([$imgId]); header("Location: project-edit.php?id=$id"); exit; }
}
$gallery=$pdo->prepare("SELECT * FROM project_images WHERE project_id=?"); $gallery->execute([$id]); $imgs=$gallery->fetchAll();
?>
<h4>Edit Project #<?= (int)$id ?></h4>
<?php if($error): ?><div class="alert alert-danger py-2"><?= e($error) ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="card p-4 mt-3">
<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
<div class="row g-3">
  <div class="col-md-8"><label class="form-label">Title</label><input name="title" required class="form-control" value="<?= e($proj['title']) ?>"></div>
  <div class="col-md-4"><label class="form-label">Category</label><select name="category" class="form-select"><?php foreach(['Residential','Commercial','Roads','Bridges','Infrastructure','Structural','Other'] as $c): ?><option <?= $proj['category']===$c?'selected':'' ?>><?= $c ?></option><?php endforeach; ?></select></div>
  <div class="col-12"><label class="form-label">Description</label><textarea name="description" rows="4" required class="form-control"><?= e($proj['description']) ?></textarea></div>
  <div class="col-md-4"><label class="form-label">Location</label><input name="location" class="form-control" value="<?= e($proj['location']) ?>"></div>
  <div class="col-md-3"><label class="form-label">Start Date</label><input type="date" name="start_date" value="<?= e($proj['start_date']) ?>" class="form-control"></div>
  <div class="col-md-3"><label class="form-label">Completion Date</label><input type="date" name="completion_date" value="<?= e($proj['completion_date']) ?>" class="form-control"></div>
  <div class="col-md-2"><label class="form-label">Status</label><select name="status" class="form-select"><option <?= $proj['status']=='Completed'?'selected':'' ?>>Completed</option><option <?= $proj['status']=='Ongoing'?'selected':'' ?>>Ongoing</option><option <?= $proj['status']=='Planned'?'selected':'' ?>>Planned</option></select></div>
  <div class="col-md-6"><label class="form-label">Main Image</label><?php if($proj['main_image']): ?><img src="../uploads/projects/<?= e($proj['main_image']) ?>" style="height:80px" class="d-block mb-2 rounded"><?php endif; ?><input type="file" name="main_image" accept=".jpg,.jpeg,.png,.webp" class="form-control"></div>
  <div class="col-md-6"><label class="form-label">Add Gallery Images</label><input type="file" name="gallery[]" multiple accept=".jpg,.jpeg,.png,.webp" class="form-control"></div>
  <div class="col-12"><button class="btn btn-primary">Save Changes</button> <a href="projects.php" class="btn btn-secondary">Back</a></div>
</div>
</form>
<div class="card p-3 mt-3">
<h6>Current Gallery</h6>
<div class="d-flex flex-wrap gap-3">
<?php foreach($imgs as $im): ?>
<div class="text-center"><img src="../uploads/projects/<?= e($im['image']) ?>" style="height:90px;width:120px;object-fit:cover" class="rounded"><br><a href="?id=<?= $id ?>&del_img=<?= (int)$im['id'] ?>&csrf=<?= e(csrf_token()) ?>" onclick="return confirm('Delete image?')" class="btn btn-sm btn-danger mt-1">Delete</a></div>
<?php endforeach; ?><?php if(!$imgs): ?><small class="text-muted">No gallery images.</small><?php endif; ?>
</div>
</div>
<?php include 'includes/footer.php'; ?>
