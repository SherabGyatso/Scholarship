<?php
session_start();

include("../includes/globals.inc.php");
include("../includes/functions.inc.php");
$db=dbconnect($DBHOST,$DBUSERNAME,$DBPASSWORD,$DBNAME);

$header="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title>DOE</title>
<link href=\"../style_popup.css\" rel=\"stylesheet\" type=\"text/css\">
<script type=\"text/javascript\" src=\"../includes/functions.js\"></script>
</head>
<body vlink=\"#000033\">
<table cellpadding=\"5\" cellspacing=\"0\" bgcolor=\"#ffffff\" width=\"100%\" height=\"180\">
<tr><td align=\"center\" valign=\"top\">
";
$footer="</td></tr></table></body></html>";
if(isset($_SESSION['logged']) && $_SESSION['logged']==1) {
	$ewn=0;
	if(isset($_POST['submitted']) && $_POST['submitted']==1) {
		$lqry="";
		if(isset($_GET['a']) && $_GET['a']=="a") {
			$qry = "
			INSERT INTO `SchoolCategory` ( `SchoolCategoryId` , `SchoolCategoryName` ) 
			VALUES (
			'', '".$_POST['scname']."'
			)";
			$lqry.=$qry;
			add_log($db,$_SESSION['userid'],$_SESSION['utype'],4,$lqry);
			$ewnid=201;
		} else if(isset($_GET['a']) && $_GET['a']=="e") {
			$qry = "
			UPDATE `SchoolCategory` 
			SET `SchoolCategoryName` = '".$_POST['scname']."' 
			WHERE `SchoolCategoryId` = '".$_GET['id']."' LIMIT 1";
			$ewnid=202;
			$lqry.=$qry;
			add_log($db,$_SESSION['userid'],$_SESSION['utype'],5,$lqry);
		}
		if(mysqli_query($db,$qry)) $ewn=$ewnid;
	}
	
	if(isset($_GET['a'])) {
		if($_GET['a']=="a") {
			$sc_value="";
			$scf="?a=a";
		} else if($_GET['a']=="e") {
			$scf="?a=e&id=".$_GET['id']."";
			$qry = "SELECT * FROM SchoolCategory WHERE SchoolCategoryId='".$_GET['id']."'";
			$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
			$line = mysqli_fetch_array($rs);
			$sc_value=$line['SchoolCategoryName'];
		}
	}
	
	$form="
	<form action=\"$scf\" method=\"post\">
	<b>School Category Name:</b><br /><br>
	<input type=\"text\" size=\"70\" name=\"scname\" maxlength=\"255\" class=\"inputbox\" value=\"$sc_value\" /><br>
<br>
	<input type=\"hidden\" name=\"submitted\" value=\"1\" />
	<input type=\"submit\" value=\"Save\" class=\"button\" />&nbsp;&nbsp;&nbsp;
	<input type=\"button\" value=\"Close window\" onclick=\"window.close()\" class=\"button\" />
	</form>
	";
	
	echo $header;
	if($ewn!=0) {
		echo "
		<script language=\"JavaScript\" type=\"text/JavaScript\">
		window.opener.location='../index.php?pid=100';
		</script>
		";
		display_ewn($ewn,1);
	} 
	echo $form;
	echo $footer;
} else {
	echo $header;
	display_ewn(2,1);
	echo $footer;
}


?>