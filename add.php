<?php

require_once "config/database.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

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


    $nama = trim($_POST["nama"] ?? "");
    $kategori = trim($_POST["kategori"] ?? "");

    $harga = filter_var(
        $_POST["harga"] ?? null,
        FILTER_VALIDATE_FLOAT
    );

    $stok = filter_var(
        $_POST["stok"] ?? null,
        FILTER_VALIDATE_INT
    );


    // Validasi nama

    if (strlen($nama) < 3) {

        $errors["nama"] =
            "Nama produk minimal 3 karakter.";

    }


    // Validasi harga

    if ($harga === false || $harga <= 0) {

        $errors["harga"] =
            "Harga harus lebih dari 0.";

    }


    // Validasi stok

    if ($stok === false || $stok < 0) {

        $errors["stok"] =
            "Stok tidak boleh kurang dari 0.";

    }


    // Cek nama produk duplikat

    if (!isset($errors["nama"])) {

        $stmt = $pdo->prepare(
            "SELECT id FROM products WHERE nama = ?"
        );

        $stmt->execute([$nama]);

        if ($stmt->fetch()) {

            $errors["nama"] =
                "Nama produk sudah digunakan.";

        }

    }


    // Simpan produk

    if (empty($errors)) {

        $stmt = $pdo->prepare("
            INSERT INTO products
            (nama, kategori, harga, stok)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $nama,
            $kategori,
            $harga,
            $stok
        ]);


        // PRG:
        // setelah berhasil, kembali ke index
        header("Location: index.php");
        exit;

    }

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Tambah Produk</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f2f6ff;
            padding: 40px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background-color: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        h1 {
            color: #3157a4;
        }

        label {
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        button {
            background-color: #3157a4;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #1f4080;
        }

        .error {
            color: #d62828;
            font-size: 14px;
            margin-top: -8px;
            margin-bottom: 12px;
        }

        .kembali {
            display: inline-block;
            margin-left: 10px;
            color: #3157a4;
            text-decoration: none;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>Tambah Produk</h1>

    <form method="POST">

        <!-- CSRF Token -->

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars(
                $_SESSION["csrf_token"],
                ENT_QUOTES,
                "UTF-8"
            ) ?>"
        >


        <label>Nama Produk</label>

        <input
            type="text"
            name="nama"
            value="<?= htmlspecialchars(
                $_POST["nama"] ?? "",
                ENT_QUOTES,
                "UTF-8"
            ) ?>"
        >

        <?php if (isset($errors["nama"])): ?>

            <div class="error">
                <?= htmlspecialchars(
                    $errors["nama"],
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>
            </div>

        <?php endif; ?>


        <label>Kategori</label>

        <input
            type="text"
            name="kategori"
            value="<?= htmlspecialchars(
                $_POST["kategori"] ?? "",
                ENT_QUOTES,
                "UTF-8"
            ) ?>"
        >


        <label>Harga</label>

        <input
            type="number"
            name="harga"
            value="<?= htmlspecialchars(
                $_POST["harga"] ?? "",
                ENT_QUOTES,
                "UTF-8"
            ) ?>"
        >

        <?php if (isset($errors["harga"])): ?>

            <div class="error">
                <?= htmlspecialchars(
                    $errors["harga"],
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>
            </div>

        <?php endif; ?>


        <label>Stok</label>

        <input
            type="number"
            name="stok"
            value="<?= htmlspecialchars(
                $_POST["stok"] ?? "",
                ENT_QUOTES,
                "UTF-8"
            ) ?>"
        >

        <?php if (isset($errors["stok"])): ?>

            <div class="error">
                <?= htmlspecialchars(
                    $errors["stok"],
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>
            </div>

        <?php endif; ?>


        <button type="submit">
            Simpan Produk
        </button>

        <a href="index.php" class="kembali">
            Kembali
        </a>

    </form>

</div>

</body>

</html>