<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
$success=''; $error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(!csrf_verify($_POST['csrf'] ?? '')) $error='Invalid request. Refresh and try again.';
    else{
        $name=trim($_POST['name'] ?? ''); $email=trim($_POST['email'] ?? ''); $phone=trim($_POST['phone'] ?? '');
        $project_type=trim($_POST['project_type'] ?? ''); $location=trim($_POST['location'] ?? ''); $message=trim($_POST['message'] ?? '');
        if(strlen($name)<2 || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($message)<10){
            $error='Please fill name, valid email and a message (min 10 chars).';
        } else {
            // sanitize
            $name=strip_tags($name); $project_type=strip_tags($project_type); $location=strip_tags($location);
            $stmt=$pdo->prepare("INSERT INTO messages(name,email,phone,project_type,location,message) VALUES(?,?,?,?,?,?)");
            $stmt->execute([$name,$email,$phone,$project_type,$location,strip_tags($message)]);
            $success='Thank you — your message has been received. We will contact you soon.';
            log_activity($pdo,'new_message',"Contact from $email");
        }
    }
}
$pageTitle='Contact'; $pageDesc='Contact Adolphe Civil Engineering, Muganza Rwanda.';
include 'includes/header.php';
$phone=get_setting($pdo,'phone','+250 788 000 000');
$email=get_setting($pdo,'email','info@adolphe.rw');
$wa=get_setting($pdo,'whatsapp','+250788000000');
$waClean=preg_replace('/[^0-9]/','',$wa);
$loc=get_setting($pdo,'location','Muganza, Rwanda');
?>
<section class="section" style="padding-top:110px">
  <div class="container">
    <div class="text-center mb-5" data-aos="fade-up">
      <h1 class="section-title">CONTACT <span>US</span></h1><p class="text-white-50">Muganza, Rwanda — Let's build together</p>
    </div>
    <div class="row g-4">
      <div class="col-lg-5" data-aos="fade-right">
        <div class="glass-card p-4">
          <h5>Adolphe Civil Engineering</h5>
          <p class="small text-white-50"><i class="fa-solid fa-location-dot text-cyan me-2"></i><?= e($loc) ?></p>
          <div class="d-grid gap-2 mt-3">
            <a href="tel:<?= e($phone) ?>" class="btn btn-cyan"><i class="fa-solid fa-phone me-2"></i><?= e($phone) ?></a>
            <a href="mailto:<?= e($email) ?>" class="btn btn-outline-cyan"><i class="fa-solid fa-envelope me-2"></i><?= e($email) ?></a>
            <a href="https://wa.me/<?= e($waClean) ?>" target="_blank" class="btn btn-success"><i class="fab fa-whatsapp me-2"></i>WhatsApp</a>
          </div>
          <hr class="border-secondary my-4">
          <p class="small text-white-50"><i class="fa-regular fa-clock me-2 text-cyan"></i><?= e(get_setting($pdo,'working_hours','Mon - Sat: 08:00 - 18:00')) ?></p>
          <div class="rounded-4 overflow-hidden mt-3" style="height:220px;background:#0a1628;display:grid;place-items:center;border:1px solid rgba(255,255,255,.1)">
            <?php $map=get_setting($pdo,'map_embed',''); if($map): echo $map; else: ?>
            <p class="small text-white-50 text-center p-3">Map placeholder<br>Muganza, Rwanda<br><a href="https://maps.google.com/?q=Muganza,Rwanda" target="_blank" class="text-cyan">Open in Google Maps</a></p>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="col-lg-7" data-aos="fade-left">
        <div class="glass-card p-4">
          <?php if($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>
          <?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
          <form method="post" novalidate>
            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
            <div class="row g-3">
              <div class="col-md-6"><label class="form-label small">Full Name *</label><input name="name" required class="form-control" value="<?= e($_POST['name'] ?? '') ?>"></div>
              <div class="col-md-6"><label class="form-label small">Phone</label><input name="phone" class="form-control" value="<?= e($_POST['phone'] ?? '') ?>"></div>
              <div class="col-md-6"><label class="form-label small">Email *</label><input name="email" type="email" required class="form-control" value="<?= e($_POST['email'] ?? '') ?>"></div>
              <div class="col-md-6"><label class="form-label small">Project Type</label><select name="project_type" class="form-select"><option>Residential</option><option>Commercial</option><option>Roads</option><option>Bridges</option><option>Infrastructure</option><option>Other</option></select></div>
              <div class="col-12"><label class="form-label small">Location</label><input name="location" class="form-control" placeholder="e.g., Muganza, Kigali..." value="<?= e($_POST['location'] ?? '') ?>"></div>
              <div class="col-12"><label class="form-label small">Message *</label><textarea name="message" rows="5" required class="form-control"><?= e($_POST['message'] ?? '') ?></textarea></div>
              <div class="col-12"><button class="btn btn-cyan w-100 py-2">Send Message <i class="fa-solid fa-paper-plane ms-2"></i></button></div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
