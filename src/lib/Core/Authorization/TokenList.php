<?php

declare(strict_types=1);

namespace ItSpirit\Payum\Payever\lib\Core\Authorization;

class TokenList extends \Payever\ExternalIntegration\Core\Authorization\TokenList
{
    /** @var \PDO */
    private $pdo;

    /**
     * {@inheritdoc}
     *
     * @param $clientId
     * @return \Payever\ExternalIntegration\Core\Authorization\TokenList|void
     * @throws \Exception
     */
    public function load()
    {
        $pdo = $this->getMemoryTokenDb();
        $tokensDb = $pdo->query('SELECT * FROM payever_core_tokens');

        foreach ($tokensDb as $tokenDb) {
            $params = [
                'access_token'  => $tokenDb['access_token'],
                'refresh_token' => $tokenDb['refresh_token'],
                'scope'         => $tokenDb['scope'],
                'created_at'    => $tokenDb['created_at'],
                'updated_at'    => $tokenDb['updated_at'],
            ];

            $this->add(
                $tokenDb['scope'],
                $this->create()->load($params)
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $pdo = $this->getMemoryTokenDb();
        
        $pdo->exec('TRUNCATE payever_core_tokens');

        $insert = 'INSERT INTO payever_core_tokens (access_token, refresh_token, scope, created_at, updated_at) 
                VALUES (:accessToken, :refreshToken, :scope, :createdAt, :updatedAt)';

        $pdo = $pdo->prepare($insert);

       /** @var Token $token */
        foreach ($this->getAll() as $token) {
            // Bind values directly to statement variables
            $pdo->bindValue(':accessToken', $token->getAccessToken(), SQLITE3_TEXT);
            $pdo->bindValue(':refreshToken', $token->getAccessToken(), SQLITE3_TEXT);
            $pdo->bindValue(':scope', $token->getAccessToken(), SQLITE3_TEXT);
            $pdo->bindValue(':createdAt', $token->getCreatedAt(), SQLITE3_INTEGER);
            $pdo->bindValue(':updatedAt', $token->getUpdatedAt(), SQLITE3_INTEGER);

            // Execute statement
            $pdo->execute();
        }
    }

    /**
     * @inheritdoc
     *
     * @throws \Exception
     */
    public function create(): Token
    {
        return new Token();
    }

    /**
     * @return \PDO
     */
    protected function getMemoryTokenDb(): \PDO
    {
        if ($this->pdo === null) {
            $this->pdo = new \PDO(
                'sqlite::memory:',
                null,
                null,
                [\PDO::ATTR_PERSISTENT => true]
            );
            $this->pdo->exec(
                'CREATE TABLE IF NOT EXISTS payever_core_tokens (
                    access_token TEXT, 
                    refresh_token TEXT, 
                    scope TEXT, 
                    created_at INTEGER, 
                    updated_at INTEGER)'
            );
        }
        
        return $this->pdo;
    }
}
