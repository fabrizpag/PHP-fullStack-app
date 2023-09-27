<?php

session_start();
session_unset();
session_destroy();

header("location: shop.php");

// mi serve un attimo