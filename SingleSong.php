<?php

// establish a database connection
try {
    $db = new PDO('sqlite:./data/music.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

try {
    $songSQL = 'SELECT * FROM songs WHERE song_id=:si';
    
    // statement is prepared for songs
    $statement = $db->prepare($songSQL);

    // querystring value is retrieved
    $song_id = filter_input(INPUT_GET, 'song_id', FILTER_VALIDATE_INT);
    $statement->bindValue(':si', $song_id, PDO::PARAM_INT);

    // query is executed
    $statement->execute();

    // song info is fetched
    $s = $statement->fetch();
} catch (PDOException $e) {
    die($e->getMessage());
}

try {
    $artistSQL = 'SELECT * FROM artists WHERE artist_id=:artist_id';

    // statement is prepared for artists
    $artistStatement = $db->prepare($artistSQL);

    // querystring value is retrieved
    $artist_id = $s['artist_id']; 
    $artistStatement->bindValue(':artist_id', $artist_id, PDO::PARAM_INT);

    // query is executed
    $artistStatement->execute();

    // artist info is fetched
    $artistData = $artistStatement->fetch();
} catch (PDOException $e) {
    die($e->getMessage());
}

try {
    $genreSQL = 'SELECT * FROM genres WHERE genre_id=:genre_id';

    // statement is prepared for genre
    $genreStatement = $db->prepare($genreSQL);

    // querystring value is retrieved
    $genre_id = $s['genre_id']; 
    $genreStatement->bindValue(':genre_id', $genre_id, PDO::PARAM_INT);

    // query is executed
    $genreStatement->execute();

    // genre info is fetched
    $genreData = $genreStatement->fetch();

} catch (PDOException $e) {
    die($e->getMessage());
}

try {
    $typeSql = 'SELECT t.type_name FROM types t INNER JOIN artists a ON t.type_id = a.artist_type_id WHERE a.artist_id=:artist_id';

    // statement is prepared for type
    $typeStatement = $db->prepare($typeSql);

    // querystring value is retrieved
    $artist_id = $s['artist_id']; 
    $typeStatement->bindValue(':artist_id', $artist_id, PDO::PARAM_INT);

    // query is executed
    $typeStatement->execute();

    // type info is fetched
    $typeData = $typeStatement->fetch();

} catch (PDOException $e) {
    die($e->getMessage());
}

//converts seconds to m:ss format
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

    <nav>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">Browse</a></li>
            <li><a href="#">Search</a></li>
            <li><a href="#">About Us</a></li>
        </ul>
    </nav>

    <section>
        <h1>Song Information</h1>
        <!-- Your song information content goes here -->
        
        <!-- Verify contents of database -->
        <?php if ($s) : ?>
        <p><?php echo htmlspecialchars($s['title']); ?>, <?php echo htmlspecialchars($artistData['artist_name']); ?>,
        <?php if ($typeData !== false) : ?>
        <?php echo htmlspecialchars($typeData['type_name']); ?>,
        <?php endif; ?>
        <?php echo htmlspecialchars($genreData['genre_name']); ?>, 
        <?php echo htmlspecialchars($s['year']); ?>,
        <?php echo secondsToMinutesSeconds($s['duration']); ?></p>
    <ul>
        <li><strong>BPM:</strong> <?php echo htmlspecialchars($s['bpm']); ?></li>
        <li><strong>Energy:</strong> <?php echo htmlspecialchars($s['energy']); ?></li>
        <li><strong>Danceability:</strong> <?php echo htmlspecialchars($s['danceability']); ?></li>
        <li><strong>Liveness:</strong> <?php echo htmlspecialchars($s['liveness']); ?></li>
        <li><strong>Valence:</strong> <?php echo htmlspecialchars($s['valence']); ?></li>
        <li><strong>Acousticness:</strong> <?php echo htmlspecialchars($s['acousticness']); ?></li>
        <li><strong>Speechiness:</strong> <?php echo htmlspecialchars($s['speechiness']); ?></li>
        <li><strong>Popularity:</strong> <?php echo htmlspecialchars($s['popularity']); ?></li>
        <li><strong>Loudness:</strong> <?php echo htmlspecialchars($s['loudness']); ?></li>
    </ul>
    <?php else : ?>
    <li>No song found</li>
    <?php endif; ?>
    </ul>
    </section>

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
