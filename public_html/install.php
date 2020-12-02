<?php

use App\App;
use Core\FileDB;

require '../bootloader.php';

App::$db = new FileDB(DB_FILE);

App::$db->createTable('users');
App::$db->insertRow('users', ['username' => 'test1', 'password' => 'test', 'gems' => 0]);

App::$db->createTable('history');
