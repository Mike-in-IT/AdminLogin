<?php
    ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    include('Helper/PageName.php');
?>
<!DOCTYPE html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $Title . " " . substr($_SERVER['COMPUTERNAME'],strlen($_SERVER['COMPUTERNAME'])-1,1); ?></title>
        <?php require 'StyleLinks.php'; ?>

        <?php //include 'DataTableLinks.php'; include'array_column.php'; ?>

    </head>
    <body>
        <?php
            require 'Helper/CheckLogin.php';
            include 'Menu.php';
        ?>
        <br><br><br>
        <h1><?php echo $HeadingDesc; if(strpos($_SERVER['PHP_SELF'],"/",1)>0){echo "<br>Dev Site";} ?></h1>
<?php
    include('DBConn.php');
    require 'Helper/Version.php';
    $GetReleaseNotes = $conn->query($Nsql);
    $ReleaseNotes = $GetReleaseNotes->fetch(PDO::FETCH_ASSOC);
    //print_r($_POST); echo " Post<br>";
    //print_r($_REQUEST); echo " REQUEST<br>";
    //print_r($_COOKIE); echo " Cookie<br>";   
    if(isset($_POST['UserName']))
    {
        $UserName = $_POST['UserName'];    
        if(isset($UserName))
        {
            $Expiration = time() + (60*60*24*7);
            if(isset($_COOKIE['UserName']))
            {
                setcookie("UserName",$_COOKIE['UserName'],$Expiration,'/','.engsys.corp.ftr.com',0);
                setcookie("CookieTime",$Expiration,time() + (60*60*24*7),'/','.engsys.corp.ftr.com',0);
                error_log(date("Y/m/d h:i:sa")." AdminPage.php line 40 Cookie: " .$_COOKIE['UserName']. "\n",3,'D:\WebContent\engsys.corp.ftr.com\Helper\LogPHP.txt');
                //echo "Cookie UserName is already set!<br>" .date('d-M-Y g:i:s a',$_COOKIE['CookieTime']);
                echo "<script>location.href = 'http://" .$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']. "'</script>";
            }
            else
            {
                setcookie("UserName",$UserName,$Expiration,'/');
                setcookie("CookieTime",$Expiration,time() + (60*60*24*7),'/','.engsys.corp.ftr.com',0);
                error_log(date("Y/m/d h:i:sa")." AdminPage.php line 47 Cookie: " .$_COOKIE['UserName']. "\n",3,'D:\WebContent\engsys.corp.ftr.com\Helper\LogPHP.txt');
                //echo "Cookie UserName is not already set!<br>" .date('d-M-Y g:i:s a',$_COOKIE['CookieTime']);
                echo "<script>location.href = 'http://" .$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']. "'</script>";
            }
        }
    }
    else
    {
        foreach($_SERVER as $Index => $value)
        {
           //echo $Index. "== " .$value. "<BR>";
        }
        //print_r($_SERVER);
        error_log(date("Y/m/d h:i:sa")." AdminPage.php line 61 UserName was not set!\n",3,'D:\WebContent\engsys.corp.ftr.com\Helper\LogPHP.txt');
    }  
    if(isset($_REQUEST['action']))
    {
        if(isset($_POST['TableName']))
        {
            $TableName = substr($_POST['TableName'],strpos($_POST['TableName'],'.')+1,strlen($_POST['TableName'])-strpos($_POST['TableName'],'.'));
        }
        
        //print_r($_COOKIE); echo " Cookie<br>";
        switch($_REQUEST['action'])
        {
            case 'AddTable':
                try
                {//print_r($_POST); echo " Post<br>";
                    if($_COOKIE['AddHeadingCookie'] == "true")
                    {
                        if($_POST['YesNo'] == 'No')
                        {
                            //print_r($_POST); echo " Post<br>";
                            $AddSql = "select UPPER(COLUMN_NAME) COLUMN_NAME,TABLE_SCHEMA from EngSys.INFORMATION_SCHEMA.COLUMNS where TABLE_NAME = N'" . $TableName . "'
                                        and COLUMN_NAME != 'ID'";
                            //echo $AddSql . "<br>";
                            $sth = $conn->prepare($AddSql);
                            $sth->execute();
                            //echo "<br>sth: "; print_r($sth);echo "<br>";
                            $IdColumn = 2;
                            $rAddSql = $sth->fetchALL(PDO::FETCH_ASSOC);
                            //print_r($rAddSql);
                            $NumColumns = count($rAddSql);
                            //echo "Number of Columns: " . $NumColumns;
                            $ColumnNames = array();

                            for ($i = 0;$i < $NumColumns; $i++)
                            {
                                $ColumnNames[] = $rAddSql[$i]["COLUMN_NAME"];
                            }
                            //print_r($ColumnNames);
                            $InsertSql = "insert into dbo.TableHeadings (ID,TableName,Headings)
                                            select 1, '" . $TableName . "', 'Id'";
                            //echo "<br>" . $InsertSql . "<br>";
                            $sth = $conn->prepare($InsertSql);
                            $sth->execute();
                            for ($i = 0;$i < $NumColumns; $i++)
                            {
                                $InsertSql = "insert into dbo.TableHeadings (ID,TableName,Headings)
                                                select " . $IdColumn . ", '" . $TableName . "', UPPER('" . $ColumnNames[$i] . "')";
                                //echo "<br>" . $InsertSql . "<br>";
                                $sth = $conn->prepare($InsertSql);
                                $sth->execute();
                                $IdColumn = $IdColumn + 1;
                            }
                        }
                        elseif($_POST['YesNo'] == 'Yes')
                        {
                            //print_r($_POST); echo " Post<br>";
                            $AddSql = "select UPPER(COLUMN_NAME) COLUMN_NAME,TABLE_SCHEMA from EngSys.INFORMATION_SCHEMA.COLUMNS where TABLE_NAME = N'" . $TableName . "'
                                        and COLUMN_NAME != 'ID'";
                            //echo $AddSql . "<br>";
                            $sth = $conn->prepare($AddSql);
                            $sth->execute();
                            //echo "<br>sth: "; print_r($sth);echo "<br>";
                            $IdColumn = 3;
                            $rAddSql = $sth->fetchALL(PDO::FETCH_ASSOC);
                            //print_r($rAddSql); echo "  rAddSQL<br>";
                            $NumColumns = count($rAddSql);
                            echo "Number of Columns: " . $NumColumns;
                            $ColumnNames = array();

                            for ($i = 0;$i < $NumColumns; $i++)
                            {
                                $ColumnNames[] = $rAddSql[$i]["COLUMN_NAME"];
                            }
                            //print_r($ColumnNames);
                            $InsertSql = "insert into dbo.TableHeadings (ID,TableName,Headings)
                                            Values (1, '" . $TableName . "', 'Edit'),
                                                    (2, '" . $TableName . "', 'Id')";
                            //echo "<br>" . $InsertSql . "<br>";
                            $sth = $conn->prepare($InsertSql);
                            $sth->execute();
                            for ($i = 0;$i < $NumColumns; $i++)
                            {
                                $InsertSql = "insert into dbo.TableHeadings (ID,TableName,Headings)
                                                select " . $IdColumn . ", '" . $TableName . "', UPPER('" . $ColumnNames[$i] . "')";
                                //echo "<br>" . $InsertSql . "<br>";
                                $sth = $conn->prepare($InsertSql);
                                $sth->execute();
                                $IdColumn = $IdColumn + 1;
                            }
                        }
                    }
                
                    $link = "<script>window.open('http://engsys.corp.ftr.com/dev/FrontierReports/AddTable.php?TableName=" . urlencode($_POST['TableName']) . "','width=825,height=215,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');</script>";
                    //echo "window.open('http://engsys.corp.ftr.com/AddTable.php?TableName=$TableName','width=825,height=215,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0')";
                    //header("location: http://engsys.corp.ftr.com/AddTable.php?TableName=$TableName&pop=yes");
                    //print_r($link);
                    echo $link;
                }
                catch(Exception $e)
                {
                    die(print_r($e->getMessage()));
                }
                //}
                GetCommand(!NULL,$VersionNumber,$ReleaseNotes);
            break;
            
            case 'RemoveTable':
                try
                {
                    //print_r($_POST); echo "<br>Post<br>";
                    //print_r($_GET); echo "<br>GET<br>";
                    if(isset($_POST['TableName']))
                    {
                        $TableName = substr($_POST['TableName'],strpos($_POST['TableName'],'.'),strlen($_POST['TableName'])-strpos($_POST['TableName'],'.'));
                    }
                    if($_COOKIE['AddHeadingCookie'] == "true")
                    {
                        $DeleteSql = "delete from TableHeadings from TableHeadings where TableName = '" . $TableName . "'";
                        //print_r($DeleteSql);
                        $sth = $conn->prepare($DeleteSql);
                        $sth->execute();
                    }
                }
                catch(Exception $e)
                {
                    die(print_r($e->getMessage()));
                }
                GetCommand(!NULL,$VersionNumber,$ReleaseNotes);
            break;

            case 'CopyFiles':
                $script = chr(92) . chr(92) . 'MAFINFWWWPV02\D$\WebContent\engsys.corp.ftr.com\BatchFiles\CopyFiles.bat';
                if (!file_exists($script))
                {
                    print_r($script); echo " Script<br>";
                    echo "Script doesn't exist!<br>";
                    print_r(!file_exists($script));
                }
                /*elseif(!is_executable($script))
                {
                    print_r($script); echo "<br>";
                    echo "Script is not executable!";
                
                }*/
                else
                {
                    system('cmd /c ' . $script); echo " <br>";
                }
                GetCommand(!NULL,$VersionNumber,$ReleaseNotes);
            break;

            case 'Refresh':
                if(strpos($_SERVER['PHP_SELF'],"dev/FrontierReports",1)>0)
                {
                    echo "<script>location.href = 'http://engsys.corp.ftr.com/dev/FrontierReports/AdminPage.php?PageName=AdminPage'</script>";
                }
                elseif(strpos($_SERVER['PHP_SELF'],"dev/Testing",1) > 0)
                {
                    echo "<script>location.href = 'http://engsys.corp.ftr.com/dev/Testing/AdminPage.php?PageName=AdminPage'</script>";
                }
                else
                {
                    echo "<script>location.href = 'http://engsys.corp.ftr.com/AdminPage.php?PageName=AdminPage'</script>";
                }
            break;

            case 'version':
                include 'DBConn.php';
                $CheckSQL = "select VersionNo from pmdb.Version where Name = '" .$_POST['Name']. "' order by VersionNo desc";
                error_log(date("Y/m/d h:i:sa"). " CheckSQL: " .$CheckSQL. "\n",3,'D:\WebContent\engsys.corp.ftr.com\Helper\LogPHP.txt');
                $GetVersionCheck = $conn->query($CheckSQL);
                if($_POST['VersionNumberCheck'] == $GetVersionCheck->fetch(PDO::FETCH_ASSOC))
                {
                    echo "You did not update the Version Number.<br>To add a version you must update the Version Number.";
                }
                else
                {
                    $InsertSql = "insert into pmdb.Version(Name,VersionNo,VersionType,ReleaseNotes)
                                select '" .$_POST['Name']. "','" .$_POST['VersionNumber']. "', " .$_POST['VersionType']. ",'" .$_POST['Notes']. "'";
                    //print_r($InsertSql);
                    error_log(date("Y/m/d h:i:sa"). " InsertSql: " .$InsertSql. "\n",3,'D:\WebContent\engsys.corp.ftr.com\Helper\LogPHP.txt');
                    $sth = $conn->prepare($InsertSql);
                    $sth->execute();
                }
                
                GetCommand(!NULL,$VersionNumber,$ReleaseNotes);
            break;
        }
    }
    else
    {
        GetCommand(!NULL,$VersionNumber,$ReleaseNotes);
    }
