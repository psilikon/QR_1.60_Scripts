<?

### rebut_acp.php - vicidial agent script with dynamic content

if (isset($_GET["state"]))					{$state=$_GET["state"];}
	elseif (isset($_POST["state"]))			{$state=$_POST["state"];}
if (isset($_GET["first_name"]))				{$fname=$_GET["first_name"];}
	elseif (isset($_POST["first_name"]))	{$fname=$_POST["first_name"];}
if (isset($_GET["province"]))				{$province=$_GET["province"];}
	elseif (isset($_POST["province"]))		{$province=$_POST["province"];}
if (isset($_GET["alt_phone"]))				{$alt_phone=$_GET["alt_phone"];}
	elseif (isset($_POST["alt_phone"]))		{$alt_phone=$_POST["alt_phone"];}
if (isset($_GET["last_name"]))				{$last_name=$_GET["last_name"];}
	elseif (isset($_POST["last_name"]))		{$last_name=$_POST["last_name"];}
if (isset($_GET["address1"]))				{$address1=$_GET["address1"];}
	elseif (isset($_POST["address1"]))		{$address1=$_POST["address1"];}
if (isset($_GET["city"]))					{$city=$_GET["city"];}
	elseif (isset($_POST["city"]))			{$city=$_POST["city"];}
if (isset($_GET["postal_code"]))			{$postal_code=$_GET["postal_code"];}
	elseif (isset($_POST["postal_code"]))	{$postal_code=$_POST["postal_code"];}
	

if (eregi("FL|IN|MO|NV|OK|UT|OR|ND",$state))
	{
	echo "<p><B>PRIVACY MATTERS</B></p>";
		
	echo "<font size=4 face=Arial>Now, <b>$fname</b>, as a special bonus, you're eligible to claim 
	a $25 Wal-Mart gift card just for trying the first 30 days of Privacy Matters 
	for only one dollar.</font>";

	echo "<p><b>$fname</b>, did you know someone’s identity is stolen every 3 
	seconds?!  Privacy matters will protect your identity by giving you automatic 
	daily email alerts if there is any suspicious activity to any of your credit 
	accounts, gives you unlimited online access to your personal credit reports to 
	ensure accuracy of your credit and so much more.</p>";

	echo "<font size=4 face=Arial><p>READ TO ALL STATES EXCEPT NY</p></font>";

	echo "<p>Now, Privacy Matters will charge the one-dollar introductory fee to your 
	<B>$province</B> with the last four digits (read last 4 digits: 
	<B>$alt_phone</B>) within 1-5 days (UNLESS AN ALTERNATE FORM OF 
	PAYMENT IS DESIGNATED).  After the first 30 days, unless you call to cancel, 
	Privacy Matters will automatically charge the membership fee of $29.95 at about 
	the same time each month at the then current monthly membership fee to the same 
	card.  Now, <B>$first_name</B> you can certainly cancel within the 
	next 30 days by calling the toll-free number which is 1-877-993-6264 or cancel 
	after the trial period to discontinue billing.  But the $25 Wal-Mart Gift Card 
	is yours to claim as our way of saying thank you.  Okay?  <B> [Must get an 
	affirmative response]</B></p>"; 

	echo "<p>IF NOT YES, OK, OR ALRIGHT:</p>";  

	echo "<p>     I'M SORRY(sir/maam), WAS THAT A YES?</p>"; 

	echo "<p>YOU MUST GET A YES, OK OR ALRIGHT Continue...</p>"; 

	echo "<p>So Mr/Mrs <B>$last_name</B> just to confirm your order, with your 
	approval, you’ll be charged under the terms I just described, and I’ll send 
	your membership materials, ok?</p>"; 

	echo "<p>(Must get a yes, ok or alright) </p>";


	echo "<p><b>NEW YORK CONFIRMATION</b></p>";

	echo "<p>We’ll get your membership materials out to you at the address you provided 
	today.  Just to review, Privacy Matters will charge your 
	<B>$province</B> with the last four digits (read last 4 digits: 
	<B>$alt_phone</B>) within 1-5 days (UNLESS AN ALTERNATE FORM OF 
	PAYMENT IS DESIGNATED) a $1.00 trial fee and then will automatically charge the 
	same card $29.95 at the end of your 30-day trial period, and at or prior to the 
	beginning of each new membership month at the then current monthly membership 
	fee unless you call to cancel.  If you decide to cancel the membership, call 
	1-877-993-6264 within the next 30 days. Will it be alright to charge the $1.00 
	trial fee within 1-5 days and then charge the monthly fee of $29.95 on your 
	<B>$province</B> with the last four digits (read last 4 digits: 
	<B>$alt_phone</B>)  (UNLESS AN ALTERNATE FORM OF PAYMENT IS 
	DESIGNATED) after 30 days unless you cancel?</p>"; 

	echo "<p><B>[Must get an affirmative response]</B></p>";

	echo "<p>Great, Mr/Mrs <B>$last_name</B> just to confirm your order, with your 
	approval, you’ll be charged under the terms I just described, and I’ll send 
	your membership materials, ok?</p>";

	echo "<p><B>[Must get an affirmative response]</B></p>";

	echo "<p>IF NOT YES, OK, OR ALRIGHT:</p>";  

	echo "<p>     I'M SORRY(sir/maam), WAS THAT A YES?</p>"; 

	echo "<p>YOU MUST GET A YES, OK OR ALRIGHT Continue...</p>";

	echo "<p><B>READ TO ALL:</B></p>";

	echo "<p>AND FOR CONFIRMATION PURPOSES <B>$fname</B> CAN YOU PLEASE TELL 
	ME YOUR DATE OF BIRTH WITHOUT THE YEAR? This constitutes your authorization by 
	electronic signature for your Privacy Matters order under the terms I just 
	described. Can you please enter the four digits of your month and day of birth 
	using your telephone keypad? (WAIT FOR RESPONSE)</p>";

	echo "<p>GO AHEAD AND PUNCH THAT IN THE PHONE FOR ME PLEASE.  *Example if January 5th 
	customer must type in 0 1 0 5… MUST BE 4 DIGITS!* </p>";

	echo "<p><B>FINAL CLOSE</B></p>";

	echo "<p>If “YES”:  Great!  You can also access your benefits by calling the # just 
	provided or by going online to <B>www.privacymatters123.com.</B> Thank you for 
	your time and patience and you have a wonderful day!</p>\n";
	}

