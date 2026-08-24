<?php
require_once __DIR__.'/../../config/database.php';
require_once __DIR__.'/../../includes/functions.php';
require_once __DIR__.'/../../includes/auth.php';
auth_require();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin — Adolphe Engineering</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<link href="../assets/css/style.css" rel="stylesheet">
<style>.admin-body{background:#f4f7fb}</style>
</head>
<body class="admin-body">
<?php include __DIR__.'/sidebar.php'; ?>
<div class="admin-main">
