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
    $title = $_POST['title'];
    $body = $_POST['body'];
    $author = $_POST['author'];
    $created_at = $_POST['created_at'];

    $sql = "INSERT INTO post (title, body, author, created_at) VALUES (:title, :body, :author, :created_at)";
    $statement = $connection->prepare($sql);
    $statement->bindParam(':title', $title);
    $statement->bindParam(':body', $body);
    $statement->bindParam(':author', $author);
    $statement->bindParam(':created_at', $created_at);
  
    if ($statement->execute()) {
        
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error creating post.";
    }
}

$sql = "SELECT * FROM post ORDER BY created_at DESC";
$statement = $connection->prepare($sql);
$statement->execute();
$posts = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($posts) {
    foreach ($posts as $post) {
?>
    <h2><a href="single-post.php?id=<?php echo $post['id']; ?>"><?php echo $post['title']; ?></a></h2>
    <p><?php echo $post['body']; ?></p>
    <p>Author: <?php echo $post['author']; ?></p>
    <p>Created at: <?php echo $post['created_at']; ?></p>
<?php
    }
}
?>



<form action="create-post.php" method="POST">
  <label for="title">Title:</label>
  <input type="text" name="title" id="title" required>

  <label for="body">Body:</label>
  <input type="text" name="body" id="body" required>

  <label for="author">Author:</label>
  <input type="text" name="author" id="author" required>

  <label for="created_at">Created At:</label>
  <input type="date" name="created_at" id="created_at" required>

  
  <input type="submit" name="submit"  value="Create Post">
</form>
<?php
include 'sidebar.php';
include 'footer.php';
?>
