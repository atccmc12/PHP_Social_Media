<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Welcome Page</title>
        <link rel="stylesheet" type="text/css" href="CSS/Site.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.6/dist/css/bootstrap.min.css">

        <style>
            body {
                
                padding-left: 0px; 
            }
            .content-container {
                margin-right: auto; 
                margin-left: auto;
                max-width: 600px; 
            }
        </style>
    </head>
    <body class="bg-light">
        <?php include 'Header.php'; ?>
        <div class="content-container">
            <h2>Welcome to Algonquin Social Media Website</h2><br>
            If you have never used this before, you have to <a href="NewUser.php">sign up</a> first.<br><br>
            If you have already signed up, you can <a href="Login.php">log in</a> now.
        </div>
        <?php include 'Footer.php'; ?>
    </body>
</html>
