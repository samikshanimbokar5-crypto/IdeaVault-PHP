<?php

require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}
verify_csrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    flash('error', 'Record not found.');
    redirect('index.php');
}
[$idea, $errors] = validate_idea($_POST);
if ($errors) {
    flash('error', 'Invalid input. Please check all fields.');
    redirect('edit.php?id=' . $id);
}
$idea['id'] = $id;
$statement = $pdo->prepare('UPDATE project_ideas SET title = :title, domain = :domain, description = :description, technologies = :technologies, difficulty = :difficulty, status = :status WHERE id = :id');
$statement->execute($idea);
flash('success', 'Idea updated successfully.');
redirect('details.php?id=' . $id);