<?php 
namespace Src\interfaces;

interface IMailSender
{
    public function send(int $userId);
}