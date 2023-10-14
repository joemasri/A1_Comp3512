<?php

session_start();

if (isset($_GET['removeall'])) {
    // If the "Remove All" button is clicked, clear the favorites list
    $_SESSION['favorites'] = array();
    header("Location: Favorites.php");
    exit;
}

if (isset($_SESSION['favorites']) && is_array($_SESSION['favorites'])) {
    // Establish a database connection
    try {
        $db = new PDO('sqlite:./data/music.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die('Database connection failed: ' . $e->getMessage());
    }

    $favoriteSongs = [];

    foreach ($_SESSION['favorites'] as $song_id) {

        $query = "SELECT songs.song_id, songs.title, artists.artist_name, songs.year, genres.genre_name FROM songs
                  JOIN artists ON songs.artist_id = artists.artist_id
                  JOIN genres ON songs.genre_id = genres.genre_id
                  WHERE songs.song_id = :song_id";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':song_id', $song_id, PDO::PARAM_INT);
        $stmt->execute();

        $favoriteSongs[] = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if (isset($_POST['remove'])) {
        $removeSongID = $_POST['song_id'];
        // Find and remove the specific song from the favorites array
        if (($key = array_search($removeSongID, $_SESSION['favorites'])) !== false) {
            unset($_SESSION['favorites'][$key]);
        }
        // Redirect to update the favorites list
        header("Location: Favorites.php");
        exit;
    }
}
?>

<!-- Favourites page -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>COMP 3512 Assign1</title>
    <link rel="stylesheet" type="text/css" href="FavoritesStyles.css">
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
            <li><a href="#">About Us</a></li>
        </ul>
    </nav>

    <section class="table-container">

<!-- Remove All Button -->
<form action="./Favorites.php" method="GET">
    <input class="remove-all-button" type="submit" name="removeall" value="Remove All">
</form>

<h1>Song List</h1>
<table class="song-table">
    <thead>
        <tr>
            <th>Title</th>
            <th>Artist</th>
            <th>Year</th>
            <th>Genre</th>
            <th>Remove</th>
            <th>View</th>
        </tr>
    </thead>
    <tbody>
        <?php
         if (isset($favoriteSongs) && is_array($favoriteSongs)) {
            foreach ($favoriteSongs as $song) {
                echo "<tr>";
                echo "<td>{$song['title']}</td>";
                echo "<td>{$song['artist_name']}</td>";
                echo "<td>{$song['year']}</td>";
                echo "<td>{$song['genre_name']}</td>";
                echo "<td>";
                echo '<form action="./Favorites.php" method="POST">';
                echo '<input type="hidden" name="song_id" value="' . $song['song_id'] . '">';
                echo '<input class="remove-button" type="submit" name="remove" value="Remove">';
                echo '</form>';
                echo "</td>";

                // View button
                echo "<td>";
                echo '<form action="SingleSong.php" method="GET">';
                echo '<input type="hidden" name="song_id" value="' . $song['song_id'] . '">';
                echo '<input class="" type="submit" name="viewBtn" value="View">';
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No favorite songs available</td></tr>";
        }
        ?>
    </tbody>
</table>
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
