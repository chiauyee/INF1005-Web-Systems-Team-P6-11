<?php
session_start();

$success = '';
$error   = '';

// Generate math CAPTCHA (same pattern as login.php)
if (!isset($_SESSION['contact_captcha_n1']) || !isset($_SESSION['contact_captcha_n2'])) {
    $_SESSION['contact_captcha_n1'] = rand(1, 10);
    $_SESSION['contact_captcha_n2'] = rand(1, 10);
}
$captcha_answer = $_SESSION['contact_captcha_n1'] + $_SESSION['contact_captcha_n2'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $name            = htmlspecialchars(trim($_POST['name']    ?? ''), ENT_QUOTES, 'UTF-8');
    $email           = filter_var(trim($_POST['email']  ?? ''), FILTER_SANITIZE_EMAIL);
    $subject         = htmlspecialchars(trim($_POST['subject']  ?? ''), ENT_QUOTES, 'UTF-8');
    $topic           = htmlspecialchars(trim($_POST['topic']    ?? ''), ENT_QUOTES, 'UTF-8');
    $message         = htmlspecialchars(trim($_POST['message']  ?? ''), ENT_QUOTES, 'UTF-8');
    $captcha_input   = trim($_POST['captcha'] ?? '');
    $feedback_rating = intval($_POST['feedback_rating'] ?? 0);

    // Regenerate captcha for next attempt
    $_SESSION['contact_captcha_n1'] = rand(1, 10);
    $_SESSION['contact_captcha_n2'] = rand(1, 10);

    // Validate
    if (!$name || !$email || !$subject || !$message || !$topic) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif ($captcha_input != $captcha_answer) {
        $error = 'Incorrect answer to the security question. Please try again.';
    } elseif ($feedback_rating < 1 || $feedback_rating > 5) {
        $error = 'Please select a rating before submitting.';
    } else {
        // TODO: persist to DB or send email
        $success = 'Thank you, ' . $name . '! We\'ll get back to you within 2 business days.';
    }

    $captcha_answer = $_SESSION['contact_captcha_n1'] + $_SESSION['contact_captcha_n2'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us – MusicMarket</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="/css/navigation.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

  <style>
    :root {
      --bg:          #f8f8f6;
      --white:       #ffffff;
      --border:      #e2e2de;
      --text:        #1a1a1a;
      --text-muted:  #666;
      --input-focus: rgba(26, 26, 26, 0.08);
    }

    * { box-sizing: border-box; }

    body {
      font-family: 'DM Sans', sans-serif;
      font-weight: 300;
      background-color: var(--bg);
      color: var(--text);
      min-height: 100vh;
    }

    .navbar-brand {
      font-weight: 700 !important;
      color: var(--text) !important;
    }
    .navbar .nav-link, .navbar .btn {
      font-family: 'DM Sans', sans-serif;
      font-weight: 300;
      color: var(--text);
    }
    .btn, .btn-primary, .navbar .btn {
      background-color: #1a1a1a;
      color: #fff;
      font-family: 'DM Sans', sans-serif;
      font-weight: 500;
      border-radius: 6px;
      border: none;
      transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
      cursor: pointer;
    }
    .btn:hover, .btn-primary:hover {
      background-color: #333;
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    }
    .btn:active, .btn-primary:active { transform: translateY(0); }

    .contact-hero {
      background-color: #1a1a1a;
      position: relative;
      overflow: hidden;
      padding: 5.5rem 0 4.5rem;
      color: #fff;
    }
    .contact-hero::before {
      content: '';
      position: absolute;
      width: 500px; height: 500px;
      border-radius: 50%;
      right: -150px; bottom: -150px;
      border: 60px solid rgba(255,255,255,0.03);
      box-shadow:
        0 0 0 60px  rgba(255,255,255,0.03),
        0 0 0 120px rgba(255,255,255,0.02),
        0 0 0 180px rgba(255,255,255,0.015);
      pointer-events: none;
    }
    .contact-hero::after {
      content: '';
      position: absolute;
      width: 260px; height: 260px;
      border-radius: 50%;
      left: -60px; top: -60px;
      border: 40px solid rgba(255,255,255,0.025);
      box-shadow: 0 0 0 40px rgba(255,255,255,0.015);
      pointer-events: none;
    }

    .hero-eyebrow {
      font-size: 0.7rem;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      color: rgba(255,255,255,0.4);
      margin-bottom: 1.25rem;
    }
    .hero-heading {
      font-family: 'Playfair Display', serif;
      font-size: clamp(2rem, 4.5vw, 3.2rem);
      line-height: 1.2;
      color: #fff;
      margin-bottom: 1rem;
    }
    .hero-heading em {
      font-style: italic;
      color: rgba(255,255,255,0.45);
    }
    .hero-desc {
      font-size: 0.92rem;
      color: rgba(255,255,255,0.4);
      line-height: 1.9;
      max-width: 400px;
    }

    .contact-body {
      padding: 5rem 0 6rem;
    }

    .section-eyebrow {
      font-size: 0.7rem;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      color: var(--text-muted);
      margin-bottom: 0.5rem;
    }
    .section-heading {
      font-family: 'Playfair Display', serif;
      font-size: 1.8rem;
      line-height: 1.25;
      color: var(--text);
      margin-bottom: 1.5rem;
    }
    .section-heading em {
      font-style: italic;
      color: var(--text-muted);
    }

    .info-card {
      display: flex;
      align-items: flex-start;
      gap: 1rem;
      padding: 1.25rem 0;
      border-bottom: 1.5px solid var(--border);
    }
    .info-card:first-of-type { border-top: 1.5px solid var(--border); }

    .info-icon {
      width: 40px; height: 40px;
      background: #1a1a1a;
      border-radius: 7px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 1rem;
      flex-shrink: 0;
    }

    .info-label {
      font-size: 0.7rem;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: var(--text-muted);
      margin-bottom: 0.15rem;
    }
    .info-value {
      font-size: 0.9rem;
      color: var(--text);
      margin: 0;
    }

    .response-note {
      margin-top: 2rem;
      background: var(--white);
      border: 1.5px solid var(--border);
      border-radius: 8px;
      padding: 1.25rem 1.5rem;
      font-size: 0.85rem;
      color: var(--text-muted);
      line-height: 1.75;
    }
    .response-note strong {
      color: var(--text);
      font-weight: 500;
    }

    .form-card {
      background: var(--white);
      border: 1.5px solid var(--border);
      border-radius: 10px;
      padding: 2.5rem;
    }

    .form-section-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.1rem;
      color: var(--text);
      margin-bottom: 1.25rem;
      padding-bottom: 0.6rem;
      border-bottom: 1.5px solid var(--border);
    }

    .form-label {
      font-size: 0.75rem;
      font-weight: 500;
      letter-spacing: 0.06em;
      color: #555;
      text-transform: uppercase;
      margin-bottom: 0.35rem;
      display: block;
    }

    .input-wrap { position: relative; }
    .input-wrap .bi {
      position: absolute;
      left: 0.85rem;
      top: 50%;
      transform: translateY(-50%);
      color: #bbb;
      font-size: 0.9rem;
      pointer-events: none;
      transition: color 0.2s;
    }
    .input-wrap:focus-within .bi { color: var(--text); }

    .form-control, .form-select {
      background: var(--bg);
      border: 1.5px solid var(--border);
      border-radius: 6px;
      color: var(--text);
      font-family: 'DM Sans', sans-serif;
      font-weight: 300;
      font-size: 0.95rem;
      transition: border-color 0.2s, box-shadow 0.2s;
      width: 100%;
    }
    .form-control { padding: 0.65rem 0.9rem 0.65rem 2.4rem; }
    .form-select  { padding: 0.65rem 2.4rem 0.65rem 2.4rem; }
    textarea.form-control { padding-left: 0.9rem; resize: vertical; min-height: 130px; }

    .form-control:focus, .form-select:focus {
      background: #fff;
      border-color: var(--text);
      box-shadow: 0 0 0 3px var(--input-focus);
      outline: none;
    }
    .form-control::placeholder { color: #bbb; }

    .star-group {
      display: flex;
      gap: 0.35rem;
      flex-direction: row-reverse;
      justify-content: flex-end;
    }
    .star-group input[type="radio"] { display: none; }

    .star-group label {
      font-size: 1.6rem;
      color: #d4d0ca;
      cursor: pointer;
      transition: color 0.15s, transform 0.12s;
      line-height: 1;
    }

    /* Highlight hovered star and all stars to its right (higher value) */
    .star-group label:hover,
    .star-group label:hover ~ label,
    .star-group input:checked ~ label {
      color: #1a1a1a;
    }
    .star-group label:hover { transform: scale(1.15); }

    .star-hint {
      font-size: 0.78rem;
      color: var(--text-muted);
      margin-top: 0.4rem;
      min-height: 1.2em;
    }

    .btn-submit {
      background: #1a1a1a;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-family: 'DM Sans', sans-serif;
      font-weight: 500;
      font-size: 0.95rem;
      padding: 0.75rem 2rem;
      width: 100%;
      margin-top: 0.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      cursor: pointer;
      transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
    }
    .btn-submit:hover {
      background: #333;
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    }
    .btn-submit:active { transform: translateY(0); }

    .alert-success-custom {
      background: #f0faf4;
      border: 1.5px solid #a7f3c4;
      color: #166534;
      border-radius: 6px;
      font-size: 0.875rem;
      padding: 0.75rem 1rem;
      margin-bottom: 1.5rem;
    }
    .alert-danger-custom {
      background: #fff5f5;
      border: 1.5px solid #fecaca;
      color: #b91c1c;
      border-radius: 6px;
      font-size: 0.875rem;
      padding: 0.75rem 1rem;
      margin-bottom: 1.5rem;
    }

    footer {
      background-color: #1a1a1a;
      color: #fff;
      padding: 20px 0;
      text-align: center;
    }
    footer a { color: #fff; text-decoration: none; }

    @media (max-width: 768px) {
      .contact-hero { padding: 4rem 0 3rem; }
      .form-card { padding: 1.75rem 1.25rem; }
    }
  </style>
</head>

<body>

  <?php include __DIR__ . '/includes/navigation.php'; ?>

  <main>

    <section class="contact-hero" aria-label="Contact MusicMarket">
      <div class="container" style="position:relative;z-index:1;">
        <p class="hero-eyebrow">Get In Touch</p>
        <h1 class="hero-heading">
          We'd love to<br>hear <em>from you.</em>
        </h1>
        <p class="hero-desc">
          Questions about an order, feedback on the platform, or just want to 
          talk records — we're here for all of it.
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
            <div class="form-card">

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

              <form method="POST" action="" novalidate>

                <h3 class="form-section-title">Your details</h3>
                <div class="row g-3 mb-4">

                  <div class="col-sm-6">
                    <label for="name" class="form-label">Full Name <span aria-hidden="true">*</span></label>
                    <div class="input-wrap">
                      <i class="bi bi-person"></i>
                      <input
                        type="text" name="name" id="name"
                        class="form-control"
                        placeholder="Jane Smith"
                        value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                        required
                        aria-required="true"
                      >
                    </div>
                  </div>

                  <div class="col-sm-6">
                    <label for="email" class="form-label">Email Address <span aria-hidden="true">*</span></label>
                    <div class="input-wrap">
                      <i class="bi bi-envelope"></i>
                      <input
                        type="email" name="email" id="email"
                        class="form-control"
                        placeholder="jane@example.com"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        required
                        aria-required="true"
                      >
                    </div>
                  </div>

                  <div class="col-sm-6">
                    <label for="topic" class="form-label">Topic <span aria-hidden="true">*</span></label>
                    <div class="input-wrap">
                      <i class="bi bi-tag"></i>
                      <select name="topic" id="topic" class="form-select" required aria-required="true">
                        <option value="" disabled <?= empty($_POST['topic']) ? 'selected' : '' ?>>Select a topic…</option>
                        <option value="order"     <?= (($_POST['topic'] ?? '') === 'order')     ? 'selected' : '' ?>>Order / Shipping</option>
                        <option value="selling"   <?= (($_POST['topic'] ?? '') === 'selling')   ? 'selected' : '' ?>>Selling on MusicMarket</option>
                        <option value="account"   <?= (($_POST['topic'] ?? '') === 'account')   ? 'selected' : '' ?>>Account &amp; Billing</option>
                        <option value="feedback"  <?= (($_POST['topic'] ?? '') === 'feedback')  ? 'selected' : '' ?>>Platform Feedback</option>
                        <option value="press"     <?= (($_POST['topic'] ?? '') === 'press')     ? 'selected' : '' ?>>Press &amp; Partnerships</option>
                        <option value="other"     <?= (($_POST['topic'] ?? '') === 'other')     ? 'selected' : '' ?>>Other</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-sm-6">
                    <label for="subject" class="form-label">Subject <span aria-hidden="true">*</span></label>
                    <div class="input-wrap">
                      <i class="bi bi-chat-left-text"></i>
                      <input
                        type="text" name="subject" id="subject"
                        class="form-control"
                        placeholder="Brief summary of your enquiry"
                        value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>"
                        required
                        aria-required="true"
                      >
                    </div>
                  </div>

                  <div class="col-12">
                    <label for="message" class="form-label">Message <span aria-hidden="true">*</span></label>
                    <div class="input-wrap">
                      <textarea
                        name="message" id="message"
                        class="form-control"
                        placeholder="Tell us what's on your mind…"
                        required
                        aria-required="true"
                      ><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                    </div>
                  </div>

                </div>

                <h3 class="form-section-title">Your feedback</h3>
                <div class="mb-4">

                  <label class="form-label mb-2">How would you rate your MusicMarket experience? <span aria-hidden="true">*</span></label>

                  <div class="star-group" role="radiogroup" aria-label="Rating out of 5 stars">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                      <input
                        type="radio" name="feedback_rating" id="star<?= $i ?>"
                        value="<?= $i ?>"
                        <?= (intval($_POST['feedback_rating'] ?? 0) === $i) ? 'checked' : '' ?>
                      >
                      <label for="star<?= $i ?>" title="<?= $i ?> star<?= $i > 1 ? 's' : '' ?>" aria-label="<?= $i ?> star<?= $i > 1 ? 's' : '' ?>">★</label>
                    <?php endfor; ?>
                  </div>
                  <p class="star-hint" id="star-hint" aria-live="polite"></p>

                </div>

                <h3 class="form-section-title">Security check</h3>
                <div class="mb-4">
                  <label for="captcha" class="form-label">
                    What is <?= $_SESSION['contact_captcha_n1'] ?> + <?= $_SESSION['contact_captcha_n2'] ?>? <span aria-hidden="true">*</span>
                  </label>
                  <div class="input-wrap" style="max-width:180px;">
                    <i class="bi bi-shield-lock"></i>
                    <input
                      type="text" name="captcha" id="captcha"
                      class="form-control"
                      placeholder="Your answer"
                      required
                      autocomplete="off"
                      aria-required="true"
                    >
                  </div>
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

  <script>
    // Star rating hint labels
    const hints = ['', 'Poor', 'Fair', 'Good', 'Great', 'Excellent'];
    const hintEl = document.getElementById('star-hint');
    const stars  = document.querySelectorAll('.star-group input[type="radio"]');

    stars.forEach(radio => {
      radio.addEventListener('change', () => {
        hintEl.textContent = hints[parseInt(radio.value)] || '';
      });
    });

    // Restore hint text on page load if rating was previously selected (e.g. after failed submission)
    const checked = document.querySelector('.star-group input:checked');
    if (checked) hintEl.textContent = hints[parseInt(checked.value)] || '';

    // Scroll-reveal
    (function () {
      const style = document.createElement('style');
      style.textContent = `
        .reveal { opacity: 0; transform: translateY(20px); transition: opacity 0.5s ease, transform 0.5s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }
      `;
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

</body>
</html>