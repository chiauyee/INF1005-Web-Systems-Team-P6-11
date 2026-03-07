<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us – MusicMarket</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
      :root {
        --bg: #f8f8f6;
        --white: #ffffff;
        --border: #e2e2de;
        --text: #1a1a1a;
        --text-muted: #666;
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
        font-family: 'DM Sans', sans-serif;
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

      /* Hero Banner */
      .about-hero {
        background-color: #1a1a1a;
        position: relative;
        overflow: hidden;
        padding: 7rem 0 6rem;
        color: #fff;
      }

      /* Ring motif */
      .about-hero::before {
        content: '';
        position: absolute;
        width: 600px;
        height: 600px;
        border-radius: 50%;
        right: -180px;
        bottom: -180px;
        border: 60px solid rgba(255,255,255,0.03);
        box-shadow:
          0 0 0 60px  rgba(255,255,255,0.03),
          0 0 0 120px rgba(255,255,255,0.02),
          0 0 0 180px rgba(255,255,255,0.015);
        pointer-events: none;
      }
      .about-hero::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        left: -80px;
        top: -80px;
        border: 40px solid rgba(255,255,255,0.025);
        box-shadow:
          0 0 0 40px rgba(255,255,255,0.02),
          0 0 0 80px rgba(255,255,255,0.01);
        pointer-events: none;
      }

      .hero-eyebrow {
        font-size: 0.7rem;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.4);
        margin-bottom: 1.5rem;
      }

      .hero-heading {
        font-family: 'Playfair Display', serif;
        font-size: clamp(2.4rem, 5vw, 3.8rem);
        line-height: 1.15;
        color: #fff;
        margin-bottom: 1.5rem;
      }

      .hero-heading em {
        font-style: italic;
        color: rgba(255,255,255,0.45);
      }

      .hero-desc {
        font-size: 0.95rem;
        color: rgba(255,255,255,0.45);
        line-height: 1.9;
        max-width: 460px;
      }

      .hero-stats {
        display: flex;
        gap: 3rem;
        margin-top: 3rem;
        flex-wrap: wrap;
      }

      .stat-number {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        color: #fff;
        line-height: 1;
      }

      .stat-label {
        font-size: 0.72rem;
        color: rgba(255,255,255,0.35);
        letter-spacing: 0.06em;
        text-transform: uppercase;
        margin-top: 0.3rem;
      }

      /* Section label */
      .section-eyebrow {
        font-size: 0.7rem;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 0.6rem;
      }

      .section-heading {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.6rem, 3vw, 2.4rem);
        line-height: 1.25;
        color: var(--text);
        margin-bottom: 1rem;
      }

      .section-heading em {
        font-style: italic;
        color: var(--text-muted);
      }

      /* Story section */
      .story-section {
        padding: 6rem 0;
        background: var(--white);
      }

      .story-body {
        font-size: 0.95rem;
        color: var(--text-muted);
        line-height: 1.9;
      }

      .story-body p + p {
        margin-top: 1.2rem;
      }

      .story-img-wrap {
        position: relative;
      }

      .story-img-wrap img {
        width: 100%;
        height: 420px;
        object-fit: cover;
        border-radius: 4px;
        display: block;
      }

      /* Offset accent frame behind image */
      .story-img-wrap::before {
        content: '';
        position: absolute;
        inset: 16px -16px -16px 16px;
        border: 1.5px solid var(--border);
        border-radius: 4px;
        pointer-events: none;
        z-index: 0;
      }

      .story-img-wrap img { position: relative; z-index: 1; }

      /* Values section */
      .values-section {
        padding: 6rem 0;
        background: var(--bg);
      }

      .value-card {
        background: var(--white);
        border: 1.5px solid var(--border);
        border-radius: 8px;
        padding: 2rem 1.75rem;
        height: 100%;
        transition: box-shadow 0.25s, transform 0.2s;
      }

      .value-card:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.07);
        transform: translateY(-3px);
      }

      .value-icon {
        width: 44px;
        height: 44px;
        background: #1a1a1a;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.1rem;
        margin-bottom: 1.25rem;
        flex-shrink: 0;
      }

      .value-icon .bi {
        line-height: 1;
        padding-top: 2px;
      }

      .value-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.15rem;
        color: var(--text);
        margin-bottom: 0.5rem;
      }

      .value-desc {
        font-size: 0.875rem;
        color: var(--text-muted);
        line-height: 1.8;
        margin: 0;
      }

      /* Team section */
      .team-section {
        padding: 6rem 0;
        background: var(--white);
      }

      .team-card {
        text-align: center;
      }

      .team-avatar {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--border);
        margin: 0 auto 1rem;
        display: block;
        background: #e8e8e4; /* fallback if no img */
      }

      /* Placeholder avatar circle when no image */
      .team-avatar-placeholder {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        background: #1a1a1a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: rgba(255,255,255,0.7);
        margin: 0 auto 1rem;
      }

      .team-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.05rem;
        color: var(--text);
        margin-bottom: 0.2rem;
      }

      .team-role {
        font-size: 0.72rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 0.75rem;
      }

      .team-bio {
        font-size: 0.85rem;
        color: var(--text-muted);
        line-height: 1.75;
      }

      /* CTA banner */
      .cta-section {
        background: #1a1a1a;
        padding: 5rem 0;
        position: relative;
        overflow: hidden;
        text-align: center;
        color: #fff;
      }

      .cta-section::before {
        content: '';
        position: absolute;
        width: 500px;
        height: 500px;
        border-radius: 50%;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        border: 80px solid rgba(255,255,255,0.02);
        box-shadow:
          0 0 0 80px  rgba(255,255,255,0.02),
          0 0 0 160px rgba(255,255,255,0.015);
        pointer-events: none;
      }

      .cta-heading {
        font-family: 'Playfair Display', serif;
        font-size: clamp(1.8rem, 3.5vw, 2.8rem);
        margin-bottom: 0.75rem;
        position: relative;
        z-index: 1;
      }

      .cta-sub {
        font-size: 0.95rem;
        color: rgba(255,255,255,0.45);
        margin-bottom: 2rem;
        position: relative;
        z-index: 1;
      }

      .btn-outline-light-custom {
        background: transparent;
        color: #fff;
        border: 1.5px solid rgba(255,255,255,0.35);
        border-radius: 6px;
        font-family: 'DM Sans', sans-serif;
        font-weight: 500;
        font-size: 0.9rem;
        padding: 0.65rem 1.5rem;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s, transform 0.15s;
        position: relative;
        z-index: 1;
        text-decoration: none;
        display: inline-block;
      }

      .btn-outline-light-custom:hover {
        border-color: rgba(255,255,255,0.7);
        background: rgba(255,255,255,0.06);
        transform: translateY(-1px);
        color: #fff;
      }

      .btn-white {
        background: #fff;
        color: #1a1a1a;
        border: none;
        border-radius: 6px;
        font-family: 'DM Sans', sans-serif;
        font-weight: 500;
        font-size: 0.9rem;
        padding: 0.65rem 1.5rem;
        cursor: pointer;
        transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        position: relative;
        z-index: 1;
        text-decoration: none;
        display: inline-block;
      }

      .btn-white:hover {
        background: #f0f0f0;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        color: #1a1a1a;
      }

      /* Footer (mirrors index.php) */
      footer {
        background-color: #1a1a1a;
        color: #fff;
        padding: 20px 0;
        text-align: center;
      }

      footer a {
        color: #fff;
        text-decoration: none;
      }

      /* Divider rule */
      .ruled {
        border: none;
        border-top: 1.5px solid var(--border);
        margin: 0;
      }

      /* Responsive */
      @media (max-width: 768px) {
        .about-hero { padding: 5rem 0 4rem; }
        .hero-stats { gap: 2rem; }
        .story-img-wrap::before { display: none; }
        .story-img-wrap img { height: 280px; }
      }
    </style>
  </head>

  <body>

    <?php include __DIR__ . '/includes/navigation.php'; ?>

    <main>

      <section class="about-hero" aria-label="About MusicMarket">
        <div class="container" style="position:relative; z-index:1;">
          <p class="hero-eyebrow">About Us</p>
          <h1 class="hero-heading">
            Where every record<br>
            tells <em>a story.</em>
          </h1>
          <p class="hero-desc">
            MusicMarket is a community marketplace for music enthusiasts to discover, 
            buy, sell and celebrate physical media — vinyl, CDs, cassettes and beyond.
          </p>
          <div class="hero-stats">
            <div>
              <div class="stat-number">120K+</div>
              <div class="stat-label">Listings</div>
            </div>
            <div>
              <div class="stat-number">38K+</div>
              <div class="stat-label">Members</div>
            </div>
            <div>
              <div class="stat-number">92</div>
              <div class="stat-label">Countries</div>
            </div>
            <div>
              <div class="stat-number">4.9 ★</div>
              <div class="stat-label">Avg. rating</div>
            </div>
          </div>
        </div>
      </section>

      <!-- ═══ Our Story ══════════════════════════════════════════ -->
      <section class="story-section" aria-labelledby="story-heading">
        <div class="container">
          <div class="row g-5 align-items-center">

            <!-- Text -->
            <div class="col-lg-6 order-lg-1 order-2">
              <p class="section-eyebrow">Our Story</p>
              <h2 class="section-heading" id="story-heading">
                Born from a love of<br><em>tangible music.</em>
              </h2>
              <div class="story-body">
                <p>
                  MusicMarket started in 2021 when a handful of passionate collectors grew 
                  frustrated with bloated, impersonal platforms that treated vinyl like any 
                  other commodity. We knew music deserved better — a space where 
                  context, condition and community actually mattered.
                </p>
                <p>
                  We built the platform we always wished existed: clean, fast and curated 
                  by people who care. From first-pressing rarities to everyday classics, 
                  every listing on MusicMarket is handled with the attention it deserves.
                </p>
                <p>
                  Today we connect tens of thousands of collectors across the globe, 
                  facilitating discoveries that would never happen in a local record shop — 
                  while keeping the warmth of that experience alive.
                </p>
              </div>
            </div>

            <!-- Image -->
            <div class="col-lg-6 order-lg-2 order-1">
              <div class="story-img-wrap">
                <img
                  src="https://images.unsplash.com/photo-1483412033650-1015ddeb83d1?w=900&q=80"
                  alt="A person browsing a crate of vinyl records in a warm, well-lit record shop"
                  loading="lazy"
                >
              </div>
            </div>

          </div>
        </div>
      </section>

      <hr class="ruled">

      <!-- ═══ Values ═════════════════════════════════════════════ -->
      <section class="values-section" aria-labelledby="values-heading">
        <div class="container">
          <div class="text-center mb-5">
            <p class="section-eyebrow">What We Stand For</p>
            <h2 class="section-heading" id="values-heading">Our values</h2>
          </div>

          <div class="row g-4">

            <div class="col-md-6 col-lg-3">
              <div class="value-card">
                <div class="value-icon" aria-hidden="true">
                  <i class="bi bi-people-fill"></i>
                </div>
                <h3 class="value-title">Community First</h3>
                <p class="value-desc">
                  Every feature we build starts with a simple question: does this help 
                  collectors connect? Our community is the product.
                </p>
              </div>
            </div>

            <div class="col-md-6 col-lg-3">
              <div class="value-card">
                <div class="value-icon" aria-hidden="true">
                  <i class="bi bi-shield-check"></i>
                </div>
                <h3 class="value-title">Trust &amp; Transparency</h3>
                <p class="value-desc">
                  Honest grading, verified sellers and buyer protection baked into 
                  every transaction. No surprises, no fine print.
                </p>
              </div>
            </div>

            <div class="col-md-6 col-lg-3">
              <div class="value-card">
                <div class="value-icon" aria-hidden="true">
                  <i class="bi bi-vinyl"></i>
                </div>
                <h3 class="value-title">Format Agnostic</h3>
                <p class="value-desc">
                  Vinyl, CD, cassette, 8-track — all formats are celebrated equally here. 
                  Great music transcends the medium.
                </p>
              </div>
            </div>

            <div class="col-md-6 col-lg-3">
              <div class="value-card">
                <div class="value-icon" aria-hidden="true">
                  <i class="bi bi-globe2"></i>
                </div>
                <h3 class="value-title">Global Reach</h3>
                <p class="value-desc">
                  The rarest pressings hide around the corners of the world. 
                  We make global shipping feel local and accessible.
                </p>
              </div>
            </div>

          </div>
        </div>
      </section>

      <hr class="ruled">

      <section class="team-section" aria-labelledby="team-heading">
        <div class="container">
          <div class="text-center mb-5">
            <p class="section-eyebrow">The People Behind It</p>
            <h2 class="section-heading" id="team-heading">Meet the <em>team.</em></h2>
          </div>

          <div class="row g-4 justify-content-center">

            <div class="col-sm-6 col-lg-3">
              <div class="team-card">
                <div class="team-avatar-placeholder" aria-hidden="true">A</div>
                <h3 class="team-name">Amara Singh</h3>
                <p class="team-role">Co-Founder &amp; CEO</p>
                <p class="team-bio">
                  Former session musician turned product builder. Amara set out to make 
                  physical music discovery feel as magical as it did in 1998.
                </p>
              </div>
            </div>

            <div class="col-sm-6 col-lg-3">
              <div class="team-card">
                <div class="team-avatar-placeholder" aria-hidden="true">L</div>
                <h3 class="team-name">Leon Hartley</h3>
                <p class="team-role">Co-Founder &amp; CTO</p>
                <p class="team-bio">
                  Obsessive crate-digger by weekend, infrastructure engineer by day. 
                  Leon keeps the platform fast, secure and always online.
                </p>
              </div>
            </div>

            <div class="col-sm-6 col-lg-3">
              <div class="team-card">
                <div class="team-avatar-placeholder" aria-hidden="true">N</div>
                <h3 class="team-name">Naomi Yew</h3>
                <p class="team-role">Head of Community</p>
                <p class="team-bio">
                  Naomi runs seller onboarding, dispute resolution and the MusicMarket 
                  newsletter — read by over 25,000 collectors every fortnight.
                </p>
              </div>
            </div>

            <div class="col-sm-6 col-lg-3">
              <div class="team-card">
                <div class="team-avatar-placeholder" aria-hidden="true">R</div>
                <h3 class="team-name">Rafael Okonkwo</h3>
                <p class="team-role">Lead Designer</p>
                <p class="team-bio">
                  A vinyl obsessive with a type degree. Rafael ensures that every pixel on 
                  MusicMarket reflects the care that goes into a great sleeve design.
                </p>
              </div>
            </div>

          </div>
        </div>
      </section>

      <section class="cta-section" aria-label="Call to action">
        <div class="container">
          <h2 class="cta-heading">Ready to dig in?</h2>
          <p class="cta-sub">Join thousands of collectors buying and selling music they love.</p>
          <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="register.php" class="btn-white">Create a free account</a>
            <a href="index.php" class="btn-outline-light-custom">Browse the marketplace</a>
          </div>
        </div>
      </section>

    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <!-- Subtle scroll-reveal via Intersection Observer -->
    <script>
      (function () {
        const style = document.createElement('style');
        style.textContent = `
          .reveal { opacity: 0; transform: translateY(24px); transition: opacity 0.55s ease, transform 0.55s ease; }
          .reveal.visible { opacity: 1; transform: translateY(0); }
        `;
        document.head.appendChild(style);

        const targets = document.querySelectorAll(
          '.value-card, .team-card, .story-body, .story-img-wrap'
        );
        targets.forEach(el => el.classList.add('reveal'));

        const observer = new IntersectionObserver((entries) => {
          entries.forEach(e => {
            if (e.isIntersecting) {
              e.target.classList.add('visible');
              observer.unobserve(e.target);
            }
          });
        }, { threshold: 0.12 });

        targets.forEach(el => observer.observe(el));
      })();
    </script>

  </body>
</html>