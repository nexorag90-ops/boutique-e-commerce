<?php

require_once __DIR__ . '/../includes/cors.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée.'
    ]);

    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$name = trim($data['name'] ?? '');

if ($name === '') {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Le nom de la catégorie est obligatoire.'
    ]);

    exit;
}

try {
    $checkStatement = $pdo->prepare(
        'SELECT id FROM categories WHERE name = :name'
    );

    $checkStatement->execute([
        'name' => $name
    ]);

    if ($checkStatement->fetch()) {
        http_response_code(409);

        echo json_encode([
            'success' => false,
            'message' => 'Cette catégorie existe déjà.'
        ]);

        exit;
    }

    $statement = $pdo->prepare(
        'INSERT INTO categories (name)
         VALUES (:name)'
    );

    $statement->execute([
        'name' => $name
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Catégorie ajoutée avec succès.',
        'category_id' => (int) $pdo->lastInsertId()
    ]);
} catch (PDOException $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Impossible d’ajouter la catégorie.'
    ]);
}