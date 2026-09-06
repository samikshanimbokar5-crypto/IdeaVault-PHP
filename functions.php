<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect(string $location): never
{
    header('Location: ' . $location);
    exit;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        http_response_code(419);
        exit('The form expired. Please try again.');
    }
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    $message = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $message;
}

function validate_idea(array $input): array
{
    $idea = [
        'title' => trim((string) ($input['title'] ?? '')),
        'domain' => trim((string) ($input['domain'] ?? '')),
        'description' => trim((string) ($input['description'] ?? '')),
        'technologies' => trim((string) ($input['technologies'] ?? '')),
        'difficulty' => trim((string) ($input['difficulty'] ?? '')),
        'status' => trim((string) ($input['status'] ?? '')),
    ];
    $errors = [];

    foreach (['title', 'domain', 'description', 'technologies', 'difficulty', 'status'] as $field) {
        if ($idea[$field] === '') {
            $errors[$field] = 'This field is required.';
        }
    }

    if (mb_strlen($idea['title']) > 150) {
        $errors['title'] = 'Title must be 150 characters or fewer.';
    }
    if (mb_strlen($idea['domain']) > 80) {
        $errors['domain'] = 'Domain must be 80 characters or fewer.';
    }
    if (mb_strlen($idea['technologies']) > 255) {
        $errors['technologies'] = 'Technologies must be 255 characters or fewer.';
    }
    if (!in_array($idea['difficulty'], ['Beginner', 'Intermediate', 'Advanced'], true)) {
        $errors['difficulty'] = 'Select a valid difficulty.';
    }
    if (!in_array($idea['status'], ['Idea', 'Planning', 'In progress', 'Completed'], true)) {
        $errors['status'] = 'Select a valid status.';
    }

    return [$idea, $errors];
}

function page_header(string $title, string $active = 'dashboard'): void
{
    $flash = get_flash();
    require __DIR__ . '/partials/header.php';
}

function page_footer(): void
{
    require __DIR__ . '/partials/footer.php';
}