<?php
    include("includes/header.php");
?>

<div class="postsContainer">
    <?php    
        $stmt = $con->prepare("SELECT * FROM posts ORDER BY post_id DESC");
        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $post = new Post($con, $row);
            $postUrl = $post->getPostUrl();
            $postAuthor = $post->getPostAuthor();
            $postedDate = $post->getPostedDate();
            $postContent = $post->getPostContent();

            $item = "<div class='post' style='border: 1px solid rgb(47, 51, 54); border-radius: 0px; margin-bottom: 5px;'>
                            <span style='font-size: 12px;'><b>" . $postAuthor . " · <span style='color: gray'>" . $postedDate . "</b></span></span>
                            <p class='card-text'>" . $postContent . "</p>
                            <a href='status.php?url=$postUrl' class='text-decoration-none' style='font-size: 10px;'>View Post</a>
                     </div>";

            echo $item;
        }
    ?>
</div>

<?php
    include("includes/footer.php");
?>