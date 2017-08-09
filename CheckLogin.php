<?php
    $Expiration = time() + (60*60*24*7);
    //echo "You made it to here!";
    if(!isset($_COOKIE['UserName']))
    {
        if(isset($_POST['UserName']))
        {
            setcookie("UserName",$_POST['UserName'],$Expiration);
        }
        else   
        {
            //echo "<br><br><br><br>Post: "; print_r($_POST);
            //echo "<script>location.href='LoginForm.php'</script>";
            ?>  
                <div id="LoginModal" class="modal">
                    <?php include 'LoginModal.php'; ?>
                </div>
                <script>document.getElementById('LoginModal').style.display='block'</script>
                <?php
            //echo "Made it here!";
        }
    }
    else
    {
        if(isset($_POST['UserName']))
        {
            setcookie("UserName",$_POST['UserName'],$Expiration);
        }
        else
        {
            setcookie("UserName",$_COOKIE['UserName'],$Expiration);
            //echo "Set old time";
        }        
    }
?>