<?php
session_start();
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validasi CSRF Token
    $csrf_token = $_POST["csrf"] ?? "";
    if (!hash_equals($_SESSION["csrf"] ?? "", $csrf_token)) {
        http_response_code(403);
        exit("Akses ditolak: CSRF Token tidak valid!");
    }

    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute(["id" => $id]);
    }

    header("Location: index.php?status=deleted");
    exit;
} else {
    // Tolak request jika bukan method POST
    http_response_code(405);
    exit("Method Not Allowed");
}