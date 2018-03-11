Facebook API for websites to export and import files from your 
FB groups and pages using access token valid for one hour
===

This Facebook API is for websites to export and import files from your Facebook groups and pages using access token, which is valid for one hour. You must be at least a member of the FB grop or page to be able to export or import data. For some actions, you may need admin access.

For group, export options are for group posts, albums, videos, files and docs - user wise or for the whole group and import options are for posts, albums and videos. For page, export options are posts, albums, videos and photos and import options are for posts, albums and videos.

Although we have tried to remove any coding mistakes, but if there is any mistake in code or if other developers make further improvements, please mail the same to tcpdemo2@gmail.com  

<b>Other Contributors -</b>
ParthaSarathiMishra,
Kmoksha Rishi,
Tarpinder Singh 

### Please read - 

<b>Basic installation instructions</b> -
   ===
1. Download these files by clicking on the Green `Download` buton. Upload the zip file into the root folder of your website and unzip the file.

2. Create Facebook or Twitter app and upload the Consumer Key / Consumer Secret credentials in the config files
	

<b>Instructions for export/import of data from/to a faceboook group / page</b> -
   ===
	
3. (a) Go to https://developers.facebook.com/tools/explorer/   
   (b) Click on `Get Token` and then `Get User Token` if you want to export/import from/to groups in which you are at least a member. 
	Tick all the fields under `User Data Permissions` and `Events, Groups & Pages` and click on `Get User Token` (This token is valid only for one hour, after which the token must be renewed). Allow the Facebook App or twitter app these chosen permissions when it asks permission.  
(c) If you want to export / import from/to a Page in which you are admin, then click on `Page Access Token`
	
4. Then, login url will be the YourDomain.com/facebook-export-import or whatever your url for the installed folder

5. Login using your Facebook or Twitter App and authorize the 
	App to access your Facebook or Twitter account.
	
6.  This will take you to a landing page having interface for choosing the options for Facebook group / page export / import.
	
7. (a) In the Interface page, firstly choose whether you want to export/import from/to a group or page.  
	(b) Then, choose whether you would like to export or import from or to a group or page.  
	(c) Then, select the available actions from the dropdown.  
	(d) Then, fill the group or page id as per your earlier choice. You can find out the group or page id from this link - findmyfbid.com   
	(e) Then, fill the access token for the user or page  which you got from the step 3 and depending on whether you want to export/import from/to a group or page  
	(f) If you would like to export data for only a particular user from a group, enter your user id. To find the user id of the logged in fb user. go to the page in step 3 and click on `Submit`
	
8. When we export, two files are created and zipped which is then downloaded, one is html file, other is json file's
    html file is used to view the content and json file is used to import the content.
<br>


<b>Note</b> -
   
   1. Exporting data may take some time depending on the size and amount of data you would like to export. If there are a lot of posts, photos etc. it will take longer time. Please do not close the window until the data has been successfully exported.
   
   2. After video or album has been successfully imported, it will reflect after some time on your group or page, only after the video or album has been processed by Facebook. Please do not close the window until the data has been successfully imported.
