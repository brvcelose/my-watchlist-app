<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];

$id = $_GET['id'] ?? '';
$pdo = getDB();

// Buscar o item no banco
$stmt = $pdo->prepare("SELECT * FROM watchlist WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
    $_SESSION['error'] = 'Item não encontrado';
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $pdo->prepare("UPDATE watchlist SET status = ?, rating = ? WHERE id = ?");
        $stmt->execute([
                $_POST['status'],
                $_POST['rating'] ?: null,
                $id
        ]);

        $_SESSION['success'] = htmlspecialchars($item['title']) . ' atualizado!';
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Erro ao atualizar: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar - MyWatchList</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Arial;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        i {
            margin-right: 8px;
            opacity: 0.9;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            text-align: center;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        select, input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
        }

        button {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 10px;
            font-size: 18px;
            cursor: pointer;
            width: 100%;
            margin-top: 15px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1><i class="fa-solid fa-pen-to-square" style="color: #764ba2"></i>Editar <?php echo htmlspecialchars($item['title']); ?>
    </h1>

    <form method="POST">
        <?php if (!empty($item['poster_path'])): ?>
            <img src="<?php echo htmlspecialchars($item['poster_path']); ?>"
                 style="height: 349px; border-radius: 10px; margin-bottom: 20px;">
        <p>
        <?php endif; ?>

        <label>Status:</label>
        <select name="status" required>
            <option value="nao_assistido" <?php echo $item['status'] == 'nao_assistido' ? 'selected' : ''; ?>>Não
                assistido
            </option>
            <option value="assistindo" <?php echo $item['status'] == 'assistindo' ? 'selected' : ''; ?>>Assistindo
            </option>
            <option value="assistido" <?php echo $item['status'] == 'assistido' ? 'selected' : ''; ?>>Assistido
            </option>
            <option value="pretendo_assistir" <?php echo $item['status'] == 'pretendo_assistir' ? 'selected' : ''; ?>>Pretendo assistir
            </option>
        </select>

        <label>Avaliação:</label>
        <select name="rating">
            <option value="">⭐ Sem nota</option>
            <option value="1" <?php echo ($item['rating'] ?? '') == '1' ? 'selected' : ''; ?>>⭐ 1</option>
            <option value="2" <?php echo ($item['rating'] ?? '') == '2' ? 'selected' : ''; ?>>⭐⭐ 2</option>
            <option value="3" <?php echo ($item['rating'] ?? '') == '3' ? 'selected' : ''; ?>>⭐⭐⭐ 3</option>
            <option value="4" <?php echo ($item['rating'] ?? '') == '4' ? 'selected' : ''; ?>>⭐⭐⭐⭐ 4</option>
            <option value="5" <?php echo ($item['rating'] ?? '') == '5' ? 'selected' : ''; ?>>⭐⭐⭐⭐⭐ 5</option>
        </select>

        <button type="submit"><i class="fa-regular fa-floppy-disk"></i>Salvar Alterações</button>
        <a href="index.php"
           style="display: block; text-align: center; margin-top: 15px; color: #667eea; text-decoration: none;"><i class="fa-solid fa-arrow-left"></i>Voltar</a>
    </form>
</div>
</body>
</html>