<?php

// Establish a database connection
try {
    $db = new PDO('sqlite:./data/music.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Query the database to retrieve artist names
$query = "SELECT DISTINCT artist_name FROM artists"; // Replace your_table_name with the actual table name
$stmt = $db->query($query);

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
    <h1>Song Search</h1>
    <form action="/action_page.php" method="GET">
    <label for="title">Title </label>
    <input type="text" id="fname" name="fname"><br><br>
    <label for="artist">Artist:</label>
    <select id="artist" name="artistlist">
    <option value=""></option>
    <?php
    // Loop through the results and generate the <option> elements
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $artistName = $row['artist_name'];
    echo "<option value='$artistName'>$artistName</option>";
    }
    ?>
    </select><br><br>
    
    <input type="submit" value="Search">
    </form>
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
