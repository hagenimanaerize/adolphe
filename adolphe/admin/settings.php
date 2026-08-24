<?php
include 'includes/header.php';
$keys=['site_name','tagline','hero_title','hero_subtitle','hero_description','hero_side_title','hero_side_desc','phone','email','whatsapp','location','working_hours','facebook','instagram','linkedin','footer_text','map_embed'];
$settings=[]; foreach($keys as $k){ $settings[$k]=get_setting($pdo,$k,''); }
$stats=$pdo->query("SELECT * FROM site_stats ORDER BY id")->fetchAll();
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!csrf_verify($_POST['csrf'] ?? '')) $msg='Invalid token';
    else{
        if(isset($_POST['setting_key'])){
            foreach($keys as $k){
                $val=$_POST[$k] ?? '';
                $pdo->prepare("INSERT INTO site_settings(setting_key,setting_value) VALUES(?,?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)")->execute([$k,$val]);
            }
            $msg='Settings saved';
        }
        if(isset($_POST['save_stats'])){
            foreach($_POST['stats'] as $sk=>$sv){
                $label=$_POST['labels'][$sk] ?? $sk;
                $pdo->prepare("UPDATE site_stats SET stat_value=?, stat_label=? WHERE stat_key=?")->execute([(int)$sv,$label,$sk]);
            }
            $msg='Stats updated';
        }
        if(isset($_POST['change_password'])){
            $new=trim($_POST['new_password'] ?? '');
            if(strlen($new)>=6){
                $hash=password_hash($new, PASSWORD_DEFAULT);
                $pdo->prepare("UPDATE admins SET password=? WHERE id=?")->execute([$hash, $_SESSION['admin_id']]);
                $msg='Password changed';
            } else $msg='Password too short (min 6)';
        }
        if(isset($_POST['change_email'])){
            $newEmail=trim($_POST['new_email'] ?? '');
            $newUsername=trim($_POST['new_username'] ?? '');
            if(!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) $msg='Invalid email format';
            elseif(strlen($newUsername)<2) $msg='Username too short';
            else {
                // check duplicate email
                $chk=$pdo->prepare("SELECT id FROM admins WHERE email=? AND id<>?");
                $chk->execute([$newEmail, $_SESSION['admin_id']]);
                if($chk->fetch()) $msg='Email already used by another admin';
                else {
                    $pdo->prepare("UPDATE admins SET email=?, username=? WHERE id=?")->execute([$newEmail,$newUsername,$_SESSION['admin_id']]);
                    $_SESSION['admin_email']=$newEmail;
                    $_SESSION['admin_username']=$newUsername;
                    $msg='Admin email/username updated — use new email next login';
                }
            }
        }
        // Hero side image upload
        if(isset($_POST['save_hero_image']) && !empty($_FILES['hero_side_image']['name'])){
            if(!csrf_verify($_POST['csrf'] ?? '')) $msg='Invalid token';
            else {
                [$ok,$res]=secure_upload($_FILES['hero_side_image'], __DIR__.'/../uploads/hero');
                if(!$ok) $msg=$res;
                else {
                    // delete old
                    $old=get_setting($pdo,'hero_side_image','');
                    if($old && file_exists(__DIR__.'/../uploads/hero/'.$old)) @unlink(__DIR__.'/../uploads/hero/'.$old);
                    $pdo->prepare("INSERT INTO site_settings(setting_key,setting_value) VALUES('hero_side_image',?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)")->execute([$res]);
                    $msg='Hero image updated';
                }
            }
        }
        $settings=[]; foreach($keys as $k){ $settings[$k]=get_setting($pdo,$k,''); }
        $stats=$pdo->query("SELECT * FROM site_stats ORDER BY id")->fetchAll();
    }
}
?>
<h4>Settings</h4>
<?php if($msg): ?><div class="alert alert-success py-2"><?= e($msg) ?></div><?php endif; ?>
<form method="post" class="card p-4 mt-3">
<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="setting_key" value="1">
<h6>General & Hero</h6>
<div class="row g-3">
<?php foreach($keys as $k): ?>
  <div class="col-md-6"><label class="form-label small"><?= e($k) ?></label>
  <?php if(in_array($k,['hero_description','footer_text','map_embed'])): ?><textarea name="<?= e($k) ?>" rows="2" class="form-control"><?= e($settings[$k]) ?></textarea>
  <?php else: ?><input name="<?= e($k) ?>" class="form-control" value="<?= e($settings[$k]) ?>"><?php endif; ?>
  </div>
