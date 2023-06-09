<?php
include 'header.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blog";

try {
    $connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $author = $_POST['author'];
    $tekst = $_POST['tekst'];
    $post_id = $_POST['post_id'];

    $sql = "INSERT INTO comments (author, tekst, post_id) VALUES (:author, :tekst, :post_id)";
    $statement = $connection->prepare($sql);
    $statement->bindParam(':author', $author);
    $statement->bindParam(':tekst', $tekst);
    $statement->bindParam(':post_id', $post_id);
  
    if ($statement->execute()) {
        
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error creating comment.";
    }
}

$sql = "SELECT * FROM comments";
$statement = $connection->prepare($sql);
$statement->execute();
$comments = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($comments) {
    ?>
    <ul>
    <?php
    foreach ($comments as $comment) {
        ?>
        <li>
            <p><strong><?php echo $comment['author']; ?></strong></p>
            <p><?php echo $comment['tekst']; ?></p>
            <p><a href="single-post.php?id=<?php echo $comment['post_id']; ?>">View Post</a></p>
        </li>
        <?php
    }
    ?>
    </ul>
    <?php
} else {
    
}


?>



<form action="comments.php" method="POST">

    

    <label for="author">Author:</label>
    <input type="text" name="author" id="author" required>

    <label for="tekst">Tekst:</label>
    <input type="text" name="tekst" id="tekst">

    <label for="post_id">Post ID:</label>
    <input type="integer" name="post_id" id="post_id" required>

    <input type="submit" name="submit" value="Create Comment">
</form>

<?php include 'footer.php';?>