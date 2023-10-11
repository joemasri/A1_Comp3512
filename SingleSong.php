<?php

// Establish a database connection
try {
    $db = new PDO('sqlite:./data/music.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

try {
    $songSQL = 'SELECT * FROM songs WHERE song_id=:song_id';

    // Statement is prepared for songs
    $songStatement = $db->prepare($songSQL);

    // Querystring value is retrieved
    $song_id = filter_input(INPUT_GET, 'song_id', FILTER_VALIDATE_INT);
    $songStatement->bindValue(':song_id', $song_id, PDO::PARAM_INT);

    // Query is executed
    $songStatement->execute();

    // Song info is fetched
    $songData = $songStatement->fetch();

    if ($songData) {
        $artistSQL = 'SELECT * FROM artists WHERE artist_id=:artist_id';

        // Statement is prepared for artists
        $artistStatement = $db->prepare($artistSQL);

        // Querystring value is retrieved
        $artist_id = $songData['artist_id'];
        $artistStatement->bindValue(':artist_id', $artist_id, PDO::PARAM_INT);

        // Query is executed
        $artistStatement->execute();

        // Artist info is fetched
        $artistData = $artistStatement->fetch();

    if ($artistData) {
        $genreSQL = 'SELECT * FROM genres WHERE genre_id=:genre_id';

        // Statement is prepared for genre
        $genreStatement = $db->prepare($genreSQL);

         // Suerystring value is retrieved
        $genre_id = $songData['genre_id'];
        $genreStatement->bindValue(':genre_id', $genre_id, PDO::PARAM_INT);

        // Query is executed
        $genreStatement->execute();

        // Genre info is fetched
        $genreData = $genreStatement->fetch();

    if ($artistData['artist_type_id']) {
        $typeSql = 'SELECT t.type_name FROM types t WHERE t.type_id = :type_id';

        // Statement is prepared for type
        $typeStatement = $db->prepare($typeSql);

         // Querystring value is retrieved
        $artist_type_id = $artistData['artist_type_id'];
        $typeStatement->bindValue(':type_id', $artist_type_id, PDO::PARAM_INT);

        // Query is executed
        $typeStatement->execute();

        // Type info is fetched
        $typeData = $typeStatement->fetch();
       }
    }
}
} catch (PDOException $e) {
    die($e->getMessage());
}

// Converts seconds to m:ss format
function secondsToMinutesSeconds($seconds) {
    $minutes = floor($seconds / 60);
    $remainingSeconds = $seconds % 60;
    return sprintf("%d:%02d", $minutes, $remainingSeconds);
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>COMP 3512 Assign1</title>
    <link rel="stylesheet" type="text/css" href="SingleSongStyles.css">
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
        <h1>Song Information</h1>
        <!-- Your song information content goes here -->
        
        <!-- Verify contents of database -->
        <?php if ($songData) : ?>
        <p><?php echo htmlspecialchars($songData['title']); ?>, <?php echo htmlspecialchars($artistData['artist_name']); ?>,
        <?php if ($typeData !== false && isset($typeData['type_name'])) : ?>
        <?php echo htmlspecialchars($typeData['type_name']); ?>,
        <?php endif; ?>
        <?php if ($genreData !== false && isset($genreData['genre_name'])) : ?>
        <?php echo htmlspecialchars($genreData['genre_name']); ?>, 
        <?php endif; ?>
        <?php echo htmlspecialchars($songData['year']); ?>,
        <?php echo secondsToMinutesSeconds($songData['duration']); ?></p>
    <ul>
        <li><strong>BPM:</strong> <?php echo htmlspecialchars($songData['bpm']); ?></li>
        <li><strong>Energy:</strong> <?php echo htmlspecialchars($songData['energy']); ?></li>
        <li><strong>Danceability:</strong> <?php echo htmlspecialchars($songData['danceability']); ?></li>
        <li><strong>Liveness:</strong> <?php echo htmlspecialchars($songData['liveness']); ?></li>
        <li><strong>Valence:</strong> <?php echo htmlspecialchars($songData['valence']); ?></li>
        <li><strong>Acousticness:</strong> <?php echo htmlspecialchars($songData['acousticness']); ?></li>
        <li><strong>Speechiness:</strong> <?php echo htmlspecialchars($songData['speechiness']); ?></li>
        <li><strong>Popularity:</strong> <?php echo htmlspecialchars($songData['popularity']); ?></li>
    </ul>
    <?php else : ?>
    <li>No song found</li>
    <?php endif; ?>
    </ul>
    </section>

    <!-- Footer section of page -->
    <footer>
        <h2>Footer</h2>
    </footer>

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
