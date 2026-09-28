<?php
session_start();
require_once '../config/db.php';

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index.php");
    exit;
}

// Ambil data lama
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute(["id" => $id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: index.php");
    exit;
}

$errors = [];
$name = $product["name"];
$category = $product["category"];
$price = $product["price"];
$stock = $product["stock"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $category = trim($_POST["category"] ?? "") ?: "Umum";
    $price = filter_input(INPUT_POST, "price", FILTER_VALIDATE_FLOAT);
    $stock = filter_input(INPUT_POST, "stock", FILTER_VALIDATE_INT);

    if (mb_strlen($name) < 3) {
        $errors["name"] = "Nama produk minimal 3 karakter.";
    } else {
        // Cek Nama Unik selain milik ID saat ini
        $stmtCek = $pdo->prepare("SELECT id FROM products WHERE name = :name AND id != :id");
        $stmtCek->execute(["name" => $name, "id" => $id]);
        if ($stmtCek->fetch()) {
            $errors["name"] = "Nama produk sudah digunakan oleh data lain.";
        }
    }

    if ($price === false || $price <= 0) {
        $errors["price"] = "Harga harus angka lebih besar dari 0.";
    }

    if ($stock === false || $stock < 0) {
        $errors["stock"] = "Stok tidak boleh negatif.";
    }

    if (empty($errors)) {
        $stmtUpdate = $pdo->prepare("UPDATE products SET name = :name, category = :category, price = :price, stock = :stock WHERE id = :id");
        $stmtUpdate->execute([
            "name" => $name,
            "category" => $category,
            "price" => $price,
            "stock" => $stock,
            "id" => $id
        ]);

        header("Location: index.php?status=updated");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <div class="form-card">
            <h2>Edit Produk</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errors as $err): ?>
                            <li><?= htmlspecialchars($err, ENT_QUOTES, "UTF-8") ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="edit.php?id=<?= $id ?>" method="POST">
                <div class="form-group">
                    <label for="name">Nama Produk</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($name, ENT_QUOTES, "UTF-8") ?>" required minlength="3">
                </div>

                <div class="form-group">
                    <label for="category">Kategori</label>
                    <input type="text" id="category" name="category" value="<?= htmlspecialchars($category, ENT_QUOTES, "UTF-8") ?>">
                </div>

                <div class="form-group">
                    <label for="price">Harga (Rp)</label>
                    <input type="number" id="price" name="price" step="0.01" value="<?= htmlspecialchars((string)$price, ENT_QUOTES, "UTF-8") ?>" required min="1">
                </div>

                <div class="form-group">
                    <label for="stock">Stok</label>
                    <input type="number" id="stock" name="stock" value="<?= htmlspecialchars((string)$stock, ENT_QUOTES, "UTF-8") ?>" required min="0">
                </div>

                <button type="submit" class="btn btn-warning">Update Produk</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>