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
        
        <!--verify contents of database-->
        <?php if ($s) : ?>
        <!--contents of database are displayed-->
        <p><?php echo htmlspecialchars ($s['title']); ?>, <?php echo htmlspecialchars ($artistData['artist_name']); ?>, <?php echo htmlspecialchars ($genreData['genre_name']); ?>, <?php echo htmlspecialchars ($s['year']); ?></p>
        <ul>
            <li><strong>BPM:</strong> <?php echo htmlspecialchars($s['bpm']); ?></li>
            <li><strong>Energy:</strong> <?php echo htmlspecialchars($s['energy']); ?></li>
            <li><strong>Danceability:</strong> <?php echo htmlspecialchars($s['danceability']); ?></li>
            <li><strong>Loudness:</strong> <?php echo htmlspecialchars($s['loudness']); ?></li>
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
