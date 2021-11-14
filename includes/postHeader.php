<?php
    include("config.php");
    include("classes/Post.php");
    include("classes/Comment.php");

    $postUrl = htmlspecialchars(strip_tags($_GET["url"], ENT_QUOTES));
    
    // check if posturl exists
    if(!$postUrl) {
        exit("That post does not exist.");
    }

    $post = new Post($con, $postUrl);

    $postUrl = $post->getPostUrl();
    $postAuthor = $post->getPostAuthor();
    $postedDate = $post->getPostedDate();
    //$postContent = $Parsedown->text($post->getPostContent());
    $postContent = $post->getPostContent();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $postAuthor ?> on WinsForum: "<?php echo $postContent ?>"</title>
    
    <meta name="title" content="@<?php echo $postAuthor . " · " . $postedDate ?>">
    <meta name="description" content="<?php echo $postContent; ?>">
    <meta name="theme-color" content="#22b14c">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Win's Forum">
    <meta property="og:url" content="https://winsdominoes.winscloud.net/status?url=<?php echo $postUrl ?>">
    <meta property="og:title" content="@<?php echo $postAuthor . " · " . $postedDate ?>">
    <meta property="og:description" content="<?php echo $postContent; ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://winsdominoes.winscloud.net/status?url=<?php echo $postUrl ?>">
    <meta property="twitter:title" content="@<?php echo $postAuthor . " · " . $postedDate ?>">
    <meta property="twitter:description" content="<?php echo $postContent; ?>">

    <!-- my custom css files-->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- recaptcha -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <header>
        <h1>Win's Forum</h1>
        <a href="/">Home</a> | <a href="post.php">Post</a> | <a href="tos.php">Terms of Service</a> | <a href="contact.php">Contact</a>
        <hr>
    </header>