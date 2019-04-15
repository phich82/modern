<?php 
namespace Src;

use \Src\Cart;
use \Src\DIContainer;
use \Src\implementions\Logger;
use \Src\implementions\Database;
use \Src\implementions\MailSender;

/** config */
$config = [
    'aws' => [
        'key'         => '123',
        'private_key' => 'abc'
    ],
    'db' => [
        'username' => '456',
        'password' => 'def'
    ]
];


/** register the new services */
DIContainer::register(Database::class, function ($service) use ($config) {
    return new $service;    
});

DIContainer::register(Logger::class, function ($service) use ($config) {
    return new $service;
});

DIContainer::register(Cart::class, function ($service) {
    // instance được truyền từ ngoài vào (truyền manual hoặc nhờ DI Container).
    return new $service(new Database, new Logger, new MailSender);
});

DIContainer::register(MailSender::class, function ($service) {
    return new $service;
});