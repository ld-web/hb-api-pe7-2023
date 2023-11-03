<?php

namespace App\Repository;

class EmailRepository extends AbstractRepository
{
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM emails");
        return $stmt->fetchAll();
    }

    /**
     * Create a new email
     *
     * @param array $data
     * @return integer ID of inserted email
     */
    public function create(array $data): int
    {
        $insertStmt = $this->pdo->prepare("INSERT INTO emails (name, first_name, email) VALUES(:name, :fname, :email)");
        $insertStmt->execute([
          'name' => $data['name'],
          'fname' => $data['firstname'],
          'email' => $data['email']
        ]);
        return intval($this->pdo->lastInsertId());
    }

    /**
     * Get an email
     *
     * @param integer $id
     * @return array|null null if not found
     */
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM emails WHERE id=?");
        $stmt->execute([$id]);
        $email = $stmt->fetch();

        return ($email === false) ? null : $email;
    }

    public function update(int $id, array $data): bool
    {
        $nbParams = 0;
        $query = "UPDATE emails SET";

        if (isset($data['name'])) {
            $query .= " name = :name";
            $nbParams++;
        }
        if (isset($data['firstname'])) {
            if ($nbParams > 0) {
                $query .= ',';
            }
            $query .= " first_name = :fname";
            $nbParams++;
        }
        if (isset($data['email'])) {
            if ($nbParams > 0) {
                $query .= ',';
            }
            $query .= " email = :email";
            $nbParams++;
        }

        $query .= " WHERE id=:id";

        $stmt = $this->pdo->prepare($query);

        if (isset($data['name'])) {
            $stmt->bindValue('name', $data['name']);
        }
        if (isset($data['firstname'])) {
            $stmt->bindValue('fname', $data['firstname']);
        }
        if (isset($data['email'])) {
            $stmt->bindValue('email', $data['email']);
        }

        $stmt->bindValue('id', $id);

        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM emails WHERE id=:id");
        return $stmt->execute(['id' => $id]);
    }
}
