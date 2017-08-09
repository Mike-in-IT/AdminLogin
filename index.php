<?php 
    //print_r($_POST);
?>
<!DOCTYPE html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Frontier Reports<?php if(strpos($_SERVER['PHP_SELF'],"/",1)>0){echo " Dev ". substr($_SERVER['COMPUTERNAME'],strlen($_SERVER['COMPUTERNAME'])-1,1);} ?></title>
        <?php require 'StyleLinks.php'; ?>

        <script src="http://playground.emanuelblagonic.com/creating-nested-drop-down-menus/menu.js" type="text/javascript"></script>
    </head>
    <body>
        <?php
            require 'Helper/CheckLogin.php';
            include 'Menu.php';
        ?>
        <br><br><br>
        <div id="header">
            <div class="wrap">
                <div class="logo">
                    <h1></h1>
                </div>
            </div>
        </div>
        <h1><img alt="Frontier Logo" src="http://engsys.corp.ftr.com/FrontierLogo294-117.png" width="294" height="117" /></h1>
        <p><strong>Welcome to the Frontier Reporting Dashboard<br/><br/>
		        Please use one of the links above to find the report that you are looking for<br/><br/>
                Current reports include:</strong><br/>
        </p>
        <p style="color: #3e6688"><strong>
                Material Tracking, OPR Reports and CAFII Reports<br/><br/>
            </strong><br/><br/>
        </p>
        <?php
            include 'DBConn.php';
            require 'Helper/Version.php';
        ?>
        <table id="Version">
            <tr>
                <td>
                    <?php echo $VersionNumber['Name']; ?>
                </td>
            </tr>
        </table>
    </body>
</html>