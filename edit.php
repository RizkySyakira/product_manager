<?php

require_once "config/database.php";

$errors = [];

// Ambil ID dari URL
$id = filter_input(
    INPUT_GET,
    "id",
    FILTER_VALIDATE_INT
);

if (!$id) {
    die("ID produk tidak valid.");
}


// Ambil data produk berdasarkan ID
$stmt = $pdo->prepare(
    "SELECT * FROM products WHERE id = ?"
);

$stmt->execute([$id]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);


// Jika produk tidak ditemukan
if (!$product) {
    die("Produk tidak ditemukan.");
}


// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Cek CSRF Token
    if (
        !isset($_POST["csrf_token"]) ||
        !hash_equals(
            $_SESSION["csrf_token"],
            $_POST["csrf_token"]
        )
    ) {
        die("Token keamanan tidak valid.");
    }


    // Ambil data dari form
    $nama = trim($_POST["nama"]);
    $kategori = trim($_POST["kategori"]);

    $harga = filter_var(
        $_POST["harga"],
        FILTER_VALIDATE_FLOAT
    );

    $stok = filter_var(
        $_POST["stok"],
        FILTER_VALIDATE_INT
    );


    // =========================
    // VALIDASI
    // =========================

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
            "SELECT id
             FROM products
             WHERE nama = ?
             AND id != ?"
        );

        $stmt->execute([
            $nama,
            $id
        ]);


        if ($stmt->fetch()) {

            $errors["nama"] =
                "Nama produk sudah digunakan.";

        }

    }


    // =========================
    // UPDATE DATA
    // =========================

    if (empty($errors)) {

        $stmt = $pdo->prepare(
            "UPDATE products
             SET nama = ?,
                 kategori = ?,
                 harga = ?,
                 stok = ?
             WHERE id = ?"
        );


        $stmt->execute([
            $nama,
            $kategori,
            $harga,
            $stok,
            $id
        ]);


        // Kembali ke halaman utama
        header("Location: index.php");
        exit;

    }


    // Jika ada error,
    // tampilkan kembali input yang sudah dimasukkan

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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit Produk</title>


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

            max-width: 550px;

            margin: auto;

        }


        .form-box {

            background-color: white;

            padding: 30px;

            border-radius: 12px;

            box-shadow:
                0 3px 10px
                rgba(0, 0, 0, 0.08);

        }


        h1 {

            text-align: center;

            color: #3157a4;

            margin-top: 0;

            margin-bottom: 8px;

        }


        .subtitle {

            text-align: center;

            color: #666;

            margin-bottom: 25px;

        }


        label {

            display: block;

            font-weight: bold;

            margin-bottom: 6px;

            color: #444;

        }


        input {

            width: 100%;

            padding: 11px;

            border: 1px solid #ccc;

            border-radius: 6px;

            font-size: 14px;

            margin-bottom: 5px;

        }


        input:focus {

            outline: none;

            border-color: #3157a4;

        }


        .form-group {

            margin-bottom: 18px;

        }


        .error {

            color: #d62828;

            font-size: 13px;

            margin-top: 5px;

        }


        .button-group {

            margin-top: 25px;

            display: flex;

            gap: 10px;

        }


        .simpan {

            flex: 1;

            background-color: #3157a4;

            color: white;

            border: none;

            padding: 11px;

            border-radius: 6px;

            cursor: pointer;

            font-size: 14px;

        }


        .simpan:hover {

            background-color: #1f4080;

        }


        .kembali {

            flex: 1;

            background-color: #777;

            color: white;

            text-decoration: none;

            padding: 11px;

            border-radius: 6px;

            text-align: center;

            font-size: 14px;

        }


        .kembali:hover {

            background-color: #555;

        }


        .footer {

            text-align: center;

            margin-top: 20px;

            color: #777;

            font-size: 13px;

        }


        /* Responsive */

        @media (max-width: 600px) {

            body {

                padding: 20px 10px;

            }


            .form-box {

                padding: 20px;

            }


            .button-group {

                flex-direction: column;

            }

        }

    </style>

</head>


<body>


<div class="container">


    <div class="form-box">


        <h1>Edit Produk</h1>


        <p class="subtitle">
            Ubah informasi produk
        </p>


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


            <!-- Nama -->

            <div class="form-group">

                <label for="nama">
                    Nama Produk
                </label>

                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="<?= htmlspecialchars(
                        $product["nama"],
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>"
                    required
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

            </div>


            <!-- Kategori -->

            <div class="form-group">

                <label for="kategori">
                    Kategori
                </label>

                <input
                    type="text"
                    id="kategori"
                    name="kategori"
                    value="<?= htmlspecialchars(
                        $product["kategori"],
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>"
                    required
                >

            </div>


            <!-- Harga -->

            <div class="form-group">

                <label for="harga">
                    Harga
                </label>

                <input
                    type="number"
                    id="harga"
                    name="harga"
                    value="<?= htmlspecialchars(
                        $product["harga"],
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>"
                    min="1"
                    required
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

            </div>


            <!-- Stok -->

            <div class="form-group">

                <label for="stok">
                    Stok
                </label>

                <input
                    type="number"
                    id="stok"
                    name="stok"
                    value="<?= htmlspecialchars(
                        $product["stok"],
                        ENT_QUOTES,
                        "UTF-8"
                    ) ?>"
                    min="0"
                    required
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

            </div>


            <!-- Tombol -->

            <div class="button-group">

                <button
                    type="submit"
                    class="simpan"
                >
                    Simpan Perubahan
                </button>


                <a
                    href="index.php"
                    class="kembali"
                >
                    Kembali
                </a>

            </div>


        </form>


    </div>


    <div class="footer">

        Sistem Informasi Data Produk

    </div>


</div>


</body>

</html>