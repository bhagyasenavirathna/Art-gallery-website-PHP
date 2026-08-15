<?php
require_once __DIR__ . '/includes/helpers.php';

$pageTitle = 'About Us';
$bodyClass = 'about-page';
$extraHead = <<<'CSS'
.about-hero {
    position: relative;
    isolation: isolate;
    min-height: 560px;
    display: grid;
    align-items: center;
    padding: 96px 0 82px;
    color: #fff;
    background:
        linear-gradient(90deg, rgba(0,0,0,.78) 0%, rgba(0,0,0,.58) 48%, rgba(0,0,0,.24) 100%),
        url('assets/images/hero-bg.jpg') center/cover no-repeat;
    overflow: hidden;
}
.about-hero::after {
    content: '';
    position: absolute;
    inset: auto -120px -180px auto;
    width: 520px;
    height: 520px;
    border-radius: 50%;
    background: rgba(255,255,255,.10);
    z-index: -1;
}
.about-hero-grid {
    display: grid;
    grid-template-columns: minmax(0, 760px) minmax(280px, 420px);
    gap: 44px;
    align-items: center;
}
.about-hero h1 {
    margin: 10px 0 18px;
    font-size: clamp(2.4rem, 5.6vw, 5rem);
    line-height: .96;
    letter-spacing: -.07em;
}
.about-hero p {
    max-width: 640px;
    color: rgba(255,255,255,.80);
    line-height: 1.8;
    font-size: 1rem;
}
.about-hero-card {
    border: 1px solid rgba(255,255,255,.22);
    border-radius: 34px;
    padding: 24px;
    background: rgba(255,255,255,.12);
    backdrop-filter: blur(18px);
    box-shadow: 0 30px 90px rgba(0,0,0,.24);
}
.about-hero-card img {
    width: 100%;
    height: 320px;
    object-fit: cover;
    display: block;
    border-radius: 26px;
    margin-bottom: 18px;
}
.about-hero-card strong {
    display: block;
    font-size: 1.25rem;
    letter-spacing: -.04em;
}
.about-hero-card span {
    display: block;
    margin-top: 6px;
    color: rgba(255,255,255,.70);
    line-height: 1.6;
}
.about-story {
    display: grid;
    grid-template-columns: .95fr 1.05fr;
    gap: 52px;
    align-items: center;
}
.about-image-stack {
    position: relative;
    min-height: 520px;
}
.about-image-stack img {
    position: absolute;
    width: 72%;
    height: 370px;
    object-fit: cover;
    border-radius: 34px;
    border: 1px solid var(--line);
    box-shadow: var(--shadow);
}
.about-image-stack img:first-child { left: 0; top: 0; }
.about-image-stack img:last-child { right: 0; bottom: 0; }
.about-story-content h2,
.about-section-title h2 {
    margin: 10px 0 18px;
    font-size: clamp(2rem, 4vw, 3.65rem);
    line-height: .98;
    letter-spacing: -.07em;
}
.about-story-content p,
.about-section-title p,
.about-value-card p,
.about-process-card p {
    color: var(--muted);
    line-height: 1.78;
}
.about-highlight {
    margin-top: 26px;
    border: 1px solid var(--line);
    border-radius: 26px;
    padding: 22px;
    background: #fff;
    box-shadow: var(--shadow-soft);
}
.about-highlight strong {
    display: block;
    margin-bottom: 8px;
    font-size: 1.05rem;
}
.about-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}
.about-stat {
    padding: 24px;
    border: 1px solid var(--line);
    border-radius: 26px;
    background: #fff;
    box-shadow: 0 10px 30px rgba(0,0,0,.04);
}
.about-stat strong {
    display: block;
    font-size: 2rem;
    letter-spacing: -.06em;
}
.about-stat span {
    display: block;
    margin-top: 6px;
    color: var(--muted);
    font-size: .9rem;
}
.about-section-title {
    max-width: 720px;
    margin: 0 auto 38px;
    text-align: center;
}
.about-values,
.about-process {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 22px;
}
.about-value-card,
.about-process-card {
    position: relative;
    overflow: hidden;
    border: 1px solid var(--line);
    border-radius: 30px;
    padding: 28px;
    background: #fff;
    box-shadow: 0 10px 35px rgba(0,0,0,.04);
}
.about-value-card::after,
.about-process-card::after {
    content: '';
    position: absolute;
    width: 150px;
    height: 150px;
    right: -70px;
    top: -70px;
    border-radius: 50%;
    background: var(--bg-soft);
}
.about-icon {
    width: 52px;
    height: 52px;
    display: grid;
    place-items: center;
    border-radius: 18px;
    color: #fff;
    background: #050505;
    margin-bottom: 22px;
    position: relative;
    z-index: 1;
}
.about-value-card h3,
.about-process-card h3 {
    margin: 0 0 10px;
    letter-spacing: -.04em;
    position: relative;
    z-index: 1;
}
.about-value-card p,
.about-process-card p { margin-bottom: 0; position: relative; z-index: 1; }
.about-team-band {
    border-radius: var(--radius-xl);
    background: #080808;
    color: #fff;
    padding: 54px;
    display: grid;
    grid-template-columns: 1fr .9fr;
    gap: 34px;
    align-items: center;
    overflow: hidden;
    position: relative;
}
.about-team-band::after {
    content: '';
    position: absolute;
    width: 420px;
    height: 420px;
    right: -170px;
    bottom: -210px;
    border-radius: 50%;
    background: rgba(255,255,255,.10);
}
.about-team-band h2 {
    margin: 0 0 14px;
    font-size: clamp(2rem, 4vw, 3.6rem);
    line-height: 1;
    letter-spacing: -.07em;
}
.about-team-band p {
    color: rgba(255,255,255,.72);
    line-height: 1.75;
    max-width: 660px;
}
.about-team-list {
    display: grid;
    gap: 12px;
    position: relative;
    z-index: 1;
}
.about-team-list div {
    border: 1px solid rgba(255,255,255,.18);
    border-radius: 20px;
    padding: 16px 18px;
    background: rgba(255,255,255,.08);
    backdrop-filter: blur(12px);
}
.about-team-list strong { display: block; }
.about-team-list span { color: rgba(255,255,255,.64); font-size: .9rem; }
.about-reveal { opacity: 0; transform: translateY(18px); transition: opacity .65s ease, transform .65s ease; }
.about-reveal.is-visible { opacity: 1; transform: translateY(0); }
@media (max-width: 1060px) {
    .about-hero-grid,
    .about-story,
    .about-team-band { grid-template-columns: 1fr; }
    .about-stats { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 820px) {
    .about-hero { min-height: auto; padding: 72px 0 62px; }
    .about-hero-card img { height: 250px; }
    .about-image-stack { min-height: auto; display: grid; gap: 16px; }
    .about-image-stack img { position: static; width: 100%; height: 270px; }
    .about-values,
    .about-process,
    .about-stats { grid-template-columns: 1fr; }
    .about-team-band { padding: 34px; border-radius: 30px; }
}
CSS;

require_once __DIR__ . '/includes/header.php';
?>
<section class="about-hero">
    <div class="container about-hero-grid">
        <div class="about-reveal">
            <span class="eyebrow">About Arts Gallery</span>
            <h1>Connecting artists, collectors, and meaningful visual stories.</h1>
            <p>Arts Gallery is a premium online art gallery created to make artwork discovery, custom artwork ordering, purchasing, and order tracking simple, elegant, and reliable for every customer.</p>
            <div class="hero-actions">
                <a class="btn btn-dark" href="<?= url('gallery.php') ?>">Explore Collection <?= icon_svg('arrow') ?></a>
                <a class="btn btn-light" href="<?= url('custom-order.php') ?>">Request Custom Art</a>
            </div>
        </div>
        <div class="about-hero-card about-reveal">
            <img src="<?= asset('images/golden-memory.jpg') ?>" alt="Premium artwork display">
            <strong>Curated with care</strong>
            <span>Every artwork is presented with clear details, artist information, pricing, availability, and a smooth ordering flow.</span>
        </div>
    </div>
</section>

<section class="section">
    <div class="container about-story">
        <div class="about-image-stack about-reveal">
            <img src="<?= asset('images/artist-amara.jpg') ?>" alt="Artist creating artwork">
            <img src="<?= asset('images/exhibition-01.jpg') ?>" alt="Gallery exhibition artwork">
        </div>
        <div class="about-story-content about-reveal">
            <span class="eyebrow">Our Story</span>
            <h2>A modern digital gallery for real art experiences.</h2>
            <p>We built Arts Gallery to bring artworks, artist profiles, exhibitions, articles, custom orders, shopping cart, checkout, and customer dashboard features into one clean platform. The system supports customers who want to discover art online and artists who want their work to reach more people.</p>
            <p>Our design focuses on premium presentation, simple navigation, mobile responsiveness, secure account access, and a professional buying experience from first visit to final order tracking.</p>
            <div class="about-highlight">
                <strong>What makes us different?</strong>
                <span>We combine curated artwork presentation with practical e-commerce features such as cart management, inquiry handling, custom artwork requests, and order status tracking.</span>
            </div>
        </div>
    </div>
</section>

<section class="section section-soft">
    <div class="container">
        <div class="about-stats">
            <div class="about-stat about-reveal"><strong data-count="120">0</strong><span>Curated artworks</span></div>
            <div class="about-stat about-reveal"><strong data-count="25">0</strong><span>Artist profiles</span></div>
            <div class="about-stat about-reveal"><strong data-count="15">0</strong><span>Exhibitions and articles</span></div>
            <div class="about-stat about-reveal"><strong data-count="95">0</strong><span>Responsive experience</span></div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="about-section-title about-reveal">
            <span class="eyebrow">Our Values</span>
            <h2>Designed around trust, beauty, and usability.</h2>
            <p>Arts Gallery keeps the experience professional for customers while giving the admin team a clear workflow to manage artworks, artists, orders, reports, and messages.</p>
        </div>
        <div class="about-values">
            <article class="about-value-card about-reveal">
                <span class="about-icon"><?= icon_svg('star') ?></span>
                <h3>Premium Curation</h3>
                <p>Artwork collections are displayed with high-quality visuals, clean categories, artist details, and availability information.</p>
            </article>
            <article class="about-value-card about-reveal">
                <span class="about-icon"><?= icon_svg('shield') ?></span>
                <h3>Reliable Experience</h3>
                <p>Login, cart, checkout, contact messages, and dashboard features are arranged to support a practical online gallery workflow.</p>
            </article>
            <article class="about-value-card about-reveal">
                <span class="about-icon"><?= icon_svg('box') ?></span>
                <h3>Customer Focus</h3>
                <p>Customers can browse, add to cart, place orders, request custom artwork, and follow order updates from one platform.</p>
            </article>
        </div>
    </div>
</section>

<section class="section section-soft">
    <div class="container">
        <div class="about-section-title about-reveal">
            <span class="eyebrow">How It Works</span>
            <h2>A simple journey from discovery to delivery.</h2>
        </div>
        <div class="about-process">
            <article class="about-process-card about-reveal">
                <span class="badge">Step 01</span>
                <h3>Explore Artworks</h3>
                <p>Customers browse artworks, artists, exhibitions, and articles to understand the style and story behind each piece.</p>
            </article>
            <article class="about-process-card about-reveal">
                <span class="badge">Step 02</span>
                <h3>Order or Customize</h3>
                <p>They can add available artworks to cart or submit a custom order request with reference details.</p>
            </article>
            <article class="about-process-card about-reveal">
                <span class="badge">Step 03</span>
                <h3>Track Progress</h3>
                <p>After checkout, customers can view order history and track status updates through their dashboard.</p>
            </article>
        </div>
    </div>
</section>

<section class="section">
    <div class="container about-team-band about-reveal">
        <div>
            <span class="eyebrow">Gallery Team</span>
            <h2>Built for artists, managed for customers.</h2>
            <p>The platform supports admin-side management for artworks, artists, customers, orders, reports, and messages, helping the gallery operate in a clean and organized way.</p>
            <div class="hero-actions">
                <a class="btn" href="<?= url('contact.php') ?>">Contact Gallery <?= icon_svg('arrow') ?></a>
            </div>
        </div>
        <div class="about-team-list">
            <div><strong>Creative Management</strong><span>Artwork and artist profile handling</span></div>
            <div><strong>Customer Support</strong><span>Inquiry and message management</span></div>
            <div><strong>Order Workflow</strong><span>Checkout, status updates, and reports</span></div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const revealItems = document.querySelectorAll('.about-reveal');
    const counters = document.querySelectorAll('[data-count]');

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.16 });

    revealItems.forEach((item) => revealObserver.observe(item));

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            const el = entry.target;
            const target = Number(el.dataset.count || 0);
            let current = 0;
            const step = Math.max(1, Math.ceil(target / 42));
            const timer = window.setInterval(() => {
                current += step;
                if (current >= target) {
                    current = target;
                    window.clearInterval(timer);
                }
                el.textContent = target === 95 ? current + '%' : current + '+';
            }, 28);
            counterObserver.unobserve(el);
        });
    }, { threshold: 0.7 });

    counters.forEach((counter) => counterObserver.observe(counter));

    const toggle = document.querySelector('[data-nav-toggle]');
    const menu = document.querySelector('[data-nav-menu]');
    if (toggle && menu) {
        toggle.addEventListener('click', () => {
            toggle.setAttribute('aria-expanded', menu.classList.contains('is-open') ? 'false' : 'true');
        });
    }
});
</script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>