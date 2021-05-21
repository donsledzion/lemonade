<?php

$config = require_once 'dbconfig.php' ;
require_once('Database.php');

$db = new Database($config['host'],$config['database'], $config['user'], $config['password']);
