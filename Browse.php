<?php
// Establish a database connection
try {
    $db = new PDO('sqlite:./data/music.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

if (isset($_GET['title']) || isset($_GET['artistlist']) || isset($_GET['genrelist']) || isset($_GET['syear'])) {
    
    // Query based on search criteria
    $query = "SELECT songs.title, artists.artist_name, songs.year, genres.genre_name, songs.popularity
              FROM songs
              JOIN artists ON songs.artist_id = artists.artist_id
              JOIN genres ON songs.genre_id = genres.genre_id
              WHERE 1";
    
    if (!empty($_GET['title'])) {
        $query .= " AND songs.title LIKE '%" . $_GET['title'] . "%'";
    }

    
} else {
    // If no query string parameters, display all songs
    $query = "SELECT songs.title, artists.artist_name, songs.year, genres.genre_name, songs.popularity
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
    <h1>Song List</h1>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Artist</th>
                <th>Year</th>
                <th>Genre</th>
                <th>Popularity</th>
            </tr>
        </thead>
        <tbody>
            <?php
             while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>{$row['title']}</td>";
                echo "<td>{$row['artist_name']}</td>";
                echo "<td>{$row['year']}</td>";
                echo "<td>{$row['genre_name']}</td>";
                echo "<td>{$row['popularity']}</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
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
