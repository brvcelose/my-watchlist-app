<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];

$id = $_GET['id'] ?? '';

if ($id) {
    try {
        $pdo = getDB();
        $stmt = $pdo->prepare("DELETE FROM watchlist WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['success'] = 'Item removido!';
        } else {
            $_SESSION['error'] = 'Item não encontrado no banco';
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Erro ao remover: ' . $e->getMessage();
    }
} else {
    $_SESSION['error'] = 'ID inválido';
}

header('Location: index.php');
exit;
?>