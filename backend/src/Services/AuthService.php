<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/Models/Utilizador.php';
require_once dirname(__DIR__) . '/Repositories/AuthRepository.php';

class AuthService
{
    public function __construct(private readonly AuthRepository $repository)
    {
    }

    public function register(array $input): array
    {
        $name = trim((string) ($input['nome'] ?? ''));
        $email = strtolower(trim((string) ($input['email'] ?? '')));
        $password = (string) ($input['senha'] ?? '');
        $phone = isset($input['telefone']) ? trim((string) $input['telefone']) : null;
        $type = (string) ($input['tipo'] ?? 'interessado');

        if ($name === '' || strlen($name) > 100) {
            throw new InvalidArgumentException('O nome e obrigatorio e deve ter no maximo 100 caracteres');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
            throw new InvalidArgumentException('O email e invalido');
        }
        if (strlen($password) < 8) {
            throw new InvalidArgumentException('A senha deve ter pelo menos 8 caracteres');
        }
        if (!in_array($type, ['proprietario', 'interessado'], true)) {
            throw new InvalidArgumentException('O tipo de utilizador e invalido');
        }
        if ($this->repository->findByEmail($email) !== null) {
            throw new DomainException('Ja existe um utilizador com este email');
        }

        $user = new Utilizador(
            $name,
            $email,
            AuthUtility::hashPassword($password),
            $phone === '' ? null : $phone,
            $type
        );
        $user = $this->repository->create($user);

        return [
            'user' => $this->publicUser($user->toArray()),
            'token' => $this->issueToken($user),
        ];
    }

    public function login(array $input): array
    {
        $email = strtolower(trim((string) ($input['email'] ?? '')));
        $password = (string) ($input['senha'] ?? '');
        $user = $this->repository->findByEmail($email);

        if ($user === null || !AuthUtility::verifyPassword($password, (string) $user['senha'])) {
            throw new DomainException('Credenciais invalidas');
        }

        return [
            'user' => $this->publicUser($user),
            'token' => $this->issueTokenFromRow($user),
        ];
    }

    public function validateToken(?string $token): ?array
    {
        if ($token === null || $token === '') {
            return null;
        }

        $parts = explode('.', $token, 2);
        if (count($parts) !== 2) {
            return null;
        }

        [$encodedPayload, $signature] = $parts;
        $expectedSignature = $this->sign($encodedPayload);
        if (!hash_equals($expectedSignature, $signature)) {
            return null;
        }

        $payload = json_decode($this->base64UrlDecode($encodedPayload), true);
        if (!is_array($payload) || !isset($payload['sub'], $payload['exp']) || (int) $payload['exp'] < time()) {
            return null;
        }

        return $payload;
    }

    private function issueToken(Utilizador $user): string
    {
        return $this->issueTokenFromRow($user->toArray());
    }

    private function issueTokenFromRow(array $user): string
    {
        $payload = [
            'sub' => (int) $user['id'],
            'email' => $user['email'],
            'tipo' => $user['tipo'],
            'exp' => time() + (int) env('AUTH_TTL', 86400),
        ];
        $encodedPayload = $this->base64UrlEncode((string) json_encode($payload));

        return $encodedPayload . '.' . $this->sign($encodedPayload);
    }

    private function sign(string $value): string
    {
        $secret = (string) env('AUTH_SECRET', '');
        if (strlen($secret) < 32) {
            throw new RuntimeException('AUTH_SECRET deve ter pelo menos 32 caracteres');
        }

        return $this->base64UrlEncode(hash_hmac('sha256', $value, $secret, true));
    }

    private function publicUser(array $user): array
    {
        unset($user['senha']);
        return $user;
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $value): string
    {
        return (string) base64_decode(strtr($value, '-_', '+/') . str_repeat('=', (4 - strlen($value) % 4) % 4));
    }
}
