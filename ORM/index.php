<?php 

require 'lib/rb.php';

//R::setup('pgsql:host=localhost;dbname=mydatabase', 'user', 'password'); //for PostgreSQL
//R::setup('sqlite:/tmp/dbfile.db'); //for SQLite
//R::setup('cubrid:host=localhost;port=30000;dbname=mydatabase', 'user','password'); // for CUBRID
R::setup('mysql:host=localhost;dbname=orm', 'phich82', '20091982'); //for both mysql or mariaDB

// beans
// create
$user = R::dispense('users');
$user->login = 'phich82';
$user->password = sha1('1234');
$user->email = 'phich82@gmail.com';
$user->money = 100;
$id = R::store($user);
echo $id;

// update
// $u = R::load('users', 1);
// $u->login = 'jhphich';
// R::store($u);

// delete
// $u = R::load('users', 1);
// R::trash($u);

// get all
$users = R::findAll('users');
print_r($users);