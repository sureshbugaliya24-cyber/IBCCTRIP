<?php
require_once "backend/config/config.php";
require_once "backend/config/database.php";
require_once "backend/api/admin.php"; // This will execute admin.php until it hits a missing $_GET/$_POST but let's see where it gets to

echo "Test complete\n";
