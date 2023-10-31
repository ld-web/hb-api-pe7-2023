<?php

// On indique avec cette en-tête le type de contenu que l'on va renvoyer au client
header('Content-Type: application/json; charset=UTF-8');
// Avec le header Access-Control-Allow-Origin,
// j'autorise http://127.0.0.1:5500 à requêter
// header('Access-Control-Allow-Origin: http://127.0.0.1:5500');

require_once 'vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;
use App\DbConnection;

// En cas d'exception non "catch", j'exécute cette fonction
// Elle recevra l'exception qui n'a pas été "catch", et
// affichera simplement un message d'erreur générique
set_exception_handler(function (Throwable $ex): void {
    http_response_code(500);
    echo json_encode([
        'error' => "Une erreur est survenue"
    ]);
    exit;
});

// Chargement des variables d'environnement
$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/.env');
// Récupération de la connexion à la BDD
$pdo = DbConnection::getConnection();

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri    = $_SERVER['REQUEST_URI'];

// /users/4
$uriParts = explode('/', $requestUri);

if (count($uriParts) === 3) { // Dans mon URI, je veux un élément seul
    $id = intval($uriParts[2]);

    if ($id === 0) {
        http_response_code(404);
        echo json_encode([
            'info' => 'not found'
        ]);
        exit;
    }

    $stmtUser = $pdo->prepare("SELECT id, last_name, first_name, email, active, profile_pic FROM users WHERE id=:id");
    $stmtUser->execute(['id' => $id]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if ($user === false) {
        http_response_code(404);
        echo json_encode([
            'info' => 'not found'
        ]);
        exit;
    }

    echo json_encode($user);
}

// Afficher tous les utilisateurs
if ($requestUri === '/users' && $requestMethod === 'GET') {
    $stmt = $pdo->query("SELECT id, last_name, first_name, email, active, profile_pic FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
}

// Créer un nouvel utilisateur
if ($requestUri === '/users' && $requestMethod === 'POST') {
    // J'extraie les données du corps de la requête
    // php://input représente le flux qui diffuse ces données (le corps de la requête)
    $body = file_get_contents("php://input");
    // Les données sont arrivées sous forme d'une string,
    // donc je vais les convertir en tableau associatif PHP
    $data = json_decode($body, true);

    $stmtInsert = $pdo->prepare("INSERT INTO users (last_name, first_name, email, password) VALUES (:lname, :fname, :email, :password)");
    $stmtInsert->execute([
        "lname" => $data['name'],
        "fname" => $data['firstname'],
        "email" => $data['email'],
        "password" => password_hash($data['password'], PASSWORD_DEFAULT)
    ]);

    http_response_code(201);
    $newUserId = intval($pdo->lastInsertId());

    echo json_encode([
        'id' => $newUserId
    ]);
}
