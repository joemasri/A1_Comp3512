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
        // Makes song title a link
        $songLink = "SingleSong.php?song_id={$row['song_id']}";

        $formattedOutput = "<li><a href='{$songLink}'>{$row['title']}</a> - {$row['artist_name']} - Popularity Score: {$row['popularity']}</li>";
        echo $formattedOutput;
    }
    echo '</ul>';
}

// Function for displaying the most biggest one hit wonders
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
        ORDER BY s.popularity DESC, a.artist_name
        LIMIT 10
    ";

    $stmt = $db->query($query);

    echo '<ul>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Makes song title a link
        $songLink = "SingleSong.php?song_id={$row['song_id']}";

        $formattedOutput = "<li><a href='{$songLink}'>{$row['title']}</a> - {$row['artist_name']} - Popularity Score: {$row['popularity']}</li>";
        echo $formattedOutput;
    }
    echo '</ul>';
}

// Function for displaying the longest acoustic songs
function displayLongestAcousticSongs($db) {
    $query = "
        SELECT s.song_id, s.title, s.duration, s.acousticness, a.artist_name
        FROM songs AS s
        JOIN artists AS a ON s.artist_id = a.artist_id
        WHERE s.acousticness > 40
        ORDER BY s.duration DESC, s.title
        LIMIT 10
    ";

    $stmt = $db->query($query);

    echo '<ul>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Makes song title a link
        $songLink = "SingleSong.php?song_id={$row['song_id']}";

        // Converts seconds to minutes and seconds
        $durationMinutes = floor($row['duration'] / 60);
        $durationSeconds = sprintf("%02d", $row['duration'] % 60);

        $formattedOutput = "<li><a href='{$songLink}'>{$row['title']}</a> - {$row['artist_name']} - Duration: {$durationMinutes}:{$durationSeconds} (Acousticness: {$row['acousticness']}%)</li>";
        echo $formattedOutput;
    }
    echo '</ul>';
}

// Function for displaying the most club-friendly songs
function displayAtTheClub($db) {
    $query = "
        SELECT s.song_id, s.title, s.energy, s.danceability, a.artist_name,
        (s.danceability * 1.6 + s.energy * 1.4) AS club_friendly_score
        FROM songs AS s
        JOIN artists AS a ON s.artist_id = a.artist_id
        WHERE s.danceability > 80
        ORDER BY club_friendly_score DESC
        LIMIT 10
    ";

    $stmt = $db->query($query);

    echo '<ul>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Makes song title a link
        $songLink = "SingleSong.php?song_id={$row['song_id']}";

        $formattedOutput = "<li><a href='{$songLink}'>{$row['title']}</a> - {$row['artist_name']} - Club Friendly Score: {$row['club_friendly_score']}</li>";
        echo $formattedOutput;
    }
    echo '</ul>';
}

// Function for displaying the best songs for running
function displayRunningSongs($db) {
    $query = "
        SELECT s.song_id, s.title, s.energy, s.valence, s.bpm, a.artist_name,
        (s.energy * 1.3 + s.valence * 1.6) AS running_score
        FROM songs AS s
        JOIN artists AS a ON s.artist_id = a.artist_id
        WHERE s.bpm >= 120 AND s.bpm <= 125
        ORDER BY running_score DESC
        LIMIT 10
    ";

    $stmt = $db->query($query);

    echo '<ul>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Makes song title a link
        $songLink = "SingleSong.php?song_id={$row['song_id']}";

        $formattedOutput = "<li><a href='{$songLink}'>{$row['title']}</a> - {$row['artist_name']} - Running Score: {$row['running_score']}</li>";
        echo $formattedOutput;
    }
    echo '</ul>';
}

// Function for displaying the best songs for studying
function displayStudying($db) {
    $query = "
        SELECT s.song_id, s.title, s.bpm, s.acousticness, s.valence, s.speechiness, a.artist_name,
        (s.acousticness * 0.8) + (100 - s.speechiness) + (100 - s.valence) AS studying_score
        FROM songs AS s
        JOIN artists AS a ON s.artist_id = a.artist_id
        WHERE s.bpm >= 100 AND s.bpm <= 115
        AND s.speechiness >= 1 AND s.speechiness <= 20
        ORDER BY studying_score DESC
        LIMIT 10
    ";

    $stmt = $db->query($query);

    echo '<ul>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Makes song title a link
        $songLink = "SingleSong.php?song_id={$row['song_id']}";

        $formattedOutput = "<li><a href='{$songLink}'>{$row['title']}</a> - {$row['artist_name']} - Studying Score: {$row['studying_score']}</li>";
        echo $formattedOutput;
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
    <link rel="stylesheet" type="text/css" href="./css/HomeStyles.css">
</head>
<body>
    <header>
        <h1>COMP 3512 Assign1</h1>
        <h4>Zee El Masri, Andrew Yu</h4>
    </header>

    <!-- Header Items -->
    <nav>
        <ul>
            <li><a href="Home.php">Home</a></li>
            <li><a href="Browse.php">Browse</a></li>
            <li><a href="Search.php">Search</a></li>
            <li><a href="AboutUs.php">About Us</a></li>
        </ul>
    </nav>

    <section>
    <h1>Home Page</h1>
    <p>
        The assignment involves creating a web application for browsing and searching a music database. Using multiple PHP pages that all interact with eachother to create an aesthetically pleasing and functioning program.<br><br>

        Group Members: Zee El-Masri, Andrew Yu <br><br>

        Course Name: Comp 3512 <br>

        GitHub Link: <a href="https://github.com/joemasri/A1_Comp3512">https://github.com/joemasri/A1_Comp3512</a>

    </p>

    <!-- Display boxes with corresponding db information in them -->
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
        <?php displayLongestAcousticSongs($db); ?>
    </div>
    <div class="box">
        <h2>At The Club</h2>
        <?php displayAtTheClub($db); ?>
    </div>
    <div class="box">
        <h2>Running Songs</h2>
        <?php displayRunningSongs($db); ?>
    </div>
    <div class="box">
        <h2>Studying</h2>
        <?php displayStudying($db); ?>
    </div>
    </section>

    <!-- Footer Section -->
    <footer>
        <p>Course: COMP 3512</p>
        <p>&copy; Zee El-Masri, Andrew Yu</p>
        <p>
            <a class="footer-link" href="https://github.com/joemasri/A1_Comp3512.git">GitHub Repo</a>
            <a class="footer-link" href="https://github.com/joemasri">Group Member 1</a>
            <a class="footer-link" href="https://github.com/ayu381">Group Member 2</a>
        </p>
        <p>
            The assignment involves creating a web application for browsing and searching a music database. Using multiple PHP pages that all interact with eachother to create an aesthetically pleasing and functioning program.
        </p>
    </footer>
</body>
</html>