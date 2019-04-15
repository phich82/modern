<?php 
namespace Src\implementions;

use Src\interfaces\IDatabase;

class Database implements IDatabase
{
    public function save(int $orderId)
    {
        echo "Saved to database.\n";
    }
}