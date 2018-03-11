<?php
/**
* Oauth authorization
 */
session_start();
include ('config.php');

require "lib/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

$connection = new TwitterOAuth(Consumer_Key, Consumer_Secret);
$request_token = $connection->oauth("oauth/request_token", array("oauth_callback" => "./facebook-export-import/twitter/twitter_back.php")); //Enter Your callback url path

$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

$url = $connection->url("oauth/authorize", array("oauth_token" => $request_token['oauth_token']));
header('Location: ' . $url);


?>