<? 
### qrstats_totals.php
### 
### Copyright (C) 2007  Matt Florell <vicidial@gmail.com>    LICENSE: GPLv2
###
# CHANGES
#
# 70813-1526 - First Build
# 71008-1436 - Added shift to be defined in dbconnect.php
# 71217-1128 - Changed method for calculating stats
#

require("dbconnect.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];
if (isset($_GET["DB"]))				{$DB=$_GET["DB"];}
	elseif (isset($_POST["DB"]))		{$DB=$_POST["DB"];}
if (isset($_GET["autorefresh"]))				{$autorefresh=$_GET["autorefresh"];}
	elseif (isset($_POST["autorefresh"]))		{$autorefresh=$_POST["autorefresh"];}
if (isset($_GET["query_date"]))				{$query_date=$_GET["query_date"];}
	elseif (isset($_POST["query_date"]))		{$query_date=$_POST["query_date"];}
if (isset($_GET["shift"]))				{$shift=$_GET["shift"];}
	elseif (isset($_POST["shift"]))		{$shift=$_POST["shift"];}
if (isset($_GET["submit"]))				{$submit=$_GET["submit"];}
	elseif (isset($_POST["submit"]))		{$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))				{$SUBMIT=$_GET["SUBMIT"];}
	elseif (isset($_POST["SUBMIT"]))		{$SUBMIT=$_POST["SUBMIT"];}

$PHP_AUTH_USER = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_USER);
$PHP_AUTH_PW = ereg_replace("[^0-9a-zA-Z]","",$PHP_AUTH_PW);

	$stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and pass='$PHP_AUTH_PW' and user_level > 6 and view_reports='1';";
	if ($DB) {echo "|$stmt|\n";}
	$rslt=mysql_query($stmt, $link);
	$row=mysql_fetch_row($rslt);
	$auth=$row[0];

  if( (strlen($PHP_AUTH_USER)<2) or (strlen($PHP_AUTH_PW)<2) or (!$auth))
	{
    Header("WWW-Authenticate: Basic realm=\"VICI-PROJECTS\"");
    Header("HTTP/1.0 401 Unauthorized");
    echo "Invalid Username/Password: |$PHP_AUTH_USER|$PHP_AUTH_PW|\n";
    exit;
	}

$NOW_DATE = date("Y-m-d");
$NOW_TIME = date("Y-m-d H:i:s");
$STARTtime = date("U");
if (!isset($query_date)) {$query_date = $NOW_DATE;}

if (!isset($shift)) 
	{
	$shiftTIME = date("His");
	if ($shiftTIME < 174501)
		{$shift = 'AM';}
	else
		{$shift = 'PM';}
	}

?>

<HTML>
<HEAD>
<STYLE type="text/css">
<!--
   .green {color: white; background-color: green}
   .red {color: white; background-color: red}
   .blue {color: white; background-color: blue}
   .purple {color: white; background-color: purple}
-->
 </STYLE>

<? 
if ($autorefresh>0)
{
echo "<META HTTP-EQUIV=Refresh CONTENT=\"30; URL=$PHP_SELF?autorefresh=$autorefresh\">";
}

echo "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=utf-8\">\n";
echo "<TITLE>VICIDIAL: VDAD Room Sales Points Totals</TITLE></HEAD><BODY BGCOLOR=WHITE>\n";

echo "<FONT SIZE=2>\n\n";


if ($shift == 'AM') 
	{
	$time_BEGIN=$AM_shift_BEGIN;
	$time_END=$AM_shift_END;
	if (strlen($time_BEGIN) < 6) {$time_BEGIN = "00:00:00";}   
	if (strlen($time_END) < 6) {$time_END = "17:45:00";}
	}
if ($shift == 'PM') 
	{
	$time_BEGIN=$PM_shift_BEGIN;
	$time_END=$PM_shift_END;
	if (strlen($time_BEGIN) < 6) {$time_BEGIN = "17:45:01";}
	if (strlen($time_END) < 6) {$time_END = "23:59:59";}
	}
if ($shift == 'ALL') 
	{
	if (strlen($time_BEGIN) < 6) {$time_BEGIN = "00:00:00";}
	if (strlen($time_END) < 6) {$time_END = "23:59:59";}
	}
$query_date_BEGIN = "$query_date $time_BEGIN";   
$query_date_END = "$query_date $time_END";

echo "VICIDIAL: Sales Points Stats                      $NOW_TIME\n";

echo "\n";
echo "---------- TOTALS FOR $query_date_BEGIN to $query_date_END\n\n";



################################################################################
### ROOM A
################################################################################
$stmt="select count(*) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id='CL_QRA_L' and status = 'A1';";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$row=mysql_fetch_row($rslt);
$A1_points = ($row[0] * 1);
$A1_points =	sprintf("%10s", $A1_points);
$A1_tally =	sprintf("%10s", $row[0]);

$stmt="select count(*) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id='CL_QRA_L' and status = 'A2';";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$row=mysql_fetch_row($rslt);
$A2_points = ($row[0] * 2);
$A2_points =	sprintf("%10s", $A2_points);
$A2_tally =	sprintf("%10s", $row[0]);

