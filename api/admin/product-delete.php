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
        'message' => 'Identifiant du produit invalide.'
    ]);

    exit;
}

try {
    $productStatement = $pdo->prepare(
        'SELECT id FROM products WHERE id = :id'
    );

    $productStatement->execute([
        'id' => $id
    ]);

    if (!$productStatement->fetch()) {
        http_response_code(404);

        echo json_encode([
            'success' => false,
            'message' => 'Produit introuvable.'
        ]);

        exit;
    }

    $statement = $pdo->prepare(
        'DELETE FROM products WHERE id = :id'
    );

    $statement->execute([
        'id' => $id
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Produit supprimé avec succès.'
    ]);
} catch (PDOException $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Impossible de supprimer le produit.'
    ]);
}