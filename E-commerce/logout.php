<?php

$status = '';
session_start();
session_destroy();
$status = '200';

echo $status;

header('location: index.php');
