<?php require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyWatchList - Filmes e Séries</title>
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
            padding: 20px;
        }

        i {
            margin-right: 8px;
            opacity: 0.9;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 0 30px;
            border-radius: 12px;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .user-info i {
            font-size: 1.2rem;
        }

        .btn-logout {
            background: #e74c3c;
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 0.9rem;
            font-weight: bold;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-logout:hover {
            background: #c0392b;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }

        .search-section {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .search-form {
            display: grid;
            grid-template-columns: 1fr 150px 100px;
            gap: 10px;
            margin-bottom: 10px;
        }

        input[type="text"], select {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        button:hover {
            background: #764ba2;
        }

        .results-section {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            display: none;
        }

        .results-section.active {
            display: block;
        }

        .results-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
        }

        .result-item {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .result-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .result-poster {
            width: 100%;
            height: 200px;
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #999;
        }

        .result-poster img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .result-info {
            padding: 10px;
        }

        .result-title {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 5px;
            color: #333;
        }

        .result-type {
            font-size: 12px;
            color: #999;
            margin-bottom: 8px;
        }

        .result-btn {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            font-size: 12px;
            background: #667eea;
            text-decoration: none;
            color: #fff;
        }

        .watchlist-section h2 {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }

        .watchlist-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .watchlist-table th {
            background: #667eea;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: bold;
        }

        .watchlist-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .watchlist-table tr:hover {
            background: #f9f9f9;
        }

        .poster-thumb {
            width: 40px;
            height: 60px;
            object-fit: cover;
            border-radius: 3px;
        }

        .no-poster {
            width: 40px;
            height: 60px;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #999;
            border-radius: 3px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .actions a {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            text-decoration: none;
            color: white;
            background: #667eea;
            transition: 0.3s;
        }

        .actions a:hover {
            background: #764ba2;
        }

        .actions a.delete {
            background: #e74c3c;
        }

        .actions a.delete:hover {
            background: #c0392b;
        }

        .empty {
            text-align: center;
            color: #999;
            padding: 40px;
        }

        .error {
            background: #ffebee;
            color: #c62828;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .success {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        @media (max-width: 600px) {
            .search-form {
                grid-template-columns: 1fr;
            }

            .watchlist-table {
                font-size: 13px;
            }

            .watchlist-table th, .watchlist-table td {
                padding: 10px;
            }

            .results-list {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }
        }
    </style>
</head>
<body>
<div class="container">

    <div class="navbar">
        <div class="user-info">
            <i class="fa-solid fa-circle-user"></i>
            <span>Olá, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
        </div>

        <a href="logout.php" class="btn-logout" onclick="return confirm('Deseja realmente sair?')">
            <i class="fa-solid fa-right-from-bracket"></i> Sair
        </a>
    </div>

    <h1><i class="fa-solid fa-clapperboard" style="color: #764ba2"></i>MyWatchList</h1>
    <p class="subtitle">Organize seus filmes e séries favoritas</p>

    <!-- Mensagens de feedback -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="success"><?php echo $_SESSION['success'];
            unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error"><?php echo $_SESSION['error'];
            unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <!-- Seção de Busca -->
    <div class="search-section">
        <form method="GET" class="search-form">
            <input type="text" name="q" placeholder="Buscar filme ou série..."
                   value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
            <select name="type">
                <option value="multi" <?php echo ($_GET['type'] ?? '') == 'multi' ? 'selected' : ''; ?>>Tudo</option>
                <option value="movie" <?php echo ($_GET['type'] ?? '') == 'movie' ? 'selected' : ''; ?>>Filme</option>
                <option value="tv" <?php echo ($_GET['type'] ?? '') == 'tv' ? 'selected' : ''; ?>>Série</option>
            </select>
            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </div>

    <!-- Resultados da Busca -->
    <?php if (isset($_GET['q']) && !empty($_GET['q'])):
        $query = urlencode($_GET['q']);
        $type = $_GET['type'] ?? 'multi';
        $data = tmdbRequest("/search/$type?query=$query");
        $results = $data['results'] ?? [];
        ?>
        <div class="results-section active">
            <h3>Resultados da Busca (<?php echo count($results); ?> encontrados)</h3>
            <?php if (empty($results)): ?>
                <div class="empty">Nenhum resultado encontrado</div>
            <?php else: ?>
                <div class="results-list">
                    <?php foreach (array_slice($results, 0, 20) as $item):
                        $tmdbId = $item['id'];
                        $mediaType = $item['media_type'] ?? (isset($item['title']) ? 'movie' : (isset($item['name']) ? 'tv' : ($_GET['type'] ?? 'movie')));
                        $isMovie = ($mediaType === 'movie');
                        $title = $item['title'] ?? $item['name'] ?? 'Sem título';
                        $poster = isset($item['poster_path']) && $item['poster_path'] ? POSTER_BASE_URL . $item['poster_path'] : null;
                        $year = substr($item['release_date'] ?? $item['first_air_date'] ?? '0000', 0, 4);
                        ?>
                        <div class="result-item">
                            <div class="result-poster">
                                <?php if ($poster): ?>
                                    <img src="<?php echo $poster; ?>" alt="<?php echo htmlspecialchars($title); ?>">
                                <?php else: ?>
                                    Sem poster
                                <?php endif; ?>
                            </div>
                            <div class="result-info">
                                <div class="result-title"><?php echo htmlspecialchars(substr($title, 0, 30)); ?></div>
                                <div class="result-type"><?php echo($isMovie ? 'Filme' : 'Série'); ?>
                                    • <?php echo $year; ?></div>
                                <a href="add.php?tmdb_id=<?php echo $tmdbId; ?>&type=<?php echo($isMovie ? 'movie' : 'tv'); ?>&title=<?php echo urlencode($title); ?>&poster=<?php echo urlencode($poster ?? ''); ?>&year=<?php echo $year; ?>"
                                   class="result-btn">Adicionar<i class="fa-solid fa-plus" style="margin: 0 0 0 6px"></i></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Minha WatchList -->
    <div class="watchlist-section">
        <h2><i class="fa-solid fa-clipboard-list" style="color: #667eea;"></i>Minha WatchList</h2><?php
        $pdo = getDB();
        $items = $pdo->prepare("SELECT * FROM watchlist WHERE user_id = ? ORDER BY added_at DESC");
        $items->execute([$user_id]);
        $items = $items->fetchAll();

        if (empty($items)): ?>
            <div class="empty">Sua watchlist está vazia. Comece adicionando filmes ou séries!</div>
        <?php else: ?>
            <table class="watchlist-table">
                <thead>
                <tr>
                    <th style="width: 50px;">Poster</th>
                    <th style="width: 35%;">Título</th>
                    <th style="width: 20%;">Status</th>
                    <th style="width: 15%;">Rating</th>
                    <th style="width: 30%;">Ações</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $row): ?>
                    <tr>
                        <td>
                            <?php if ($row['poster_path'] && strpos($row['poster_path'], 'http') === 0): ?>
                                <img src="<?php echo htmlspecialchars($row['poster_path']); ?>"
                                     alt="<?php echo htmlspecialchars($row['title']); ?>" class="poster-thumb">
                            <?php else: ?>
                                <div class="no-poster">N/A</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
                            <small style="color: #999;"><?php echo ucfirst($row['type']); ?></small>
                        </td>
                        <td>
                                <span class="status-badge status-<?php echo $row['status']; ?>">
                                    <?php
                                    $statusMap = [
                                            'nao_assistido' => '<i class="fa-regular fa-circle" style="color: #e74c3c;"></i> Não assistido',
                                            'assistindo' => '<i class="fa-solid fa-circle-play" style="color: #667eea;"></i> Assistindo',
                                            'assistido' => '<i class="fa-solid fa-circle-check" style="color: #2e7d32;"></i> Assistido',
                                            'pretendo_assistir' => '<i class="fa-regular fa-clock" style="color: #f1c40f;"></i> Pretendo'
                                    ];
                                    echo $statusMap[$row['status']] ?? $row['status'];
                                    ?>
                                </span>
                        </td>
                        <td>
                            <?php if ($row['rating']) {
                                for ($i = 1; $i <= 5; $i++) {
                                    echo $i <= $row['rating']
                                            ? '<i class="fa-solid fa-star" style="color: #f1c40f; margin:0;"></i>'
                                            : '<i class="fa-regular fa-star" style="color: #ddd; margin:0;"></i>';
                                }
                            } ?>

                        </td>
                        <td class="actions">
                            <a href="edit.php?id=<?php echo $row['id']; ?>"><i class="fa-solid fa-pen-to-square"></i>Editar</a>
                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="delete"
                               onclick="return confirm('Tem certeza que deseja remover?');"><i class="fa-regular fa-circle-xmark"></i>Remover</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
