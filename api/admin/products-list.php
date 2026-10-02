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
        'SELECT
            products.id,
            products.name,
            products.description,
            products.price,
            products.image,
            products.category_id,
            categories.name AS category
        FROM products
        LEFT JOIN categories
            ON categories.id = products.category_id
        ORDER BY products.id DESC'
    );

    $products = $statement->fetchAll();

    echo json_encode([
        'success' => true,
        'products' => $products
    ]);
} catch (PDOException $exception) {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Impossible de récupérer les produits.'
    ]);
}