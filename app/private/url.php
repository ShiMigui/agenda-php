<?php
const BASE_PATH = 'localhost:80/';
const PRIVATE_PATH = __DIR__ . '/';

session_start();
require_once PRIVATE_PATH . 'component/Alert.php';
require_once PRIVATE_PATH . 'template/head.php';