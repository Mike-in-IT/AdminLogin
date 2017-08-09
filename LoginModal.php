    <form class="modal-content animate" method="post" action="<?php echo $_SERVER['HTTP_REFERER']; ?>">
        <div class="imgcontainer">
            <img src="/Images/FrontierLogo294-117.png" alt="Avatar" class="avatar">
        </div>
        <div>
            <label><b>UserName</b></label>
            <input class="Login" type="text" placeholder="UserName required (use CorpID)" name="UserName" required><br>
            <label><b>Password</b></label>
            <input class="Login" type="password" placeholder="Password not currently required" name="password">
            <button type="submit" class="Green">Login</button>
        </div>
        <div class="container" style="background-color: #f1f1f1">
            <button type="button" onclick="document.getElementById('LoginModal').style.display='none'" class="cancelbtn">Cancel</button>
            <!--span class="psw">Forgot <a href="#">password?</a></span-->
        </div>
    <?php echo $_SERVER['HTTP_REFERER'];
        //error_log(date("Y/m/d h:i:sa")." LoginModal.php line 16 HTTP_REFERER: " .$_SERVER['HTTP_REFERER']. "\n",3,'D:\WebContent\engsys.corp.ftr.com\Helper\LogPHP.txt'); ?>
    </form>
    <script>
        // Get the modal
        var modal = document.getElementById('LoginModal');

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>