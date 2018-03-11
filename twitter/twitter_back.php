<?php
/**
 File for going back to twitter page
 */
session_start();
include ('config.php');
//LOADING LIBRARY
require "lib/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;


if($_GET['oauth_token'] || $_GET['oauth_verifier'])
{	
	$connection = new TwitterOAuth(Consumer_Key, Consumer_Secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
	$access_token = $connection->oauth('oauth/access_token', array('oauth_verifier' => $_REQUEST['oauth_verifier'], 'oauth_token'=> $_GET['oauth_token']));

	$connection = new TwitterOAuth(Consumer_Key, Consumer_Secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);

	$user_info = $connection->get('account/verify_credentials');
	
	$oauth_token = $access_token['oauth_token'];
	$oauth_token_secret = $access_token['oauth_token_secret'];
	
	//print_r($user_info);
	$user_id = $user_info->id;
	$user_name = $user_info->name;
	$user_pic = $user_info->profile_image_url_https;
	$text = $user_info->status->text;
	$username = $user_info->screen_name;
        header('Location:../facebook_export.php');
}else
{
	header('Location: twitter_login.php');
}
	
	

?>