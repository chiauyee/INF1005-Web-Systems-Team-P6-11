<?php
session_start();

// PHP Mailer stuff
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer-master/src/Exception.php';
require __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-master/src/SMTP.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Honeypot check
  $honeypot = trim($_POST['website_url'] ?? '');

  // Sanitize inputs
  $name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
  $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
  $subject = htmlspecialchars(trim($_POST['subject'] ?? ''), ENT_QUOTES, 'UTF-8');
  $topic = htmlspecialchars(trim($_POST['topic'] ?? ''), ENT_QUOTES, 'UTF-8');
  $message = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');
  $feedback_rating = intval($_POST['feedback_rating'] ?? 0);

  // if honeypot is filled pretend submission was successful to deter bots
  if ($honeypot !== '') {
    $success = 'Thank you, ' . $name . '! We\'ll get back to you within 2 business days.';
  } else {
    // validation
    if (!$name || !$email || !$subject || !$message || !$topic) {
      $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = 'Please enter a valid email address.';
    } elseif ($feedback_rating < 1 || $feedback_rating > 5) {
      $error = 'Please select a rating before submitting.';
    } else {
      try {
        $mail = new PHPMailer(true); // configz
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'MusicMarket2026@gmail.com';
        $mail->Password   = 'csmq cqml fwuv zdax';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // send from the system email to the system email
        $mail->setFrom('MusicMarket2026@gmail.com', 'MusicMarket System');
        $mail->addAddress('MusicMarket2026@gmail.com', 'Admin'); 
        $mail->addReplyTo($email, $name);

        $mail->isHTML(false);
        $mail->Subject = "New Contact Entry: $subject";
        $mail->Body    = "You received a new message from the MusicMarket contact form.\n\n"
                       . "Name: $name\n"
                       . "Email: $email\n"
                       . "Topic: $topic\n"
                       . "Rating: $feedback_rating / 5\n\n"
                       . "Message:\n$message";

        $mail->send();
        $success = 'Thank you, ' . $name . '! We\'ll get back to you within 2 business days.';

        // clear form fields after successful submission
        $name = '';
        $email = '';
        $topic = '';
        $subject = '';
        $message = '';
        $feedback_rating = 0;
        $_POST = [];
      } catch (Exception $e) {
        $error = 'There was a problem submitting your message. Please try again later.';
        error_log("Contact Form Email Error: " . $mail->ErrorInfo);
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us – MusicMarket</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="/css/navigation.css">
  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="/css/contact.css">
</head>

<body>

  <?php include __DIR__ . '/includes/navigation.php'; ?>

  <main>

    <section class="contact-hero" aria-label="Contact MusicMarket">
      <div id="speaker-container" class="hero-speaker"></div>
      <div class="container" style="position:relative;z-index:1;">
        <p class="hero-eyebrow">Get In Touch</p>
        <h1 class="hero-heading">
          We'd love to<br>hear <em>from you.</em>
        </h1>
        <p class="hero-desc">
          Questions about an order, feedback on the platform, just want to talk records? Keep us in the loop — drop the
          needle and we're here for all of it.
        </p>
      </div>
    </section>

    <section class="contact-body">
      <div class="container">
        <div class="row g-5">

          <div class="col-lg-4">
            <p class="section-eyebrow">Contact Details</p>
            <h2 class="section-heading">Reach us <em>directly.</em></h2>

            <div class="info-card">
              <div class="info-icon" aria-hidden="true"><i class="bi bi-envelope"></i></div>
              <div>
                <p class="info-label">Email</p>
                <p class="info-value">hello@musicmarket.com</p>
              </div>
            </div>

            <div class="info-card">
              <div class="info-icon" aria-hidden="true"><i class="bi bi-telephone"></i></div>
              <div>
                <p class="info-label">Phone</p>
                <p class="info-value">+65 6123 4567</p>
              </div>
            </div>

            <div class="info-card">
              <div class="info-icon" aria-hidden="true"><i class="bi bi-geo-alt"></i></div>
              <div>
                <p class="info-label">Office</p>
                <p class="info-value">198 Tg Pagar Rd<br>Singapore 088198</p>
              </div>
            </div>

            <div class="info-card">
              <div class="info-icon" aria-hidden="true"><i class="bi bi-clock"></i></div>
              <div>
                <p class="info-label">Hours</p>
                <p class="info-value">Mon – Fri, 9 am – 6 pm SGT</p>
              </div>
            </div>

            <div class="response-note">
              <strong>Typical response time:</strong> we aim to reply to all enquiries
              within <strong>2 business days</strong>. For urgent order issues, please
              include your order number in the subject line.
            </div>
          </div>

          <div class="col-lg-8">
            <div class="form-card" id="contact-form">

              <?php if ($success): ?>
                <div class="alert-success-custom" role="alert">
                  <i class="bi bi-check-circle me-2"></i><?= $success ?>
                </div>
              <?php endif; ?>

              <?php if ($error): ?>
                <div class="alert-danger-custom" role="alert">
                  <i class="bi bi-exclamation-circle me-2"></i><?= $error ?>
                </div>
              <?php endif; ?>

              <form method="POST" action="#contact-form" novalidate>

                <h3 class="form-section-title">Your details</h3>
                <div class="row g-3 mb-4">

                  <div class="col-sm-6">
                    <label for="name" class="form-label">Full Name <span aria-hidden="true">*</span></label>
                    <div class="input-wrap">
                      <i class="bi bi-person"></i>
                      <input type="text" name="name" id="name" class="form-control" placeholder="Jane Smith"
                        value="<?= htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required
                        aria-required="true">
                    </div>
                  </div>

                  <div class="col-sm-6">
                    <label for="email" class="form-label">Email Address <span aria-hidden="true">*</span></label>
                    <div class="input-wrap">
                      <i class="bi bi-envelope"></i>
                      <input type="email" name="email" id="email" class="form-control" placeholder="jane@example.com"
                        value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required
                        aria-required="true">
                    </div>
                  </div>

                  <div class="col-sm-6">
                    <label for="topic" class="form-label">Topic <span aria-hidden="true">*</span></label>
                    <div class="input-wrap">
                      <i class="bi bi-tag"></i>
                      <select name="topic" id="topic" class="form-select" required aria-required="true">
                        <option value="" disabled <?= empty($_POST['topic']) ? 'selected' : '' ?>>Select a topic…
                        </option>
                        <option value="order" <?= (($_POST['topic'] ?? '') === 'order') ? 'selected' : '' ?>>Order /
                          Shipping</option>
                        <option value="selling" <?= (($_POST['topic'] ?? '') === 'selling') ? 'selected' : '' ?>>Selling on
                          MusicMarket</option>
                        <option value="account" <?= (($_POST['topic'] ?? '') === 'account') ? 'selected' : '' ?>>Account
                          &amp; Billing</option>
                        <option value="feedback" <?= (($_POST['topic'] ?? '') === 'feedback') ? 'selected' : '' ?>>Platform
                          Feedback</option>
                        <option value="press" <?= (($_POST['topic'] ?? '') === 'press') ? 'selected' : '' ?>>Press &amp;
                          Partnerships</option>
                        <option value="other" <?= (($_POST['topic'] ?? '') === 'other') ? 'selected' : '' ?>>Other</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-sm-6">
                    <label for="subject" class="form-label">Subject <span aria-hidden="true">*</span></label>
                    <div class="input-wrap">
                      <i class="bi bi-chat-left-text"></i>
                      <input type="text" name="subject" id="subject" class="form-control"
                        placeholder="Brief summary of your enquiry"
                        value="<?= htmlspecialchars($_POST['subject'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required
                        aria-required="true">
                    </div>
                  </div>

                  <div class="col-12">
                    <label for="message" class="form-label">Message <span aria-hidden="true">*</span></label>
                    <div class="input-wrap">
                      <textarea name="message" id="message" class="form-control"
                        placeholder="Tell us what's on your mind…" required
                        aria-required="true"><?= htmlspecialchars($_POST['message'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                  </div>

                </div>

                <h3 class="form-section-title">Your feedback</h3>
                <div class="mb-4">

                  <label class="form-label mb-2">How would you rate your MusicMarket experience? <span
                      aria-hidden="true">*</span></label>

                  <div class="star-group" role="radiogroup" aria-label="Rating out of 5 stars">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                      <input type="radio" name="feedback_rating" id="star<?= $i ?>" value="<?= $i ?>"
                        <?= (intval($_POST['feedback_rating'] ?? 0) === $i) ? 'checked' : '' ?>> <label for="star<?= $i ?>"
                        title="<?= $i ?> star<?= $i > 1 ? 's' : '' ?>"
                        aria-label="<?= $i ?> star<?= $i > 1 ? 's' : '' ?>">★</label>
                    <?php endfor; ?>
                  </div>
                  <p class="star-hint" id="star-hint" aria-live="polite"></p>

                </div>

                <!-- Honeypot -->
                <div style="display:none;" aria-hidden="true">
                  <label for="website_url">Leave this field blank if you are human:</label>
                  <input type="text" name="website_url" id="website_url" tabindex="-1" autocomplete="off">
                </div>

                <button type="submit" class="btn-submit">
                  Send message <i class="bi bi-arrow-right"></i>
                </button>

              </form>
            </div>
          </div>

        </div>
      </div>
    </section>

  </main>

  <?php include __DIR__ . '/includes/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>

  <script>
    // star rating hint labels
    const hints = ['', 'Poor', 'Fair', 'Good', 'Great', 'Excellent'];
    const hintEl = document.getElementById('star-hint');
    const stars = document.querySelectorAll('.star-group input[type="radio"]');

    stars.forEach(radio => {
      radio.addEventListener('change', () => {
        hintEl.textContent = hints[parseInt(radio.value)] || '';
      });
    });

    // restore hint text on page load if rating was previously selected (e.g. after failed submission)
    const checked = document.querySelector('.star-group input:checked');
    if (checked) hintEl.textContent = hints[parseInt(checked.value)] || '';

    // scroll-reveal
    (function () {
      const style = document.createElement('style');
      style.textContent = `
        .reveal { opacity: 0; transform: translateY(20px); transition: opacity 0.5s ease, transform 0.5s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        @media (prefers-reduced-motion: reduce) {
          .reveal { opacity: 1; transform: none; transition: none; }
        }
      `; // WCAG 2.2.2 and 2.3.3
      document.head.appendChild(style);

      const targets = document.querySelectorAll('.form-card, .info-card, .response-note');
      targets.forEach(el => el.classList.add('reveal'));

      const observer = new IntersectionObserver(entries => {
        entries.forEach(e => {
          if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); }
        });
      }, { threshold: 0.1 });

      targets.forEach(el => observer.observe(el));
    })();
  </script>

  <!-- Three.js Speaker -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  <script>
    (function () {
      const container = document.getElementById('speaker-container');
      if (!container) return;

      const scene = new THREE.Scene();

      const camera = new THREE.PerspectiveCamera(34, 1, 0.1, 1000);
      camera.position.set(0, 0, 24);
      camera.lookAt(0, 0, 0);

      const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
      renderer.setSize(container.clientWidth, container.clientHeight);
      renderer.setPixelRatio(window.devicePixelRatio);
      container.appendChild(renderer.domElement);

      const speaker = new THREE.Group();
      scene.add(speaker);

      const lineMat = new THREE.LineBasicMaterial({ color: 0xffffff, transparent: true, opacity: 0.25 });
      const faintLineMat = new THREE.LineBasicMaterial({ color: 0xffffff, transparent: true, opacity: 0.1 });
      const meshMat = new THREE.MeshBasicMaterial({
        color: 0x1a1a1a,
        polygonOffset: true,
        polygonOffsetFactor: 1,
        polygonOffsetUnits: 1
      });

      function createSolid(geometry, edgeMat, parent, x = 0, y = 0, z = 0) {
        const group = new THREE.Group();
        group.position.set(x, y, z);

        const mesh = new THREE.Mesh(geometry, meshMat);
        group.add(mesh);

        const edges = new THREE.EdgesGeometry(geometry);
        const lines = new THREE.LineSegments(edges, edgeMat);
        group.add(lines);

        parent.add(group);
        return group;
      }

      // Speaker Cabinet
      createSolid(new THREE.BoxGeometry(6.6, 11.2, 5.0), lineMat, speaker, 0, 0, 0);

      // Tweeter Frame
      const tweeterGroup = new THREE.Group();
      tweeterGroup.position.set(0, 3.2, 2.5);
      speaker.add(tweeterGroup);

      const tweeterBase = new THREE.CylinderGeometry(1.4, 1.4, 0.2, 32);
      tweeterBase.rotateX(Math.PI / 2);
      createSolid(tweeterBase, lineMat, tweeterGroup, 0, 0, 0);

      const tweeterInside = new THREE.CylinderGeometry(0.8, 1.3, 0.4, 32);
      tweeterInside.rotateX(Math.PI / 2);
      createSolid(tweeterInside, faintLineMat, tweeterGroup, 0, 0, -0.2);

      const tweeterCap = new THREE.SphereGeometry(0.5, 16, 16, 0, Math.PI * 2, 0, Math.PI / 2);
      tweeterCap.rotateX(Math.PI / 2);
      createSolid(tweeterCap, lineMat, tweeterGroup, 0, 0, 0);

      // Woofer Frame
      const wooferGroup = new THREE.Group();
      wooferGroup.position.set(0, -1.6, 2.5);
      speaker.add(wooferGroup);

      const wooferBase = new THREE.CylinderGeometry(2.8, 2.8, 0.2, 32);
      wooferBase.rotateX(Math.PI / 2);
      createSolid(wooferBase, lineMat, wooferGroup, 0, 0, 0);

      const wooferSurround = new THREE.CylinderGeometry(2.4, 2.7, 0.3, 32);
      wooferSurround.rotateX(Math.PI / 2);
      createSolid(wooferSurround, faintLineMat, wooferGroup, 0, 0, 0);

      // Moving pieces of the woofer
      const wooferConeMat = faintLineMat;
      const wooferConeGeo = new THREE.CylinderGeometry(0.8, 2.4, 1.0, 32);
      wooferConeGeo.rotateX(Math.PI / 2);

      const wooferCapGeo = new THREE.SphereGeometry(0.9, 16, 16, 0, Math.PI * 2, 0, Math.PI / 2);
      wooferCapGeo.rotateX(Math.PI / 2);

      // We manually add depth logic to separate the cone / cap meshes and animate them directly
      const coneGroup = createSolid(wooferConeGeo, wooferConeMat, wooferGroup, 0, 0, -0.4);
      const capGroup = createSolid(wooferCapGeo, lineMat, wooferGroup, 0, 0, 0.2);

      // Audio Port Hole
      const portHole = new THREE.CylinderGeometry(1.0, 1.0, 0.4, 32);
      portHole.rotateX(Math.PI / 2);
      createSolid(portHole, lineMat, speaker, 0, -4.6, 2.5);

      speaker.rotation.x = -Math.PI / 12;
      speaker.rotation.y = -Math.PI / 8;
      speaker.rotation.z = Math.PI / 30;

      function animate() {
        requestAnimationFrame(animate);

        speaker.position.y = Math.sin(Date.now() * 0.001) * 0.3;
        speaker.rotation.y = -Math.PI / 8 + Math.sin(Date.now() * 0.0005) * 0.03;

        // Pumping effect logic
        const pumpAmount = Math.sin(Date.now() * 0.015) * 0.15;
        // Pumping the cone + cap for woofer
        coneGroup.position.z = pumpAmount - 0.4;
        capGroup.position.z = pumpAmount * 1.05 + 0.1;

        // Smaller pumping for tweeter
        const tweetPump = Math.sin(Date.now() * 0.02) * 0.05;
        tweeterGroup.children[1].position.z = tweetPump - 0.2;
        tweeterGroup.children[2].position.z = tweetPump;

        renderer.render(scene, camera);
      }
      
      // WCAG respect user's motion preferences
      const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
      if (!prefersReducedMotion.matches) {
        animate();
      } else {
        renderer.render(scene, camera); // render the initial static frame only
      }

      window.addEventListener('resize', () => {
        if (!container) return;
        const width = container.clientWidth;
        const height = container.clientHeight;
        renderer.setSize(width, height);
        camera.aspect = width / height;
        camera.updateProjectionMatrix();
      });
    })();
  </script>

</body>

</html>
