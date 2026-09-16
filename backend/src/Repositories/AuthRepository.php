<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/Models/Utilizador.php';
require_once dirname(__DIR__) . '/Core/Contracts/PdoConnectionInterface.php';

class AuthRepository
{
    private readonly PDO $connection;

    public function __construct(PdoConnectionInterface $provider)
    {
        $this->connection = $provider->getConnection();
    }

    public function findByEmail(string $email): ?array
    {
        $statement = $this->connection->prepare(
            'SELECT id, nome, email, senha, telefone, tipo, criado_em
             FROM utilizadores WHERE email = :email LIMIT 1'
        );
        $statement->execute(['email' => $email]);
        $user = $statement->fetch();

        return $user === false ? null : $user;
    }

    public function create(Utilizador $user): Utilizador
    {
        try {
            $statement = $this->connection->prepare(
                'INSERT INTO utilizadores (nome, email, senha, telefone, tipo)
                 VALUES (:nome, :email, :senha, :telefone, :tipo)'
            );
            $statement->execute([
                'nome' => $user->nome,
                'email' => $user->email,
                'senha' => $user->senha,
                'telefone' => $user->telefone,
                'tipo' => $user->tipo,
            ]);
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                throw new DomainException('Ja existe um utilizador com este email', 0, $exception);
            }

            throw $exception;
        }

        $user->id = (int) $this->connection->lastInsertId();
        $user->criadoEm = date('Y-m-d H:i:s');

        return $user;
    }
}
