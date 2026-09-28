<?php
session_start();
require_once '../config/db.php';

// Generate Token CSRF untuk form hapus jika belum ada
$_SESSION["csrf"] ??= bin2hex(random_bytes(32));

// Filter/Search via GET (Fitur Bonus)
$q = trim($_GET["q"] ?? "");

if ($q !== "") {
    $sql = "SELECT * FROM products WHERE name LIKE :q OR category LIKE :q ORDER BY id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["q" => "%$q%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
}
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Manager</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Daftar Produk</h1>
            <a href="create.php" class="btn btn-primary">+ Tambah Produk</a>
        </header>

        <!-- Form Pencarian (GET) -->
        <form method="GET" action="index.php" class="search-form" style="margin-bottom: 20px;">
            <input type="text" name="q" placeholder="Cari nama/kategori..." value="<?= htmlspecialchars($q, ENT_QUOTES, "UTF-8") ?>">
            <button type="submit" class="btn btn-secondary">Cari</button>
            <?php if ($q !== ""): ?>
                <a href="index.php" class="btn btn-secondary">Reset</a>
            <?php endif; ?>
        </form>

        <!-- Pesan Status PRG -->
        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] === 'created'): ?>
                <div class="alert alert-success">Produk berhasil ditambahkan!</div>
            <?php elseif ($_GET['status'] === 'updated'): ?>
                <div class="alert alert-success">Produk berhasil diperbarui!</div>
            <?php elseif ($_GET['status'] === 'deleted'): ?>
                <div class="alert alert-success">Produk berhasil dihapus!</div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- List Produk dengan Flexbox -->
        <div class="products">
            <?php if (empty($products)): ?>
                <p>Tidak ada produk ditemukan.</p>
            <?php else: ?>
                <?php foreach ($products as $p): ?>
                    <div class="card">
                        <div class="card-body">
                            <h3><?= htmlspecialchars($p["name"], ENT_QUOTES, "UTF-8") ?></h3>
                            <span class="badge"><?= htmlspecialchars($p["category"], ENT_QUOTES, "UTF-8") ?></span>
                            <div class="price">Rp <?= number_format($p["price"], 0, ",", ".") ?></div>
                            <div class="stock">Stok: <?= (int)$p["stock"] ?></div>
                        </div>
                        <div class="actions">
                            <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-warning">Edit</a>
                            
                            <!-- Form Delete aman dengan CSRF & Method POST -->
                            <form method="POST" action="delete.php" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                <input type="hidden" name="id" value="<?= $p["id"] ?>">
                                <input type="hidden" name="csrf" value="<?= $_SESSION["csrf"] ?>">
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>