<?php
/**
 Home Page for Signing in
 */
session_start();
session_destroy();
?>
<!doctype HTML>
<html>
    <head>
        <title>Sign in</title>
        <!-- Add icon library -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
/* Style all font awesome icons */
.fa {
    padding: 20px;
    font-size: 30px;
    width: 50px;
    text-align: center;
    text-decoration: none;
}

/* Add a hover effect if you want */
.fa:hover {
    opacity: 0.7;
}

/* Set a specific color for each brand */

/* Facebook */
.fa-facebook {
    background: #3B5998;
    color: white;
}

/* Twitter */
.fa-twitter {
    background: #55ACEE;
    color: white;
}
</style>
    </head>
    <body>
        <!-- Add font awesome icons -->
        <div style="margin-top:10%;width:50%;margin-lefT:auto;margin-right:auto;border">
            <h2>Sign in using facebook or twitter</h1>
            <div style="width:60%;margin-lefT:auto;margin-right:auto;">
                <a href="fblogin.php" class="fa fa-facebook"></a>
                <br/><br/>
                <a href="twitter/" class="fa fa-twitter"></a>
            </div>
        </div>
    </body>
</html>