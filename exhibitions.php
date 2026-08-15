<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/config/database.php';
$pageTitle = 'Exhibitions';
$items = $pdo->query("SELECT * FROM exhibitions ORDER BY start_date DESC")->fetchAll();
require_once __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow">Exhibitions</span><h1>Gallery Events</h1><p>Display exhibitions and public or private art viewing events.</p></div></section>
<section class="section"><div class="container"><div class="grid-3">
<?php foreach ($items as $item): ?>
    <article class="card article-card">
        <img src="<?= url($item['image']) ?>" alt="<?= e($item['title']) ?>">
        <div class="art-card-body">
            <div class="card-meta"><span><?= e($item['location']) ?></span><span><?= e(date('M d', strtotime($item['start_date']))) ?> - <?= e(date('M d, Y', strtotime($item['end_date']))) ?></span></div>
            <h3><?= e($item['title']) ?></h3>
            <p><?= e($item['description']) ?></p>
        </div>
    </article>
<?php endforeach; ?>
</div></div></section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
