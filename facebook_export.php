<?php
/**
 Landing page after Signing in
 */
?>
<!doctype HTML>
<html>
    <head>
        <title>Interface</title>
        <style type="text/css">
            #file,#c
            {
                visibility: hidden;
            }
        </style>
        <script  src="http://code.jquery.com/jquery-3.3.1.min.js"  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="  crossorigin="anonymous"></script>
        <script>
            $(document).ready(function(){
            $("#action").children("option[value='6']").hide();
        });

            function check()
            {
                var iecheck=document.getElementById('import').value;
                if(iecheck==2)
                {
                    document.getElementById('file').style.visibility="visible";
                    document.getElementById('c').style.visibility="visible";
                    $("#action").children("option[value='5']").hide();
                    $("#action").children("option[value='7']").hide();
                    $("#action").children("option[value='6']").hide();
                    $('#action').val(1);
                }
                else
                {
                    document.getElementById('file').style.visibility="hidden";
                    document.getElementById('c').style.visibility="hidden";
                    $("#action").children("option[value='5']").show();
                    $("#action").children("option[value='7']").show();
                    $("#action").children("option[value='6']").show();
                }
            }
            
            function check1()
            {
                var pgcheck=document.getElementById('pagegroupid').value;
                if(pgcheck==1)
                {
                    $("#action").children("option[value='5']").hide();
                    $("#action").children("option[value='6']").show();
                    $("#action").children("option[value='7']").hide();
                }
                else
                {
                    $("#action").children("option[value='5']").show();
                    $("#action").children("option[value='6']").hide();
                    $("#action").children("option[value='7']").show();
                }
            }
        </script>
    </head>
    <body>
        <form action="facebookfeed.php" method="post" enctype="multipart/form-data">
            Facebook Page id or group id
            <select id="pagegroupid" name="pagegroupid" onchange="check1();">
                <option value="1">I want to enter page id</option>
                <option value="2" selected="selected">I want to enter group id</option>
            </select>
            <br/><br/>
			Select import or export
            <select id="import" name="import" onchange="check();">
                <option value="1">Export</option>
                <option value="2">Import</option>
            </select>
            
            <br/><br/>
            Select action
            <select id="action" name="action">
                <option value="1">Posts</option>
                <option value="3">Albums</option>
                <option value="4">Videos</option>
                <option value="5">Docs</option>
                <option value="6">Photos</option>
                <option value="7">Files</option>
                <!--<option value="6">Photos</option>-->
            </select>
            <input type="text" name="pageorgroup" placeholder="Enter page or group id" required/>
            <input type="text" name="guserid" placeholder="Enter user id or leave blank" />
            <input type="text" name="accesstoken" placeholder="Enter Access Token" required />
            <br/><br/>
            <span id="c">Choose File</span>
            <input id="file" name="file" type="file" />
            <input type="submit" value="Submit" />
        </form>
    </body>
</html>