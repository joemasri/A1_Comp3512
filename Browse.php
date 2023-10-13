<?php
session_start();
// Database connection
try {
    $db = new PDO('sqlite:./data/music.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

if (isset($_GET['title']) || isset($_GET['artistlist']) || isset($_GET['genrelist']) || isset($_GET['syear'])) {
    
    // Query based on search criteria
    $query = "SELECT songs.song_id, songs.title, artists.artist_name, songs.year, genres.genre_name
    FROM songs
    JOIN artists ON songs.artist_id = artists.artist_id
    JOIN genres ON songs.genre_id = genres.genre_id";
    
    // Add conditions based on search parameters
    if (!empty($_GET['title'])) {
        $query .= " AND songs.title LIKE '%" . $_GET['title'] . "%'";
    }
    if (!empty($_GET['artistlist'])) {
        $query .= " AND artists.artist_name LIKE '%" . $_GET['artistlist'] . "%'";
    }
    if (!empty($_GET['genrelist'])) {
        $query .= " AND genres.genre_name = '" . $_GET['genrelist'] . "'";
    }
    if (!empty($_GET['syear'])) {
        $query .= " AND songs.year = " . $_GET['syear'];
    }

    $stmt = $db->query($query);

    // Show All button is clicked
    if (isset($_GET['showall'])) {
    $query = "SELECT songs.song_id, songs.title, artists.artist_name, songs.year, genres.genre_name
    FROM songs
    JOIN artists ON songs.artist_id = artists.artist_id
    JOIN genres ON songs.genre_id = genres.genre_id";
}


} else {
    // If no query string parameters, display all songs
    $query = "SELECT songs.song_id, songs.title, artists.artist_name, songs.year, genres.genre_name
          FROM songs
          JOIN artists ON songs.artist_id = artists.artist_id
          JOIN genres ON songs.genre_id = genres.genre_id";
    $stmt = $db->query($query);
}

?>

<!-- Browse page -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>COMP 3512 Assign1</title>
    <link rel="stylesheet" type="text/css" href="BrowseStyles.css">
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

    <!-- Show All Button -->
    <form action="./Browse.php" method="GET">
        <input class="show-all-button" type="submit" name="showall" value="Show All">
    </form>

    <h1>Song List</h1>
    <table class="song-table">
<thead>
    <tr>
        <th>Title</th>
        <th>Artist</th>
        <th>Year</th>
        <th>Genre</th>
        <th>Add To Favorites</th>
        <th>View</th> 
    </tr>
</thead>
<tbody>
    <?php
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $title = $row['title'];
        if (strlen($title) > 25) {
            $title = substr($title, 0, 25) . '&hellip;';
        }

        echo "<tr>";
        
        // song title and song_id in the querystring
        echo "<td><a href='SingleSong.php?song_id={$row['song_id']}'>{$title}</a></td>";
        echo "<td>{$row['artist_name']}</td>";
        echo "<td>{$row['year']}</td>";
        echo "<td>{$row['genre_name']}</td>";
        echo "<td>";
        
        // Add to Favorites
        echo "<form action='./Browse.php' method='POST'>";
        echo "<input type='hidden' name='song_id' value='{$row['song_id']}'>";
        echo "<button type='submit' name='add_to_favorites'>Add To Favorites</button>";
        echo "</form>";
        echo "</td>";

         // AddToFav button is clicked
        if (isset($_POST['add_to_favorites'])) {
        $song_id = $_POST['song_id'];
        
        if (!isset($_SESSION['favorites'])) {
            $_SESSION['favorites'] = [];
        }
        // if song not in favorites, add it
        if (!in_array($song_id, $_SESSION['favorites'])) {
            $_SESSION['favorites'][] = $song_id;
        }
    }

        // View Button
        echo "<td>";
        echo "<form action='SingleSong.php' method='GET'>";
        echo "<input type='hidden' name='song_id' value='{$row['song_id']}'>";
        echo "<button type='submit' name='viewBtn'>View</button>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";

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
