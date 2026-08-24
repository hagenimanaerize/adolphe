<?php
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../includes/functions.php';
if(session_status()===PHP_SESSION_NONE) session_start();

// rate limit
$ip=$_SERVER['REMOTE_ADDR'] ?? 'unknown';
$key='login_attempts_'.$ip;
$_SESSION[$key]=$_SESSION[$key] ?? ['count'=>0,'time'=>time()];
if(time()-$_SESSION[$key]['time']>900){ $_SESSION[$key]=['count'=>0,'time'=>time()]; }

$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!csrf_verify($_POST['csrf'] ?? '')) $error='Invalid token';
    elseif($_SESSION[$key]['count']>=5) $error='Too many attempts. Try again in 15 minutes.';
    else{
        $email=trim($_POST['email'] ?? '');
        $pass=$_POST['password'] ?? '';
        $stmt=$pdo->prepare("SELECT * FROM admins WHERE email=?");
        $stmt->execute([$email]);
        $admin=$stmt->fetch();
        if($admin && password_verify($pass,$admin['password'])){
            session_regenerate_id(true);
            $_SESSION['admin_id']=$admin['id']; $_SESSION['admin_email']=$admin['email']; $_SESSION['admin_username']=$admin['username']; $_SESSION['last_activity']=time();
            $_SESSION[$key]=['count'=>0,'time'=>time()];
            header('Location: dashboard.php'); exit;
        } else {
            $_SESSION[$key]['count']++;
            $error='Invalid credentials.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Login — Adolphe Engineering</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<style>body{background:#0a1628;color:#e8eef6;min-height:100vh;display:grid;place-items:center}.card{max-width:420px;width:100%;background:rgba(255,255,255,.06);backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,.12);border-radius:20px}</style>
</head>
<body>
<div class="card p-4 m-3">
  <h4 class="text-center fw-bold"><i class="fa-solid fa-helmet-safety text-info me-2"></i>Admin Login</h4>
  <p class="text-center small text-white-50">Adolphe Civil Engineering — Muganza, Rwanda</p>
  <?php if(isset($_GET['timeout'])): ?><div class="alert alert-warning py-2 small">Session expired. Please login again.</div><?php endif; ?>
  <?php if($error): ?><div class="alert alert-danger py-2 small"><?= e($error) ?></div><?php endif; ?>
  <form method="post">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <div class="mb-3"><label class="form-label small">Email</label><input name="email" type="email" required class="form-control" value="<?= e($_POST['email'] ?? '') ?>"></div>
    <div class="mb-3"><label class="form-label small">Password</label><input name="password" type="password" required class="form-control"></div>
    <button class="btn btn-info w-100 fw-bold">Login</button>
  </form>
  <p class="small text-white-50 text-center mt-3">Default: admin@adolphe.rw / Admin@123</p>
</div>
</body>
</html>
