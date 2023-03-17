<?php

namespace Models;

require __DIR__ . '/../../vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

class DB
{
    private $host;
    private $db;
    private $user;
    private $password;
    private $port;

    public function __construct()
    {
        $this->host = $_ENV['DB_HOST'];
        $this->db = $_ENV['DB_NAME'];
        $this->user = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASSWORD'];
        $this->port = $_ENV['DB_PORT'];
    }

    function connect()
    {
        try {
            $dns = "mysql:dbname={$this->db};host={$this->host}";
            $options = [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new \PDO($dns, $this->user, $this->password,  $options);

            return $pdo;
        } catch (\PDOException $e) {
            print_r('Error connection: ' . $e->getMessage());
        }
    }
}
