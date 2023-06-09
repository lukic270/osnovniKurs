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

if (isset($_GET['id'])) {
    $postID = $_GET['id'];

    $sql = "SELECT * FROM post WHERE id = :id";
    $statement = $connection->prepare($sql);
    $statement->bindParam(':id', $postID);
    $statement->execute();
    $post = $statement->fetch(PDO::FETCH_ASSOC);

    if ($post) {
        ?>
        <h2><?php echo $post['title']; ?></h2>
        <p><?php echo $post['body']; ?></p>
        <p>Author: <?php echo $post['author']; ?></p>
        <p>Created at: <?php echo $post['created_at']; ?></p>
        <?php
    } else {
        echo "";
    }
} else {
    echo "Invalid request.";
}

$sql = "SELECT * FROM comments WHERE post_id = :post_id";
$statement = $connection->prepare($sql);
$statement->bindParam(':post_id', $postID);
$statement->execute();
$comments = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($comments) {
    ?>
    <h3>Comments:</h3>
    <ul>
    <?php
    foreach ($comments as $comment) {
        ?>
        <li>
            <p><strong><?php echo $comment['author']; ?></strong></p>
            <p><?php echo $comment['tekst']; ?></p>
        </li>
        <?php
    }
    ?>
    </ul>
    <?php
} else {
    echo "";
}

include 'footer.php';
?>
