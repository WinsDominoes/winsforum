<?php
    include("config.php");
    include("classes/Post.php");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WinsForum - Say whatever you want!</title>

    <!-- my custom css files-->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- recaptcha -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <header>
        <h1>Win's Forum</h1>
        <span>Place where you can say anything!</span><br>
        <a href="/">Home</a> | <a href="post.php">Post</a> | <a href="tos.php">Terms of Service</a> | <a href="contact.php">Contact</a>
        <hr>
    </header>