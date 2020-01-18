<?php
error_reporting(E_ALL);
ini_set('display_errors', true);

$host = 'localhost';
$db = 'product_db';
$username = 'root';
$password = 'root';

$key = 'secret_key';

if ($_SERVER['HTTP_TOKEN'] != $key) {
    die('Access denied');
}

$dsn = "mysql:host=$host;dbname=$db";

$conn = new PDO($dsn, $username, $password);

$result = $conn->query("SELECT * FROM products");
$product = $result->fetch();

$result = file_get_contents('php://input');

$logString = 'Content received: ' . $result . "\n";

file_put_contents('log.txt', $logString, FILE_APPEND);

$array = json_decode($result, true);

foreach ($array['product'] as $product) {
    print_r($product);

        $query = $conn->prepare("SELECT * FROM table WHERE unid = :unid, quantity = :quantity, scu = :scu, barcode = :barcode, warehouse = :warehouse, size = :size");
        $result = $query->execute($product);
        $query->fetchAll();

    if ($query->fetch()) {
        $query = ("UPDATE table SET unid = :unid, quantity = :quantity, scu = :scu, barcode = :barcode, warehouse = :warehouse, size = :size WHERE unid = :unid");
        $result = $query->execute($product);
        $query->fetchAll();

    }else {
        $query = $conn->prepare("INSERT INTO products (unid, quantity, scu, barcode, warehouse, size) VALUES (:unid, :quantity, :scu, :barcode, :warehouse, :size)");
        $result = $query->execute($product);
        $query->fetchAll();
    }
}


