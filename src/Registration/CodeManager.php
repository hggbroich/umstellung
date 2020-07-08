<?php

namespace App\Registration;

use Doctrine\DBAL\Connection;
use Ramsey\Uuid\Uuid;

class CodeManager {

    private $userTypeId;
    private const Suffix = '@e.hgg-broich.de';

    private $iccConnection;
    private $idpConnection;

    public function __construct(int $userTypeId, Connection $iccConnection, Connection $idpConnection) {
        $this->userTypeId = $userTypeId;
        $this->iccConnection = $iccConnection;
        $this->idpConnection = $idpConnection;
    }

    public function getCode(string $username): string {
        $code = $this->getExistingCode($username);

        if(!empty($code)) {
            return $code;
        }

        $code = $this->generateCode();
        $this->persistCode($username, $code);
        $this->registerCodeInIdp($code);

        return $code;
    }

    private function getRandomString(int $length): string {
        $range = array_merge(
            range(50, 57),
            range(65, 72),
            range(74,78),
            range(80, 90)
        );

        $output = '';

        for($i = 0; $i < $length; $i++) {
            $output .= chr($range[rand(0, count($range)-1)]);
        }

        return $output;
    }

    private function generateCode(): string {
        do {
            $code = $this->getRandomString(5) . '-' . $this->getRandomString(3);
        } while($this->codeExists($code));

        return $code;
    }

    private function codeExists(string $code): bool {
        $stmt = $this->idpConnection->prepare('SELECT 1 FROM registration_code WHERE code = :code');
        $stmt->bindParam('code', $code);
        $stmt->execute();

        return $stmt->fetch() !== false;
    }

    private function getExistingCode(string $username): ?string {
        $stmt = $this->iccConnection->prepare('SELECT code FROM students WHERE parent_username = :username');
        $stmt->bindParam('username', $username);
        $stmt->execute();

        $user = $stmt->fetch();

        if($user === false || !isset($user['code'])) {
            return null;
        }

        return $user['code'];
    }

    private function persistCode(string $username, string $code): void {
        $stmt = $this->iccConnection->prepare('UPDATE students SET code = :code WHERE parent_username = :username');
        $stmt->bindParam('code', $code);
        $stmt->bindParam('username', $username);

        $stmt->execute();
    }

    private function registerCodeInIdp(string $code): void {
        $suffix = static::Suffix;
        $uuid = Uuid::uuid4()->toString();

        $stmt = $this->idpConnection->prepare('INSERT INTO registration_code (type_id, code, username_suffix, uuid) VALUES(:type, :code, :suffix, :uuid)');
        $stmt->bindParam('type', $this->userTypeId);
        $stmt->bindParam('code', $code);
        $stmt->bindParam('uuid', $uuid);
        $stmt->bindParam('suffix', $suffix);

        $stmt->execute();
    }
}