<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DriveSphere - Buy & Rent Vehicles Online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --ds-navy: #0b1d3a;
            --ds-navy-light: #14294f;
            --ds-orange: #ff6b35;
            --ds-orange-dark: #e8551f;
            --ds-gold: #ffb703;
            --ds-gray: #6c7683;
            --ds-light-bg: #f6f8fb;
            --ds-radius: 14px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            color: #1c2b3a;
            background: #fff;
            margin: 0;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
        }

        a { text-decoration: none; }

        /* ===== NAVBAR (overlaid on hero) ===== */
        .ds-navbar {
            position: absolute;
            top: 0; left: 0; right: 0;
            z-index: 10;
            padding: 22px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .ds-logo {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            font-size: 1.5rem;
            color: #fff;
            letter-spacing: -0.5px;
        }
        .ds-logo span { color: var(--ds-orange); }
        .ds-nav-center {
            display: flex;
            gap: 34px;
        }
        .ds-nav-center a {
            color: rgba(255,255,255,0.85);
            font-weight: 500;
            font-size: 0.95rem;
            transition: 0.2s;
        }
        .ds-nav-center a:hover { color: #fff; }
        .ds-nav-links { display: flex; align-items: center; gap: 14px; }
        .ds-icon-circle {
            width: 40px; height: 40px;
            border-radius: 50%;
            border: 1.5px solid rgba(255,255,255,0.35);
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            transition: 0.2s;
            font-size: 1.1rem;
        }
        .ds-icon-circle:hover { background: rgba(255,255,255,0.1); }
        .ds-btn {
            padding: 10px 22px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            border: 1.5px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-block;
        }
        .ds-btn-outline { border-color: rgba(255,255,255,0.35); color: #fff; }
        .ds-btn-outline:hover { background: rgba(255,255,255,0.1); border-color: #fff; }
        .ds-btn-solid { background: var(--ds-orange); color: #fff; }
        .ds-btn-solid:hover { background: var(--ds-orange-dark); transform: translateY(-1px); }
        .ds-btn-navy { background: var(--ds-navy); color: #fff; }
        .ds-btn-navy:hover { background: var(--ds-navy-light); }

        /* ===== HERO ===== */
        .ds-hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 100px 40px 40px;
            color: #fff;
            overflow: hidden;
        }
        .ds-hero-slide {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 1.8s ease-in-out;
            z-index: 0;
        }
        .ds-hero-slide.active { opacity: 1; }
        .ds-hero-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(90deg, rgba(11,29,58,0.94) 22%, rgba(11,29,58,0.55) 55%, rgba(11,29,58,0.25) 100%);
            z-index: 1;
        }
        .ds-hero > * { position: relative; z-index: 2; }
        }
        .ds-hero-inner { max-width: 620px; }
        .ds-hero h1 {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 18px;
        }
        .ds-hero h1 .accent { color: var(--ds-orange); }
        .ds-hero p {
            font-size: 1.05rem;
            color: rgba(255,255,255,0.75);
            margin-bottom: 34px;
            max-width: 520px;
        }

        .ds-search-card {
            background: #fff;
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            max-width: 950px;
        }
        .ds-search-grid {
            display: grid;
            grid-template-columns: 1.3fr 1.3fr 1fr 1fr 0.9fr;
            gap: 10px;
            align-items: center;
        }
        .ds-input-icon {
            position: relative;
            display: flex;
            align-items: center;
        }
        .ds-input-icon span {
            position: absolute;
            left: 14px;
            font-size: 1rem;
            opacity: 0.55;
            pointer-events: none;
        }
        .ds-input-icon input, .ds-input-icon select {
            width: 100%;
            padding: 12px 14px 12px 38px;
            border-radius: 10px;
            border: 1.5px solid #e6e9ef;
            font-size: 0.9rem;
            background: var(--ds-light-bg);
            font-family: 'Inter', sans-serif;
        }
        .ds-input-icon select { padding-left: 14px; }
        .ds-input-icon input:focus, .ds-input-icon select:focus {
            outline: none;
            border-color: var(--ds-orange);
            background: #fff;
        }
        .ds-search-btn {
            background: var(--ds-orange);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            padding: 12px;
            cursor: pointer;
            transition: 0.2s;
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
        }
        .ds-search-btn:hover { background: var(--ds-orange-dark); }

        /* ===== HERO STATS ===== */
        .ds-hero-stats {
            display: flex;
            gap: 16px;
            margin-top: 26px;
            flex-wrap: wrap;
            padding-bottom: 40px;
        }
        .ds-hero-stat-box {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 12px;
            padding: 14px 22px;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 150px;
        }
        .ds-hero-stat-icon {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: rgba(255,107,53,0.18);
            color: var(--ds-orange);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
        }
        .ds-hero-stat-box h2 { font-size: 1.4rem; font-weight: 800; margin: 0; color: #fff; }
        .ds-hero-stat-box p { margin: 0; font-size: 0.82rem; color: rgba(255,255,255,0.6); }

        /* ===== TRUST BADGES ===== */
        .ds-trust-row {
            background: rgba(255,255,255,0.04);
            border-top: 1px solid rgba(255,255,255,0.1);
            padding: 24px 40px;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 24px;
            margin-top: 20px;
        }
        .ds-trust-item {
            display: flex;
            align-items: center;
            gap: 14px;
            color: #fff;
            max-width: 260px;
        }
        .ds-trust-icon {
            width: 46px; height: 46px;
            min-width: 46px;
            border-radius: 50%;
            border: 1.5px solid rgba(255,255,255,0.3);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
        }
        .ds-trust-item h6 { margin: 0 0 3px; font-weight: 600; font-size: 0.92rem; }
        .ds-trust-item p { margin: 0; font-size: 0.8rem; color: rgba(255,255,255,0.6); }

        /* ===== SECTIONS ===== */
        .ds-section { max-width: 1200px; margin: 0 auto; padding: 60px 40px; }
        .ds-section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 28px;
        }
        .ds-section-header h2 {
            font-size: 1.7rem;
            font-weight: 700;
            margin: 0;
            color: var(--ds-navy);
        }
        .ds-section-header p { color: var(--ds-gray); margin: 4px 0 0; font-size: 0.95rem; }
        .ds-viewall {
            color: var(--ds-orange);
            font-weight: 600;
            font-size: 0.9rem;
            white-space: nowrap;
        }
        .ds-viewall:hover { color: var(--ds-orange-dark); }

        /* ===== BRAND PILLS ===== */
        .ds-brands { background: var(--ds-light-bg); padding: 40px; text-align: center; }
        .ds-brands h2 { font-size:1.4rem; font-weight:700; color:var(--ds-navy); margin-bottom:20px; }
        .ds-brand-pill {
            display: inline-block;
            padding: 8px 20px;
            margin: 5px;
            border-radius: 30px;
            background: #fff;
            color: var(--ds-navy);
            font-weight: 600;
            font-size: 0.88rem;
            border: 1.5px solid #e6e9ef;
            transition: 0.2s;
        }
        .ds-brand-pill:hover {
            background: var(--ds-navy);
            color: #fff;
            border-color: var(--ds-navy);
            transform: translateY(-2px);
        }

        /* ===== CAR CARDS ===== */
        .ds-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 22px; }
        .ds-car-card {
            background: #fff;
            border-radius: var(--ds-radius);
            overflow: hidden;
            border: 1px solid #edf0f4;
            transition: 0.25s ease;
            display: flex;
            flex-direction: column;
        }
        .ds-car-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 35px rgba(11,29,58,0.12);
        }
        .ds-car-card img, .ds-car-card .no-img {
            height: 175px;
            width: 100%;
            object-fit: cover;
            background: #dfe4ea;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8a94a3;
            font-size: 0.85rem;
        }
        .ds-car-body { padding: 16px 18px 18px; flex-grow: 1; display: flex; flex-direction: column; }
        .ds-car-title { font-weight: 700; font-size: 1rem; color: var(--ds-navy); margin-bottom: 4px; }
        .ds-car-price { color: var(--ds-orange); font-weight: 700; font-size: 1.05rem; margin-bottom: 12px; }
        .ds-car-btn {
            margin-top: auto;
            display: inline-block;
            text-align: center;
            padding: 9px;
            border-radius: 9px;
            font-weight: 600;
            font-size: 0.85rem;
            border: 1.5px solid var(--ds-navy);
            color: var(--ds-navy);
            transition: 0.2s;
        }
        .ds-car-btn:hover { background: var(--ds-navy); color: #fff; }
        .ds-car-btn.rent { border-color: #17a672; color: #17a672; }
        .ds-car-btn.rent:hover { background: #17a672; color: #fff; }

        /* ===== WHY CHOOSE US ===== */
        .ds-why { padding: 70px 40px; }
        .ds-why-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }
        .ds-why h2 {
            font-size: 1.9rem;
            font-weight: 700;
            color: var(--ds-navy);
            margin-bottom: 30px;
            letter-spacing: 0.5px;
        }
        .ds-accordion-item {
            border-bottom: 1px solid #e6e9ef;
            padding: 18px 0;
            cursor: pointer;
        }
        .ds-accordion-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 700;
            font-size: 0.92rem;
            letter-spacing: 0.5px;
            color: var(--ds-navy);
        }
        .ds-accordion-head span.arrow {
            transition: transform 0.25s;
            color: var(--ds-orange);
            font-size: 1.1rem;
        }
        .ds-accordion-item.open .arrow { transform: rotate(180deg); }
        .ds-accordion-body {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            color: var(--ds-gray);
            font-size: 0.92rem;
            line-height: 1.6;
        }
        .ds-accordion-item.open .ds-accordion-body {
            max-height: 200px;
            padding-top: 12px;
        }
        .ds-why-img { position: relative; }
        .ds-why-img img {
            width: 100%;
            border-radius: var(--ds-radius);
            display: block;
        }
        .ds-why-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #fff;
            border-radius: 12px;
            padding: 16px 22px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        .ds-why-badge .num { font-size: 1.7rem; font-weight: 800; color: var(--ds-navy); }
        .ds-why-badge .stars { color: var(--ds-gold); font-size: 0.85rem; margin: 4px 0; }
        .ds-why-badge .sub { font-size: 0.75rem; color: var(--ds-gray); }

        @media (max-width: 900px) {
            .ds-why-inner { grid-template-columns: 1fr; }
        }

        /* ===== TESTIMONIALS ===== */
        .ds-testimonials { background: var(--ds-light-bg); padding: 70px 40px; text-align: center; }
        .ds-testimonials h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--ds-navy);
            margin-bottom: 40px;
            letter-spacing: 0.5px;
        }
        .ds-testi-card {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            border-radius: var(--ds-radius);
            padding: 32px 36px;
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 26px;
            align-items: center;
            text-align: left;
            box-shadow: 0 10px 30px rgba(11,29,58,0.06);
        }
        .ds-testi-avatar {
            width: 66px; height: 66px;
            border-radius: 50%;
            object-fit: cover;
            background: #dfe4ea;
        }
        .ds-testi-content h4 { margin: 0 0 6px; font-size: 1rem; color: var(--ds-navy); font-weight: 700; }
        .ds-testi-content p { margin: 0 0 10px; font-style: italic; color: #43505f; font-size: 0.92rem; line-height: 1.55; }
        .ds-testi-stars { color: var(--ds-gold); font-size: 0.9rem; }
        .ds-testi-author { text-align: right; white-space: nowrap; }
        .ds-testi-author strong { display: block; color: var(--ds-navy); font-size: 0.92rem; }
        .ds-testi-author span { font-size: 0.8rem; color: var(--ds-gray); }
        .ds-testi-stats {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 34px;
            flex-wrap: wrap;
        }
        .ds-testi-stats div { font-size: 1.4rem; font-weight: 800; color: var(--ds-navy); }
        .ds-testi-stats div span { font-size: 0.85rem; font-weight: 500; color: var(--ds-gray); margin-left: 6px; }

        @media (max-width: 700px) {
            .ds-testi-card { grid-template-columns: 1fr; text-align: center; }
            .ds-testi-author { text-align: center; }
        }

        /* ===== NEWSLETTER ===== */
        .ds-newsletter { text-align: center; padding: 70px 40px; }
        .ds-newsletter h2 { font-size: 1.6rem; font-weight: 700; color: var(--ds-navy); }
        .ds-newsletter p { color: var(--ds-gray); margin-bottom: 28px; }
        .ds-newsletter-form { display: flex; justify-content: center; gap: 10px; max-width: 460px; margin: 0 auto; }
        .ds-newsletter-form input {
            flex: 1;
            padding: 12px 16px;
            border-radius: 30px;
            border: 1.5px solid #e6e9ef;
            font-family: 'Inter', sans-serif;
        }
        .ds-newsletter-form input:focus { outline: none; border-color: var(--ds-orange); }
        .ds-newsletter-form button {
            padding: 12px 26px;
            border-radius: 30px;
            border: none;
            background: var(--ds-orange);
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .ds-newsletter-form button:hover { background: var(--ds-orange-dark); }

        /* ===== FOOTER ===== */
        .ds-footer { background: var(--ds-navy); color: rgba(255,255,255,0.65); padding: 50px 40px 20px; }
        .ds-footer-inner { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 30px; }
        .ds-footer h5, .ds-footer h6 { color: #fff; margin-bottom: 14px; }
        .ds-footer p, .ds-footer a { font-size: 0.88rem; color: rgba(255,255,255,0.65); display: block; margin-bottom: 8px; }
        .ds-footer a:hover { color: #fff; }
        .ds-footer-bottom { text-align: center; font-size: 0.82rem; margin-top: 30px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); }

        @media (max-width: 900px) {
            .ds-search-grid { grid-template-columns: 1fr 1fr; }
            .ds-nav-center { display: none; }
        }
        @media (max-width: 768px) {
            .ds-hero { padding: 110px 20px 0; }
            .ds-hero h1 { font-size: 2.1rem; }
            .ds-section { padding: 45px 20px; }
            .ds-footer-inner { grid-template-columns: 1fr; }
            .ds-navbar { padding: 16px 20px; }
        }
        @media (max-width: 560px) {
            .ds-search-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- HERO (navbar overlaid on top) -->
<section class="ds-hero" id="dsHero">
    <div class="ds-hero-slide active" style="background-image:url('https://images.unsplash.com/photo-1494976388531-d1058494cdd8?auto=format&fit=crop&w=1800&q=80');"></div>
    <div class="ds-hero-slide" style="background-image:url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=1800&q=80');"></div>
    <div class="ds-hero-slide" style="background-image:url('https://images.unsplash.com/photo-1553440569-bcc63803a83d?auto=format&fit=crop&w=1800&q=80');"></div>
    <div class="ds-hero-slide" style="background-image:url('https://images.unsplash.com/photo-1552519507-da3b142c6e3d?auto=format&fit=crop&w=1800&q=80');"></div>
    <div class="ds-hero-overlay"></div>

    <nav class="ds-navbar"></nav>
        <a href="<?= BASE_URL ?>/" class="ds-logo">Drive<span>Sphere</span></a>
        <div class="ds-nav-center">
            <a href="<?= BASE_URL ?>/cars">Browse Cars</a>
            <a href="#">How It Works</a>
            <a href="#">About Us</a>
            <a href="#">Contact</a>
        </div>
        <div class="ds-nav-links">
            <?php if (isLoggedIn()): ?>
                <a href="<?= BASE_URL ?>/profile" class="ds-icon-circle" title="Profile">👤</a>
                <a href="<?= BASE_URL ?>/<?= getUserRole() ?>/dashboard" class="ds-btn ds-btn-outline">Dashboard</a>
                <a href="<?= BASE_URL ?>/logout" class="ds-btn ds-btn-solid">Logout</a>
            <?php else: ?>
                <span class="ds-icon-circle">👤</span>
                <a href="<?= BASE_URL ?>/login" class="ds-btn ds-btn-outline">Login</a>
                <a href="<?= BASE_URL ?>/register" class="ds-btn ds-btn-solid">Register</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="ds-hero-inner">
        <h1>Find Your Perfect <span class="accent">Ride,</span> Buy or Rent</h1>
        <p>Browse thousands of verified vehicles from trusted sellers and dealerships — all in one secure marketplace.</p>
    </div>

    <form method="GET" action="<?= BASE_URL ?>/cars" class="ds-search-card">
        <div class="ds-search-grid">
            <div class="ds-input-icon">
                <span>🚗</span>
                <input type="text" name="brand" placeholder="Brand (e.g. Toyota)">
            </div>
            <div class="ds-input-icon">
                <span>🚙</span>
                <input type="text" name="model" placeholder="Model">
            </div>
            <div class="ds-input-icon">
                <select name="listing_type">
                    <option value="">Sale or Rent</option>
                    <option value="sale">For Sale</option>
                    <option value="rent">For Rent</option>
                </select>
            </div>
            <div class="ds-input-icon">
                <span>📍</span>
                <input type="text" name="location" placeholder="Location">
            </div>
            <button type="submit" class="ds-search-btn">Search</button>
        </div>
    </form>

    <div class="ds-hero-stats">
        <div class="ds-hero-stat-box">
            <div class="ds-hero-stat-icon">🚗</div>
            <div>
                <h2><?= $stats['total_cars'] ?>+</h2>
                <p>Vehicles Listed</p>
            </div>
        </div>
        <div class="ds-hero-stat-box">
            <div class="ds-hero-stat-icon">👥</div>
            <div>
                <h2><?= $stats['total_sellers'] ?>+</h2>
                <p>Trusted Sellers</p>
            </div>
        </div>
        <div class="ds-hero-stat-box">
            <div class="ds-hero-stat-icon">✓</div>
            <div>
                <h2><?= $stats['total_sold'] ?>+</h2>
                <p>Vehicles Sold</p>
            </div>
        </div>
    </div>

    <div class="ds-trust-row">
        <div class="ds-trust-item">
            <div class="ds-trust-icon">🛡️</div>
            <div>
                <h6>Verified Sellers</h6>
                <p>All sellers are verified for your safety</p>
            </div>
        </div>
        <div class="ds-trust-item">
            <div class="ds-trust-icon">🏅</div>
            <div>
                <h6>Quality Vehicles</h6>
                <p>Inspected and verified vehicles only</p>
            </div>
        </div>
        <div class="ds-trust-item">
            <div class="ds-trust-icon">🔒</div>
            <div>
                <h6>Secure Payments</h6>
                <p>Safe and secure transactions</p>
            </div>
        </div>
        <div class="ds-trust-item">
            <div class="ds-trust-icon">🎧</div>
            <div>
                <h6>24/7 Support</h6>
                <p>We're here whenever you need us</p>
            </div>
        </div>
    </div>
</section>
<script>
    (function() {
        const slides = document.querySelectorAll('#dsHero .ds-hero-slide');
        let current = 0;
        setInterval(function() {
            slides[current].classList.remove('active');
            current = (current + 1) % slides.length;
            slides[current].classList.add('active');
        }, 5000);
    })();
</script>

<!-- BRANDS -->
<?php if (!empty($brands)): ?>
<section class="ds-brands">
    <h2>Browse by Brand</h2>
    <?php foreach ($brands as $brand): ?>
        <a href="<?= BASE_URL ?>/cars?brand=<?= urlencode($brand) ?>" class="ds-brand-pill"><?= htmlspecialchars($brand) ?></a>
    <?php endforeach; ?>
</section>
<?php endif; ?>
<!-- WHY CHOOSE US -->
<section class="ds-why">
    <div class="ds-why-inner">
        <div>
            <h2>WHY CHOOSE US?</h2>

            <div class="ds-accordion-item open" onclick="this.classList.toggle('open')">
                <div class="ds-accordion-head">
                    EXCEPTIONAL VEHICLE SELECTION
                    <span class="arrow">⌄</span>
                </div>
                <div class="ds-accordion-body">
                    Discover a diverse collection of vehicles, from everyday sedans to premium SUVs. Every listing is reviewed for quality, blending performance, comfort, and value to meet your needs.
                </div>
            </div>

            <div class="ds-accordion-item" onclick="this.classList.toggle('open')">
                <div class="ds-accordion-head">
                    TOP-NOTCH CUSTOMER SERVICE
                    <span class="arrow">⌄</span>
                </div>
                <div class="ds-accordion-body">
                    Our support team is on hand to help you through every step, from browsing to booking, so you always have someone to turn to.
                </div>
            </div>

            <div class="ds-accordion-item" onclick="this.classList.toggle('open')">
                <div class="ds-accordion-head">
                    FLEXIBLE BUYING & RENTAL OPTIONS
                    <span class="arrow">⌄</span>
                </div>
                <div class="ds-accordion-body">
                    Choose to buy outright, reserve with a booking fee, or rent by the day — whatever fits your plans and budget best.
                </div>
            </div>

            <div class="ds-accordion-item" onclick="this.classList.toggle('open')">
                <div class="ds-accordion-head">
                    TRUSTED SELLER VERIFICATION
                    <span class="arrow">⌄</span>
                </div>
                <div class="ds-accordion-body">
                    Every seller on DriveSphere goes through a verification process, so you can browse and transact with confidence.
                </div>
            </div>
        </div>

        <div class="ds-why-img">
            <img src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&w=900&q=80" alt="Happy customers driving">
            <div class="ds-why-badge">
                <div class="num">4.9</div>
                <div class="stars">★★★★★</div>
                <div class="sub">+2.3K Reviews</div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURED VEHICLES -->
<section class="ds-section">
    <div class="ds-section-header">
        <div>
            <h2>Featured Vehicles</h2>
            <p>Hand-picked listings worth a look</p>
        </div>
        <a href="<?= BASE_URL ?>/cars" class="ds-viewall">View All →</a>
    </div>

    <?php if (empty($featuredCars)): ?>
        <p style="color: var(--ds-gray);">No featured vehicles at the moment. Check out our latest arrivals below.</p>
    <?php else: ?>
        <div class="ds-grid">
            <?php foreach ($featuredCars as $car): ?>
                <div class="ds-car-card">
                    <?php if ($car['primary_image']): ?>
                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($car['primary_image']) ?>">
                    <?php else: ?>
                        <div class="no-img">No Image</div>
                    <?php endif; ?>
                    <div class="ds-car-body">
                        <div class="ds-car-title"><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?> (<?= $car['year'] ?>)</div>
                        <?php if ($car['price']): ?>
                            <div class="ds-car-price">KES <?= number_format($car['price']) ?></div>
                        <?php endif; ?>
                        <a href="<?= BASE_URL ?>/cars/view?id=<?= $car['id'] ?>" class="ds-car-btn">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<!-- LATEST ARRIVALS -->
<section class="ds-section" style="padding-top:0;">
    <div class="ds-section-header">
        <div>
            <h2>Latest Arrivals</h2>
            <p>Freshly listed vehicles</p>
        </div>
        <a href="<?= BASE_URL ?>/cars" class="ds-viewall">View All →</a>
    </div>

    <?php if (empty($latestCars)): ?>
        <p style="color: var(--ds-gray);">No vehicles listed yet.</p>
    <?php else: ?>
        <div class="ds-grid">
            <?php foreach ($latestCars as $car): ?>
                <div class="ds-car-card">
                    <?php if ($car['primary_image']): ?>
                        <img src="<?= BASE_URL ?>/<?= htmlspecialchars($car['primary_image']) ?>">
                    <?php else: ?>
                        <div class="no-img">No Image</div>
                    <?php endif; ?>
                    <div class="ds-car-body">
                        <div class="ds-car-title"><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?> (<?= $car['year'] ?>)</div>
                        <?php if ($car['price']): ?>
                            <div class="ds-car-price">KES <?= number_format($car['price']) ?></div>
                        <?php endif; ?>
                        <a href="<?= BASE_URL ?>/cars/view?id=<?= $car['id'] ?>" class="ds-car-btn">View</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<!-- POPULAR RENTALS -->
<?php if (!empty($popularRentals)): ?>
<section class="ds-section" style="padding-top:0;">
    <div class="ds-section-header">
        <div>
            <h2>Popular Rental Cars</h2>
            <p>Most viewed vehicles available for rent</p>
        </div>
        <a href="<?= BASE_URL ?>/cars?listing_type=rent" class="ds-viewall">View All →</a>
    </div>

    <div class="ds-grid">
        <?php foreach ($popularRentals as $car): ?>
            <div class="ds-car-card">
                <?php if ($car['primary_image']): ?>
                    <img src="<?= BASE_URL ?>/<?= htmlspecialchars($car['primary_image']) ?>">
                <?php else: ?>
                    <div class="no-img">No Image</div>
                <?php endif; ?>
                <div class="ds-car-body">
                    <div class="ds-car-title"><?= htmlspecialchars($car['brand'] . ' ' . $car['model']) ?> (<?= $car['year'] ?>)</div>
                    <?php if ($car['rental_price_per_day']): ?>
                        <div class="ds-car-price">KES <?= number_format($car['rental_price_per_day']) ?>/day</div>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>/cars/view?id=<?= $car['id'] ?>" class="ds-car-btn rent">View & Rent</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- TESTIMONIALS -->
<section class="ds-testimonials">
    <h2>OUR CUSTOMERS SAY</h2>

    <div class="ds-testi-card">
        <img class="ds-testi-avatar" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&q=80" alt="Amina W.">
        <div class="ds-testi-content">
            <h4>Excellent Rental Experience</h4>
            <p>"Booking a rental was so easy, and the whole process felt secure from start to finish. The seller was responsive and the car was exactly as described."</p>
            <div class="ds-testi-stars">★★★★★</div>
        </div>
        <div class="ds-testi-author">
            <strong>Amina W.</strong>
            <span>Nairobi, Kenya</span>
        </div>
    </div>

    <div class="ds-testi-stats">
        <div><?= $stats['total_sellers'] * 40 ?>+ <span>Customers</span></div>
        <div>4.9★ <span>3.5K Reviews</span></div>
    </div>
</section>

<!-- NEWSLETTER -->
<section class="ds-newsletter">
    <h2>Stay Updated</h2>
    <p>Subscribe for the latest listings and offers.</p>
    <form class="ds-newsletter-form" onsubmit="alert('Thanks for subscribing!'); return false;">
        <input type="email" placeholder="Your email address" required>
        <button type="submit">Subscribe</button>
    </form>
</section>

<!-- FOOTER -->
<footer class="ds-footer">
    <div class="ds-footer-inner">
        <div>
            <h5>DriveSphere</h5>
            <p>Your trusted online marketplace for buying and renting vehicles.</p>
        </div>
        <div>
            <h6>Quick Links</h6>
            <a href="<?= BASE_URL ?>/cars">Browse Cars</a>
            <a href="<?= BASE_URL ?>/register">Become a Seller</a>
            <a href="<?= BASE_URL ?>/login">Login</a>
        </div>
        <div>
            <h6>Contact</h6>
            <p>support@drivesphere.local</p>
            <p>Nairobi, Kenya</p>
        </div>
    </div>
    <div class="ds-footer-bottom">
        &copy; <?= date('Y') ?> DriveSphere. All rights reserved.
    </div>
</footer>

</body>
</html>