<?php header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval';sandbox allow-scripts allow-same-origin"); header("X-XSS-Protection: 1; mode=block"); function blacklist($strings) { if(strlen($strings)>500)
	{
		die("Too Long");
	}

	if(preg_match('/[\'`]+/i', $strings))
	{
		die('Not Allow');
	}
}
if(isset($_GET['pl'])&&!empty($_GET['pl']))
{
	$xss = $_GET['pl'];
	blacklist($xss);
?>

<script type="text/javascript">Object.freeze(location)</script>
<script>location='http://<?=$xss ?>';</script>



<?php
	
}
else
{
	echo "<a href='?pl=xss'>click me</a>
Bot check: <a href='submit.php'>here</a>";
}
?>

