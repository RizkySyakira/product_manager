<?php

require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    die("Metode request tidak valid.");

}


// Cek CSRF

if (
    !isset($_POST["csrf_token"]) ||
    !hash_equals(
        $_SESSION["csrf_token"],
        $_POST["csrf_token"]
    )
) {

    die("Token keamanan tidak valid.");

}


// Ambil ID

$id = filter_input(
    INPUT_POST,
    "id",
    FILTER_VALIDATE_INT
);


if (!$id) {

    die("ID produk tidak valid.");

}


// Hapus produk

$stmt = $pdo->prepare(
    "DELETE FROM products WHERE id = ?"
);

$stmt->execute([$id]);


// Kembali ke halaman utama

header("Location: index.php");
exit;