function GetCommand($Success,$VersionNumber,$ReleaseNotes)
{
    include('DBConn.php');
    
    $TableNameSql = "select TABLE_SCHEMA, TABLE_NAME from INFORMATION_SCHEMA.TABLES 
                        where TABLE_NAME not in(select distinct TableName from dbo.TableHeadings) order by TABLE_NAME,TABLE_SCHEMA";
    $getTableNames = $conn->prepare($TableNameSql);
    $getTableNames->execute();
    $rTableNames = $getTableNames->fetchALL(PDO::FETCH_ASSOC);
    $NumTableNames = count($rTableNames);
    $TableNames = array();
    for($i = 0;$i < $NumTableNames;$i++)
    {
        $TableNames[] = $rTableNames[$i]['TABLE_SCHEMA'] . "." . $rTableNames[$i]['TABLE_NAME'];
    }
            
    if (is_null($Success))
    {
        echo "<p><strong>Pick something to do!</strong></p>";
    }
    //print_r($TableNames); echo " tablenames<br><br><br>";
?>
<table id='AdminTable' class="NormalTable">
    <tr>
        <td>
            <div>
                <p>Add table to the Headings table</p>
                <form action='AdminPage.php' enctype='multipart/form-data' method='POST' >
                    <input type="hidden" name="PageName" value="AdminPage" />
                    <input type='hidden' name='action' value='AddTable'>
                    <p>
                        <label><strong>Table Name:</strong>
                            <select id="TableName" name='TableName'><?php
                                foreach($TableNames as $Table)
                                {
                                    //print_r($Table);?>                            
                                    <option value='<?php echo $Table; ?>'><?php echo $Table; ?></option><?php
                                }?>
                            </select>
                        </label>
                    </p><p></p>
                    <p>
                        <label><strong>Editable:</strong> 
                            <select id="YesNo" name='YesNo'>
                                <option value='No' selected='selected'>No</option>
                                <option value='Yes'>Yes</option>
                            </select>
                        </label>
                    </p><p></p>
                    <p><input class="Pointer" type='submit' id="Addsubmit" name='submit' value='Add Table'></p>
                </form>
            </div>
        </td><?php
      //print_r($_POST);
    if($_REQUEST['action'] = 'Remove Table')
    {
        RemoveTableHeading($Success,$VersionNumber,$ReleaseNotes);
    }
}

