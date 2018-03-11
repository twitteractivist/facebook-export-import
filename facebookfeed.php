<!--/*
 * the file imports or exports feed, albums, videos etc. based on parameters and access token
 there are two options, one is export which can export a group or user's feed, group albums, videos, 
 files and docs.
 for page it can export feed, albums, videos and photos.
 When we export, two files are created and zipped which is then downloaded, one is html file, other is json file's
 html file is used to view the content and json file is used to import the content.
 */ -->

<?php
set_time_limit(0);
$accessToken=$_POST['accesstoken'];
/*
 * if option is export
 */
if($_POST['import']==1){
$page_id='';
$group_id='';
if($_POST['action']==1)
{
    /*
     * if page id is set then page feed else group feed
     */
if($_POST['pagegroupid']==1)
{
    $page_id=$_POST['pageorgroup'];
    $response = file_get_contents("https://graph.facebook.com/$page_id/feed?limit=100&access_token=$accessToken");
}   
else
if($_POST['pagegroupid']==2)    
{
    $group_id=$_POST['pageorgroup'];
    $response = file_get_contents("https://graph.facebook.com/$group_id/feed?limit=100&access_token=$accessToken&fields=id,description,message,created_time,link,from{id,name,picture}");
}
/*
 * if data exists then a new file is created and content stored in that file
 */
if(!empty($response))
{
if(file_exists('facebookdata'))
{
    unlink('facebookdata');
}
touch('facebookdata');
$count=0;
$output=array();
$data=json_decode($response,true);
$output=array_merge($output,$data['data']);
$count=1;
while(1)
{
    if(isset($data['paging']['next']))
    {
        $next=$data['paging']['next'];
        $response=file_get_contents($next);
        $data=json_decode($response,true);
        $output=array_merge($output,$data['data']);
    }
    else
    {
        break;
    }
}
   if(!empty($_POST['guserid']))
   {
       $output1=array();
       $userid=$_POST['guserid'];
       foreach($output as $item)
       {
           if($item['from']['id']==$userid)
           {
               $output1[]=$item;
           }
       }
       /*
        * storing the content in html file
        */
       $content="<html><body><table border=1 width=100%>";
       foreach($output1 as $item)
       {
           $message='';
           $link='';
           if(isset($item['message']))
           {
               $message=$item['message'];
           }
           if(isset($item['link']))
           {
               $link=$item['link'];
           }
           $created_time=$item['created_time'];
           $from=$item['from']['name'];
           $content = $content . "<tr><td>Message</td><td>Link</td><td>Created Time</td><td>From</td></tr>"
                   . "<tr><td>$message</td><td>$link</td><td>$created_time</td><td>$from</td></tr>";
       }
       $content = $content . "</table></body></html>";
       file_put_contents('facebookfeed.html', $content);
       file_put_contents('facebookdata',json_encode($output1));
       /*
        * making the zip file
        */
       $files = array('facebookfeed.html', 'facebookdata');
       $zipname = 'file.zip';
       $zip = new ZipArchive;
       $zip->open($zipname, ZipArchive::CREATE);
       foreach ($files as $file) {
         $zip->addFile($file);
       }
       $zip->close();
       /*
        * deleting temporary files and downloading zip file
        */
       unlink('facebookfeed.html') ;
       unlink('facebookdata');
       header('Content-Type: application/zip');
       header('Content-disposition: attachment; filename='.$zipname);
       header('Content-Length: ' . filesize($zipname));
       readfile($zipname);
       unlink('file.zip');
       die();
   }
   /*
    * storing content in html form
    */
   $content="<html><body><table border=1 width=100%>";
   foreach($output as $item)
   {
       $message='';
       $link='';
       if(isset($item['message']))
       {
           $message=$item['message'];
       }
       if(isset($item['link']))
       {
           $link=$item['link'];
       }
       $created_time=$item['created_time'];
       $from=$item['from']['name'];
       $content = $content . "<tr><td>Message</td><td>Link</td><td>Created Time</td><td>From</td></tr>"
               . "<tr><td>$message</td><td>$link</td><td>$created_time</td><td>$from</tr>";
    }
   $content = $content . "</table></body></html>";
   /*
    * making the zip file
    */
   file_put_contents('facebookfeed.html', $content);
   file_put_contents('facebookdata',json_encode($output));
   $files = array('facebookfeed.html', 'facebookdata');
   $zipname = 'file.zip';
   $zip = new ZipArchive;
   $zip->open($zipname, ZipArchive::CREATE);
   foreach ($files as $file) {
     $zip->addFile($file);
   }
   $zip->close();
   unlink('facebookfeed.html') ;
   unlink('facebookdata');
   header('Content-Type: application/zip');
   header('Content-disposition: attachment; filename='.$zipname);
   header('Content-Length: ' . filesize($zipname));
   readfile($zipname);
   unlink('file.zip');
   die();
}
}
else
    /*
     * exporting the albums
     */
if($_POST['action']==3)
{
if($_POST['pagegroupid']==1)
{
    $page_id=$_POST['pageorgroup'];
    $response = file_get_contents("https://graph.facebook.com/$page_id/albums?access_token=$accessToken&fields=id,name,message,link,cover_photo.fields(name,source),photos.fields(name,picture,source)");
}   
else
if($_POST['pagegroupid']==2)    
{
    $group_id=$_POST['pageorgroup'];
    $response = file_get_contents("https://graph.facebook.com/$group_id/albums?access_token=$accessToken&fields=id,name,message,link,cover_photo.fields(name,source),photos.fields(name,picture,source)");
}
if(file_exists('facebookalbums'))
{
    unlink('facebookalbums');
}
touch('facebookalbums');
$output=array();
$data=json_decode($response,true);
$output=array_merge($output,$data['data']);
$count=1;
/*
 * paging in json data to get all data
 */
while(1)
{
    if(isset($data['paging']['next']))
    {
        $next=$data['paging']['next'];
        $response=file_get_contents($next);
        $data=json_decode($response,true);
        $output=array_merge($output,$data['data']);
    }
    else
    {
        break;
    }
}

   file_put_contents('facebookalbums',json_encode($output));
   /*
    * storing the content in html form
    */
   $content="<html><body><table border=1 width=100%>";
   foreach($output as $item)
   {
       $name=$item['name'];
       $cover_photo=$item['cover_photo']['source'];
       $cover_photo_name=$item['cover_photo']['name'];
       $content = $content . "<tr><td>Album Name</td><td>Cover Photo</td><td>Cover Photo Name</td></tr>"
               . "<tr><td>$name</td><td><img src=$cover_photo></td><td>$cover_photo_name</td></tr>";
       $content=$content .  "<tr>";
       foreach($item['photos']['data'] as $item1)
       {
            $picture=$item1['source'];
            $content=$content . "<td><img src=$picture></td>";
       }
       $content = $content . "</tr>";
    }
   $content = $content . "</table></body></html>";
   /*
    * making the zip file
    */
   file_put_contents('facebookalbums.html', $content);
   $files = array('facebookalbums.html', 'facebookalbums');
   $zipname = 'filealbums.zip';
   $zip = new ZipArchive;
   $zip->open($zipname, ZipArchive::CREATE);
   foreach ($files as $file) {
     $zip->addFile($file);
   }
   $zip->close();
   /*
    * deleting temporary files
    */
   unlink('facebookalbums.html');
   unlink('facebookalbums');
   header('Content-Type: application/zip');
   header('Content-disposition: attachment; filename='.$zipname);
   header('Content-Length: ' . filesize($zipname));
   readfile($zipname);
   unlink('filealbums.zip');
   die();
}
else
    /*
     * exporting videos
     */
if($_POST['action']==4)
{
if($_POST['pagegroupid']==1)
{
    $page_id=$_POST['pageorgroup'];
    $response = file_get_contents("https://graph.facebook.com/$page_id/videos?limit=100&access_token=$accessToken&fields=id,title,description,created_time,source,from{id,name,picture}");
}   
else
if($_POST['pagegroupid']==2)    
{
    $group_id=$_POST['pageorgroup'];
    $response = file_get_contents("https://graph.facebook.com/$group_id/videos?limit=100&access_token=$accessToken&fields=id,title,description,created_time,source,embed_html,from{id,name,picture}");
}
if(!empty($response))
{
if(file_exists('facebookvideos'))
{
    unlink('facebookvideos');
}
touch('facebookvideos');
$count=0;
$output=array();
$data=json_decode($response,true);
$output=array_merge($output,$data['data']);
$count=1;
/*
 * reading the json data page wise
 */
while(1)
{
    if(isset($data['paging']['next']))
    {
        $next=$data['paging']['next'];
        $response=file_get_contents($next);
        $data=json_decode($response,true);
        $output=array_merge($output,$data['data']);
    }
    else
    {
        break;
    }
}
   file_put_contents('facebookvideos',json_encode($output));
   /*
    * storing the content in html form
    */
   $content="<html><body><table border=1 width=100%>";
   foreach($output as $item)
   {
       $source=$item['source'];
       $created_time=$item['created_time'];
       $from=$item['from']['id'];
       $name=$item['from']['name'];
       $content = $content . "<tr><td>Video</td><td>Created Time</td><td>From</td><td>Name</td></tr>"
               . "<tr><td><video width=\"320\" height=\"240\" controls>
               <source src=$source type=\"video/mp4\">
               Your browser does not support the video tag.
               </video>"
               . "</td><td>$created_time</td><td>$from</td><td>$name</td></tr>";
    }
   $content = $content . "</table></body></html>";
   /*
    * making the zip file
    */
   file_put_contents('facebookvideos.html', $content);
   $files = array('facebookvideos.html', 'facebookvideos');
   $zipname = 'videofile.zip';
   $zip = new ZipArchive;
   $zip->open($zipname, ZipArchive::CREATE);
   foreach ($files as $file) {
     $zip->addFile($file);
   }
   $zip->close();
   /*
    * deleting temporary files
    */
   unlink('facebookvideos.html');
   unlink('facebookvideos');
   header('Content-Type: application/zip');
   header('Content-disposition: attachment; filename='.$zipname);
   header('Content-Length: ' . filesize($zipname));
   readfile($zipname);
   unlink('videofile.zip');
   die();
}
}
else
    /*
     * exporting docs
     */
if($_POST['action']==5)
{
if($_POST['pagegroupid']==1)
{
    $page_id=$_POST['pageorgroup'];
    $response = file_get_contents("https://graph.facebook.com/$page_id/docs?access_token=$accessToken&fields=id,from,subject,message");
}   
else
if($_POST['pagegroupid']==2)    
{
    $group_id=$_POST['pageorgroup'];
    $response = file_get_contents("https://graph.facebook.com/$group_id/docs?access_token=$accessToken");
}
if(!empty($response))
{
if(file_exists('facebookdocs'))
{
    unlink('facebookdocs');
}
touch('facebookdocs');
$count=0;
$output=array();
$data=json_decode($response,true);
$output=array_merge($output,$data['data']);
$count=1;
/*
 * reading json data page wise
 */
while(1)
{
    if(isset($data['paging']['next']))
    {
        $next=$data['paging']['next'];
        $response=file_get_contents($next);
        $data=json_decode($response,true);
        $output=array_merge($output,$data['data']);
    }
    else
    {
        break;
    }
}
   file_put_contents('facebookdocs',json_encode($output));
   /*
    * storing the content in html file
    */
   $content="<html><body><table border=1 width=100%>";
   foreach($output as $item)
   {
       $subject=$item['subject'];
       $message=$item['message'];
       $from=$item['from']['name'];
       $content = $content . "<tr><td>Title</td><td>Doc Content</td><td>From</td></tr>"
               . "<tr><td>$subject</td><td>$message</td><td>$from</td></tr>";
    }
   $content = $content . "</table></body></html>";
   /*
    * making the zip file
    */
   file_put_contents('facebookdocs.html', $content);
   $files = array('facebookdocs', 'facebookdocs.html');
   $zipname = 'filedocs.zip';
   $zip = new ZipArchive;
   $zip->open($zipname, ZipArchive::CREATE);
   foreach ($files as $file) {
     $zip->addFile($file);
   }
   $zip->close();
   /*
    * deleting temporary files
    */
   unlink('facebookdocs.html');
   unlink('facebookdocs');
   header('Content-Type: application/zip');
   header('Content-disposition: attachment; filename='.$zipname);
   header('Content-Length: ' . filesize($zipname));
   readfile($zipname);
   unlink('filedocs.zip');
   die();
}
}
else
    /*
     * exporting the files
     */
if($_POST['action']==7)
{
if($_POST['pagegroupid']==1)
{
    $page_id=$_POST['pageorgroup'];
    $response = file_get_contents("https://graph.facebook.com/$page_id/files?access_token=$accessToken&fields=id,from,message,group,download_link");
}   
else
if($_POST['pagegroupid']==2)    
{
    $group_id=$_POST['pageorgroup'];
    $response = file_get_contents("https://graph.facebook.com/$group_id/files?access_token=$accessToken&fields=id,from,message,group,download_link");
}
if(!empty($response))
{
if(file_exists('facebookfiles'))
{
    unlink('facebookfiles');
}
touch('facebookfiles');
$count=0;
$output=array();
$data=json_decode($response,true);
$output=array_merge($output,$data['data']);
$count=1;
/*
 * reading json data page wise
 */
while(1)
{
    if(isset($data['paging']['next']))
    {
        $next=$data['paging']['next'];
        $response=file_get_contents($next);
        $data=json_decode($response,true);
        $output=array_merge($output,$data['data']);
    }
    else
    {
        break;
    }
}

   file_put_contents('facebookfiles',json_encode($output));
   /*
    * storing the content in html form
    */
   $content="<html><body><table border=1 width=100%>";
       foreach($output as $item)
       {
           $from=$item['from']['name'];
           $message=$item['message'];
           $download_link=$item['download_link'];
           $content = $content . "<tr><td>From</td><td>Message</td><td>Download Link</td></tr>"
                   . "<tr><td>$from</td><td>$message</td><td><a href=\"$download_link\">Download</a></td></tr>";
       }
       $content = $content . "</table></body></html>";
       /*
        * making zip file
        */
       file_put_contents('facebookfiles.html', $content);
       $files = array('facebookfiles.html', 'facebookfiles');
       $zipname = 'facebookfile.zip';
       $zip = new ZipArchive;
       $zip->open($zipname, ZipArchive::CREATE);
       foreach ($files as $file) {
         $zip->addFile($file);
       }
       $zip->close();
       /*
        * deleting temporary files
        */
       unlink('facebookfiles.html');
       unlink('facebookfiles');
       header('Content-Type: application/zip');
       header('Content-disposition: attachment; filename='.$zipname);
       header('Content-Length: ' . filesize($zipname));
       readfile($zipname);
       unlink('facebookfile.zip');
       die();

}
}
if($_POST['action']==6)
{
    /*
     * exporting the photos
     */
if($_POST['pagegroupid']==1)
{
    $page_id=$_POST['pageorgroup'];
    $response = file_get_contents("https://graph.facebook.com/$page_id/photos?access_token=$accessToken&type=uploaded&fields=picture");
}   
else
if($_POST['pagegroupid']==2)    
{
    $group_id=$_POST['pageorgroup'];
    $response = file_get_contents("https://graph.facebook.com/$group_id/photos?access_token=$accessToken&type=uploaded&fields=picture");
}
if(!empty($response))
{
if(file_exists('facebookphotos'))
{
    unlink('facebookphotos');
}
touch('facebookphotos');
$count=0;
$output=array();
$data=json_decode($response,true);
$output=array_merge($output,$data['data']);
$count=1;
/*
 * reading json data page wise
 */
while(1)
{
    if(isset($data['paging']['next']))
    {
        $next=$data['paging']['next'];
        $response=file_get_contents($next);
        $data=json_decode($response,true);
        $output=array_merge($output,$data['data']);
    }
    else
    {
        break;
    }
}
   file_put_contents('facebookphotos',json_encode($output));
   /*
    * storing the content in html form
    */
   $content="<html><body><table border=1 width=100%>";
   foreach($output as $item)
   {
       $picture=$item['picture'];
       $content = $content . "<tr><td>Page Id</td><td>Photo</td></tr>"
               . "<tr><td>$page_id</td><td><img src=\"$picture\"></td></tr>";
    }
   $content = $content . "</table></body></html>";
   /*
    * making the zip file
    */
   file_put_contents('facebookphotos.html', $content);
   $files = array('facebookphotos', 'facebookphotos.html');
   $zipname = 'filephotos.zip';
   $zip = new ZipArchive;
   $zip->open($zipname, ZipArchive::CREATE);
   foreach ($files as $file) {
     $zip->addFile($file);
   }
   $zip->close();
   /*
    * deleting temporary files
    */
   unlink('facebookphotos.html');
   unlink('facebookphotos');
   header('Content-Type: application/zip');
   header('Content-disposition: attachment; filename='.$zipname);
   header('Content-Length: ' . filesize($zipname));
   readfile($zipname);
   unlink('filephotos.zip');
   die();
}
}
}
/*
 * if option is to import data
 */
{
$page_id='';
$group_id='';
if($_POST['pagegroupid']==1)
{
    $page_id=$_POST['pageorgroup'];
}   
else
if($_POST['pagegroupid']==2)    
{
    $group_id=$_POST['pageorgroup'];
}
if($_POST['action']==1){    
$tmpFilePath = $_FILES['file']['tmp_name'];

//Make sure we have a filepath
if($tmpFilePath != ""){
    //return;
    //save the filename
    $filePath = $_FILES['file']['name'];
    $moved=move_uploaded_file($tmpFilePath, $filePath);
    if(!$moved)
    {
        echo "Error";
        die();
    }
}
    $response=file_get_contents($filePath);
    $response= json_decode($response,true);
    /*
     * reading the file and storing the feed in group or page
     */
    foreach($response as $item)
    {
        $link='';
        $message='';
        if(isset($item['link']))
        {
            $link=$item['link'];
        }
        else
        if(isset($item['message']))
        {
            $message=$item['message'];
        }
        if (!($link==''&&$message=='')){
        if($page_id=="")
        {
            $graph_url= "https://graph.facebook.com/$group_id/feed/";
            $postData = "message=" . urlencode($message)
           ."&link=" . urlencode($link)
           . "&access_token=" .$accessToken;
        }
        else
        {
            $graph_url= "https://graph.facebook.com/$page_id/feed/";
            $postData = "message=" . urlencode($message)
           ."&link=" . urlencode($link)
           . "&access_token=" .$accessToken;
        }
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $graph_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $output = curl_exec($ch);
        print_r($output);
        curl_close($ch);
        }
    }
    unlink($filePath);
}
else
    if($_POST['action']==3)
    {
        $tmpFilePath = $_FILES['file']['tmp_name'];

//Make sure we have a filepath
if($tmpFilePath != ""){
    //return;
    //save the filename
    $filePath = $_FILES['file']['name'];
    $moved=move_uploaded_file($tmpFilePath, $filePath);
    if(!$moved)
    {
        echo "Error";
        die();
    }
}
    $response=file_get_contents($filePath);
    $response= json_decode($response,true);
    /*
     * reading the file and storing the data for album
     */
    foreach($response as $item)
    {
        $name=$item['name'];
        if($page_id=="")
        {
            $graph_url= "https://graph.facebook.com/$group_id/albums/";
            $postData = "name=" . urlencode($name)
           . "&access_token=" .$accessToken;
        }
        else
        {
            $graph_url= "https://graph.facebook.com/$page_id/albums/";
            $postData = "name=" . urlencode($name)
           . "&access_token=" .$accessToken;
        }
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $graph_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $output = curl_exec($ch);
        curl_close($ch);
        $output= json_decode($output,true);
        $id=$output['id'];
        //echo "Id is $id" ."<br/><br/>";
        foreach($item['photos']['data'] as $item1)
        {
            $source=$item1['source'];
            $graph_url= "https://graph.facebook.com/$id/photos";
            $postData = "url=" . urlencode($source)
           . "&access_token=" .$accessToken;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $graph_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $output = curl_exec($ch);
        echo "Album uploaded successfully Please check after few minutes<br/>";
        //print_r($output);
        curl_close($ch);
        }
    }   
    unlink($filePath);
    }
    else
    if($_POST['action']==4)
    {
        $tmpFilePath = $_FILES['file']['tmp_name'];

//Make sure we have a filepath
if($tmpFilePath != ""){
    //return;
    //save the filename
    $filePath = $_FILES['file']['name'];
    $moved=move_uploaded_file($tmpFilePath, $filePath);
    if(!$moved)
    {
        echo "Error";
        die();
    }
}
    $response=file_get_contents($filePath);
    $response= json_decode($response,true);
    /*
     * reading the file and storing the data for videos
     */
    foreach($response as $item)
    {
        $name=$item['source'];
        if($page_id=="")
        {
            $graph_url= "https://graph-video.facebook.com/$group_id/videos/";
            $postData = "file_url=" . urlencode($name)
           . "&access_token=" .$accessToken;
        }
        else
        {
            $graph_url= "https://graph-video.facebook.com/$page_id/videos/";
            $postData = "file_url=" . urlencode($name)
           . "&access_token=" .$accessToken;
        }
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $graph_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $output = curl_exec($ch);
        curl_close($ch);
        //print_r($output);
        echo "Video uploaded successfully please check after few minutes<br/>";
    }
    unlink($filePath);
    }
    else
        /*
         * storing the data for photos
         */
    if($_POST['action']==6)
    {
        $tmpFilePath = $_FILES['file']['tmp_name'];

//Make sure we have a filepath
if($tmpFilePath != ""){
    //return;
    //save the filename
    $filePath = $_FILES['file']['name'];
    $moved=move_uploaded_file($tmpFilePath, $filePath);
    if(!$moved)
    {
        echo "Error";
        die();
    }
}
    $response=file_get_contents($filePath);
    $response= json_decode($response,true);
    foreach($response as $item)
    {
        $url=$item['picture'];
        if($page_id!="")
        {
            $graph_url= "https://graph.facebook.com/$page_id/photos/";
            $postData = "url=" . urlencode($url)
           . "&access_token=" .$accessToken;
        }
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $graph_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $output = curl_exec($ch);
        curl_close($ch);
    }  
    unlink($filePath);
    }
}
echo "Done ". "<br/><br/><br/><br/><<br/>";
echo "<a href=\"facebook_export.php\">Back</a>";