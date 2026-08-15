<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/config/database.php';
$pageTitle = 'Artists';
$artists = $pdo->query("SELECT artists.*, COUNT(artworks.id) AS artwork_count FROM artists LEFT JOIN artworks ON artists.id = artworks.artist_id GROUP BY artists.id ORDER BY artists.name")->fetchAll();
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow">Artists</span><h1>Creative Minds</h1><p>Manage and display artist profiles and artwork contributions.</p></div></section>
<section class="section"><div class="container"><div class="grid-4">
<?php foreach ($artists as $artist): ?>
    <article class="artist-card">
        <img src="<?= url($artist['image']) ?>" alt="<?= e($artist['name']) ?>">
        <h3><?= e($artist['name']) ?></h3>
        <p><?= e($artist['location']) ?></p>
        <p><?= e($artist['bio']) ?></p>
        <span class="badge"><?= (int)$artist['artwork_count'] ?> Artworks</span>
    </article>
<?php endforeach; ?>
</div></div></section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
