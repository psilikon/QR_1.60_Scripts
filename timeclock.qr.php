<?php

	ob_start();
	session_start();
	
	// required includes
	// IN TEMPLATE - SO CORRECT THE PATHS
	require_once("../inc/constants.php");
	require_once("../inc/functions.php");
	require_once("../cls/idatabase.php");
	require_once("../cls/database.php");
	require_once("../cls/qresource.php");

	// GATHER ALL DATA - INIT PAGE
	$data = array();
	$data = init($data);
	
	// IMPORTANT - this file uses direct access
	// iDatabase object always - even on 
	// selects and inserts - timeclock must
	// be perfect and thus we don't read from slave server
	// connection - see iDatabase and Database objects for details.
	$idb = new iDatabase($data['isDebugMode']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        
<?
// show fronter/closer sales
// stats? turns true when clocked out
$allowStats = false;

$version = '2.0.5-4';
$build = '80602-0641';

$StarTtimE = date("U");
$NOW_TIME = date("Y-m-d H:i:s");

///-->QR
// rounded times (to 1/4 hour)
$r_StarTtimE = getRoundedTime($StarTtimE);
$r_NOW_TIME = date("Y-m-d H:i:s", $r_StarTtimE);
///

$last_action_date = $NOW_TIME;

$US='_';
$CL=':';
$AT='@';
$DS='-';
$date = date("r");
$ip = getenv("REMOTE_ADDR");
$browser = getenv("HTTP_USER_AGENT");
$script_name = getenv("SCRIPT_NAME");
$server_name = getenv("SERVER_NAME");
$server_port = getenv("SERVER_PORT");
if (eregi("443", $server_port)){
	$HTTPprotocol = 'https://';
}
else{
	$HTTPprotocol = 'http://';
}
if(($server_port == '80') or ($server_port == '443') ) {
	$server_port='';
}
else {
	$server_port = "$CL$server_port";
}
$agcPAGE = "$HTTPprotocol$server_name$server_port$script_name";
$agcDIR = eregi_replace('timeclock.php', '', $agcPAGE);

// defaults
$phone_login= "";
$phone_pass	= "";
$VD_login	= "";
$VD_pass	= "";
$VD_campaign= "";
$user		= "";
$pass		= "";
$stage		= "";
$commit		= "";
$referrer	= "";

if(isset($data["phone_login"])){
	$phone_login = $data["phone_login"];
}
if(isset($data["pl"])){
	$phone_login = $data["pl"];
}
if(isset($data["phone_pass"])){
	$phone_pass = $data["phone_pass"];
}
if(isset($data["pp"])){
	$phone_pass = $data["pp"];
}
if(isset($data["VD_login"])){
	$VD_login = $data["VD_login"];
}
if(isset($data["VD_pass"])){
	$VD_pass = $data["VD_pass"];
}
if(isset($data["VD_campaign"])){
	$VD_campaign = $data["VD_campaign"];
}
if(isset($data["stage"])){
	$stage = $data["stage"];
}
if(isset($data["commit"])){
	$commit = $data["commit"];
}
if(isset($data["referrer"])){
	$referrer = $data["referrer"];
}
if(isset($data["user"])){
	$user = $data["user"];
}
if(isset($data["pass"])){
	$pass = $data["pass"];
}
	
### security strip all non-alphanumeric characters out of the variables ###
	//$DB=ereg_replace("[^0-9a-z]","",$DB);
	$phone_login	= ereg_replace("[^\,0-9a-zA-Z]","",	$phone_login);
	$phone_pass		= ereg_replace("[^0-9a-zA-Z]",	"",	$phone_pass);
	$VD_login		= ereg_replace("[^0-9a-zA-Z]",	"",	$VD_login);
	$VD_pass		= ereg_replace("[^0-9a-zA-Z]",	"",	$VD_pass);
	$VD_campaign	= ereg_replace("[^0-9a-zA-Z_]",	"",	$VD_campaign);
	$user			= ereg_replace("[^0-9a-zA-Z]",	"",	$user);
	$pass			= ereg_replace("[^0-9a-zA-Z]",	"",	$pass);
	$stage			= ereg_replace("[^0-9a-zA-Z]",	"",	$stage);
	$commit			= ereg_replace("[^0-9a-zA-Z]",	"",	$commit);
	$referrer		= ereg_replace("[^0-9a-zA-Z]",	"",	$referrer);

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
$stmt = "SELECT use_non_latin,admin_home_url FROM system_settings;";
//$rslt = mysql_query($stmt, $link);
$rslt = $idb->getResultSet($stmt);
//if ($DB) {echo "$stmt\n";}
$qm_conf_ct = mysql_num_rows($rslt);
$i = 0;
while ($i < $qm_conf_ct){
	$row = mysql_fetch_row($rslt);
	$non_latin =	$row[0];
	$welcomeURL =	$row[1];
	$i++;
}
##### END SETTINGS LOOKUP #####
###########################################

header ("Content-type: text/html; charset=utf-8");
header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?=$PGTITLE?></title>
		<link href="../css/styles.css" rel="stylesheet" type="text/css" media="screen" />
		<script language="javascript" src="../js/functions.js"></script>
        <script language="javascript" src="../js/xbrowser.js"></script>
        <script language="javascript" src="../js/validation.js"></script>
        <script language="javascript" src="../js/formatting.js"></script>
	<style type="text/css">
			.logintable{
			 	 background-color:#6699FF;
				 border-top:1px solid #666666;
				 border-left:1px solid #666666;
				 border-bottom:2px solid #000000;
				 border-right:2px solid #000000;
				 font-family:Verdana;
				 font-size:12px;
				 width:450px;
				 margin:50px 0px 0px 0px;
			}
			.ver{
				font-size:10px;
				color:#3366FF;
			}
        </style>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class='noprint'>
  <tr>
    <td style='height:60px;background-color:#182029;background-image:url(../img/headlogo.png); background-repeat:no-repeat; background-position:center center;'>&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#182029" style="background-image:url(../img/menubg.png); background-repeat:repeat-x;height:14px;"></td>
  </tr>
</table>
<table border='0' cellpadding="10" cellspacing="0" align='center' class='noprint' >
    <tr>
        <td align='center' class='reportTitle'>Time Clock</td>
    </tr>
</table>
<form action='<?=$PHPSELF?>' method='POST' name='form_main' id='form_main' style='margin:0px;padding:0px;'>
    <div style='padding:0px;margin:0px;width:100%;'>     
      <div align='center' style='text-align:-moz-center;'>
<?
// login form was submitted
if(($stage == 'login') or ($stage == 'logout')){
	### see if user/pass exist for this user in vicidial_users table
	//$stmt = "SELECT count(*) from vicidial_users where user='$user' and pass='$pass' and user_level > 0;";	
	$stmt = "SELECT count(*) FROM vicidial_users WHERE user='$user' AND pass='$pass' AND user_level > 0 AND active = 'Y';";	
	$rslt = $idb->getResultSet($stmt);	
	$row = mysql_fetch_row($rslt);
	$valid_user = $row[0];
	print "<!-- vicidial_users active count for $user:   |$valid_user| -->\n";

	if ($valid_user < 1){
		### NOT A VALID USER/PASS
		$VDdisplayMESSAGE = "The user and password you entered are not active in the system<BR>Please try again...";
?>
        <input type='hidden' name='referrer' value='<?=$referrer?>' />
        <input type='hidden' name='stage' value='login' />
        <input type='hidden' name='phone_login' value='<?=$phone_login?>' />
        <input type='hidden' name='phone_pass' value='<?=$phone_pass?>' />
        <input type='hidden' name='VD_login' value='<?=$VD_login?>' />
        <input type='hidden' name='VD_pass' value='<?=$VD_pass?>' />
        <table cellpadding='0' cellspacing='0' class="logintable">
          <tr>
            <td colspan="2" align='left' valign='bottom'>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" align='left' valign='bottom'>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" align='left' valign='bottom'>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" align='left' valign='bottom'>
            <div align="center"><?=$VDdisplayMESSAGE?></div>
            </td>
		  </tr>
          <tr>
          <td align="left" colspan="2">&nbsp;  </td></tr>
          <tr>
            <td align="right" width="50%">User Login:  </td>
		  <td align="left"><input type='text' name='user' size="10" maxlength="20" value='<?=$VD_login?>' /></td></tr>
          <tr>
            <td align="right">User Password:  </td>
		  <td align="left"><input type='password' name='pass' id='pass' size="10" maxlength="20" value='' /></td></tr>
          <tr>
            <td align="center" colspan="2">&nbsp;</td>
	      </tr>
          <tr>
            <td align="center" colspan="2"><input type='submit' name='SUBMIT' value='LOGIN' /></td>
		  </tr>
          <tr><td colspan="2"><div style='height:50px;'></div></td></tr>
          <tr>
          <td align="left" colspan="2"><div align="center" class='ver'>VERSION: <?=$version?>&nbsp; &nbsp; &nbsp; BUILD: <?=$build?></div></td></tr>
        </table>
  <?
	}
	else{

		### VALID USER/PASS, CONTINUE
		### get name and group for this user
		$stmt = "SELECT u.full_name, u.user_group, q.payroll_shift, q.user_type
				FROM vicidial_users u, qri_users q
				WHERE u.user='$user' AND u.pass='$pass' AND u.user = q.user;";	
		$rslt = $idb->getResultSet($stmt);	
		$row = mysql_fetch_row($rslt);
		$full_name =	$row[0];
		$user_group =	$row[1];
		$payroll_shift = $row[2];
		$user_type =	$row[3];
		print "<!-- vicidial_users name and group for $user:   |$full_name|$user_group| -->\n";

		### get vicidial_timeclock_status record count for this user
		$stmt = "SELECT count(*) FROM vicidial_timeclock_status where user='$user';";
		$rslt = $idb->getResultSet($stmt);	
		$row = mysql_fetch_row($rslt);
		$vts_count =	$row[0];

		$last_action_sec = 99;

		if ($vts_count > 0){
			### vicidial_timeclock_status record found, grab status and date of last activity
			$stmt = "SELECT status,event_epoch FROM vicidial_timeclock_status where user='$user';";
			$rslt = $idb->getResultSet($stmt);
			$row = mysql_fetch_row($rslt);
			$status =		$row[0];
			$event_epoch =	$row[1];
			$last_action_date = date("Y-m-d H:i:s", $event_epoch);
			$last_action_sec = ($StarTtimE - $event_epoch);
			
			// get rounded time
			$r_event_epoch = getRoundedTime($event_epoch);
			$r_last_action_sec = ($r_StarTtimE - $r_event_epoch);	

			if ($last_action_sec > 0){
				$totTIME_H = ($last_action_sec / 3600);
				$totTIME_H_int = round($totTIME_H, 2);
				$totTIME_H_int = intval("$totTIME_H");
				$totTIME_M = ($totTIME_H - $totTIME_H_int);
				$totTIME_M = ($totTIME_M * 60);
				$totTIME_M_int = round($totTIME_M, 2);
				$totTIME_M_int = intval("$totTIME_M");
				$totTIME_S = ($totTIME_M - $totTIME_M_int);
				$totTIME_S = ($totTIME_S * 60);
				$totTIME_S = round($totTIME_S, 0);
				if (strlen($totTIME_H_int) < 1) {$totTIME_H_int = "0";}
				if ($totTIME_M_int < 10) {$totTIME_M_int = "0$totTIME_M_int";}
				if ($totTIME_S < 10) {$totTIME_S = "0$totTIME_S";}
				$totTIME_HMS = "$totTIME_H_int:$totTIME_M_int:$totTIME_S";
			}
			else{
				$totTIME_HMS='0:00:00';
			}
			print "<!-- vicidial_timeclock_status previous status for $user:   |$status|$event_epoch|$last_action_sec| -->\n";
		}
		else{
			### No vicidial_timeclock_status record found, insert one
			$stmt = "INSERT INTO vicidial_timeclock_status 
					set status='START', user='$user', user_group='$user_group', event_epoch='$StarTtimE', ip_address='$ip';";
			$rslt = $idb->execute($stmt);
			$status = 'START';
			$totTIME_HMS = '0:00:00';
			$affected_rows = $idb->affRows();
			print "<!-- NEW vicidial_timeclock_status record inserted for $user:   |$affected_rows| -->\n";
		}
		
		if(($last_action_sec < 30) and ($status != 'START')){
			### You cannot log in or out within 30 seconds of your last login/logout
			$VDdisplayMESSAGE = "You cannot Clock In or Clock Out within 30<br />seconds of your last Clock In/Clock Out.";
?>
        <input type="hidden" name="stage" value="login" />
        <input type="hidden" name="referrer" value="<?=$referrer?>" />
        <input type="hidden" name="phone_login" value="<?=$phone_login?>" />
        <input type="hidden" name="phone_pass" value="<?=$phone_pass?>" />
        <input type="hidden" name="VD_login" value="<?=$VD_login?>" />
        <input type="hidden" name="VD_pass" value="<?=$VD_pass?>" />
        <table cellpadding="0" cellspacing="0" class="logintable">
          <tr>
            <td colspan="2" align="left" valign="bottom">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" align="left" valign="bottom">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" align="left" valign="bottom">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" align="left" valign="bottom"><div align="center">
              <?=$VDdisplayMESSAGE?>
            </div></td>
		  </tr>
          <tr>
          <td align="left" colspan="2">&nbsp;  </td></tr>
          <tr>
            <td align="right" width="50%"><!--User Login:-->&nbsp;  </td>
	      <td align="left"><input type="hidden" name="user" size="10" maxlength="20" value="<?=$user?>" /></td></tr>
          <tr>
            <td align="right"><!--User Password:--> &nbsp; </td>
	      <td align="left"><input type="hidden" name="pass" size="10" maxlength="20" value='<?=$pass?>' /></td></tr>
          <tr>
          <td align="center" colspan="2"><input type="submit" name="SUBMIT" class="btn-std" value="Click Here to Try Again" /> &nbsp; </td></tr>
          <tr><td colspan="2"><div style='height:50px;'></div></td></tr>
          <tr>
          <td align="left" colspan="2"><div align="center" class='ver'>VERSION: <?=$version?>&nbsp; &nbsp; &nbsp; BUILD: <?=$build?></div></td></tr>
        </table>
  <?
		}
		elseif($commit == 'YES'){
			
			if(((($status=='AUTOLOGOUT') or ($status=='START') or ($status=='LOGOUT')) and ($stage=='logout')) or (($status=='LOGIN') and ($stage=='login'))){
				$VDdisplayMESSAGE = "Entry already exists:<br/>";
				if(($status=='LOGIN') and ($stage=='login')){
					$VDdisplayMESSAGE .= "You are trying to Clock In while already clocked in.<br/>";
				}
				else{
					$VDdisplayMESSAGE .= "You are trying to Clock Out while already clocked out.<br/>";
				}
				$VDdisplayMESSAGE .= "<a href='$PHPSELF'>Click Here to start over and try again.</a>";
?>
        <table cellpadding="0" cellspacing="0" class="logintable">
  <tr>
    <td align="left" class='errormsg' valign="bottom">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" class='errormsg' valign="bottom">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" class='errormsg' valign="bottom">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" class='errormsg' valign="bottom"><div align="center">There was an error!</div></td>
                  </tr>
          <tr>
            <td align="left">&nbsp;</td></tr>
          <tr>
            <td align="center"> <?=$VDdisplayMESSAGE?> <br />
            &nbsp; </td></tr>
          <tr><td><div style='height:50px;'></div></td></tr>
          <tr>
            <td align="left"><div align="center" class='ver'>VERSION: <?=$version?>
  &nbsp; &nbsp; &nbsp; BUILD:
  <?=$build?></div></td></tr>
        </table>
  <?
			}
			else{			
				if ( ( ($status=='AUTOLOGOUT') or ($status=='START') or ($status=='LOGOUT') ) and ($stage=='login') ){
					$VDdisplayMESSAGE = "<span style='font-size:16px;font-weight:bold;color:#990000;'>$full_name<br/>You have now CLOCKED IN</span>";
					$LOGtimeMESSAGE = "You CLOCKED IN at $NOW_TIME";
					
					### Add a record to the timeclock log, timeclock status table, and timeclock audit log tables					
					// INSERT LOGIN RECORDS
					$stmt = "INSERT INTO vicidial_timeclock_log 
							set event='LOGIN', user='$user', user_group='$user_group', event_epoch='$StarTtimE', ip_address='$ip', event_date='$NOW_TIME';";
					$rslt = $idb->execute($stmt);
					$affected_rows = $idb->affRows();
					$timeclock_id = $idb->recId();
					print "<!-- NEW vicidial_timeclock_log record inserted for $user:   |$affected_rows|$timeclock_id| -->\n";
	
					### Update the user's timeclock status record
					$stmt = "UPDATE vicidial_timeclock_status 
							set status='LOGIN', user_group='$user_group', event_epoch='$StarTtimE', ip_address='$ip' where user='$user';";
					$rslt = $idb->execute($stmt);
					$affected_rows = $idb->affRows();
					print "<!-- vicidial_timeclock_status record updated for $user:   |$affected_rows| -->\n";
	
					### Add a record to the timeclock audit log
					$stmt = "INSERT INTO vicidial_timeclock_audit_log 
							set timeclock_id='$timeclock_id', event='LOGIN', user='$user', user_group='$user_group', event_epoch='$StarTtimE', ip_address='$ip', event_date='$NOW_TIME';";
					$rslt = $idb->execute($stmt);
					$affected_rows = $idb->affRows();
					print "<!-- NEW vicidial_timeclock_audit_log record inserted for $user:   |$affected_rows| -->\n";
					
					/*
					///-->QR	
					MOVED TO MYSQL TRIGGERS ON vicidial_timeclock_log
					// Add a record to the qri timeclock log
					// with rounded time entries and 
					// payroll shift information
					$stmt = "INSERT INTO qri_timeclock_log
							SET timeclock_id = '$timeclock_id', 
								rnd_event_epoch = '$r_StarTtimE', 
								rnd_event_date = '$r_NOW_TIME', 
								rnd_login_sec = '0', 
								event = 'LOGIN', 
								user = '$user', 
								user_type = '$user_type', 
								payroll_shift = '$payroll_shift' ; ";
					$rslt = $idb->execute($stmt);
					$affected_rows = $idb->affRows();
					print "<!-- NEW qri_timeclock_log clock in record inserted for $user:|$affected_rows| -->\n";
					*/
					
					$tc_eventtime = date("h:i:s A", $StarTtimE);
					$header_tc_status = "CLOCKED IN ($tc_eventtime)";
					$header_tc_color = "#009900";
					$BACKlink = "<a href='http://10.10.15.2/agc/vicidial.php";
					$addamp = "?";
					if(strlen($phone_login)>0){
						$BACKlink .= $addamp . "pl=$phone_login";
						$addamp = "&";
					}
					if(strlen($phone_pass)>0){
						$BACKlink .= $addamp . "pp=$phone_pass";
						$addamp = "&";
					}
					if(strlen($user)>0){
						$BACKlink .= $addamp . "VD_login=$user";
						$addamp = "&";
					}
					if(strlen($pass)>0){
						$BACKlink .= $addamp . "VD_pass=$pass";
						$addamp = "&";
					}
					$BACKlink .= "'>Agent Phone Login Screen</a>";			
				}
				elseif ( ($status=='LOGIN') and ($stage=='logout') ){
					$VDdisplayMESSAGE = "<span style='font-size:16px;font-weight:bold;color:#990000;'>$full_name<br/>You have now CLOCKED OUT</span>";
					$LOGtimeMESSAGE = "You CLOCKED OUT at $NOW_TIME<BR>Amount of time you were CLOCKED IN: $totTIME_HMS";					
					### Add a record to the timeclock log
					### Update last login record in the timeclock log
					### Update the user's timeclock status record
					### Add a record to the timeclock audit log
					### Update last login record in the timeclock audit log
					// INSERT LOGOUT RECORDS
					$stmt = "INSERT INTO vicidial_timeclock_log 
							set event='LOGOUT', user='$user', user_group='$user_group', event_epoch='$StarTtimE', ip_address='$ip', login_sec='$last_action_sec', event_date='$NOW_TIME';";
					$rslt = $idb->execute($stmt);
					$affected_rows = $idb->affRows();
					$timeclock_id = $idb->recId();
					print "<!-- NEW vicidial_timeclock_log record inserted for $user:   |$affected_rows|$timeclock_id| -->\n";
										
					### Update last login record in the timeclock log
					$stmt = "UPDATE vicidial_timeclock_log 
							set login_sec='$last_action_sec',tcid_link='$timeclock_id' where event='LOGIN' and user='$user' order by timeclock_id desc limit 1;";
					$rslt = $idb->execute($stmt);
					$affected_rows = $idb->affRows();
					print "<!-- vicidial_timeclock_log record updated for $user:   |$affected_rows| -->\n";
					
					$tc_eventtime = date("h:i:s A", $StarTtimE);
					$header_tc_status = "CLOCKED OUT ($tc_eventtime)";
					$header_tc_color = "#990000";
					
					### Update the user's timeclock status record
					$stmt = "UPDATE vicidial_timeclock_status 
							set status='LOGOUT', user_group='$user_group', event_epoch='$StarTtimE', ip_address='$ip' where user='$user';";
					$rslt = $idb->execute($stmt);
					$affected_rows = $idb->affRows();
					print "<!-- vicidial_timeclock_status record updated for $user:   |$affected_rows| -->\n";
	
					### Add a record to the timeclock audit log
					$stmt = "INSERT INTO vicidial_timeclock_audit_log 
							set timeclock_id='$timeclock_id', event='LOGOUT', user='$user', user_group='$user_group', event_epoch='$StarTtimE', ip_address='$ip', login_sec='$last_action_sec', event_date='$NOW_TIME';";
					$rslt = $idb->execute($stmt);
					$affected_rows = $idb->affRows();
					print "<!-- NEW vicidial_timeclock_audit_log record inserted for $user:   |$affected_rows| -->\n";
	
					### Update last login record in the timeclock audit log
					$stmt = "UPDATE vicidial_timeclock_audit_log 
							set login_sec='$last_action_sec',tcid_link='$timeclock_id' where event='LOGIN' and user='$user' order by timeclock_id desc limit 1;";
					$rslt = $idb->execute($stmt);
					$affected_rows = $idb->affRows();
					print "<!-- vicidial_timeclock_audit_log record updated for $user:   |$affected_rows| -->\n";
					$BACKlink = "";	
					$allowStats = true;			
				}	
				// display timeclock status
?>
        <table cellpadding="0" cellspacing="0" class="logintable">
          <tr>
            <td align="left" valign="bottom">&nbsp;</td>
          </tr>
          <tr>
            <td align="left" valign="bottom">&nbsp;</td>
          </tr>
          <tr>
            <td align="left" valign="bottom">&nbsp;</td>
          </tr>
          <tr>
            <td align="left" valign="bottom"><div align="center"><?=$VDdisplayMESSAGE?></div></td>
          </tr>
          <tr>
          <td align="left">&nbsp;  </td></tr>
          <tr>
            <td align="center"> <?=$LOGtimeMESSAGE?><br />
          &nbsp; </td></tr>
          <tr>
            <td align="center"> <?=$BACKlink?> <br />
          &nbsp; </td></tr>
          <tr><td><div style='height:50px;'></div></td></tr>
          <tr>
          <td align="left"><div align="center" class='ver'>VERSION:<?=$version?>&nbsp; &nbsp; &nbsp; BUILD: <?=$build?></div>            </td></tr>
        </table>
        <script language="JavaScript" type="text/javascript">
			function childOnload(){
				getObj("txtTimeClockStatus").innerHTML = '<?=$header_tc_status?>';
				getObj("txtTimeClockStatus").style.color = '<?=$header_tc_color?>';
			}
		</script>
  <?
			}
		}
		else{
			if ( ($status=='AUTOLOGOUT') or ($status=='START') or ($status=='LOGOUT') ){
				$UserIdentMessage = "<span style='font-size:16px;font-weight:bold;color:#990000;'>CLOCKING IN User: " . $full_name . "</span>";
				$VDdisplayMESSAGE = "Time since you were last CLOCKED IN: $totTIME_HMS";
				$log_action = 'login';
				$button_name = 'LOGIN';
				$button_name_display = 'CLOCK IN';
				$LOGtimeMESSAGE = "You last CLOCKED OUT at: $last_action_date<BR><BR>Click 'CLOCK IN' below to CLOCKED IN";
			}
			if ($status=='LOGIN'){
				$UserIdentMessage = "<span style='font-size:16px;font-weight:bold;color:#990000;'>CLOCKING OUT User: " . $full_name . "</span>";
				$VDdisplayMESSAGE = "Amount of time you have been CLOCKED IN: $totTIME_HMS";
				$log_action = 'logout';
				$button_name = 'LOGOUT';
				$button_name_display = 'CLOCK OUT';
				$LOGtimeMESSAGE = "You CLOCKED IN at: $last_action_date<BR>Amount of time you have been CLOCKED IN: $totTIME_HMS<BR><BR>Click CLOCK OUT below to clock-out";
			}
			// DISPLAY CLOCK IN OR CLOCK OUT BUTTON
			// DEPENDING ON CURRENT STATE
	?>
        <input type="hidden" name="stage" value="<?=$log_action?>" />
        <input type="hidden" name="commit" value="YES" />
        <input type="hidden" name="referrer" value="<?=$referrer?>" />
        <input type="hidden" name="phone_login" value="<?=$phone_login?>" />
        <input type="hidden" name="phone_pass" value="<?=$phone_pass?>" />
        <input type="hidden" name="VD_login" value="<?=$VD_login?>" />
        <input type="hidden" name="VD_pass" value="<?=$VD_pass?>" />
        <input type="hidden" name="user" value="<?=$user?>" />
        <input type="hidden" name="pass" value="<?=$pass?>" />
        <table cellpadding="0" cellspacing="0" class="logintable">
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><?=$UserIdentMessage?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><div align="center"><?=$VDdisplayMESSAGE?></div></td>
		  </tr>
          <tr>
          <td>&nbsp; </td></tr>
          <tr>
            <td align="center"><?=$LOGtimeMESSAGE?></td>
		  </tr>
          <tr>
            <td align="center">&nbsp;</td>
	      </tr>
          <tr>
            <td align="center"><input type="submit" class="btn-std" name="<?=$button_name?>" value="<?=$button_name_display?>" /></td>
		  </tr>
          <tr><td><div style='height:30px;'></div></td></tr>
          <tr>
            <td align="left">
              <div align="center" class='ver'>VERSION: 
                <?=$version?>
  &nbsp; &nbsp; &nbsp; BUILD:
  <?=$build?>
          </div></td></tr>
        </table>
  <?
		}
	}
}
else{
	if(!isset($VDdisplayMESSAGE)){
		$VDdisplayMESSAGE = "";
	}
	// NEW TO PAGE - NEED USER LOGIN INFO
?>
        <input type="hidden" name="stage" value="login" />
        <input type="hidden" name="referrer" value="<?=$referrer?>" />
        <input type="hidden" name="phone_login" value="<?=$phone_login?>" />
        <input type="hidden" name="phone_pass" value="<?=$phone_pass?>" />
        <input type="hidden" name="VD_login" value="<?=$VD_login?>" />
        <input type="hidden" name="VD_pass" value="<?=$VD_pass?>" />
        <table width="460" cellpadding="0" cellspacing="0" class="logintable">
          <tr>
            <td colspan="2" align="left" valign="bottom">&nbsp;</td>
	      </tr>
          <tr>
            <td colspan="2" align="left" valign="bottom">&nbsp;</td>
	      </tr>
          <tr>
            <td colspan="2" align="left" valign="bottom">&nbsp;</td>
	      </tr>
          <tr>
            <td colspan="2" align="left" valign="bottom">
            <div align="center"><?=$VDdisplayMESSAGE?></div>
            </td>
	  </tr>
          <tr>
          <td align="left" colspan="2">&nbsp;  </td></tr>
          <tr>
            <td align="right" width="50%">User Login:  </td>
	  <td align="left"><input type="text" name="user" size="10" maxlength="20" value="<?=$VD_login?>" /></td></tr>
          <tr>
            <td align="right">User Password:  </td>
	  <td align="left"><input type='password' name='pass' id='pass' size="10" maxlength="20" value='' /></td></tr>
          <tr>
            <td align="center" colspan="2">&nbsp;</td>
	    </tr>
          <tr>
            <td align="center" colspan="2"><input type='submit' name='SUBMIT' value='LOGIN' class="btn-std" /></td>
	  </tr>
          <tr><td colspan="2"><div style='height:50px;'></div></td></tr>
          <tr>
            <td align="left" colspan="2">
              <div align="center" class='ver'>VERSION: <?=$version?>  &nbsp; &nbsp; &nbsp; BUILD:  <?=$build?>
          </div></td></tr>
        </table>
  <?
}
?>
      </div>
    </div>
	<input type='hidden' name='isPostBack' value='true'>
    </form>
<?
	if($data["isDebugMode"] == true){
?>
	<div class="debug-floater" id="debug-floater">
        <div <?=$ONMOUSE?> style="height:26px;width:100%;">
            <span id='dbwinshowhide' onclick="toggleObjectDisplay('debug-container');" class="red12">Show/Hide</span>&nbsp;&nbsp;
            <span id='dbwidthwide' onclick="getObj('debug-floater').style.width='80%';getObj('dbwidthwide').style.display='none';getObj('dbwidthshort').style.display='';" class="red12">Wide</span>
        	<span id='dbwidthshort' style="display:none;" onclick="getObj('debug-floater').style.width='20%';getObj('dbwidthshort').style.display='none';getObj('dbwidthwide').style.display='';" class="red12">Thin</span>
			&nbsp;&nbsp;        	
            <span id='dbheighttall' onclick="getObj('debug-container').style.height='600px';getObj('dbheighttall').style.display='none';getObj('dbheightshort').style.display='';" class="red12">Tall</span>
        	<span id='dbheightshort' style="display:none;" onclick="getObj('debug-container').style.height='290px';getObj('dbheightshort').style.display='none';getObj('dbheighttall').style.display='';" class="red12">Short</span>
        </div>
        <div class="debug-container" id="debug-container" <?=(($data["isDebugMode"] == false) ? "style='height:40px;'" : "")?>>
            <?
			if($data["isDebugMode"] == true){
            	foreach($DEBUG_STACK as $dbstack){
					if(!is_array($dbstack)){
						echo "<div class='debug-error'>" . $dbstack . "</div>";
					}
					else{
						print_r($dbstack);
					}
				}
			}
			?>
        </div>    
    </div>
<?
	}
	
	// gather some stats for user
	// connects to slave server for read only access
	if(isset($valid_user) && $valid_user > 0){
		$qr = new QResource($data['isDebugMode']);
		
		
		$edate = date("Y-m-d");
		$sdate = date("Y-m-d", time() - (DAY_SECS * 8));
		$sales = $qr->getAgentSphByDay($user, $sdate, $edate);
		$tcrecs = $qr->getTimeClockHoursByDay($user, $sdate, $edate);
		
		$dayspan = array();
		if($qr->makeDaySpanWithDefaults($sdate, $edate, $dayspan)){
			$stats = $qr->getDailyGrossSales($user, $sdate, $edate, $dayspan);
		}
		
		if($tcrecs !== false && count($tcrecs) > 0){
?>
			<div align="center" style="text-align:-moz-center;">
			<table width="500" cellpadding='4' cellspacing='0' class="logintable" style="background-color:#FFFFFF;">
                <tr>
                	<td colspan="6" bgcolor="#6699FF"><div align="left">Time Clock Summary</div></td>
              </tr>                
                <tr>
                	<td class="colhdr">Date</td>
                	<td class="colhdr">Real Time</td>
                	<td class="colhdr">TC Time</td>
                	<td class="colhdr">Dept.</td>
                	<td class="colhdr">Base Role</td>
                	<td class="colhdr">Shift</td>
                </tr>
				<?
				$bgc = "e4e4e4";
				foreach($tcrecs as $tc){
				?>
                <tr bgcolor="#<?=$bgc?>">
                    <td class="black11"><?=date("Y-m-d", strtotime($tc["event_date"]))?></td>
                    <td class="black11"><?=cdec(csectohour($tc["login_sec"]))?></td>
                    <td class="black11"><?=cdec(csectohour($tc["rnd_login_sec"]))?></td>
                    <td class="black11"><?=$tc["user_group"]?></td>
                    <td class="black11"><?=$tc["user_type_name"]?></td>
                    <td class="black11"><?=$tc["payroll_shift_name"]?></td>
                </tr>
                <?
					$bgc = cbgc($bgc, "e4e4e4", "ffffff");
				}
				?>
            </table>           
            </div>
<?		
		}
		if($allowStats === true){
			if($sales !== false && count($sales)>0){
?>
				<div align="center" style="text-align:-moz-center;">
				<table width="500" cellpadding='4' cellspacing='0' class="logintable" style="background-color:#FFFFFF;">
					<tr>
						<td colspan="6" bgcolor="#6699FF"><div align="left">Sales Summary</div></td>
				  </tr>                
					<tr>
						<td class="colhdr">Date</td>
						<td class="colhdr">Role</td>
						<td class="colhdr">Calls</td>
						<td class="colhdr">Sales</td>
						<td class="colhdr">Phone Time</td>
						<td class="colhdr">SPH</td>
					</tr>
					<?
					$bgc = "e4e4e4";
					foreach($sales as $s){
					?>
					<tr bgcolor="#<?=$bgc?>">
						<td class="black11"><?=$s["stat_date"]?></td>
						<td class="black11"><?=(($s["role"] == "FRONTER") ? $s["role"] : "VERIFYER")?></td>
						<td class="black11"><?=$s["calls"]?></td>
						<td class="black11"><?=$s["sales"]?></td>
						<td class="black11"><?=$s["login_hours"]?> hours</td>
						<td class="black11"><?=$s["sph"]?></td>
					</tr>
					<?
						$bgc = cbgc($bgc, "e4e4e4", "ffffff");
					}
					?>
					<tr bgcolor="#<?=$bgc?>"><td colspan="6" align="center" class="red12" style="text-align:-moz-center;">Sales/SPH DOES NOT INCLUDE rejects.</td></tr>
				</table>            
				</div>
<?
			}
			if($stats){
?>
				<div align="center" style="text-align:-moz-center;">
				<table width="500" cellpadding='4' cellspacing='0' class="logintable" style="background-color:#FFFFFF;">
					<tr>
					  <td colspan="6" bgcolor="#6699FF"><div align="left">Sales Details</div></td>
					</tr> 
					<tr>
						<td colspan="6">As Fronter</td>
					</tr>               
					<tr>
						<td class="colhdr">Date</td>
						<td class="colhdr">A1</td>
						<td class="colhdr">A2</td>
						<td class="colhdr">A3</td>
						<td class="colhdr">A4</td>
						<td class="colhdr">Pts</td>
					</tr>
					<?
					$bgc = "e4e4e4";
					foreach($dayspan as $day){
					?>
					<tr bgcolor="#<?=$bgc?>">
						<td class="black11"><?=$day["date"]?></td>
						<td class="black11"><?=$day["emp_fronter_a1"]?></td>
						<td class="black11"><?=$day["emp_fronter_a2"]?></td>
						<td class="black11"><?=$day["emp_fronter_a3"]?></td>
						<td class="black11"><?=$day["emp_fronter_a4"]?></td>
						<td class="black11"><?=$day["emp_fronter_points"]?></td>
					</tr>
					<?
						$bgc = cbgc($bgc, "e4e4e4", "ffffff");
					}
					?>                
					<tr bgcolor="#<?=$bgc?>"><td colspan="6" align="center" class="red12" style="text-align:-moz-center;">Sales Details NOT INCLUDE rejects.</td></tr>
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>            
					<tr bgcolor="#<?=$bgc?>">
						<td colspan="6">As Verifier</td>
					</tr>             
					<tr>
						<td class="colhdr">Date</td>
						<td class="colhdr">A1</td>
						<td class="colhdr">A2</td>
						<td class="colhdr">A3</td>
						<td class="colhdr">A4</td>
						<td class="colhdr">Pts</td>
					</tr>
					<?
					$bgc = "e4e4e4";
					foreach($dayspan as $day){
					?>
					<tr bgcolor="#<?=$bgc?>">
						<td class="black11"><?=$day["date"]?></td>
						<td class="black11"><?=$day["emp_verifyer_a1"]?></td>
						<td class="black11"><?=$day["emp_verifyer_a2"]?></td>
						<td class="black11"><?=$day["emp_verifyer_a3"]?></td>
						<td class="black11"><?=$day["emp_verifyer_a4"]?></td>
						<td class="black11"><?=$day["emp_verifyer_points"]?></td>
					</tr>
					<?
						$bgc = cbgc($bgc, "e4e4e4", "ffffff");
					}
					?>
					<tr bgcolor="#<?=$bgc?>"><td colspan="6" align="center" class="red12" style="text-align:-moz-center;">Sales Details NOT INCLUDE rejects.</td></tr>
				</table>            
				</div>
				<br /><br /><br />
<?		
			}
		}
		else{
?>
			<div align="center" style="text-align:-moz-center;">
				<table width="500" cellpadding='4' cellspacing='0' class="logintable" style="background-color:#FFFFFF;">
					<tr>
					  <td colspan="6" bgcolor="#6699FF"><div align="center" class="red14">Sales Statistics will be shown after clock out only.</div></td>
					</tr>
				</table>
			</div>
<?		
		}
	}
?>
    </body> 
</html>
<?	
	session_write_close();
	ob_end_flush();
?>