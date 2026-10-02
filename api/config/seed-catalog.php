<?php
// Insertion des données initiales du catalogue.

require_once __DIR__ . '/database.php';

$products = [
    [
        'name' => 'Poulet braisé',
        'description' => 'Poulet braisé accompagné de ses garnitures.',
        'price' => 4500,
        'category' => 'Plats'
    ],
    [
        'name' => 'Burger classique',
        'description' => 'Burger avec steak, fromage et légumes frais.',
        'price' => 3500,
        'category' => 'Burgers'
    ],
    [
        'name' => 'Salade fraîche',
        'description' => 'Salade composée de légumes frais.',
        'price' => 2500,
        'category' => 'Salades'
    ]
];

$categoryStatement = $pdo->prepare(
    'SELECT id FROM categories WHERE name = :name'
);

$productExistsStatement = $pdo->prepare(
    'SELECT id FROM products WHERE name = :name'
);

$productStatement = $pdo->prepare(
    'INSERT INTO products (
        category_id,
        name,
        description,
        price,
        image
    ) VALUES (
        :category_id,
        :name,
        :description,
        :price,
        :image
    )'
);

foreach ($products as $product) {
    $categoryStatement->execute([
        'name' => $product['category']
    ]);

    $category = $categoryStatement->fetch();

    if (!$category) {
        continue;
    }

    $productExistsStatement->execute([
        'name' => $product['name']
    ]);

    if ($productExistsStatement->fetch()) {
        continue;
    }

    $productStatement->execute([
        'category_id' => $category['id'],
        'name' => $product['name'],
        'description' => $product['description'],
        'price' => $product['price'],
        'image' => null
    ]);
}

echo "Produits initiaux ajoutés avec succès.";