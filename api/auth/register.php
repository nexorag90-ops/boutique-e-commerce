<?php
// API d'inscription des utilisateurs.

require_once __DIR__ . '/../includes/cors.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';

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
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if ($name === '' || $email === '' || $password === '') {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Tous les champs sont obligatoires.'
    ]);

    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Adresse email invalide.'
    ]);

    exit;
}

if (strlen($password) < 6) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Le mot de passe doit contenir au moins 6 caractères.'
    ]);

    exit;
}

$statement = $pdo->prepare(
    'SELECT id FROM users WHERE email = :email'
);

$statement->execute([
    'email' => $email
]);

if ($statement->fetch()) {
    http_response_code(409);

    echo json_encode([
        'success' => false,
        'message' => 'Cette adresse email est déjà utilisée.'
    ]);

    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$statement = $pdo->prepare(
    'INSERT INTO users (name, email, password, role)
     VALUES (:name, :email, :password, :role)'
);

$statement->execute([
    'name' => $name,
    'email' => $email,
    'password' => $hashedPassword,
    'role' => 'client'
]);

http_response_code(201);

echo json_encode([
    'success' => true,
    'message' => 'Compte créé avec succès.'
]);