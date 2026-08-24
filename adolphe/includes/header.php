<?php
// includes/header.php - expects $pageTitle, $pageDesc, $pdo
require_once __DIR__.'/../config/database.php';
require_once __DIR__.'/functions.php';
$siteName = get_setting($pdo,'site_name','Adolphe Civil Engineering');
$tagline  = get_setting($pdo,'tagline','Engineering the Future. Building with Precision.');
$title = isset($pageTitle) ? e($pageTitle).' | '.e($siteName) : e($siteName).' | '.e($tagline);
$desc  = isset($pageDesc) ? e($pageDesc) : e(get_setting($pdo,'hero_description','Professional civil engineering in Muganza, Rwanda.'));
$ogImage = 'assets/images/og.jpg';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= $title ?></title>
<meta name="description" content="<?= $desc ?>">
<meta property="og:title" content="<?= $title ?>">
<meta property="og:description" content="<?= $desc ?>">
<meta property="og:type" content="website">
<meta property="og:image" content="<?= $ogImage ?>">
<link rel="icon" href="assets/images/favicon.ico">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<div id="loader"><div class="loader-grid"></div><div class="loader-text"><i class="fa-solid fa-helmet-safety fa-spin"></i> Loading...</div></div>
<?php include __DIR__.'/navbar.php'; ?>
<main>
