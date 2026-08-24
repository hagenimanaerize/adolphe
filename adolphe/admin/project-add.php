<?php
include 'includes/header.php';
$error=''; $success='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!csrf_verify($_POST['csrf'] ?? '')) $error='Invalid token';
    else{
        $title=trim($_POST['title'] ?? ''); $desc=trim($_POST['description'] ?? ''); $cat=$_POST['category'] ?? 'Residential';
        $loc=trim($_POST['location'] ?? 'Muganza, Rwanda'); $status=$_POST['status'] ?? 'Completed';
        $start=$_POST['start_date']?:null; $end=$_POST['completion_date']?:null;
        if(strlen($title)<3 || strlen($desc)<10) $error='Title and description required';
        else{
            $slug=slugify($title);
            // ensure unique slug
            $base=$slug; $i=1; while($pdo->prepare("SELECT id FROM projects WHERE slug=?")->execute([$slug]) && $pdo->query("SELECT id FROM projects WHERE slug='$slug'")->fetch()){ $slug=$base.'-'.$i++; }
            // check properly with prepared
            $chk=$pdo->prepare("SELECT id FROM projects WHERE slug=?"); $chk->execute([$slug]); if($chk->fetch()){ $slug=$slug.'-'.time(); }
            $main=null;
            if(!empty($_FILES['main_image']['name'])){
                [$ok,$res]=secure_upload($_FILES['main_image'], __DIR__.'/../uploads/projects');
                if(!$ok) $error=$res; else $main=$res;
            }
            if(!$error){
                $stmt=$pdo->prepare("INSERT INTO projects(title,slug,description,category,location,start_date,completion_date,status,main_image) VALUES(?,?,?,?,?,?,?,?,?)");
                $stmt->execute([$title,$slug,$desc,$cat,$loc,$start,$end,$status,$main]);
                $pid=$pdo->lastInsertId();
                // gallery
                if(!empty($_FILES['gallery']['name'][0])){
                    foreach($_FILES['gallery']['name'] as $idx=>$name){
                        if(empty($name)) continue;
                        $file=['name'=>$name,'type'=>$_FILES['gallery']['type'][$idx],'tmp_name'=>$_FILES['gallery']['tmp_name'][$idx],'error'=>$_FILES['gallery']['error'][$idx],'size'=>$_FILES['gallery']['size'][$idx]];
                        [$ok,$res]=secure_upload($file, __DIR__.'/../uploads/projects');
                        if($ok){ $pdo->prepare("INSERT INTO project_images(project_id,image) VALUES(?,?)")->execute([$pid,$res]); }
                    }
                }
                log_activity($pdo,'create_project',"Created $title");
                header('Location: projects.php?msg=Project+created'); exit;
            }
        }
    }
}
?>
<h4>Add Project</h4>
<?php if($error): ?><div class="alert alert-danger py-2"><?= e($error) ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="card p-4 mt-3">
<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
<div class="row g-3">
  <div class="col-md-8"><label class="form-label">Title</label><input name="title" required class="form-control" value="<?= e($_POST['title'] ?? '') ?>"></div>
  <div class="col-md-4"><label class="form-label">Category</label><select name="category" class="form-select"><option>Residential</option><option>Commercial</option><option>Roads</option><option>Bridges</option><option>Infrastructure</option><option>Structural</option><option>Other</option></select></div>
  <div class="col-12"><label class="form-label">Description</label><textarea name="description" rows="4" required class="form-control"><?= e($_POST['description'] ?? '') ?></textarea></div>
  <div class="col-md-4"><label class="form-label">Location</label><input name="location" class="form-control" value="<?= e($_POST['location'] ?? 'Muganza, Rwanda') ?>"></div>
  <div class="col-md-3"><label class="form-label">Start Date</label><input type="date" name="start_date" class="form-control"></div>
  <div class="col-md-3"><label class="form-label">Completion Date</label><input type="date" name="completion_date" class="form-control"></div>
  <div class="col-md-2"><label class="form-label">Status</label><select name="status" class="form-select"><option>Completed</option><option>Ongoing</option><option>Planned</option></select></div>
  <div class="col-md-6"><label class="form-label">Main Image (JPG/PNG/WEBP, max 5MB)</label><input type="file" name="main_image" accept=".jpg,.jpeg,.png,.webp" class="form-control"></div>
  <div class="col-md-6"><label class="form-label">Gallery Images (multiple)</label><input type="file" name="gallery[]" multiple accept=".jpg,.jpeg,.png,.webp" class="form-control"></div>
  <div class="col-12"><button class="btn btn-primary">Create Project</button> <a href="projects.php" class="btn btn-secondary">Cancel</a></div>
</div>
</form>
<?php include 'includes/footer.php'; ?>
