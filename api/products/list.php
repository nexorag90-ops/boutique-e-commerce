<?php
// API de récupération du catalogue.

require_once __DIR__ . '/../includes/cors.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée.'
    ]);

    exit;
}

$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');
$minPrice = $_GET['min_price'] ?? '';
$maxPrice = $_GET['max_price'] ?? '';

$sql = '
    SELECT
        products.id,
        products.name,
        products.description,
        products.price,
        products.image,
        categories.id AS category_id,
        categories.name AS category
    FROM products
    INNER JOIN categories
        ON categories.id = products.category_id
    WHERE 1 = 1
';

$params = [];

if ($search !== '') {
    $sql .= '
        AND (
            products.name LIKE :search
            OR products.description LIKE :search
        )
    ';

    $params['search'] = '%' . $search . '%';
}

if ($category !== '') {
    $sql .= ' AND categories.name = :category';
    $params['category'] = $category;
}

if ($minPrice !== '' && is_numeric($minPrice)) {
    $sql .= ' AND products.price >= :min_price';
    $params['min_price'] = (int) $minPrice;
}

if ($maxPrice !== '' && is_numeric($maxPrice)) {
    $sql .= ' AND products.price <= :max_price';
    $params['max_price'] = (int) $maxPrice;
}

$sql .= ' ORDER BY products.id ASC';

$statement = $pdo->prepare($sql);
$statement->execute($params);

$products = $statement->fetchAll();

echo json_encode([
    'success' => true,
    'products' => $products
]);