<?php
require_once '../private/url.php';
Auth::logout();
header('Location:login.php');