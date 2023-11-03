<?php

namespace App\Request;

class Request
{
    private int $operationType;
    private array $data;
    private ?int $resourceId = null;

    /**
     * @throws OperationUnknownException if URI is malformed
     */
    public function __construct(
        private string $uri,
        private string $method
    ) {
        $uriParts = explode('/', $this->uri);
        $this->operationType = match (count($uriParts)) {
            3 => RequestType::COLLECTION,
            4 => RequestType::ITEM,
            default => RequestType::UNKNOWN
        };

        if ($this->operationType === RequestType::UNKNOWN) {
            throw new OperationUnknownException();
        }

        if ($this->operationType === RequestType::ITEM) {
            $this->resourceId = intval($uriParts[3]);
            if ($this->resourceId === 0) {
                throw new InvalidResourceIdException();
            }
        }

        if ($this->method === "POST" || $this->method === "PUT") {
            $body = file_get_contents("php://input");
            $this->data = json_decode($body, true);
        }
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getOperationType(): int
    {
        return $this->operationType;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getResourceId(): ?int
    {
        return $this->resourceId;
    }
}
