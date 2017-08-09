<?php
    //include 'Menu.php';
    if(!isset($ReferingPage))
    {
        $ReferingPage = '';
        echo "<br><br><br>Refering Page Not set<br>";
        if(isset($_POST['UserName']))
        {
            $ReferingPage = /*'http://engsys.corp.ftr.com/dev/FrontierReports/Index.php';*/$_SERVER['HTTP_REFERER'];
            $UserName = $_POST['UserName'];    
            if(isset($UserName))
            {
                $Expiration = time() + (60*60*24*7);
                if(isset($_COOKIE['UserName']))
                {
                    setcookie("UserName",$_COOKIE['UserName'],$Expiration,'/','.engsys.corp.ftr.com',0;
                    setcookie("CookieTime",$Expiration,time() + (60*60*24*7),'/','.engsys.corp.ftr.com',0);
                    error_log(date("Y/m/d h:i:sa")." LoginForm.php line 18 Cookie: " .$_COOKIE['UserName']. "\n",3,'D:\WebContent\engsys.corp.ftr.com\Helper\LogPHP.txt');
                    //echo "Cookie UserName is already set!<br>" .date('d-M-Y g:i:s a',$_COOKIE['CookieTime']);
                    include('Helper/PageName.php');
		            //echo "<script>location.href='" .$ReferingPage. "?UserName=" .$UserName. "'</script>here";
                }
                else
                {
                    setcookie("UserName",$UserName,$Expiration,'/');
                    setcookie("CookieTime",$Expiration,time() + (60*60*24*7),'/','.engsys.corp.ftr.com',0);
                    error_log(date("Y/m/d h:i:sa")." LoginForm.php line 27 Cookie: " .$_COOKIE['UserName']. "\n",3,'D:\WebContent\engsys.corp.ftr.com\Helper\LogPHP.txt');
                    //echo "Cookie UserName is not already set!<br>" .date('d-M-Y g:i:s a',$_COOKIE['CookieTime']);
                    include('Helper/PageName.php');  
		            //echo "<br>Server User<br>";
		            //echo "<script>location.href='" .$ReferingPage. "'</script>there";
                }
            }
            echo "<br>UserName: "; print_r($UserName);
        }
        else
        {
            echo "UserName was not set!";
            error_log(date("Y/m/d h:i:sa")." LoginForm.php line 39 UserName was not set!\n",3,'D:\WebContent\engsys.corp.ftr.com\Helper\LogPHP.txt');
        }
    }
    
    if($_SERVER['HTTP_REFERER'] != 'http://engsys.corp.ftr.com/dev/FrontierReports/LoginForm.php')
    {
        $ReferingPage = $_SERVER['HTTP_REFERER'];
        echo "<br><br><br>Set the Refering page to the previous page as long as it's not the LoginForm.<br>";
    }

    echo"<br>RefereingPage: '";print_r($ReferingPage);echo"'<br>HTTP_REFERER: ";print_r($_SERVER['HTTP_REFERER']);
    echo "<br>Post: "; print_r($_POST);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Login</title>
        <?php require 'StyleLinks.php'; ?>
    </head>
    <body>
        <?php include 'LoginModal.php'; ?>
    </body>
</html>