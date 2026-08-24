<?php
// includes/functions.php
if (session_status() === PHP_SESSION_NONE) session_start();

function e($str){ return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8'); }

function csrf_token(){
    if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_verify($token){
    return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}
function redirect($url){ header("Location: $url"); exit; }

function get_setting(PDO $pdo, string $key, $default=''){
    static $cache=[];
    if(isset($cache[$key])) return $cache[$key];
    $stmt=$pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key=?");
    $stmt->execute([$key]);
    $v=$stmt->fetchColumn();
    $cache[$key]= $v!==false ? $v : $default;
    return $cache[$key];
}
function slugify($text){
    $text=preg_replace('~[^\pL\d]+~u','-',$text);
    $text=iconv('utf-8','us-ascii//TRANSLIT',$text);
    $text=preg_replace('~[^-\w]+~','',$text);
    $text=trim($text,'-'); $text=preg_replace('~-+~','-',$text);
    return strtolower($text) ?: 'n-'.time();
}
function log_activity(PDO $pdo, $action, $desc=''){
    if(empty($_SESSION['admin_id'])) return;
    $stmt=$pdo->prepare("INSERT INTO activity_logs(admin_id,action,description) VALUES(?,?,?)");
    $stmt->execute([$_SESSION['admin_id'],$action,$desc]);
}
function is_logged_in(){ return !empty($_SESSION['admin_id']); }
function require_login(){ if(!is_logged_in()) redirect('admin/login.php'); }

// Secure upload helper
function secure_upload(array $file, string $destDir, array $allowedExt=['jpg','jpeg','png','webp'], int $maxSize=5*1024*1024): array {
    if($file['error']!==UPLOAD_ERR_OK) return [false,'Upload error code '.$file['error']];
    if($file['size']>$maxSize) return [false,'File too large (max 5MB)'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if(!in_array($ext,$allowedExt,true)) return [false,'Invalid file type'];
    $finfo=finfo_open(FILEINFO_MIME_TYPE);
    $mime=finfo_file($finfo,$file['tmp_name']);
    finfo_close($finfo);
    $allowedMimes=['image/jpeg','image/png','image/webp'];
    if(!in_array($mime,$allowedMimes,true)) return [false,'Invalid MIME type'];
    // block PHP
    if(preg_match('/php|phtml|phar/i',$ext) || str_contains($mime,'php')) return [false,'Blocked file type'];
    if(!is_dir($destDir)) mkdir($destDir,0755,true);
    $name=bin2hex(random_bytes(12)).'.'.$ext;
    $dest=rtrim($destDir,'/').'/'.$name;
    if(!move_uploaded_file($file['tmp_name'],$dest)) return [false,'Failed to move file'];
    // optional thumb/compress: if GD available, create thumbnail
    if(extension_loaded('gd')){
        try{
            create_thumbnail($dest,$destDir.'/thumb_'.$name,400);
            // compress large images
            compress_image($dest, $ext);
        }catch(Throwable $e){}
    }
    return [true,$name];
}
function create_thumbnail($src,$thumbPath,$maxW=400){
    $info=getimagesize($src); if(!$info) return;
    [$w,$h,$type]=$info;
    $ratio=$maxW/$w; $nw=$maxW; $nh=(int)($h*$ratio);
    $dst=imagecreatetruecolor($nw,$nh);
    switch($type){
        case IMAGETYPE_JPEG: $srcImg=imagecreatefromjpeg($src); break;
        case IMAGETYPE_PNG: $srcImg=imagecreatefrompng($src); imagealphablending($dst,false); imagesavealpha($dst,true); break;
        case IMAGETYPE_WEBP: $srcImg=imagecreatefromwebp($src); break;
        default: return;
    }
    imagecopyresampled($dst,$srcImg,0,0,0,0,$nw,$nh,$w,$h);
    switch($type){
        case IMAGETYPE_JPEG: imagejpeg($dst,$thumbPath,80); break;
        case IMAGETYPE_PNG: imagepng($dst,$thumbPath,8); break;
        case IMAGETYPE_WEBP: imagewebp($dst,$thumbPath,80); break;
    }
    imagedestroy($srcImg); imagedestroy($dst);
}
function compress_image($path,$ext){
    $info=getimagesize($path); if(!$info) return;
    if(filesize($path) < 600*1024) return;
    [$w,$h,$type]=$info;
    // scale down if >1600px wide
    if($w>1600){
        $nw=1600; $nh=(int)($h*1600/$w);
        $dst=imagecreatetruecolor($nw,$nh);
        switch($type){
            case IMAGETYPE_JPEG: $s=imagecreatefromjpeg($path); break;
            case IMAGETYPE_PNG: $s=imagecreatefrompng($path); break;
            case IMAGETYPE_WEBP: $s=imagecreatefromwebp($path); break;
            default: return;
        }
        imagecopyresampled($dst,$s,0,0,0,0,$nw,$nh,$w,$h);
        switch($type){
            case IMAGETYPE_JPEG: imagejpeg($dst,$path,78); break;
            case IMAGETYPE_PNG: imagepng($dst,$path,8); break;
            case IMAGETYPE_WEBP: imagewebp($dst,$path,78); break;
        }
        imagedestroy($s); imagedestroy($dst);
    }
}
