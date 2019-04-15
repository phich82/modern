<?php 
namespace Src\implementions;

use Src\interfaces\ILogger;

class Logger implements ILogger
{
    public function info(string $message){
        echo $message."\n";
    }
}