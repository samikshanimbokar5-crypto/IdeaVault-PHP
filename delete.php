<?php

require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';
require __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}
verify_csrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    flash('error', 'Record not found.');
    redirect('index.php');
}
$statement = $pdo->prepare('DELETE FROM project_ideas WHERE id = :id');
$statement->execute(['id' => $id]);
flash($statement->rowCount() ? 'success' : 'error', $statement->rowCount() ? 'Idea deleted successfully.' : 'Record not found.');
redirect('index.php#ideas');