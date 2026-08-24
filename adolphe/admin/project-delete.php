<?php
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/../includes/functions.php';
require_once __DIR__.'/../includes/auth.php';
auth_require();
$id=(int)($_GET['id'] ?? 0);
$row=$pdo->prepare("SELECT * FROM projects WHERE id=?"); $row->execute([$id]); $p=$row->fetch();
if($p){
    if($p['main_image'] && file_exists(__DIR__.'/../uploads/projects/'.$p['main_image'])) unlink(__DIR__.'/../uploads/projects/'.$p['main_image']);
    $imgs=$pdo->prepare("SELECT image FROM project_images WHERE project_id=?"); $imgs->execute([$id]);
    foreach($imgs->fetchAll() as $im){ if(file_exists(__DIR__.'/../uploads/projects/'.$im['image'])) unlink(__DIR__.'/../uploads/projects/'.$im['image']); }
    $pdo->prepare("DELETE FROM projects WHERE id=?")->execute([$id]);
    log_activity($pdo,'delete_project',"Deleted {$p['title']}");
}
header('Location: projects.php?msg=Project+deleted'); exit;
