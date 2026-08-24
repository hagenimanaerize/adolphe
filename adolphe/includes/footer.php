<?php
$footerText = get_setting($pdo,'footer_text','© 2026 Adolphe Civil Engineering, Muganza Rwanda.');
$phone=get_setting($pdo,'phone','+250 788 000 000');
$email=get_setting($pdo,'email','info@adolphe.rw');
$wa=get_setting($pdo,'whatsapp','+250788000000');
$waClean=preg_replace('/[^0-9]/','',$wa);
?>
</main>
<footer class="footer pt-5">
  <div class="blueprint-bg"></div>
  <div class="container position-relative">
    <div class="row g-4">
      <div class="col-lg-4">
        <h5 class="text-cyan fw-bold"><i class="fa-solid fa-helmet-safety me-2"></i>ADOLPHE CIVIL ENGINEERING</h5>
        <p class="text-white-50 small">Engineering the Future. Building with Precision.<br>Professional civil engineering based in Muganza, Rwanda.</p>
        <div class="d-flex gap-2 mt-3">
          <a href="<?= e(get_setting($pdo,'facebook','#'))?>" class="social-btn"><i class="fab fa-facebook-f"></i></a>
          <a href="<?= e(get_setting($pdo,'instagram','#'))?>" class="social-btn"><i class="fab fa-instagram"></i></a>
          <a href="<?= e(get_setting($pdo,'linkedin','#'))?>" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
      <div class="col-6 col-lg-2">
        <h6 class="text-white">Explore</h6>
        <ul class="list-unstyled small footer-links">
          <li><a href="about.php">About</a></li><li><a href="services.php">Services</a></li><li><a href="projects.php">Projects</a></li><li><a href="gallery.php">Gallery</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-3">
        <h6 class="text-white">Services</h6>
        <ul class="list-unstyled small footer-links">
          <li>Structural Engineering</li><li>Building Design</li><li>Construction Supervision</li><li>Road & Infrastructure</li>
        </ul>
      </div>
      <div class="col-lg-3">
        <h6 class="text-white">Contact - Muganza, Rwanda</h6>
        <p class="small text-white-50 mb-1"><i class="fa-solid fa-phone me-2 text-cyan"></i><?= e($phone)?></p>
        <p class="small text-white-50 mb-1"><i class="fa-solid fa-envelope me-2 text-cyan"></i><?= e($email)?></p>
        <p class="small text-white-50"><i class="fa-solid fa-location-dot me-2 text-cyan"></i><?= e(get_setting($pdo,'location','Muganza, Rwanda'))?></p>
      </div>
    </div>
    <hr class="border-secondary my-4">
    <div class="d-flex flex-wrap justify-content-between small text-white-50 pb-3">
      <span><?= e($footerText)?></span><span>Muganza, Rwanda • Built with precision</span>
    </div>
  </div>
</footer>

<a href="https://wa.me/<?= e($waClean)?>" class="fab-whatsapp" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
<a href="tel:<?= e($phone)?>" class="fab-phone d-none d-md-flex" aria-label="Call"><i class="fa-solid fa-phone"></i></a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