$stmt="select count(*) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id='CL_QRA_L' and status = 'A3';";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$row=mysql_fetch_row($rslt);
$A3_points = ($row[0] * 2);
$A3_points =	sprintf("%10s", $A3_points);
$A3_tally =	sprintf("%10s", $row[0]);

$stmt="select count(*) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id='CL_QRA_L' and status = 'A4';";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$row=mysql_fetch_row($rslt);
$A4_points = ($row[0] * 3);
$A4_points =	sprintf("%10s", $A4_points);
$A4_tally =	sprintf("%10s", $row[0]);

$TOT_tally = ($A1_tally + $A2_tally + $A3_tally + $A4_tally);
$TOT_points = ($A1_points + $A2_points + $A3_points + $A4_points);
$TOT_tally =	sprintf("%10s", $TOT_tally);
$TOT_points =	sprintf("%10s", $TOT_points);

$tally_A = " STATUS   SALES   %     POINTS\n";
$tally_A.= " A1:  $A1_tally     $A1_points\n";
$tally_A.= " A2:  $A2_tally     $A2_points\n";
$tally_A.= " A3:  $A3_tally     $A3_points\n";
$tally_A.= " A4:  $A4_tally     $A4_points\n";
$tally_A.= " -----------------------------  \n";
$tally_A.= " TOTAL:  <B>$TOT_tally $TOT_points</B>\n";



################################################################################
### ROOM B
################################################################################
$stmt="select count(*) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id='CL_QRB_L' and status = 'A1';";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$row=mysql_fetch_row($rslt);
$A1_points = ($row[0] * 1);
$A1_points =	sprintf("%10s", $A1_points);
$A1_tally =	sprintf("%10s", $row[0]);

$stmt="select count(*) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id='CL_QRB_L' and status = 'A2';";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$row=mysql_fetch_row($rslt);
$A2_points = ($row[0] * 2);
$A2_points =	sprintf("%10s", $A2_points);
$A2_tally =	sprintf("%10s", $row[0]);

$stmt="select count(*) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id='CL_QRB_L' and status = 'A3';";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$row=mysql_fetch_row($rslt);
$A3_points = ($row[0] * 2);
$A3_points =	sprintf("%10s", $A3_points);
$A3_tally =	sprintf("%10s", $row[0]);

$stmt="select count(*) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id='CL_QRB_L' and status = 'A4';";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$row=mysql_fetch_row($rslt);
$A4_points = ($row[0] * 3);
$A4_points =	sprintf("%10s", $A4_points);
$A4_tally =	sprintf("%10s", $row[0]);

$TOT_tally = ($A1_tally + $A2_tally + $A3_tally + $A4_tally);
$TOT_points = ($A1_points + $A2_points + $A3_points + $A4_points);
$TOT_tally =	sprintf("%10s", $TOT_tally);
$TOT_points =	sprintf("%10s", $TOT_points);

$tally_B = " STATUS   CUSTOMERS     POINTS\n";
$tally_B.= " A1:     $A1_tally $A1_points\n";
$tally_B.= " A2:     $A2_tally $A2_points\n";
$tally_B.= " A3:     $A3_tally $A3_points\n";
$tally_B.= " A4:     $A4_tally $A4_points\n";
$tally_B.= " -----------------------------  \n";
$tally_B.= " TOTAL:  <B>$TOT_tally $TOT_points</B>\n";



################################################################################
### ROOM C
################################################################################
$stmt="select count(*) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id='CL_QRC_L' and status = 'A1';";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$row=mysql_fetch_row($rslt);
$A1_points = ($row[0] * 1);
$A1_points =	sprintf("%10s", $A1_points);
$A1_tally =	sprintf("%10s", $row[0]);

$stmt="select count(*) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id='CL_QRC_L' and status = 'A2';";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$row=mysql_fetch_row($rslt);
$A2_points = ($row[0] * 2);
$A2_points =	sprintf("%10s", $A2_points);
$A2_tally =	sprintf("%10s", $row[0]);

$stmt="select count(*) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id='CL_QRC_L' and status = 'A3';";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$row=mysql_fetch_row($rslt);
$A3_points = ($row[0] * 2);
$A3_points =	sprintf("%10s", $A3_points);
$A3_tally =	sprintf("%10s", $row[0]);

$stmt="select count(*) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id='CL_QRC_L' and status = 'A4';";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$row=mysql_fetch_row($rslt);
$A4_points = ($row[0] * 3);
$A4_points =	sprintf("%10s", $A4_points);
$A4_tally =	sprintf("%10s", $row[0]);

$TOT_tally = ($A1_tally + $A2_tally + $A3_tally + $A4_tally);
$TOT_points = ($A1_points + $A2_points + $A3_points + $A4_points);
$TOT_tally =	sprintf("%10s", $TOT_tally);
$TOT_points =	sprintf("%10s", $TOT_points);

$tally_C = " STATUS   CUSTOMERS     POINTS\n";
$tally_C.= " A1:     $A1_tally $A1_points\n";
$tally_C.= " A2:     $A2_tally $A2_points\n";
$tally_C.= " A3:     $A3_tally $A3_points\n";
$tally_C.= " A4:     $A4_tally $A4_points\n";
$tally_C.= " -----------------------------  \n";
$tally_C.= " TOTAL:  <B>$TOT_tally $TOT_points</B>\n";



