<?php
$current = basename($_SERVER['PHP_SELF']);
$siteName = isset($pdo) ? get_setting($pdo,'site_name','Adolphe Civil Engineering') : 'Adolphe Civil Engineering';
?>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top glass-nav" id="mainNav">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="index.php">
      <span class="brand-icon"><i class="fa-solid fa-helmet-safety"></i></span>
      <span><span class="text-cyan">ADOLPHE</span> ENGINEERING</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        <li class="nav-item"><a class="nav-link <?= $current==='index.php'?'active':''?>" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link <?= $current==='about.php'?'active':''?>" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link <?= $current==='services.php'?'active':''?>" href="services.php">Services</a></li>
        <li class="nav-item"><a class="nav-link <?= $current==='projects.php' || $current==='project.php'?'active':''?>" href="projects.php">Projects</a></li>
        <li class="nav-item"><a class="nav-link <?= $current==='gallery.php'?'active':''?>" href="gallery.php">Gallery</a></li>
        <li class="nav-item"><a class="nav-link <?= $current==='contact.php'?'active':''?>" href="contact.php">Contact</a></li>
        <li class="nav-item ms-lg-2"><a class="btn btn-cyan btn-sm px-3" href="contact.php">Request Consultation</a></li>
      </ul>
      <button id="themeToggle" class="btn btn-outline-light btn-sm ms-2" title="Toggle theme"><i class="fa-solid fa-moon"></i></button>
    </div>
  </div>
</nav>
