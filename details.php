<?php

require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';
require __DIR__ . '/auth.php';
require_login();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$statement = $pdo->prepare('SELECT * FROM project_ideas WHERE id = :id');
$statement->execute(['id' => $id ?: 0]);
$idea = $statement->fetch();
if (!$idea) {
    flash('error', 'Record not found.');
    redirect('index.php');
}

page_header('Idea details', 'ideas');
?>
<section class="detail-page">
    <a class="back-link" href="index.php#ideas">← Back to ideas</a>
    <div class="detail-header"><div><span class="tag"><?= e($idea['domain']) ?></span><h1><?= e($idea['title']) ?></h1><p class="detail-date">Saved <?= e(date('M j, Y', strtotime($idea['created_at']))) ?></p></div><span class="status status-<?= strtolower(str_replace(' ', '-', $idea['status'])) ?>"><?= e($idea['status']) ?></span></div>
    <div class="detail-layout"><div class="detail-description"><p class="eyebrow">The idea</p><p><?= nl2br(e($idea['description'])) ?></p></div><dl class="detail-facts"><div><dt>Domain</dt><dd><?= e($idea['domain']) ?></dd></div><div><dt>Difficulty</dt><dd><?= e($idea['difficulty']) ?></dd></div><div><dt>Technologies</dt><dd><?= e($idea['technologies']) ?></dd></div></dl></div>
    <div class="detail-actions"><a class="button" href="edit.php?id=<?= (int) $idea['id'] ?>">Edit idea <span>↗</span></a><form method="post" action="delete.php" onsubmit="return confirm('Delete this idea permanently?');"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int) $idea['id'] ?>"><button class="button button-danger" type="submit">Delete</button></form></div>
</section>
<?php page_footer(); ?>