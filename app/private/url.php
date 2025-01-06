<?php
session_start();
const BASE_PATH = 'localhost:80/';
const PRIVATE_PATH = __DIR__ . '/';

require_once PRIVATE_PATH . 'Auth.php';
require_once PRIVATE_PATH . 'component/Alert.php';