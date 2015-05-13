<?	
ob_start();
session_start();

$qr_redirect = "http://192.168.1.62/agc/timeclock.qr.php";
$q = "";
$p = "";
$amp = "?";

foreach($_GET as $key=>$val){
	$q .= "$amp$key=$val";
	$amp = "&";
}
foreach($_POST as $key=>$val){
	$p .= "$amp$key=$val";
	$amp = "&";
}
if(strlen(trim($p)) > 0){
	$qr_redirect = $qr_redirect . $p;
}
if(strlen(trim($q)) > 0){
	$qr_redirect = $qr_redirect . $q;
}

header("Location: $qr_redirect");

session_write_close();
ob_end_flush();

exit;
?>