<?php endforeach; ?>
  <div class="col-12"><button class="btn btn-primary">Save Settings</button></div>
</div>
</form>

<div class="card p-4 mt-3">
<h6>Hero Side Card — Precision Engineering Image</h6>
<?php $curImg=get_setting($pdo,'hero_side_image',''); ?>
<?php if($curImg): ?><img src="../uploads/hero/<?= e($curImg) ?>" style="height:120px" class="rounded mb-2 d-block"><?php else: ?><p class="small text-muted">Currently using default Unsplash image. Upload to override.</p><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="row g-3">
<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="save_hero_image" value="1">
<div class="col-md-6"><label class="form-label small">New Image (JPG/PNG/WEBP, max 5MB)</label><input type="file" name="hero_side_image" accept=".jpg,.jpeg,.png,.webp" class="form-control" required></div>
<div class="col-md-4 d-flex align-items-end"><button class="btn btn-info">Upload & Replace</button></div>
</form>
<p class="small text-muted mt-2">Title/desc for that card are `hero_side_title` / `hero_side_desc` in settings above.</p>
</div>

<form method="post" class="card p-4 mt-3">
<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="save_stats" value="1">
<h6>Homepage Stats</h6>
<div class="row g-3">
<?php foreach($stats as $s): ?>
  <div class="col-md-3"><label class="form-label small"><?= e($s['stat_key']) ?> label</label><input name="labels[<?= e($s['stat_key']) ?>]" value="<?= e($s['stat_label']) ?>" class="form-control"></div>
  <div class="col-md-3"><label class="form-label small"><?= e($s['stat_key']) ?> value</label><input type="number" name="stats[<?= e($s['stat_key']) ?>]" value="<?= (int)$s['stat_value'] ?>" class="form-control"></div>
<?php endforeach; ?>
  <div class="col-12"><button class="btn btn-primary">Save Stats</button></div>
</div>
</form>

<form method="post" class="card p-4 mt-3">
<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="change_password" value="1">
<h6>Change Admin Password</h6>
<div class="row g-3"><div class="col-md-4"><input type="password" name="new_password" placeholder="New password (min 6 chars)" class="form-control" required></div><div class="col-md-2"><button class="btn btn-warning">Update Password</button></div></div>
</form>

<?php
$curAdmin=$pdo->prepare("SELECT username,email FROM admins WHERE id=?");
$curAdmin->execute([$_SESSION['admin_id']]);
$adm=$curAdmin->fetch();
?>
<form method="post" class="card p-4 mt-3">
<input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="change_email" value="1">
<h6>Change Admin Login Email / Username</h6>
<p class="small text-muted">This changes what you type on <code>admin/login.php</code>. Site contact email is the <code>email</code> field in General settings above.</p>
<div class="row g-3">
  <div class="col-md-4"><label class="form-label small">Login Email</label><input type="email" name="new_email" required class="form-control" value="<?= e($adm['email']) ?>"></div>
  <div class="col-md-4"><label class="form-label small">Username</label><input name="new_username" required class="form-control" value="<?= e($adm['username']) ?>"></div>
  <div class="col-md-2 d-flex align-items-end"><button class="btn btn-primary">Update Email</button></div>
</div>
</form>
<?php include 'includes/footer.php'; ?>
