<?php

require_once __DIR__ . '/../includes/cors.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée.'
    ]);

    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$id = (int) ($data['id'] ?? 0);
$name = trim($data['name'] ?? '');

if ($id <= 0 || $name === '') {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Identifiant ou nom de catégorie invalide.'
    ]);

    exit;
}

try {
    $categoryStatement = $pdo->prepare(
        'SELECT id FROM categories WHERE id = :id'
    );

    $categoryStatement->execute([
        'id' => $id
    ]);

    if (!$categoryStatement->fetch()) {
        http_response_code(404);

        echo json_encode([
            'success' => false,
            'message' => 'Catégorie introuvable.'
        ]);

        exit;
    }

    $duplicateStatement = $pdo->prepare(
        'SELECT id
         FROM categories
         WHERE name = :name
         AND id != :id'
    );

    $duplicateStatement->execute([
        'name' => $name,
        'id' => $id
    ]);

    if ($duplicateStatement->fetch()) {
        http_response_code(409);

        echo json_encode([
            'success' => false,
            'message' => 'Cette catégorie existe déjà.'
        ]);

        exit;
    }

    $statement = $pdo->prepare(
        'UPDATE categories
         SET name = :name
         WHERE id = :id'
    );

    $statement->execute([
        'name' => $name,
        'id' => $id
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Catégorie modifiée avec succès.'
    ]);
} catch (PDOException $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Impossible de modifier la catégorie.'
    ]);
}