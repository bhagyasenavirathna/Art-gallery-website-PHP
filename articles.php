<?php
require_once __DIR__ . '/includes/helpers.php';
$pageTitle = 'Articles';
$articles = [
    ['img' => 'article-review.jpg', 'tag' => 'Art Review', 'title' => 'Exploring Classic Techniques in Modern Art', 'body' => 'Traditional line, colour, and texture methods can make modern artworks more expressive and collectable.'],
    ['img' => 'article-therapy.jpg', 'tag' => 'Art Therapy', 'title' => 'Exploring Art Therapy', 'body' => 'Customers often choose calm colours and balanced compositions to improve the feeling of living spaces.'],
    ['img' => 'article-borders.jpg', 'tag' => 'Digital Gallery', 'title' => 'Art Beyond Borders', 'body' => 'Online galleries allow customers to browse, inquire, and order artworks more efficiently than manual systems.'],
];
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow">Articles</span><h1>Latest Articles</h1><p>Educational content for customers, collectors, and administrators.</p></div></section>
<section class="section"><div class="container"><div class="grid-3">
<?php foreach ($articles as $article): ?>
    <article class="card article-card"><img src="<?= asset('images/' . $article['img']) ?>" alt="<?= e($article['title']) ?>"><div class="art-card-body"><div class="card-meta"><?= e($article['tag']) ?></div><h3><?= e($article['title']) ?></h3><p><?= e($article['body']) ?></p></div></article>
<?php endforeach; ?>
</div></div></section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