else
	{
	echo "<p><font size=4 face=Arial>ADVANTAGECARE PLUS DISCOUNT PROGRAM.</font></p>";

	echo "<p>AND MR/MRS <b>$last_name</b> I AM SO EXCITED FOR YOU because as a special bonus, 
	you also qualify to receive free $500 in grocery coupons for taking a look at the 
	SPECTACULAR AdvantageCare Plus discount program. You will enjoy savings from 20% to 50% 
	in areas such as dental care, prescription drugs, vision, chiropractic, and hearing care.  
	AdvantageCare Plus is a non-insurance, money saving benefit program that can be used by 
	itself or as a complement to your current health plan; and it could save you and your 
	family thousands on so many different types of medical and health expenses. 
	I’m sure you will like it a lot! </p>";

	echo "<p>What we ask you to do is to try the program for the first month for only $19.95, 
	which is fully refundable, billed to your <b>$province</b> with the last four digits (read last 4 digits: 
			<B>$alt_phone</B>) within 1-5 days (UNLESS AN ALTERNATE FORM OF 
			PAYMENT IS DESIGNATED) so you 
	can see all of the savings for your immediate family. After the 30 day trial, if you like 
	the program and decide to keep it, you and your immediate family are included for only $19.95 
	each month automatically billed to the same credit card, 
	for as long as you want to remain a customer. </p>";

	echo "<p>If you decide to cancel within the first 30 days of the enrollment, just call the toll 
	free number right in your package along with your ID card, which is 1-800-614-9781 for a full 
	refund with no further obligation at all. YOU'VE GOT NOTHING TO LOSE. Even if you cancel, please 
	keep the $500 in grocery coupons as our way of saying thank you for being a valued customer. </p>"; 


	echo "<p>So with your approval we'll start your program beginning with your month long money back 
	guarantee under the terms described today, okay? </p>";  

	echo "<p>YOU MUST GET A YES, OK OR ALRIGHT to Continue...</p>";
	
	echo "<p>     I'M SORRY(sir/maam), WAS THAT A YES?</p>"; 

	 echo "<p>You will receive your AdvantageCare Plus Member ID card ready for immediate use within 7-10 
	business days addressed to (YOU MUST READ-Customer's first and last name:) 
	<b>$fname</b> <B>$last_name</B> at (YOU MUST-repeat billing address:) <b>$address1, $city, $state, $postal_code, 
	so please be on the lookout for it. </b></p>"; 
	}


?>

