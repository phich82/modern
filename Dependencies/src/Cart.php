<?php 
namespace Src;

use Src\interfaces\ILogger;
use Src\interfaces\IDatabase;
use Src\interfaces\IMailSender;

class Cart 
{
    private $db;
    private $log;
    private $mail;

    public function __construct(IDatabase $db, ILogger $log, IMailSender $mail){
        $this->db   = $db;
        $this->log  = $log;
        $this->mail = $mail;
    }

    public function checkout(int $orderId, int $userId)
    {
        $this->db->save($orderId);
        $this->log->info('Order has been checkout.');
        $this->mail->send($userId);
    }

    public function getCart(){
        $cart = [
            ['name' => 'Product A', 'price' => 200, 'qty' => 2],
            ['name' => 'Product B', 'price' => 500, 'qty' => 1],
            ['name' => 'Product C', 'price' => 700, 'qty' => 5],
        ];
        print_r($cart);
    }
}