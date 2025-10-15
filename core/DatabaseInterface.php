<?php
namespace App\Core;

use PDO;

interface DatabaseInterface {
    public function connect(): PDO;
}
