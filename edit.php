<?php

require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';
require __DIR__ . '/auth.php';
require_login();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    flash('error', 'Record not found.');
    redirect('index.php');
}
$statement = $pdo->prepare('SELECT * FROM project_ideas WHERE id = :id');
$statement->execute(['id' => $id]);
$idea = $statement->fetch();
if (!$idea) {
    flash('error', 'Record not found.');
    redirect('index.php');
}
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    [$idea, $errors] = validate_idea($_POST);
    $idea['id'] = $id;
    if (!$errors) {
        $update = $pdo->prepare('UPDATE project_ideas SET title = :title, domain = :domain, description = :description, technologies = :technologies, difficulty = :difficulty, status = :status WHERE id = :id');
        $update->execute($idea);
        flash('success', 'Idea updated successfully.');
        redirect('details.php?id=' . $id);
    }
}

page_header('Edit idea', 'ideas');
require __DIR__ . '/partials/idea-form.php';
page_footer();