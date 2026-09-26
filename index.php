<?php

require_once "config/database.php";

$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Product Manager</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }

        h1 {
            margin-bottom: 5px;
        }

        .deskripsi {
            color: #666;
            margin-bottom: 25px;
        }

        .tambah {
            display: inline-block;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .tambah:hover {
            background-color: #218838;
        }

        .produk {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
        }

        .produk h2 {
            margin-top: 0;
        }

        .produk p {
            margin: 8px 0;
        }

        .edit {
            display: inline-block;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .edit:hover {
            background-color: #0056b3;
        }

        .hapus {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .hapus:hover {
            background-color: #c82333;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>Product Manager</h1>

    <p class="deskripsi">
        Sistem sederhana untuk mengelola data produk.
    </p>

    <a href="add.php" class="tambah">
        + Tambah Produk
    </a>


    <?php if (empty($products)): ?>

        <div class="produk">

            <p>
                Belum ada produk.
            </p>

        </div>

    <?php endif; ?>


    <?php foreach ($products as $product): ?>

        <div class="produk">

            <h2>
                <?= htmlspecialchars(
                    $product["nama"],
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>
            </h2>

            <p>

                <strong>Kategori:</strong>

                <?= htmlspecialchars(
                    $product["kategori"],
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>

            </p>

            <p>

                <strong>Harga:</strong>

                Rp<?= number_format(
                    $product["harga"],
                    0,
                    ',',
                    '.'
                ) ?>

            </p>

            <p>

                <strong>Stok:</strong>

                <?= htmlspecialchars(
                    $product["stok"],
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>

            </p>


            <a
                href="edit.php?id=<?= $product["id"] ?>"
                class="edit"
            >
                Edit
            </a>


            <form
                action="delete.php"
                method="POST"
                style="display: inline;"
            >

                <input
                    type="hidden"
                    name="id"
                    value="<?= $product["id"] ?>"
                >

                <button
                    type="submit"
                    class="hapus"
                    onclick="return confirm('Yakin ingin menghapus produk ini?')"
                >
                    Hapus
                </button>

            </form>

        </div>

    <?php endforeach; ?>

</div>

</body>

</html>