function RemoveTableHeading($Success,$VersionNumber,$ReleaseNotes)
{
    include('DBConn.php');

    $TableNameSql = "select distinct TableName from dbo.TableHeadings";
    $getTableNames = $conn->prepare($TableNameSql);
    $getTableNames->execute();
    $rTableNames = $getTableNames->fetchALL(PDO::FETCH_ASSOC);
    //print_r($rTableNames);
    $NumTableNames = count($rTableNames);
    //echo "<br>" . $NumTableNames;
    $TableNames = array();

    for($i = 0;$i < $NumTableNames;$i++)
    {
        //echo $i . "<br>";
        //echo $rTableNames[$i]['TableName'];
        $TableNames[] = $rTableNames[$i]['TableName'];
    }
    //print_r($TableNames); echo " tablenames<br><br><br>";
    if (is_null($Success))
    {
        echo "<p><strong>Pick something to do!</strong></p>";
    }?>
        <td>
            <div>
                <p>Remove table from the Headings table</p>
                <form action='AdminPage.php' enctype='multipart/form-data' method='POST' >
                    <input type="hidden" name="PageName" value="AdminPage" />
                    <input type='hidden' name='action' value='RemoveTable' />
                    <p>
                        <label><strong>Table Name:</strong>
                            <select id="TableNameR" name='TableName'><?php echo "\n";
                                foreach($TableNames as $Table)
                                {?>
                                    <option value='<?php echo $Table; ?>'><?php echo $Table; ?></option><?php echo "\n";
                                }?>
                            </select>
                        </label>
                    </p><p></p>
                    <p><input class="Pointer" type='submit' id="Removesubmit" name='submit' value='Remove Table'></p>
                </form>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <p>Copy website files from MAFINFWWWPV02 to MAFINFWWWPV01</p>
            <form action='AdminPage.php' enctype='multipart/form-data' method='post' onsubmit="return CopyFiles()">
                <input type="hidden" name="PageName" value="AdminPage" />
                <input type='hidden' name='action' value='CopyFiles'/>
                <p><input class="Pointer" type='submit' name='submit' value='Copy Files'/></p>
            </form>
            <p id="demo"></p>
        </td>
        <td>
            <p>Refresh page</p>
            <form action='AdminPage.php' enctype='multipart/form-data' method='post'>
                <input type="hidden" name="PageName" value="AdminPage" />
                <input type='hidden' name='action' value='Refresh'/>
                <p><input class="Pointer" type='submit' name='submit' value='Refresh'/></p>
            </form>
        </td>
    </tr>
    <tr>
        <td>
            <p>Help link for all reports except for Material Tracking</p>
            <a href="http://engsys.corp.ftr.com//HelpPage.php" onclick="javascript:void window.open('http://engsys.corp.ftr.com//HelpPage.php','1472831666662','width=825,height=520,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');return false;">Help</a>
            <p></p>
            <p>Help link for Material Tracking Report</p>
            <a id="Help" style="font-size: 15px" href="http://engsys.corp.ftr.com/Helper/HelperPage.php" onclick="javascript:void window.open('http://engsys.corp.ftr.com/Helper/HelperPage.php','1472831666662','width=825,height=215,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');return false;">Help</a>
        </td>
        <td>
            <p>Add new version</p>
            <form action="AdminPage.php" enctype="multipart/form-data" method="post">
                <input type="hidden" name="action" value="version" />
                <label><strong>Name:</strong></label>
                <select id="WebName" name="Name">
                    <option value="ReportDashboard">ReportDashboard</option>
                    <option value="ReportDashboard Dev"<?php if(strpos($_SERVER['PHP_SELF'],"dev/FrontierReports",1) > 0){echo " selected='selected'";}?>>ReportDashboard Dev</option>
                    <option value="ReportDashboard Testing"<?php if(strpos($_SERVER['PHP_SELF'],"dev/Testing",1) > 0){echo " selected='selected'";}?>>ReportDashboard Testing</option>
                </select><p></p>
                <label><strong>Version Number: </strong></label>
                <input id="VersionNumber" type="text" name="VersionNumber" value="<?php echo $VersionNumber['VersionNo']; ?>" /><p></p>
                <label><strong>Version Type:</strong></label>
                <select id="Type" name="VersionType">
                    <option value="1">Major</option>
                    <option value="2">Minor</option>
                    <option value="3" selected="selected">Bug</option>
                </select><p></p>
                <input type="hidden" name="VersionNumberCheck" value="<?php echo $VersionNumber['VersionNo']; ?>" />
                <label><strong>Notes</strong></label>
                <input id="Notes" type="text" name="Notes" value="<?php echo $ReleaseNotes['ReleaseNotes']; ?>" />
                <p><input class="Pointer" type="submit" name="submit" value="Update Version" /></p>
                <p><input id="VersionSubmit" class="Pointer" type="button" name="VersionSubmit" value="Current Notes" /></p>
            </form>
        </td>
    </tr>
    <tr>
        <td>
            <form action="LoginForm.php" enctype="multipart/form-data" method="post">
                <button type="submit">Login Form</button>
            </form>
            <br>
                <?php //include 'LoginForm.php'; ?>
            <button onclick="document.getElementById('LoginModal').style.display='block'">Login</button>
        </td>
        <td>

        </td>
    </tr>
</table>
<div id="LoginModal" class="modal">
<?php include 'LoginModal.php'; ?>
</div>
<table id="Version">
    <tr>
        <td>
            <?php echo $VersionNumber['Name']; ?>
        </td>
    </tr>
</table>
<table class="HideTable">
    <tr>
        <td>
            <strong>Stack Overflow Single</strong><br>
            <a href="http://stackoverflow.com/users/2911241/mike">
                <img src="http://stackoverflow.com/users/flair/2911241.png" width="208" height="58" alt="profile for Mike at Stack Overflow, Q&A for professional and enthusiast programmers" title="profile for Mike at Stack Overflow, Q&A for professional and enthusiast programmers">
            </a>
        </td>
        <td>
            <strong>Stack Overflow Combined</strong><br>
            <a href="http://stackexchange.com/users/3478603">
                <img src="https://stackexchange.com/users/flair/3478603.png?theme=dark" width="208" height="58" alt="profile for Mike on Stack Exchange, a network of free, community-driven Q&A sites" title="profile for Mike on Stack Exchange, a network of free, community-driven Q&A sites">
            </a>
        </td>
        <td>
            <strong>Experts Exchange</strong><br>
            <script type="text/javascript">
            var obj;
            function JSONscriptRequest(fullUrl)
            {
	            this.fullUrl=fullUrl;
	            this.noCacheIE='&noCacheIE=' + (new Date()).getTime();
	            if(document.getElementsByTagName("head").item(0) == null)
	            {
		            this.headLoc=document.getElementsByTagName("html").item(0);
	            }
	            else
	            {
		            this.headLoc=document.getElementsByTagName("head").item(0);
	            }
	            this.scriptId='JscriptId' + JSONscriptRequest.scriptCounter++;
            }
            JSONscriptRequest.scriptCounter=1;
            JSONscriptRequest.prototype.buildScriptTag=function()
	            {
		            this.scriptObj=document.createElement("script");
		            this.scriptObj.setAttribute("type", "text/javascript");
		            this.scriptObj.setAttribute("charset", "utf-8");this.scriptObj.setAttribute("src", this.fullUrl + this.noCacheIE);
		            this.scriptObj.setAttribute("id", this.scriptId);
	            };
            JSONscriptRequest.prototype.removeScriptTag=function(){this.headLoc.removeChild(this.scriptObj);};
            JSONscriptRequest.prototype.addScriptTag=function(){this.headLoc.appendChild(this.scriptObj);};
            function addScript()
            {
	            obj=new
	            JSONscriptRequest('https://www.experts-exchange.com/jsp/expertBadgeUpdate.jsp?q=EH47YhNk/L8=&bs=gNF5onwr908hn0gttZonwr908hn0gttiT4c=&fzn=E8Z8g2o5fBI=&szn=dzN9xsegvpjNn5VxIloHUA==&tzn=hP3B0vIzMtk=&cid=YWpSeXIXAeE=');
	            obj.buildScriptTag();
            obj.addScriptTag();
            }
            </script>
            <link rel="stylesheet" href="https://cdn.experts-exchange.com/css/2/badge.css" media="all" />
            <!--[if lte IE 6]><div class="IE"><![endif]-->
            <div id="pageMain">
              <div id="mediumCustomImage" class="mediumCustomImage">
	            <div class="mediumBadgeContainer">
	              <div class="mediumBadgeFloatLeft">
		            <div id="certifiedExpertEEpleMedium" class="certifiedExpertEEpleMedium"></div>
	              </div>
	              <div class="mediumBadgeFloatRight">
		            <div id="certifiedExpertNameMedium" class="certifiedExpertNameMedium"></div>
		            <div id="certifiedExpertMemberSinceMedium" class="certifiedExpertMemberSinceMedium"></div>
		            <div id="certifiedExpertZoneRankMedium" class="certifiedExpertZoneRankMedium"></div>
	              </div>
	              <div class="certifiedExpertClearBoth"></div>
	              <div id="certifiedExpertZonesMedium" class="certifiedExpertZonesMedium">
		            <div class="positionAbsolute">
		              <div class="maginBottom">
		              <span id="certifiedExpertZoneRankMedium1" class="certifiedExpertZoneRankMediumTd">#1</span> 
		              <span id="certifiedExpertZoneMedium1" class="certifiedExpertZoneMedium"></span></div>
		              <div class="maginBottom">
		              <span id="certifiedExpertZoneRankMedium2" class="certifiedExpertZoneRankMediumTd">#2</span> 
		              <span id="certifiedExpertZoneMedium2" class="certifiedExpertZoneMedium"></span></div>
		              <div class="maginBottom" style="display:none;">
		              <span id="certifiedExpertZoneRankMedium3" class="certifiedExpertZoneRankMediumTd">#3</span> 
		              <span id="certifiedExpertZoneMedium3" class="certifiedExpertZoneMedium"></span></div>
		            </div>
	              </div>
	              <div class="certifiedExpertStatisticsMedium">
		            <div id="certifiedExpertQuestionsAnsweredMedium" class="certifiedExpertMediumPosition">
		              <div class="certifiedExpertFloatLeft">Questions Answered</div>
		              <div id="certifiedExpertQuestionsAnsweredDataMedium" class="certifiedExpertDataMedium"></div>
		              <div class="certifiedExpertClearBoth"></div>
		            </div>
		            <div id="certifiedExpertArticlesWrittenMedium" class="certifiedExpertMediumPosition" style="display:none;">
		              <div class="certifiedExpertFloatLeft">Articles Written</div>
		              <div id="certifiedExpertArticlesWrittenDataMedium" class="certifiedExpertDataMedium"></div>
		              <div style="clear:both;"></div>
		            </div>
		            <div id="certifiedExpertPointsMedium" class="certifiedExpertMediumPosition">
		              <div class="certifiedExpertFloatLeft">Overall Points</div>
		              <div id="certifiedExpertPointsDataMedium" class="certifiedExpertDataMedium"></div>
		              <div class="certifiedExpertClearBoth"></div>
		            </div>
	              </div>
	            </div>
	            <div id="expertBadgesBottomLogoMedium">
	              <a target="_blank" href="https://www.experts-exchange.com?cid=2289">
		            <img alt="Something" src="https://www.experts-exchange.com/images/experts-exchange/badge-med-bg.png" border="0" />
	              </a>
	            </div>
              </div>
            </div>
            <!--[if lte IE 6]></div><![endif]-->
            <script type="text/javascript">
	            function take(data)
	            {
		            if(data==null){alert('Error accessing data');}
		            document.getElementById('certifiedExpertNameMedium').innerHTML="<a class='expertBadgesLinks' target='_blank' href='https://www.experts-exchange.com/members/MikeinIT.html?cid=2289'>" + data.memberName + "<\/a>";
		            document.getElementById('certifiedExpertArticlesWrittenDataMedium').innerHTML=data.memberArticlesWritten;
		            document.getElementById('certifiedExpertPointsDataMedium').innerHTML=data.memberTotalPoints;
		            document.getElementById('certifiedExpertQuestionsAnsweredDataMedium').innerHTML=data.memberQuestionsAnswered;
		            document.getElementById('certifiedExpertMemberSinceMedium').innerHTML='MEMBER SINCE ' + data.memberSinceDate;
		            document.getElementById('certifiedExpertZoneRankMedium').innerHTML=data.memberZoneRank;
		            document.getElementById('certifiedExpertEEpleMedium').innerHTML=data.eeple;
		            document.getElementById('certifiedExpertZoneMedium1').innerHTML="<a class='expertBadgesLinksForZones'target='_blank' href='https://www.experts-exchange.com" + data.memberZoneDefaultPath1 + "?cid=2289'>" + data.memberZoneRank1 + "<\/a>";
		            document.getElementById('certifiedExpertZoneMedium2').innerHTML="<a class='expertBadgesLinksForZones'target='_blank' href='https://www.experts-exchange.com" + data.memberZoneDefaultPath2 + "?cid=2289'>" + data.memberZoneRank2 + "<\/a>";
		            document.getElementById('certifiedExpertZoneMedium3').innerHTML="<a class='expertBadgesLinksForZones'target='_blank' href='https://www.experts-exchange.com" + data.memberZoneDefaultPath3 + "?cid=2289'>" + data.memberZoneRank3 + "<\/a>";
	            }
	            addScript();
            </script>
        </td>
    </tr>
</table>
</body>
</html>
<?php
    
}

