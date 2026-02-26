<?php
//API TMDB
define('TMDB_API_KEY', 'COLOQUE_SUA_API_KEY_AQUI');
define('POSTER_BASE_URL', 'https://image.tmdb.org/t/p/w200');

// Configurações do Banco de Dados
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'watchlist_db');

session_start();

function getDB() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Retorna arrays associativos por padrão
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (PDOException $e) {
        die("Erro de conexão com o banco de dados: " . $e->getMessage());
    }
}

function tmdbRequest($endpoint) {
    $url = "https://api.themoviedb.org/3{$endpoint}&api_key=" . TMDB_API_KEY . "&language=pt-BR";
    $data = @file_get_contents($url);
    return $data ? json_decode($data, true) : ['results' => []];
}
?>