################################################################################
### ROOM D
################################################################################
$stmt="select count(*) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id='CL_QRD_L' and status = 'A1';";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$row=mysql_fetch_row($rslt);
$A1_points = ($row[0] * 1);
$A1_points =	sprintf("%10s", $A1_points);
$A1_tally =	sprintf("%10s", $row[0]);

$stmt="select count(*) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id='CL_QRD_L' and status = 'A2';";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$row=mysql_fetch_row($rslt);
$A2_points = ($row[0] * 2);
$A2_points =	sprintf("%10s", $A2_points);
$A2_tally =	sprintf("%10s", $row[0]);

$stmt="select count(*) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id='CL_QRD_L' and status = 'A3';";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$row=mysql_fetch_row($rslt);
$A3_points = ($row[0] * 2);
$A3_points =	sprintf("%10s", $A3_points);
$A3_tally =	sprintf("%10s", $row[0]);

$stmt="select count(*) from vicidial_closer_log where call_date >= '$query_date_BEGIN' and call_date <= '$query_date_END' and campaign_id='CL_QRD_L' and status = 'A4';";
$rslt=mysql_query($stmt, $link);
if ($DB) {echo "$stmt\n";}
$row=mysql_fetch_row($rslt);
$A4_points = ($row[0] * 3);
$A4_points =	sprintf("%10s", $A4_points);
$A4_tally =	sprintf("%10s", $row[0]);

$TOT_tally = ($A1_tally + $A2_tally + $A3_tally + $A4_tally);
$TOT_points = ($A1_points + $A2_points + $A3_points + $A4_points);
$TOT_tally =	sprintf("%10s", $TOT_tally);
$TOT_points =	sprintf("%10s", $TOT_points);

$tally_D = " STATUS   CUSTOMERS     POINTS\n";
$tally_D.= " A1:     $A1_tally $A1_points\n";
$tally_D.= " A2:     $A2_tally $A2_points\n";
$tally_D.= " A3:     $A3_tally $A3_points\n";
$tally_D.= " A4:     $A4_tally $A4_points\n";
$tally_D.= " -----------------------------  \n";
$tally_D.= " TOTAL:  <B>$TOT_tally $TOT_points</B>\n";




################################################################################
### FORMATTING
################################################################################

?>

<TABLE BGCOLOR=white CELLPADDING=0 CELLSPACING=5 BORDER=0>
<TR>
<TD BGCOLOR=#00FF00><font size=8><B>&nbsp;A&nbsp;</B></font></TD>
<TD BGCOLOR=#00FF00><font size=4 face="courier"><PRE><?=$tally_A ?></PRE></font></TD>
<TD><font size=6> &nbsp; </font></TD>

<TD BGCOLOR=#FFFF00><font size=8><B>&nbsp;B&nbsp;</B></font></TD>
<TD BGCOLOR=#FFFF00><font size=4 face="courier"><PRE><?=$tally_B ?></PRE></font></TD>
<TD><font size=6> &nbsp; </font></TD>
</TR>

<TR><TD colspan=5> &nbsp; </TD></TR>

<TR>
<TD BGCOLOR=#FF9933><font size=8><B>&nbsp;C&nbsp;</B></font></TD>
<TD BGCOLOR=#FF9933><font size=4 face="courier"><PRE><?=$tally_C ?></PRE></font></TD>
<TD><font size=6> &nbsp; </font></TD>

<TD BGCOLOR=#99CCFF><font size=8><B>&nbsp;D&nbsp;</B></font></TD>
<TD BGCOLOR=#99CCFF><font size=4 face="courier"><PRE><?=$tally_D ?></PRE></font></TD>
<TD><font size=6> &nbsp; </font></TD>
</TR>

</TABLE>
<BR><BR><BR>

<?


echo "<FORM ACTION=\"$PHP_SELF\" METHOD=GET>\n";
echo "<INPUT TYPE=TEXT NAME=query_date SIZE=10 MAXLENGTH=10 VALUE=\"$query_date\">\n";
echo "<SELECT SIZE=1 NAME=shift>\n";
echo "<option selected value=\"$shift\">$shift</option>\n";
echo "<option value=\"\">--</option>\n";
echo "<option value=\"AM\">AM</option>\n";
echo "<option value=\"PM\">PM</option>\n";
echo "<option value=\"ALL\">ALL</option>\n";
echo "</SELECT>\n";
echo "<INPUT TYPE=hidden NAME=DB VALUE=\"$DB\">\n";
echo "<INPUT TYPE=hidden NAME=autorefresh VALUE=\"$autorefresh\">\n";
echo "<INPUT TYPE=submit NAME=SUBMIT VALUE=SUBMIT>\n";
echo "</FORM>\n\n";



$ENDtime = date("U");
$RUNtime = ($ENDtime - $STARTtime);
if ($DB) {echo "\nRun Time: $RUNtime seconds\n";}






?>
</PRE>

</BODY></HTML>

