<?php /** @var string $title */ /** @var string $active */ /** @var array|null $flash */ ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?> | IdeaVault</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="topbar">
    <a class="brand" href="index.php"><span class="brand-mark">IV</span><span>IdeaVault</span></a>
    <nav aria-label="Primary navigation">
        <a class="<?= $active === 'dashboard' ? 'active' : '' ?>" href="index.php">Dashboard</a>
        <a class="<?= $active === 'ideas' ? 'active' : '' ?>" href="index.php#ideas">Browse ideas</a>
        <a class="button button-small" href="index.php#new-idea">+ New idea</a>
    </nav>
</header>
<main class="shell">
<?php if ($flash): ?>
    <div class="alert alert-<?= e($flash['type']) ?>" role="status"><?= e($flash['message']) ?></div>
<?php endif; ?>