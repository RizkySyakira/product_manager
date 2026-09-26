<?php

require_once "config/database.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama = trim($_POST["nama"]);
    $kategori = trim($_POST["kategori"]);
    $harga = filter_var($_POST["harga"], FILTER_VALIDATE_FLOAT);
    $stok = filter_var($_POST["stok"], FILTER_VALIDATE_INT);

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

    // Cek nama produk sudah ada atau belum
    if (!isset($errors["nama"])) {

        $stmt = $pdo->prepare(
            "SELECT id FROM products WHERE nama = ?"
        );

        $stmt->execute([$nama]);

        if ($stmt->fetch()) {
            $errors["nama"] = "Nama produk sudah digunakan.";
        }
    }

    // Kalau tidak ada error, simpan ke database
    if (empty($errors)) {

        $stmt = $pdo->prepare("
            INSERT INTO products (nama, kategori, harga, stok)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $nama,
            $kategori,
            $harga,
            $stok
        ]);

        header("Location: index.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
</head>

<body>

    <h1>Tambah Produk</h1>

    <form method="POST">

        <label>Nama Produk</label><br>
        <input
            type="text"
            name="nama"
            value="<?= htmlspecialchars($_POST["nama"] ?? "") ?>"
        >

        <?php if (isset($errors["nama"])): ?>
            <p><?= htmlspecialchars($errors["nama"]) ?></p>
        <?php endif; ?>

        <br>

        <label>Kategori</label><br>
        <input
            type="text"
            name="kategori"
            value="<?= htmlspecialchars($_POST["kategori"] ?? "") ?>"
        >

        <br><br>

        <label>Harga</label><br>
        <input
            type="number"
            name="harga"
            value="<?= htmlspecialchars($_POST["harga"] ?? "") ?>"
        >

        <?php if (isset($errors["harga"])): ?>
            <p><?= htmlspecialchars($errors["harga"]) ?></p>
        <?php endif; ?>

        <br>

        <label>Stok</label><br>
        <input
            type="number"
            name="stok"
            value="<?= htmlspecialchars($_POST["stok"] ?? "") ?>"
        >

        <?php if (isset($errors["stok"])): ?>
            <p><?= htmlspecialchars($errors["stok"]) ?></p>
        <?php endif; ?>

        <br><br>

        <button type="submit">Tambah Produk</button>

    </form>

</body>
</html>