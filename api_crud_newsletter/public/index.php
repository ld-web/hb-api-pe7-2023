<?php


require_once __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

use Symfony\Component\Dotenv\Dotenv;
use App\ErrorHandler;
use App\Db;
use App\Request\Request;
use App\Response\ResponseCode;
use App\Request\OperationUnknownException;
use App\Request\InvalidResourceIdException;
use App\Request\RequestType;
use App\Repository\EmailRepository;

ErrorHandler::registerExceptionHandler();

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/../.env');

$pdo = Db::getConnection();

try {
    $request = new Request(
        $_SERVER['REQUEST_URI'],
        $_SERVER['REQUEST_METHOD']
    );
} catch (OperationUnknownException|InvalidResourceIdException $ex) {
    http_response_code(ResponseCode::BAD_REQUEST);
    echo json_encode([
      'error' => $ex->getMessage()
    ]);
    exit;
}

$emailRepository = new EmailRepository($pdo);

if ($request->getOperationType() === RequestType::COLLECTION) {
    if ($request->getMethod() === "GET") {
        $emails = array_map(function (array $email) {
            return [
              'id' => $email['id'],
              'email' => $email['email'],
              'full_name' => $email['first_name'] . ' ' . $email['name']
            ];
        }, $emailRepository->findAll());
        echo json_encode($emails);
        exit;
    }

    if ($request->getMethod() === "POST") {
        $newEmailId = $emailRepository->create($request->getData());
        http_response_code(ResponseCode::CREATED);
        echo json_encode([
          "uri" => "/api/emails/$newEmailId"
        ]);
        exit;
    }
}

if ($request->getOperationType() === RequestType::ITEM) {
    if ($request->getMethod() === "GET") {
        $email = $emailRepository->find($request->getResourceId());
        if ($email === null) {
            http_response_code(ResponseCode::NOT_FOUND);
            echo json_encode([
              'error' => 'Not found'
            ]);
            exit;
        }

        echo json_encode([
          'id' => $email['id'],
          'email' => $email['email'],
          'full_name' => $email['first_name'] . ' ' . $email['name']
        ]);
        exit;
    }

    if ($request->getMethod() === "PUT") {
        $update = $emailRepository->update($request->getResourceId(), $request->getData());
        if ($update) {
            http_response_code(ResponseCode::NO_CONTENT);
        } else {
            throw new Exception();
        }
    }

    if ($request->getMethod() === "DELETE") {
        $delete = $emailRepository->delete($request->getResourceId());
        if ($delete) {
            http_response_code(ResponseCode::NO_CONTENT);
        } else {
            throw new Exception();
        }
    }
}
