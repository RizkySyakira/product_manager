<?php

require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Metode request tidak valid.");
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id) {
    die("ID produk tidak valid.");
}

$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php");
exit;