<?php 
    include("includes/postHeader.php");

    function generateRandomString($length = 12) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    $commentUrlString = generateRandomString();

    if(isset($_POST["commentSubmission"])) {
        $secretkeyrecaptcha = "6LcBQB4dAAAAAKFQxUB81UvCXChW8cBWfr6TmNmm";
        if(isset($_POST['g-recaptcha-response'])){
            $captcha=$_POST['g-recaptcha-response'];
            $response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretkeyrecaptcha."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
            if(!$captcha){
                $missinginputsecret = "Please enter the captcha form";
            }        
        }
        if($response['success'] == true) { 
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // declare some variables
                $commentContentInput = htmlspecialchars(strip_tags($_POST["commentContentInput"]), ENT_QUOTES);
                $commentAuthorInput = htmlspecialchars(strip_tags($_POST["commentAuthorInput"]), ENT_QUOTES);
                
                if(!$commentAuthorInput) {
                    $commentAuthorInput = "Anonymous";
                }

                if(!$commentContentInput) {
                    echo "You did not enter the post content that was required";
                } else {
                    try {
                        $stmt = $con->prepare("INSERT INTO comments (comment_content, comment_author, comment_url, comment_post_url) VALUES (:commentContentInput, :commentAuthorInput, '$commentUrlString', '$postUrl')");
                        // $stmt->bind_param("sss", $postContentInput, $postAuthorInput, $postUrlString);
                        $stmt->bindParam(":commentContentInput", $commentContentInput);
                        $stmt->bindParam(":commentAuthorInput", $commentAuthorInput);
                        $stmt->execute();
                        header("Location: status.php?url=".$postUrl."");
                    } catch (PDOException $e) {
                        echo "Something wrong happened!";
                    }
                }
            }
        } else if($response['success'] == false) {
            echo "Bro. Uncool.";
        } else;
    }
?>

<div class="container">
    <h2>Post</h2>
        <div class="statusContainer" style="border: 1px solid rgb(47, 51, 54); border-radius: 0px;">
            <span style='font-size: 12px;'><b><?php echo $postAuthor ?> · <span style='color: gray'><?php echo$postedDate ?></b></span>
            <h3 class="card-text"><?php echo $post->getPostContent(); ?></h3>
        </div>
</div>

<div class="commentsContainer">
    <h4>Comments</h4>
    <form action="status.php?url=<?php echo $postUrl ?>" method="post" enctype="multipart/form-data">
        <span>Your comment!</span><br>
        <textarea rows="3" placeholder="Comment..." id="commentContent" name="commentContentInput" style="width: 500px;" required></textarea>
        <br>
        <span>Comment display name</span>
        <input type="text" name="commentAuthorInput" id="commentAuthorInput" placeholder="Display Name...">
        <br>
        <script>
            function blockCommentButton(){
                document.getElementById('publishButton').disabled = false;  
            }
        </script>
        <div class="g-recaptcha" data-callback="blockCommentButton" data-sitekey="6LcBQB4dAAAAAOGXr_Ge4QmSNiB_UZOrWmnZCQdg"></div>
        <button class="btn btn-primary" id="publishButton" type="submit" name="commentSubmission" disabled>Post</button>
    </form>
    <br>
    <?php    
        $stmt = $con->prepare("SELECT * FROM comments WHERE comment_post_url = :post_url");
        $stmt->bindParam(":post_url", $postUrl);
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if(!$row) {
                echo "No comments has been posted yet. ";
            } else {
                $comment = new Comment($con, $row);
                $commentUrl = $comment->getCommentUrl();
                $commentAuthor = $comment->getCommentAuthor();
                $commentPostedDate = $comment->getCommentPostedDate();
                $commentContent = $comment->getCommentContent();

                $item = "<div class='comment' style='border: 1px solid rgb(47, 51, 54); border-radius: 0px;'>
                                <span style='font-size: 12px;'><b>" . $commentAuthor . " · <span style='color: gray'>" . $commentPostedDate . "</b></span></span>
                                <p class='card-text'>" . $commentContent . "</p>
                        </div>";

                echo $item;
            }
        }
    ?>
</div>

<?php
    include("includes/footer.php");
?>
