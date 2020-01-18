<?php

require_once 'dbconfig.php';

$key = 'secret_key';


if ($_SERVER['HTTP_TOKEN'] != $key) {
    die('Access denied');
}

$dsn = "mysql:host=$host;dbname=$db";

$conn = new PDO($dsn, $username, $password);

$result = $conn->query("SELECT * FROM product");
$product = $result->fetch();

$result = file_get_contents('php://input');

$logString = 'Content received: ' . $result . "\n";

file_put_contents('log.txt', $logString, FILE_APPEND);

$array = json_decode($result, true);

foreach ($array['product'] as $product) {

    $exist = $conn->query("SELECT * FROM table WHERE unid = :unid", $product);
    if ($exist->fetch()) {
        //Do update data
        $conn->query("UPDATE table SET unid = :unid, quantity = :quantity, scu = :scu, barcode = :barcode, warehouse = :warehouse, size = :size WHERE unid = :unid", $product);

    }else {
        $conn->query("INSERT INTO product (unid, quantity,scu, barcode, warehouse, size) VALUES (:unid, :quantity, :scu, :barcode, :warehouse, size)", $product );
    }

}

