<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAQ – MusicMarket</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="/css/navigation.css">
  <link
    href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="/css/faq.css">
</head>

<body>

  <?php include __DIR__ . '/includes/navigation.php'; ?>

  <main>

    <section class="faq-hero" aria-label="Frequently Asked Questions">
      <div id="cassette-container" class="hero-cassette"></div>
      <div class="container" style="position:relative;z-index:1;">
        <p class="hero-eyebrow">Help Centre</p>
        <h1 class="hero-heading">
          Frequently asked<br><em>questions.</em>
        </h1>
        <p class="hero-desc">
          Everything you need to know about buying, selling and managing your
          account on MusicMarket.
        </p>
        <div class="faq-search-wrap" role="search">
          <i class="bi bi-search" aria-hidden="true"></i>
          <input type="search" id="faq-search" placeholder="Search questions…"
            aria-label="Search frequently asked questions" autocomplete="off">
        </div>
      </div>
    </section>

    <section class="faq-body">
      <div class="container">
        <div class="row g-5">

          <div class="col-lg-3">
            <nav class="cat-nav" aria-label="FAQ categories">
              <p class="cat-label">Categories</p>

              <button class="cat-btn active" data-cat="buying" aria-pressed="true">
                <span class="cat-icon" aria-hidden="true"><i class="bi bi-bag"></i></span>
                Buying
              </button>
              <button class="cat-btn" data-cat="selling" aria-pressed="false">
                <span class="cat-icon" aria-hidden="true"><i class="bi bi-tag"></i></span>
                Selling
              </button>
              <button class="cat-btn" data-cat="shipping" aria-pressed="false">
                <span class="cat-icon" aria-hidden="true"><i class="bi bi-box-seam"></i></span>
                Shipping
              </button>
              <button class="cat-btn" data-cat="account" aria-pressed="false">
                <span class="cat-icon" aria-hidden="true"><i class="bi bi-person"></i></span>
                Account
              </button>
              <button class="cat-btn" data-cat="payments" aria-pressed="false">
                <span class="cat-icon" aria-hidden="true"><i class="bi bi-credit-card"></i></span>
                Payments
              </button>
              <button class="cat-btn" data-cat="grading" aria-pressed="false">
                <span class="cat-icon" aria-hidden="true"><i class="bi bi-vinyl"></i></span>
                Grading &amp; Condition
              </button>
            </nav>
          </div>

          <!-- FAQ content  -->
          <div class="col-lg-9">

            <!-- Search: no results -->
            <div class="no-results" id="no-results" aria-live="polite">
              <i class="bi bi-search"></i>
              No questions matched your search. Try different keywords or
              <a href="contact.php">contact us</a> directly.
            </div>

            <!-- BUYING -->
            <div class="faq-section active" id="cat-buying">
              <p class="section-eyebrow">Buying</p>
              <h2 class="section-heading">Shopping on MusicMarket.</h2>

              <?php
              $buying_faqs = [
                [
                  "How do I search for a specific album or artist?",
                  "Use the search bar at the top of any page. You can search by album title, artist name, catalogue number or format. Use the filters on the results page to narrow by genre, format, condition or price range."
                ],
                [
                  "Can I buy from sellers in other countries?",
                  "Yes — MusicMarket is a global marketplace. Each listing shows the seller's location and the available shipping destinations. International shipping rates and estimated delivery times are shown at checkout before you confirm your purchase."
                ],
                [
                  "What does the buyer protection policy cover?",
                  "If an item arrives significantly not as described, is damaged in transit, or doesn't arrive at all, you're covered under our Buyer Protection policy. Open a dispute within 7 days of the expected delivery date and our team will step in to help resolve it."
                ],
                [
                  "Can I make an offer below the listed price?",
                  "Yes, if the seller has enabled the Make an Offer option on their listing. You'll see an \"Offer\" button alongside the standard Buy button. The seller has 48 hours to accept, decline or counter your offer."
                ],
                [
                  "How do I add items to a wishlist?",
                  "Click the bookmark icon on any listing to save it to your Wishlist. You can view and manage your saved items from your profile dashboard. You'll also receive a notification if a wishlisted item drops in price."
                ],
              ];
              foreach ($buying_faqs as $i => $faq): ?>
                <div class="faq-item">
                  <button class="faq-question" aria-expanded="false" aria-controls="buying-ans-<?= $i ?>"
                    id="buying-q-<?= $i ?>">
                    <?= htmlspecialchars($faq[0], ENT_QUOTES, 'UTF-8') ?>
                    <i class="bi bi-chevron-down faq-chevron" aria-hidden="true"></i>
                  </button>
                  <div class="faq-answer" id="buying-ans-<?= $i ?>" role="region" aria-labelledby="buying-q-<?= $i ?>">
                    <div class="faq-answer-inner"><?= htmlspecialchars($faq[1], ENT_QUOTES, 'UTF-8') ?></div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- SELLING -->
            <div class="faq-section" id="cat-selling">
              <p class="section-eyebrow">Selling</p>
              <h2 class="section-heading">List &amp; sell your collection.</h2>

              <?php
              $selling_faqs = [
                [
                  "How do I create my first listing?",
                  "Go to your seller dashboard and click \"New Listing\". Search for the release in our database — if it exists, most details will auto-fill. You'll then need to set the condition, price and your shipping options. Listings go live immediately after submission."
                ],
                [
                  "What seller fees does MusicMarket charge?",
                  "MusicMarket charges a flat 8% commission on the final sale price (excluding shipping). There are no listing fees and no monthly subscription required. Fees are deducted automatically before funds are released to your account."
                ],
                [
                  "How quickly do I need to ship after a sale?",
                  "Orders should be dispatched within 3 business days of payment confirmation. If you need more time, message the buyer before the dispatch deadline. Repeated late shipments may affect your seller rating."
                ],
                [
                  "Can I sell outside my home country?",
                  "Yes. When creating a listing, you can specify which countries or regions you're willing to ship to and set individual shipping rates per destination. You can also choose to offer free worldwide shipping to attract more buyers."
                ],
                [
                  "What happens if a buyer opens a dispute?",
                  "You'll receive a notification and have 48 hours to respond with your account of the situation. Provide any relevant evidence such as tracking information or photos. Our support team will review both sides and issue a resolution within 5 business days."
                ],
              ];
              foreach ($selling_faqs as $i => $faq): ?>
                <div class="faq-item">
                  <button class="faq-question" aria-expanded="false" aria-controls="selling-ans-<?= $i ?>"
                    id="selling-q-<?= $i ?>">
                    <?= htmlspecialchars($faq[0], ENT_QUOTES, 'UTF-8') ?>
                    <i class="bi bi-chevron-down faq-chevron" aria-hidden="true"></i>
                  </button>
                  <div class="faq-answer" id="selling-ans-<?= $i ?>" role="region" aria-labelledby="selling-q-<?= $i ?>">
                    <div class="faq-answer-inner"><?= htmlspecialchars($faq[1], ENT_QUOTES, 'UTF-8') ?></div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- SHIPPING -->
            <div class="faq-section" id="cat-shipping">
              <p class="section-eyebrow">Shipping</p>
              <h2 class="section-heading">Getting records to you.</h2>

              <?php
              $shipping_faqs = [
                [
                  "How do I track my order?",
                  "Once the seller marks your order as shipped, you'll receive an email with tracking details (if the seller provided a tracking number). You can also view the status of all active orders from your account dashboard under \"Purchases\"."
                ],
                [
                  "My order hasn't arrived — what should I do?",
                  "First, check the tracking information in your order details. If the estimated delivery window has passed and tracking shows no movement, contact the seller via the order messaging system. If you don't receive a satisfactory response within 48 hours, open a Buyer Protection case."
                ],
                [
                  "Are there any import duties or taxes I should know about?",
                  "International orders may be subject to import duties, customs fees or local taxes depending on your country's regulations. These charges are the buyer's responsibility and are not included in the listing or shipping price. We recommend checking your local customs rules before purchasing internationally."
                ],
                [
                  "What packaging do sellers use for vinyl?",
                  "MusicMarket strongly encourages sellers to use rigid mailers with inner sleeve protection for 12\" vinyl. Our seller guidelines recommend at minimum a stiffener board and a padded outer envelope. You can find our full packaging recommendations in the Seller Help Centre."
                ],
              ];
              foreach ($shipping_faqs as $i => $faq): ?>
                <div class="faq-item">
                  <button class="faq-question" aria-expanded="false" aria-controls="shipping-ans-<?= $i ?>"
                    id="shipping-q-<?= $i ?>">
                    <?= htmlspecialchars($faq[0], ENT_QUOTES, 'UTF-8') ?>
                    <i class="bi bi-chevron-down faq-chevron" aria-hidden="true"></i>
                  </button>
                  <div class="faq-answer" id="shipping-ans-<?= $i ?>" role="region"
                    aria-labelledby="shipping-q-<?= $i ?>">
                    <div class="faq-answer-inner"><?= htmlspecialchars($faq[1], ENT_QUOTES, 'UTF-8') ?></div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- ACCOUNT -->
            <div class="faq-section" id="cat-account">
              <p class="section-eyebrow">Account</p>
              <h2 class="section-heading">Managing your account.</h2>

              <?php
              $account_faqs = [
                [
                  "How do I create an account?",
                  "Click \"Register\" in the top navigation bar. You'll need a valid email address and a username. After submitting the form, you can log in immediately — no email verification step is required to get started."
                ],
                [
                  "How do I reset my password?",
                  "On the login page, click \"Forgot password?\". Enter the email address associated with your account and we'll send you a reset link valid for 30 minutes. If you don't see the email, check your spam folder."
                ],
                [
                  "Can I have both a buyer and a seller account?",
                  "Every MusicMarket account can buy and sell. There's no separate seller account — simply head to your dashboard and click \"Start Selling\" to activate your seller profile. You can switch between buyer and seller views from the same account."
                ],
                [
                  "How do I delete my account?",
                  "Go to Account Settings → Privacy → Delete Account. Please note that deleting your account is permanent and irreversible. Any open orders must be completed or cancelled before deletion can proceed. Your public listing history will be removed within 30 days."
                ],
              ];
              foreach ($account_faqs as $i => $faq): ?>
                <div class="faq-item">
                  <button class="faq-question" aria-expanded="false" aria-controls="account-ans-<?= $i ?>"
                    id="account-q-<?= $i ?>">
                    <?= htmlspecialchars($faq[0], ENT_QUOTES, 'UTF-8') ?>
                    <i class="bi bi-chevron-down faq-chevron" aria-hidden="true"></i>
                  </button>
                  <div class="faq-answer" id="account-ans-<?= $i ?>" role="region" aria-labelledby="account-q-<?= $i ?>">
                    <div class="faq-answer-inner"><?= htmlspecialchars($faq[1], ENT_QUOTES, 'UTF-8') ?></div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- PAYMENTS -->
            <div class="faq-section" id="cat-payments">
              <p class="section-eyebrow">Payments</p>
              <h2 class="section-heading">Payments &amp; billing.</h2>

              <?php
              $payments_faqs = [
                [
                  "What payment methods are accepted?",
                  "MusicMarket accepts Visa, Mastercard, and American Express credit and debit cards. PayPal is also supported at checkout. All transactions are processed securely — card details are never stored on our servers."
                ],
                [
                  "When does a seller get paid?",
                  "Funds are held for 3 business days after the buyer confirms receipt (or after the delivery window closes, whichever is sooner). Once released, the amount minus the 8% commission is transferred to the seller's registered payout account."
                ],
                [
                  "Can I get a refund?",
                  "If your item arrives not as described or doesn't arrive at all, open a Buyer Protection case and a refund will be processed if the claim is upheld. Change-of-mind returns are at the seller's discretion — check the individual listing's return policy before purchasing."
                ],
                [
                  "Is my payment information secure?",
                  "Yes. All payment processing is handled by a PCI-DSS-compliant payment gateway. MusicMarket never stores your full card number or CVV. Transactions are encrypted end-to-end using TLS."
                ],
              ];
              foreach ($payments_faqs as $i => $faq): ?>
                <div class="faq-item">
                  <button class="faq-question" aria-expanded="false" aria-controls="payments-ans-<?= $i ?>"
                    id="payments-q-<?= $i ?>">
                    <?= htmlspecialchars($faq[0], ENT_QUOTES, 'UTF-8') ?>
                    <i class="bi bi-chevron-down faq-chevron" aria-hidden="true"></i>
                  </button>
                  <div class="faq-answer" id="payments-ans-<?= $i ?>" role="region"
                    aria-labelledby="payments-q-<?= $i ?>">
                    <div class="faq-answer-inner"><?= htmlspecialchars($faq[1], ENT_QUOTES, 'UTF-8') ?></div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- GRADING -->
            <div class="faq-section" id="cat-grading">
              <p class="section-eyebrow">Grading &amp; Condition</p>
              <h2 class="section-heading">Understanding condition grades.</h2>

              <?php
              $grading_faqs = [
                [
                  "What grading scale does MusicMarket use?",
                  "We use the industry-standard Goldmine grading scale: Mint (M), Near Mint (NM or M-), Very Good Plus (VG+), Very Good (VG), Good Plus (G+), Good (G), Fair (F) and Poor (P). Sellers are required to grade both the media and the sleeve/jacket separately."
                ],
                [
                  "What does VG+ actually mean?",
                  "Very Good Plus (VG+) is the most commonly listed condition for second-hand records. It indicates a record that has been played carefully and shows only light signs of wear. There may be slight scuffs visible under direct light, but these should not affect playback. Think of it as a well-cared-for used copy."
                ],
                [
                  "Can I trust that a seller's grade is accurate?",
                  "Sellers are expected to grade honestly — inflating condition grades is a violation of our seller policy and can result in account suspension. If you receive an item that is clearly worse than described, you can report it through the dispute system. Consistently accurate graders earn a \"Trusted Grader\" badge on their profile."
                ],
                [
                  "What's the difference between the media grade and the sleeve grade?",
                  "The media grade refers to the condition of the vinyl or disc itself, while the sleeve grade covers the outer jacket and any inner sleeves or inserts. It's common for the two to differ — for example, a VG+ record in a VG sleeve. Both grades are shown on every listing."
                ],
              ];
              foreach ($grading_faqs as $i => $faq): ?>
                <div class="faq-item">
                  <button class="faq-question" aria-expanded="false" aria-controls="grading-ans-<?= $i ?>"
                    id="grading-q-<?= $i ?>">
                    <?= htmlspecialchars($faq[0], ENT_QUOTES, 'UTF-8') ?>
                    <i class="bi bi-chevron-down faq-chevron" aria-hidden="true"></i>
                  </button>
                  <div class="faq-answer" id="grading-ans-<?= $i ?>" role="region" aria-labelledby="grading-q-<?= $i ?>">
                    <div class="faq-answer-inner"><?= htmlspecialchars($faq[1], ENT_QUOTES, 'UTF-8') ?></div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <!-- Still need help -->
            <div class="help-banner" aria-label="Still need help">
              <div class="help-banner-text">
                <p class="help-banner-title">Still have a question?</p>
                <p class="help-banner-sub">Our support team typically responds within 2 business days.</p>
              </div>
              <a href="contact.php" class="btn-white">
                Contact us <i class="bi bi-arrow-right ms-1"></i>
              </a>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/dompurify/3.1.6/purify.min.js"></script>

  <script>
    // category switching 
    const catBtns = document.querySelectorAll('.cat-btn');
    const catPanels = document.querySelectorAll('.faq-section');

    catBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const target = btn.dataset.cat;

        catBtns.forEach(b => { b.classList.remove('active'); b.setAttribute('aria-pressed', 'false'); });
        catPanels.forEach(p => p.classList.remove('active'));

        btn.classList.add('active');
        btn.setAttribute('aria-pressed', 'true');
        document.getElementById('cat-' + target).classList.add('active');

        // clear search when switching categories
        document.getElementById('faq-search').value = '';
        document.getElementById('no-results').style.display = 'none';
        document.querySelectorAll('.faq-item').forEach(item => item.style.display = '');
      });
    });

    // Accordion
    document.querySelectorAll('.faq-question').forEach(btn => {
      btn.addEventListener('click', () => {
        const answer = document.getElementById(btn.getAttribute('aria-controls'));
        const expanded = btn.getAttribute('aria-expanded') === 'true';

        // Collapse all in the same section first
        const section = btn.closest('.faq-section');
        section.querySelectorAll('.faq-question').forEach(q => {
          q.setAttribute('aria-expanded', 'false');
          document.getElementById(q.getAttribute('aria-controls')).classList.remove('open');
        });

        // Toggle clicked item
        if (!expanded) {
          btn.setAttribute('aria-expanded', 'true');
          answer.classList.add('open');
        }
      });
    });

    // live search across all questions
    const searchInput = document.getElementById('faq-search');
    const noResults = document.getElementById('no-results');

    searchInput.addEventListener('input', () => {
      // DOMPurify to prevent XSS during searches
      let rawQuery = searchInput.value.trim().toLowerCase();
      const query = typeof DOMPurify !== 'undefined' ? DOMPurify.sanitize(rawQuery) : rawQuery;

      if (!query) {
        // restore category view
        noResults.style.display = 'none';
        catPanels.forEach(p => {
          const isActive = p.id === 'cat-' + document.querySelector('.cat-btn.active').dataset.cat;
          p.classList.toggle('active', isActive);
        });
        document.querySelectorAll('.faq-item').forEach(item => item.style.display = '');
        return;
      }

      // show all sections during search
      catPanels.forEach(p => p.classList.add('active'));

      let anyVisible = false;
      document.querySelectorAll('.faq-item').forEach(item => {
        const questionText = item.querySelector('.faq-question').textContent.toLowerCase();
        const answerText = item.querySelector('.faq-answer-inner').textContent.toLowerCase();
        const matches = questionText.includes(query) || answerText.includes(query);

        item.style.display = matches ? '' : 'none';
        if (matches) anyVisible = true;

        // auto-open matching stuff
        if (matches) {
          const btn = item.querySelector('.faq-question');
          const answer = document.getElementById(btn.getAttribute('aria-controls'));
          btn.setAttribute('aria-expanded', 'true');
          answer.classList.add('open');
        }
      });

      noResults.style.display = anyVisible ? 'none' : 'block';

      // hide empty section headings
      catPanels.forEach(panel => {
        const visibleItems = [...panel.querySelectorAll('.faq-item')].filter(i => i.style.display !== 'none');
        panel.classList.toggle('active', visibleItems.length > 0);
      });
    });
  </script>

  <!-- Three.js Cassette -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
  <script>
    (function () {
      const container = document.getElementById('cassette-container');
      if (!container) return;

      const scene = new THREE.Scene();

      // FOV is first value
      const camera = new THREE.PerspectiveCamera(35, 1, 0.1, 1000);
      camera.position.set(0, 0, 21);
      camera.lookAt(0, 0, 0);

      const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
      renderer.setSize(container.clientWidth, container.clientHeight);
      renderer.setPixelRatio(window.devicePixelRatio);
      container.appendChild(renderer.domElement);

      const cassette = new THREE.Group();
      scene.add(cassette);

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

      // 1. Main body
      createSolid(new THREE.BoxGeometry(10, 6.4, 0.8), lineMat, cassette, 0, 0, 0);

      // 2. Main label area (slightly raised)
      createSolid(new THREE.BoxGeometry(8, 3.8, 0.85), faintLineMat, cassette, 0, 0, 0);

      // 3. Central Tape Window
      createSolid(new THREE.BoxGeometry(4.4, 1.8, 0.9), lineMat, cassette, 0, 0, 0);

      // 4. Spool hubs and tape reels
      const spoolGroupL = new THREE.Group();
      spoolGroupL.position.set(-1.6, 0, 0);
      cassette.add(spoolGroupL);

      const spoolGroupR = new THREE.Group();
      spoolGroupR.position.set(1.6, 0, 0);
      cassette.add(spoolGroupR);

      // Internal hubs/gears
      const hubGeo = new THREE.CylinderGeometry(0.4, 0.4, 0.95, 12);
      hubGeo.rotateX(Math.PI / 2);
      createSolid(hubGeo, lineMat, spoolGroupL);
      createSolid(hubGeo, lineMat, spoolGroupR);

      // Internal tape rolls
      // Left roll (fuller)
      for (let r = 0.5; r <= 1.4; r += 0.15) {
        const ringGeo = new THREE.EdgesGeometry(new THREE.CylinderGeometry(r, r, 0.88, 32));
        ringGeo.rotateX(Math.PI / 2);
        spoolGroupL.add(new THREE.LineSegments(ringGeo, faintLineMat));
      }

      // Right roll (emptier)
      for (let r = 0.5; r <= 0.9; r += 0.15) {
        const ringGeo = new THREE.EdgesGeometry(new THREE.CylinderGeometry(r, r, 0.88, 32));
        ringGeo.rotateX(Math.PI / 2);
        spoolGroupR.add(new THREE.LineSegments(ringGeo, faintLineMat));
      }

      // Bridge line between spools to represent the tape unspooling
      const tapeLineGeo = new THREE.BufferGeometry().setFromPoints([
        new THREE.Vector3(-1.6, -1.4, 0.4),
        new THREE.Vector3(1.6, -0.9, 0.4)
      ]);
      cassette.add(new THREE.Line(tapeLineGeo, faintLineMat));

      const tapeLineGeoBack = new THREE.BufferGeometry().setFromPoints([
        new THREE.Vector3(-1.6, -1.4, -0.4),
        new THREE.Vector3(1.6, -0.9, -0.4)
      ]);
      cassette.add(new THREE.Line(tapeLineGeoBack, faintLineMat));

      // 5. Bottom trapezoid section
      createSolid(new THREE.BoxGeometry(7, 1.4, 0.95), lineMat, cassette, 0, -2.5, 0);

      // Two little holes at bottom corners of trapezoid
      const holeGeo = new THREE.CylinderGeometry(0.3, 0.3, 1.0, 16);
      holeGeo.rotateX(Math.PI / 2);
      createSolid(holeGeo, lineMat, cassette, -2.5, -2.5, 0);
      createSolid(holeGeo, lineMat, cassette, 2.5, -2.5, 0);

      // 6. Screws in the corners
      const screwGeo = new THREE.CylinderGeometry(0.12, 0.12, 0.9, 8);
      screwGeo.rotateX(Math.PI / 2);
      createSolid(screwGeo, lineMat, cassette, -4.6, 2.8, 0);
      createSolid(screwGeo, lineMat, cassette, 4.6, 2.8, 0);
      createSolid(screwGeo, lineMat, cassette, -4.6, -2.8, 0);
      createSolid(screwGeo, lineMat, cassette, 4.6, -2.8, 0);

      cassette.rotation.x = Math.PI / 8;
      cassette.rotation.y = -Math.PI / 5;
      cassette.rotation.z = Math.PI / 16;

      function animate() {
        requestAnimationFrame(animate);

        spoolGroupL.rotation.z -= 0.04;
        spoolGroupR.rotation.z -= 0.04;

        cassette.position.y = Math.sin(Date.now() * 0.001) * 0.3;
        cassette.rotation.y = -Math.PI / 5 + Math.sin(Date.now() * 0.0005) * 0.04;

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