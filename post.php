<?php
    include("includes/header.php");

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    function generateRandomString($length = 12) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    $postUrlString = generateRandomString();

    if(isset($_POST["postSubmission"])) {

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
                $postContentInput = htmlspecialchars(strip_tags($_POST["postContentInput"]), ENT_QUOTES);
                $postAuthorInput = htmlspecialchars(strip_tags($_POST["postAuthorInput"]), ENT_QUOTES);
                            
                if(!$postAuthorInput) {
                    $postAuthorInput = "Anonymous";
                }
    
                if(!$postContentInput) {
                    echo "You did not enter the post content that was required";
                } else {
                    try {
                        $stmt = $con->prepare("INSERT INTO posts (post_content, post_author, post_url) VALUES (:contentInput, :authorInput, '$postUrlString')");
                        // $stmt->bind_param("sss", $postContentInput, $postAuthorInput, $postUrlString);
                        $stmt->bindParam(":contentInput", $postContentInput);
                        $stmt->bindParam(":authorInput", $postAuthorInput);
                        $stmt->execute();
                        header("Location: index.php");
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
    <form action="post.php" method="post" enctype="multipart/form-data">
        <span>Your post!</span><br>
        <textarea rows="3" placeholder="What's going on?" id="postContent" name="postContentInput" style="width: 500px;" required></textarea>
        <br>
        <span>What do you want to be called?</span>
        <input type="text" name="postAuthorInput" id="postAuthorInput" placeholder="What's your name?">
        <br>
        <script>
            function blockPostButton(){
                document.getElementById('publishButton').disabled = false;  
            }
        </script>
        <div class="g-recaptcha" data-callback="blockPostButton" data-sitekey="6LcBQB4dAAAAAOGXr_Ge4QmSNiB_UZOrWmnZCQdg"></div>
        <button class="btn btn-primary" id="publishButton" type="submit" name="postSubmission" disabled>Post</button>
    </form>
</div>

<?php
    include("includes/footer.php");
?>