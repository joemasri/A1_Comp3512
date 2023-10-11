<?php
// Establish a database connection
try {
    $db = new PDO('sqlite:./data/music.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Function for displaying top genres
function displayTopGenres($db) {
    $query = "
        SELECT g.genre_name, COUNT(s.genre_id) AS genre_count
        FROM genres AS g
        INNER JOIN songs AS s ON g.genre_id = s.genre_id
        GROUP BY g.genre_id, g.genre_name
        ORDER BY genre_count DESC, g.genre_name
        LIMIT 10
    ";

    $stmt = $db->query($query);

    echo '<ul>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>{$row['genre_name']} ({$row['genre_count']} songs)</li>";
    }
    echo '</ul>';
}

// Function for displaying top artists
function displayTopArtists($db) {
    $query = "
        SELECT a.artist_name, COUNT(s.artist_id) AS song_count
        FROM artists AS a
        INNER JOIN songs AS s ON a.artist_id = s.artist_id
        GROUP BY a.artist_id, a.artist_name
        ORDER BY song_count DESC, a.artist_name
        LIMIT 10
    ";

    $stmt = $db->query($query);

    echo '<ul>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>{$row['artist_name']} ({$row['song_count']} songs)</li>";
    }
    echo '</ul>';
}

// Function for displaying top artists
function displayMostPopularSongs($db) {
    $query = "
        SELECT s.song_id, s.title, s.popularity, a.artist_id, a.artist_name
        FROM songs AS s
        INNER JOIN artists AS a ON s.artist_id = a.artist_id
        GROUP BY s.song_id, s.title, s.popularity, a.artist_id, a.artist_name
        ORDER BY s.popularity DESC, a.artist_name
        LIMIT 10
    ";

    $stmt = $db->query($query);

    echo '<ul>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $formattedOutput = "{$row['artist_name']} - {$row['title']}: Score {$row['popularity']}";
        echo "<li>{$formattedOutput}</li>";
    }
    echo '</ul>';
}

function displayOneHitWonders($db) {
    $query = "
        SELECT s.song_id, s.title, s.popularity, a.artist_id, a.artist_name
        FROM songs AS s
        INNER JOIN artists AS a ON s.artist_id = a.artist_id
        WHERE a.artist_id IN (
            SELECT artist_id
            FROM songs
            GROUP BY artist_id
            HAVING COUNT(*) = 1
        )
        GROUP BY s.song_id, s.title, s.popularity, a.artist_id, a.artist_name
        ORDER BY s.popularity DESC, a.artist_name
        LIMIT 10
    ";

    $stmt = $db->query($query);

    echo '<ul>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $formattedOutput = "{$row['artist_name']} - {$row['title']}: Score {$row['popularity']}";
        echo "<li>{$formattedOutput}</li>";
    }
    echo '</ul>';
}

?>

<!-- Home page -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>COMP 3512 Assign1</title>
    <link rel="stylesheet" type="text/css" href="HomeStyles.css">
</head>
<body>
    <header>
        <h1>Header</h1>
        <h4>Zee El Masri, Andrew Yu</h4>
    </header>

    <!-- Header Items -->
    <nav>
        <ul>
            <li><a href="Home.php">Home</a></li>
            <li><a href="Browse.php">Browse</a></li>
            <li><a href="Search.php">Search</a></li>
            <li><a href="#">About Us</a></li>
        </ul>
    </nav>

    <section>
    <h1>Home Page</h1>
    <p></p>
    <div class="box">
        <h2>Top Genres</h2>
        <?php displayTopGenres($db); ?>
    </div>
    <div class="box">
        <h2>Top Artists</h2>
        <?php displayTopArtists($db); ?>
    </div>
    <div class="box">
        <h2>Most Popular Songs</h2>
        <?php displayMostPopularSongs($db); ?>
    </div>
    <div class="box">
        <h2>One Hit Wonders</h2>
        <?php displayOneHitWonders($db); ?>
    </div>
    <div class="box">
        <h2>Longest Acoustic Songs</h2>
    </div>
    <div class="box">
        <h2>At The Club</h2>
    </div>
    <div class="box">
        <h2>Running Songs</h2>
    </div>
    <div class="box">
        <h2>Studying</h2>
    </div>
    </section>

    <footer>
        <p>Course: COMP 3512</p>
        <p>&copy; Zee El-Masri, Andrew Yu</p>
        <p>
            <a class="footer-link" href="https://github.com/joemasri/A1_Comp3512.git">GitHub Repo</a>
            <a class="footer-link" href="https://github.com/joemasri">Group Member 1</a>
            <a class="footer-link" href="https://github.com/ayu381">Group Member 2</a>
        </p>
    </footer>
</body>
</html>