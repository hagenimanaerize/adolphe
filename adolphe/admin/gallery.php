<?php
include 'includes/header.php';
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_FILES['images'])){
    if(!csrf_verify($_POST['csrf'] ?? '')) $msg='Invalid token';
    else{
        $title=trim($_POST['title'] ?? ''); $caption=trim($_POST['caption'] ?? ''); $category=trim($_POST['category'] ?? 'General'); $project_id=$_POST['project_id']?:null;
        foreach($_FILES['images']['name'] as $i=>$name){
            if(empty($name)) continue;
            $file=['name'=>$name,'type'=>$_FILES['images']['type'][$i],'tmp_name'=>$_FILES['images']['tmp_name'][$i],'error'=>$_FILES['images']['error'][$i],'size'=>$_FILES['images']['size'][$i]];
            [$ok,$res]=secure_upload($file, __DIR__.'/../uploads/gallery');
            if($ok){ $pdo->prepare("INSERT INTO gallery(title,image,caption,category,project_id) VALUES(?,?,?,?,?)")->execute([$title,$res,$caption,$category,$project_id]); $msg='Uploaded'; }
            else $msg=$res;
        }
        log_activity($pdo,'upload_gallery',"Uploaded gallery images");
    }
}
if(isset($_GET['delete'])){
    if(csrf_verify($_GET['csrf'] ?? '')){
        $id=(int)$_GET['delete']; $r=$pdo->prepare("SELECT image FROM gallery WHERE id=?"); $r->execute([$id]); $row=$r->fetch();
        if($row){ if(file_exists(__DIR__.'/../uploads/gallery/'.$row['image'])) unlink(__DIR__.'/../uploads/gallery/'.$row['image']); $pdo->prepare("DELETE FROM gallery WHERE id=?")->execute([$id]); header('Location: gallery.php'); exit; }
    }
}
$items=$pdo->query("SELECT g.*, p.title as project_title FROM gallery g LEFT JOIN projects p ON p.id=g.project_id ORDER BY g.created_at DESC")->fetchAll();
$projects=$pdo->query("SELECT id,title FROM projects ORDER BY title")->fetchAll();
?>
<h4>Gallery Management</h4>
<?php if($msg): ?><div class="alert alert-info py-2"><?= e($msg) ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="card p-3 mt-3">
<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
<div class="row g-3">
  <div class="col-md-3"><label class="form-label">Title</label><input name="title" class="form-control"></div>
  <div class="col-md-3"><label class="form-label">Caption</label><input name="caption" class="form-control"></div>
  <div class="col-md-2"><label class="form-label">Category</label><input name="category" class="form-control" value="General"></div>
  <div class="col-md-2"><label class="form-label">Project (optional)</label><select name="project_id" class="form-select"><option value="">— None —</option><?php foreach($projects as $pr): ?><option value="<?= (int)$pr['id'] ?>"><?= e($pr['title']) ?></option><?php endforeach; ?></select></div>
  <div class="col-md-2"><label class="form-label">Images</label><input type="file" name="images[]" multiple required accept=".jpg,.jpeg,.png,.webp" class="form-control"></div>
  <div class="col-12"><button class="btn btn-primary">Upload</button></div>
</div>
</form>
<div class="row g-3 mt-3">
<?php foreach($items as $it): ?>
<div class="col-6 col-md-3"><div class="card p-2 text-center"><img src="../uploads/gallery/<?= e($it['image']) ?>" style="height:140px;object-fit:cover" class="rounded w-100"><small class="d-block mt-1"><?= e($it['caption'] ?? $it['title']) ?> <span class="badge bg-info"><?= e($it['category']) ?></span></small><a href="?delete=<?= (int)$it['id'] ?>&csrf=<?= e(csrf_token()) ?>" onclick="return confirm('Delete?')" class="btn btn-sm btn-danger mt-1">Delete</a></div></div>
<?php endforeach; ?>
</div>
<?php include 'includes/footer.php'; ?>
