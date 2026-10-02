<?php
// API de connexion des utilisateurs.

require_once __DIR__ . '/../includes/cors.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée.'
    ]);

    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if ($email === '' || $password === '') {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Email et mot de passe obligatoires.'
    ]);

    exit;
}

$statement = $pdo->prepare(
    'SELECT id, name, email, password, role
     FROM users
     WHERE email = :email'
);

$statement->execute([
    'email' => $email
]);

$user = $statement->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    http_response_code(401);

    echo json_encode([
        'success' => false,
        'message' => 'Email ou mot de passe incorrect.'
    ]);

    exit;
}

unset($user['password']);

$_SESSION['user'] = $user;

echo json_encode([
    'success' => true,
    'message' => 'Connexion réussie.',
    'user' => $user
]);