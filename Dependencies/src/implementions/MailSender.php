<?php 
namespace Src\implementions;

use Src\interfaces\IMailSender;

class MailSender implements IMailSender
{
    public function send(int $userId){
        echo "Email sent.\n";
    }
}