<?php
namespace App\Libraries;

use \OAuth2\Storage\Pdo;

class OAuth2
{
    public $server;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $dsn = getenv('database.default.DNS');
        $username = getenv('database.default.username');
        $password = getenv('database.default.password');

        $storage = new Pdo(['dsn' => $dsn, 'username' => $username, 'password' => $password]);
    }

}
