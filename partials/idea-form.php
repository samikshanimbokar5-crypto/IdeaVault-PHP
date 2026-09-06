<?php $editing = isset($idea['id']); ?>
<section class="form-page">
    <a class="back-link" href="<?= $editing ? 'details.php?id=' . (int) $idea['id'] : 'index.php#ideas' ?>">← Back to ideas</a>
    <div class="form-intro"><p class="eyebrow"><?= $editing ? 'Refine the direction' : 'Add to the vault' ?></p><h1><?= $editing ? 'Edit project idea' : 'Capture a new idea' ?></h1><p>Keep the useful details together so the next step is always clear.</p></div>
    <?php if ($errors): ?><div class="alert alert-error">Please correct the highlighted fields.</div><?php endif; ?>
    <form class="idea-form" method="post" novalidate>
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <div class="form-grid">
            <label>Project title <input required maxlength="150" name="title" value="<?= e($idea['title']) ?>" placeholder="e.g. Campus Skill Exchange"><small><?= e($errors['title'] ?? '') ?></small></label>
            <label>Domain <input required maxlength="80" name="domain" value="<?= e($idea['domain']) ?>" placeholder="e.g. EdTech"><small><?= e($errors['domain'] ?? '') ?></small></label>
            <label class="full">Description <textarea required name="description" rows="6" placeholder="What problem does this project solve?"><?= e($idea['description']) ?></textarea><small><?= e($errors['description'] ?? '') ?></small></label>
            <label>Technologies <input required maxlength="255" name="technologies" value="<?= e($idea['technologies']) ?>" placeholder="PHP, MySQL, JavaScript"><small><?= e($errors['technologies'] ?? '') ?></small></label>
            <label>Difficulty <select required name="difficulty"><option value="">Choose difficulty</option><?php foreach (['Beginner', 'Intermediate', 'Advanced'] as $option): ?><option <?= $idea['difficulty'] === $option ? 'selected' : '' ?>><?= $option ?></option><?php endforeach; ?></select><small><?= e($errors['difficulty'] ?? '') ?></small></label>
            <label>Status <select required name="status"><?php foreach (['Idea', 'Planning', 'In progress', 'Completed'] as $option): ?><option <?= $idea['status'] === $option ? 'selected' : '' ?>><?= $option ?></option><?php endforeach; ?></select><small><?= e($errors['status'] ?? '') ?></small></label>
        </div>
        <div class="form-actions"><a class="button button-quiet" href="<?= $editing ? 'details.php?id=' . (int) $idea['id'] : 'index.php' ?>">Cancel</a><button class="button" type="submit"><?= $editing ? 'Save changes' : 'Save idea' ?> <span>↗</span></button></div>
    </form>
</section>