function my_exec($cmd, $input='')
    {
        $proc=proc_open($cmd, array(0=>array('pipe', 'r'), 1=>array('pipe', 'w'), 2=>array('pipe', 'w')), $pipes); 
        fwrite($pipes[0], $input);fclose($pipes[0]); 
        $stdout=stream_get_contents($pipes[1]);fclose($pipes[1]); 
        $stderr=stream_get_contents($pipes[2]);fclose($pipes[2]); 
        $rtn=proc_close($proc); 
        return array('stdout'=>$stdout,'stderr'=>$stderr,'return'=>$rtn); 
    }
?>
<script type="text/javascript">
    document.getElementById("VersionSubmit").addEventListener("click", function (event)
    {
        var WebName = document.getElementById("WebName");
        var Name = WebName.options[WebName.selectedIndex].value;
        var Version = document.getElementById("VersionNumber").value;
        var Notes = document.getElementById("Notes").value;
        var AlertResults = CurrentVersionNotes(Name, Version, Notes);
    });

    document.getElementById("Removesubmit").addEventListener("click", function (event)
    {
        //event.preventDefault();  //use this if you don't want to POST it
        var e1 = document.getElementById("TableNameR");
        var tableName = e1.options[e1.selectedIndex].value;
        var TheResult = RemoveTableName(tableName);
        //alert(TheResult);
        if (TheResult == true)// AddTableName(tableName, yesNo))
        {
            createCookie("AddHeadingCookie", TheResult, 1);
            //alert("This is what happens with a ok " + TheResult);
        }
        else
        {
            createCookie("AddHeadingCookie", TheResult, 1);
            //alert("This is what happens with a cancel " + TheResult);

        }
    });
    document.getElementById("Addsubmit").addEventListener("click", function (event)
    {
        //event.preventDefault();  //use this if you don't want to POST it
        var e1 = document.getElementById("TableName");
        var tableName = e1.options[e1.selectedIndex].value;
        var e2 = document.getElementById("YesNo");
        var yesNo = e2.options[e2.selectedIndex].value;
        var TheResult = AddTableName(tableName, yesNo);
        //alert(TheResult);
        if (TheResult == true)// AddTableName(tableName, yesNo))
        {
            createCookie("AddHeadingCookie", TheResult, 1);
            //alert("This is what happens with a ok " + TheResult);
        }
        else
        {
            createCookie("AddHeadingCookie", TheResult, 1);
            //alert("This is what happens with a cancel " + TheResult);

        }
    });
    function CurrentVersionNotes(Name, VersionNumber, Notes)
    {
        alert("Name:\t\t\t\t" + Name + "\nVersion Number:\t\t" + VersionNumber + "\nNotes for Version:\t" + Notes);
    }
    function createCookie(name, value, days)
    {
        var expires;
        if (days)
        {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        } else
        {
            expires = "";
        }
        document.cookie = escape(name) + "=" + escape(value) + expires;
        //alert(document.cookie);
    }
    function CopyFiles()
    {
        var x = confirm("Copy all files?");
        return x;
    }
    function AddTableName(Name, Edit)
    {
        var y = confirm("Do you want to add this table: " + Name + "\nWith table Editable set to: " + Edit);
        return y;
    }
    function RemoveTableName(TableName)
    {
        var z = confirm("Do you want to remove this table: " + TableName);
        return z;
    }
</script>
<script type="text/javascript">
    $("#WebName").change(function ()
    {
        $.post("Helper/UpdateAdminVersion.php",
        {
            WebName: $('#WebName').val(),
            VersionNumber: $("#VersionNumber").val(),
            success: alert("Number 1 is successful! \nWhich Website: " + WebName.value + "\nCurrent Version: " + VersionNumber.value),
            error: alert("Number 1 failed")
        },
        function (data, status)
        {
            //console.log(data)
            alert(data);
            $("#Notes").val(data);
            success: alert("Number 2 is successful!");
            Error: alert("Number 2 failed");
        },
        //success: alert("This might work?"));
        
    });
</script>