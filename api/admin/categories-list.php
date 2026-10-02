<?php

require_once __DIR__ . '/../includes/cors.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée.'
    ]);

    exit;
}

try {
    $statement = $pdo->query(
        'SELECT id, name
         FROM categories
         ORDER BY name ASC'
    );

    $categories = $statement->fetchAll();

    echo json_encode([
        'success' => true,
        'categories' => $categories
    ]);
} catch (PDOException $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Impossible de récupérer les catégories.'
    ]);
}