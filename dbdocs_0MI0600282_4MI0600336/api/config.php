<?php
$db_host = getenv('DB_HOST') ?: 'localhost'; 
$db_name = getenv('DB_NAME') ?: 'db_generator';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') ?: '';

$javadoc_path = getenv('JAVADOC_PATH') ?: 'javadoc';

exec(escapeshellarg($javadoc_path) . ' -help 2>&1', $output, $return_code);
$enable_javadoc = ($return_code === 0);
?>