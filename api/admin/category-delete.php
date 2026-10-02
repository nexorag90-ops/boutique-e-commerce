<?php

require_once __DIR__ . '/../includes/cors.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée.'
    ]);

    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$id = (int) ($data['id'] ?? 0);

if ($id <= 0) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Identifiant de catégorie invalide.'
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

    $productStatement = $pdo->prepare(
        'SELECT COUNT(*) AS total
         FROM products
         WHERE category_id = :category_id'
    );

    $productStatement->execute([
        'category_id' => $id
    ]);

    $productCount = (int) $productStatement->fetch()['total'];

    if ($productCount > 0) {
        http_response_code(409);

        echo json_encode([
            'success' => false,
            'message' => 'Impossible de supprimer cette catégorie car elle contient encore des produits.'
        ]);

        exit;
    }

    $statement = $pdo->prepare(
        'DELETE FROM categories
         WHERE id = :id'
    );

    $statement->execute([
        'id' => $id
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Catégorie supprimée avec succès.'
    ]);
} catch (PDOException $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Impossible de supprimer la catégorie.'
    ]);
}