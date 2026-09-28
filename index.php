<?php

require_once "config/database.php";

// Ambil semua data produk
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hitung total nilai stok
$totalNilaiStok = 0;

foreach ($products as $product) {
    $totalNilaiStok += $product["harga"] * $product["stok"];
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

    <title>Sistem Informasi Data Produk</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f2f6ff;
            margin: 0;
            padding: 40px 20px;
            color: #333;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        h1 {
            text-align: center;
            color: #3157a4;
            margin-bottom: 8px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }

        /* Total Nilai Stok */

        .total-box {
            background-color: #dce9ff;
            border-left: 6px solid #3157a4;
            padding: 18px 25px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .total-box h3 {
            margin: 0 0 8px 0;
            color: #3157a4;
        }

        .total {
            font-size: 25px;
            font-weight: bold;
            color: #1f4080;
        }

        /* Tombol Tambah */

        .tambah {
            display: inline-block;
            background-color: #3157a4;
            color: white;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .tambah:hover {
            background-color: #1f4080;
        }

        /* Tabel */

        .table-box {
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 750px;
        }

        th {
            background-color: #8B0000;
            color: white;
            padding: 13px;
            text-align: left;
        }

        td {
            padding: 13px;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background-color: #f1f5ff;
        }

        /* Kategori */

        .kategori {
            color: #3157a4;
            font-weight: bold;
        }

        /* Stok */

        .stok {
            font-weight: bold;
        }

        .stok-kritis {
            background-color: #ffe5e5;
        }

        .peringatan {
            color: #d62828;
            font-size: 12px;
            font-weight: bold;
            margin-left: 5px;
        }

        /* Tombol Edit */

        .edit {
            display: inline-block;
            background-color: #3157a4;
            color: white;
            text-decoration: none;
            padding: 7px 10px;
            border-radius: 5px;
            margin-right: 5px;
        }

        .edit:hover {
            background-color: #1f4080;
        }

        /* Tombol Hapus */

        .hapus {
            background-color: #d62828;
            color: white;
            border: none;
            padding: 7px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .hapus:hover {
            background-color: #b51f1f;
        }

        /* Footer */

        .footer {
            text-align: center;
            margin-top: 25px;
            color: #777;
            font-size: 14px;
        }

        /* Responsive */

        @media (max-width: 600px) {

            body {
                padding: 20px 10px;
            }

            h1 {
                font-size: 24px;
            }

            .subtitle {
                font-size: 14px;
            }

            .total-box {
                padding: 15px;
            }

            .total {
                font-size: 21px;
            }

            .table-box {
                padding: 10px;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <h1>Sistem Informasi Data Produk</h1>

    <p class="subtitle">
        Informasi Data Produk
    </p>


    <!-- Total Nilai Stok -->

    <div class="total-box">

        <h3>Total Nilai Stok</h3>

        <div class="total">

            Rp<?= number_format(
                $totalNilaiStok,
                0,
                ',',
                '.'
            ) ?>

        </div>

    </div>


    <!-- Tombol Tambah -->

    <a href="add.php" class="tambah">
        + Tambah Produk
    </a>


    <!-- Tabel Produk -->

    <div class="table-box">

        <table>

            <tr>

                <th>ID</th>

                <th>Nama</th>

                <th>Kategori</th>

                <th>Harga</th>

                <th>Stok</th>

                <th>Aksi</th>

            </tr>


            <?php foreach ($products as $product): ?>

                <tr
                    class="<?= $product["stok"] < 3
                        ? 'stok-kritis'
                        : '' ?>"
                >

                    <!-- ID -->

                    <td>
                        <?= $product["id"] ?>
                    </td>


                    <!-- Nama -->

                    <td>

                        <?= htmlspecialchars(
                            $product["nama"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>

                    </td>


                    <!-- Kategori -->

                    <td class="kategori">

                        <?= htmlspecialchars(
                            $product["kategori"],
                            ENT_QUOTES,
                            "UTF-8"
                        ) ?>

                    </td>


                    <!-- Harga -->

                    <td>

                        Rp<?= number_format(
                            $product["harga"],
                            0,
                            ',',
                            '.'
                        ) ?>

                    </td>


                    <!-- Stok -->

                    <td class="stok">

                        <?= $product["stok"] ?>

                        <?php if ($product["stok"] < 3): ?>

                            <span class="peringatan">
                                Stok Kritis
                            </span>

                        <?php endif; ?>

                    </td>


                    <!-- Aksi -->

                    <td>

                        <!-- Edit -->

                        <a
                            href="edit.php?id=<?= $product["id"] ?>"
                            class="edit"
                        >
                            Edit
                        </a>


                        <!-- Hapus -->

                        <form
                            action="delete.php"
                            method="POST"
                            style="display: inline;"
                        >

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


                            <!-- ID Produk -->

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

                    </td>

                </tr>

            <?php endforeach; ?>

        </table>

    </div>


    <div class="footer">

        Sistem Informasi Data Produk

    </div>

</div>

</body>

</html>