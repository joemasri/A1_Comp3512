<?php
// Establish a database connection
try {
    $db = new PDO('sqlite:./data/music.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

?>

<!-- Favourites page -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>COMP 3512 Assign1</title>
    <link rel="stylesheet" type="text/css" href="BrowseStyles.css">
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

    <section class="table-container">

<!-- Show All Button -->
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

            <th>
            <form action="./Favorites.php" method="GET">
                <input class="" type="submit" name="RemoveBtn" value="Remove">
            </form>
            </th>
            
            <th>
            <form action="./Favorites.php" method="GET">
                <input class="" type="submit" name="viewBtn" value="View">
            </form>
            </th>

        </tr>
    </thead>
    <tbody>
        <?php
         // Add PHP Code Here

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
