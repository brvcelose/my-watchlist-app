<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];

$tmdb_id = $_GET['tmdb_id'] ?? '';
$type = $_GET['type'] ?? 'movie';
$title = urldecode($_GET['title'] ?? '');
$poster = urldecode($_GET['poster'] ?? '');
$year = $_GET['year'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = getDB();

    // ADICIONAR NOVO
    try {
        $sql = "INSERT INTO watchlist (user_id, tmdb_id, type, title, poster_path, status, rating) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
                $user_id,
                $_POST['tmdb_id'],
                $_POST['type'],
                $_POST['title'],
                $_POST['poster_path'],
                $_POST['status'],
                $_POST['rating'] ?: null
        ]);

        $_SESSION['success'] = htmlspecialchars($_POST['title']) . ' adicionado(a)!';
        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION['error'] = "Este item já está na sua lista!";
        } else {
            $_SESSION['error'] = "Erro ao adicionar: " . $e->getMessage();
        }
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar à WatchList</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        i {
            margin-right: 8px;
            opacity: 0.9;
        }

        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 30px;
            max-width: 500px;
            width: 100%;
        }

        .cover-img{
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
        }

        input[type="text"], select, input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: inherit;
        }

        input[type="text"]:focus, select:focus, input[type="number"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .poster-preview {
            height: 349px; border-radius: 10px; margin-bottom: 20px;
        }

        .form-buttons {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        button {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            transition: 0.3s;
        }

        .btn-save {
            background: #667eea;
            color: white;
        }

        .btn-save:hover {
            background: #764ba2;
        }

        .btn-cancel {
            background: #e74c3c;
            color: white;
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            transition: 0.3s;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-cancel:hover {
            background: #c0392b;
        }

        .item-info {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .item-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .item-meta {
            color: #666;
            font-size: 13px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1><i class="fa-solid fa-plus" style="color: #764ba2"></i>Adicionar à WatchList</h1>

    <div class="cover-img">
        <?php if (!empty($poster) && strpos($poster, 'http') === 0): ?>
            <img src="<?php echo htmlspecialchars($poster); ?>" alt="<?php echo htmlspecialchars($title); ?>"
                 class="poster-preview">
        <?php endif; ?>
    </div>

    <div class="item-info">
        <div class="item-title"><?php echo htmlspecialchars($title); ?></div>
        <div class="item-meta">
            <?php echo($type == 'movie' ? '<i class="fa-solid fa-clapperboard"></i>Filme' : '<i class="fa-solid fa-tv"></i>Série'); ?> • <?php echo htmlspecialchars($year); ?>
        </div>
    </div>

    <form method="POST">
        <input type="hidden" name="tmdb_id" value="<?php echo htmlspecialchars($tmdb_id); ?>">
        <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
        <input type="hidden" name="title" value="<?php echo htmlspecialchars($title); ?>">
        <input type="hidden" name="poster_path" value="<?php echo htmlspecialchars($poster); ?>">

        <div class="form-group">
            <label for="status">Status:</label>
            <select name="status" id="status" required>
                <option value="">Selecione um status...</option>
                <option value="nao_assistido">Não assistido</option>
                <option value="assistindo" selected>Assistindo</option>
                <option value="assistido">Assistido</option>
                <option value="pretendo_assistir">Pretendo assistir</option>
            </select>
        </div>

        <div class="form-group">
            <label for="rating">Rating (1-5 estrelas):</label>
            <select name="rating" id="rating">
                <option value="">Sem avaliação</option>
                <option value="1">⭐ (1 estrela)</option>
                <option value="2">⭐⭐ (2 estrelas)</option>
                <option value="3">⭐⭐⭐ (3 estrelas)</option>
                <option value="4">⭐⭐⭐⭐ (4 estrelas)</option>
                <option value="5">⭐⭐⭐⭐⭐ (5 estrelas)</option>
            </select>
        </div>

        <div class="form-buttons">
            <button type="submit" class="btn-save"><i class="fa-regular fa-circle-check"></i>Adicionar</button>
            <a href="index.php" class="btn-cancel"><i class="fa-regular fa-circle-xmark"></i>Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>