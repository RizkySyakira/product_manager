<?php

require_once "config/database.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    die("ID produk tidak valid.");
}

// Ambil data produk
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Produk tidak ditemukan.");
}

// Proses ketika tombol Simpan ditekan
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama = trim($_POST["nama"]);
    $kategori = trim($_POST["kategori"]);
    $harga = filter_var($_POST["harga"], FILTER_VALIDATE_FLOAT);
    $stok = filter_var($_POST["stok"], FILTER_VALIDATE_INT);

    $errors = [];

    // Validasi nama
    if (strlen($nama) < 3) {
        $errors["nama"] = "Nama produk minimal 3 karakter.";
    }

    // Validasi harga
    if ($harga === false || $harga <= 0) {
        $errors["harga"] = "Harga harus lebih dari 0.";
    }

    // Validasi stok
    if ($stok === false || $stok < 0) {
        $errors["stok"] = "Stok tidak boleh kurang dari 0.";
    }

    // Cek nama duplikat
    if (!isset($errors["nama"])) {

        $stmt = $pdo->prepare(
            "SELECT id FROM products WHERE nama = ? AND id != ?"
        );

        $stmt->execute([$nama, $id]);

        if ($stmt->fetch()) {
            $errors["nama"] = "Nama produk sudah digunakan.";
        }
    }

    // Kalau tidak ada error
    if (empty($errors)) {

        $stmt = $pdo->prepare("
            UPDATE products
            SET nama = ?, kategori = ?, harga = ?, stok = ?
            WHERE id = ?
        ");

        $stmt->execute([
            $nama,
            $kategori,
            $harga,
            $stok,
            $id
        ]);

        header("Location: index.php");
        exit;
    }

    // Tampilkan kembali data yang dikirim
    $product["nama"] = $nama;
    $product["kategori"] = $kategori;
    $product["harga"] = $_POST["harga"];
    $product["stok"] = $_POST["stok"];
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Produk</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            padding: 30px;
        }

        .container {
            max-width: 500px;
            margin: auto;
            background-color: white;
            padding: 25px;
            border-radius: 8px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: -10px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Edit Produk</h1>

    <form method="POST">

        <label>Nama Produk</label>

        <input
            type="text"
            name="nama"
            value="<?= htmlspecialchars($product["nama"]) ?>"
        >

        <?php if (isset($errors["nama"])): ?>
            <div class="error">
                <?= htmlspecialchars($errors["nama"]) ?>
            </div>
        <?php endif; ?>


        <label>Kategori</label>

        <input
            type="text"
            name="kategori"
            value="<?= htmlspecialchars($product["kategori"]) ?>"
        >


        <label>Harga</label>

        <input
            type="number"
            name="harga"
            value="<?= htmlspecialchars($product["harga"]) ?>"
        >

        <?php if (isset($errors["harga"])): ?>
            <div class="error">
                <?= htmlspecialchars($errors["harga"]) ?>
            </div>
        <?php endif; ?>


        <label>Stok</label>

        <input
            type="number"
            name="stok"
            value="<?= htmlspecialchars($product["stok"]) ?>"
        >

        <?php if (isset($errors["stok"])): ?>
            <div class="error">
                <?= htmlspecialchars($errors["stok"]) ?>
            </div>
        <?php endif; ?>


        <button type="submit">
            Simpan Perubahan
        </button>

    </form>

</div>

</body>
</html>