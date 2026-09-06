<?php

require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';

$errors = [];
$idea = ['title' => '', 'domain' => '', 'description' => '', 'technologies' => '', 'difficulty' => '', 'status' => 'Idea'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    [$idea, $errors] = validate_idea($_POST);
    if (!$errors) {
        $statement = $pdo->prepare('INSERT INTO project_ideas (title, domain, description, technologies, difficulty, status) VALUES (:title, :domain, :description, :technologies, :difficulty, :status)');
        $statement->execute($idea);
        flash('success', 'Idea added successfully.');
        redirect('index.php#ideas');
    }
}

page_header('Add an idea', 'ideas');
require __DIR__ . '/partials/idea-form.php';
page_footer();