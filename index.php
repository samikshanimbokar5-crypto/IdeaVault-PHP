<?php

require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';
require __DIR__ . '/auth.php';

$search = trim((string) ($_GET['search'] ?? ''));
$domain = trim((string) ($_GET['domain'] ?? ''));
$difficulty = trim((string) ($_GET['difficulty'] ?? ''));
$status = trim((string) ($_GET['status'] ?? ''));

$where = [];
$params = [];
if ($search !== '') {
    $where[] = '(title LIKE :search OR domain LIKE :search OR technologies LIKE :search OR description LIKE :search)';
    $params['search'] = '%' . $search . '%';
}
foreach (['domain', 'difficulty', 'status'] as $filter) {
    if ($$filter !== '') {
        $where[] = $filter . ' = :' . $filter;
        $params[$filter] = $$filter;
    }
}

$query = 'SELECT * FROM project_ideas' . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY created_at DESC, id DESC';
$statement = $pdo->prepare($query);
$statement->execute($params);
$ideas = $statement->fetchAll();

$stats = $pdo->query("SELECT COUNT(*) AS total, SUM(status = 'Completed') AS completed, SUM(status = 'In progress') AS active, COUNT(DISTINCT domain) AS domains FROM project_ideas")->fetch();
$domains = $pdo->query('SELECT DISTINCT domain FROM project_ideas ORDER BY domain')->fetchAll(PDO::FETCH_COLUMN);

page_header('Dashboard');
?>
<section class="hero">
    <div>
        <p class="eyebrow">Student project repository</p>
        <h1>Turn a spark into a <em>buildable</em> plan.</h1>
        <p class="hero-copy">Capture promising ideas, keep the details close, and find the next project worth your time.</p>
        <a class="button" href="#new-idea">Add an idea <span>↗</span></a>
    </div>
    <div class="hero-note"><span class="note-pin">✦</span><strong>Small steps compound.</strong><span>Every good project starts as a note worth keeping.</span></div>
</section>

<section class="stats" aria-label="Repository statistics">
    <div><span class="stat-label">Ideas saved</span><strong><?= (int) $stats['total'] ?></strong></div>
    <div><span class="stat-label">In progress</span><strong><?= (int) $stats['active'] ?></strong></div>
    <div><span class="stat-label">Completed</span><strong><?= (int) $stats['completed'] ?></strong></div>
    <div><span class="stat-label">Domains explored</span><strong><?= (int) $stats['domains'] ?></strong></div>
</section>

<section class="workspace" id="ideas">
    <div class="section-heading"><div><p class="eyebrow">Your collection</p><h2>Project ideas</h2></div><span class="result-count"><?= count($ideas) ?> results</span></div>
    <form class="filter-bar" method="get">
        <label class="search-field"><span>⌕</span><input type="search" name="search" value="<?= e($search) ?>" placeholder="Search title, domain, tech..."></label>
        <select name="domain"><option value="">All domains</option><?php foreach ($domains as $option): ?><option value="<?= e($option) ?>" <?= $domain === $option ? 'selected' : '' ?>><?= e($option) ?></option><?php endforeach; ?></select>
        <select name="difficulty"><option value="">Any difficulty</option><?php foreach (['Beginner', 'Intermediate', 'Advanced'] as $option): ?><option <?= $difficulty === $option ? 'selected' : '' ?>><?= $option ?></option><?php endforeach; ?></select>
        <select name="status"><option value="">Any status</option><?php foreach (['Idea', 'Planning', 'In progress', 'Completed'] as $option): ?><option <?= $status === $option ? 'selected' : '' ?>><?= $option ?></option><?php endforeach; ?></select>
        <button class="button button-dark" type="submit">Filter</button>
        <?php if ($search || $domain || $difficulty || $status): ?><a class="clear-link" href="index.php">Clear</a><?php endif; ?>
    </form>
    <div class="idea-grid">
        <?php foreach ($ideas as $idea): ?>
            <article class="idea-card">
                <div class="card-top"><span class="tag"><?= e($idea['domain']) ?></span><span class="status status-<?= strtolower(str_replace(' ', '-', $idea['status'])) ?>"><?= e($idea['status']) ?></span></div>
                <h3><?= e($idea['title']) ?></h3>
                <p><?= e($idea['description']) ?></p>
                <div class="card-meta"><span><?= e($idea['difficulty']) ?></span><span><?= e($idea['technologies']) ?></span></div>
                <a class="text-link" href="details.php?id=<?= (int) $idea['id'] ?>">View details <span>→</span></a>
            </article>
        <?php endforeach; ?>
        <?php if (!$ideas): ?><div class="empty-state"><strong>No ideas match these filters.</strong><span>Try broadening your search or add a fresh idea.</span></div><?php endif; ?>
    </div>
</section>

<section class="new-idea" id="new-idea">
    <div><p class="eyebrow">Make room for the next one</p><h2>Have an idea brewing?</h2><p>Give it a title and a little shape. You can refine it later.</p></div>
    <a class="button button-light" href="add.php">Create project idea <span>↗</span></a>
</section>
<?php page_footer(); ?>