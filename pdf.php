<?php

declare(strict_types=1);

require __DIR__ . '/config.php';
require __DIR__ . '/functions.php';
require __DIR__ . '/auth.php';
require_login();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id === null) {
    flash('error', 'A valid idea ID is required.');
    redirect('index.php');
}

$statement = $pdo->prepare('SELECT * FROM project_ideas WHERE id = :id');
$statement->execute(['id' => $id]);
$idea = $statement->fetch();
if (!$idea) {
    flash('error', 'Record not found.');
    redirect('index.php');
}

function pdf_escape(string $text): string
{
    return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
}

function pdf_wrap_text(string $text, int $maxLength = 80): array
{
    $text = str_replace("\r\n", "\n", $text);
    $text = str_replace("\r", "\n", $text);
    $lines = explode("\n", $text);
    $wrapped = [];

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '') {
            $wrapped[] = '';
            continue;
        }

        $words = preg_split('/\s+/', $line);
        if ($words === false) {
            $wrapped[] = $line;
            continue;
        }

        $current = '';
        foreach ($words as $word) {
            if ($current === '') {
                $current = $word;
                continue;
            }
            if (strlen($current . ' ' . $word) > $maxLength) {
                $wrapped[] = $current;
                $current = $word;
            } else {
                $current .= ' ' . $word;
            }
        }

        if ($current !== '') {
            $wrapped[] = $current;
        }
    }

    return $wrapped;
}

function exact_insert_query_for_idea(array $idea): string
{
    $quoted = [
        'title' => "'" . str_replace("'", "''", $idea['title']) . "'",
        'domain' => "'" . str_replace("'", "''", $idea['domain']) . "'",
        'description' => "'" . str_replace("'", "''", $idea['description']) . "'",
        'technologies' => "'" . str_replace("'", "''", $idea['technologies']) . "'",
        'difficulty' => "'" . str_replace("'", "''", $idea['difficulty']) . "'",
        'status' => "'" . str_replace("'", "''", $idea['status']) . "'",
    ];

    return sprintf(
        "INSERT INTO project_ideas (title, domain, description, technologies, difficulty, status) VALUES (%s, %s, %s, %s, %s, %s);",
        $quoted['title'],
        $quoted['domain'],
        $quoted['description'],
        $quoted['technologies'],
        $quoted['difficulty'],
        $quoted['status']
    );
}

function render_pdf_content(array $idea): string
{
    $lineHeight = 22;
    $y = 792;
    $x = 50;
    $stream = '';
    $query = exact_insert_query_for_idea($idea);

    $stream .= "BT\n/F1 18 Tf\n$x {$y} Td\n(" . pdf_escape('IdeaVault Project Idea') . ") Tj\nET\n";
    $y -= 34;

    $stream .= "BT\n/F1 12 Tf\n$x {$y} Td\n(" . pdf_escape('Title: ' . $idea['title']) . ") Tj\nET\n";
    $y -= $lineHeight;

    $stream .= "BT\n/F1 12 Tf\n$x {$y} Td\n(" . pdf_escape('Domain: ' . $idea['domain']) . ") Tj\nET\n";
    $y -= $lineHeight;

    $stream .= "BT\n/F1 12 Tf\n$x {$y} Td\n(" . pdf_escape('Difficulty: ' . $idea['difficulty']) . ") Tj\nET\n";
    $y -= $lineHeight;

    $stream .= "BT\n/F1 12 Tf\n$x {$y} Td\n(" . pdf_escape('Status: ' . $idea['status']) . ") Tj\nET\n";
    $y -= $lineHeight;

    $stream .= "BT\n/F1 12 Tf\n$x {$y} Td\n(" . pdf_escape('Technologies: ' . $idea['technologies']) . ") Tj\nET\n";
    $y -= $lineHeight + 8;

    $stream .= "BT\n/F1 13 Tf\n$x {$y} Td\n(" . pdf_escape('Description') . ") Tj\nET\n";
    $y -= $lineHeight;

    foreach (pdf_wrap_text($idea['description'], 78) as $line) {
        if ($y < 44) {
            break;
        }

        $stream .= "BT\n/F1 11 Tf\n$x {$y} Td\n(" . pdf_escape($line) . ") Tj\nET\n";
        $y -= $lineHeight;
    }

    $y -= $lineHeight * 2;

    $stream .= "BT\n/F1 13 Tf\n$x {$y} Td\n(" . pdf_escape('Exact insert query') . ") Tj\nET\n";
    $y -= $lineHeight;

    foreach (pdf_wrap_text($query, 78) as $line) {
        if ($y < 44) {
            break;
        }

        $stream .= "BT\n/F1 9 Tf\n$x {$y} Td\n(" . pdf_escape($line) . ") Tj\nET\n";
        $y -= $lineHeight;
    }

    return $stream;
}

function generate_pdf(array $idea): string
{
    $content = render_pdf_content($idea);
    $objects = [];
    $objects[1] = '<< /Type /Catalog /Pages 2 0 R >>';
    $objects[2] = '<< /Type /Pages /Kids [3 0 R] /Count 1 >>';
    $objects[3] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>';
    $objects[4] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';
    $objects[5] = '<< /Length ' . strlen($content) . ' >>' . "\nstream\n" . $content . "\nendstream";

    $pdf = "%PDF-1.4\n";
    $offsets = [0];

    foreach ($objects as $number => $object) {
        $offsets[$number] = strlen($pdf);
        $pdf .= $number . ' 0 obj\n' . $object . '\nendobj\n';
    }

    $xrefPosition = strlen($pdf);
    $pdf .= 'xref\n0 ' . (count($objects) + 1) . '\n';
    $pdf .= '0000000000 65535 f \n';

    for ($i = 1; $i <= count($objects); $i++) {
        $pdf .= sprintf('%010d 00000 n \n', $offsets[$i]);
    }

    $pdf .= 'trailer\n';
    $pdf .= '<< /Size ' . (count($objects) + 1) . ' /Root 1 0 R >>\n';
    $pdf .= 'startxref\n' . $xrefPosition . '\n%%EOF';

    return $pdf;
}

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="idea-' . (int) $idea['id'] . '.pdf"');

echo generate_pdf($idea);
