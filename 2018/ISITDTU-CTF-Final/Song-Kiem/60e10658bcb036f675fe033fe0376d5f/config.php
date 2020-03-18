<?php


header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; sandbox allow-scripts allow-same-origin allow-forms; img-src 'self' https://www.google-analytics.com;");
$dbhost = "127.0.0.1";
$dbusername = "songkiem";
$dbpassword = "songdaobaotap";
$dbname = "songkiem";

$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname);

function mysqli_filter($query)
{
	$blacklists = ["union","length","substr"];
	foreach($blacklists as $blacklist)
	{
		while(strpos($query,$blacklist)!==false)
		{
			$query = str_replace($blacklist,"",$query);
		}
	}
	return $query;
}

function safe_url($url)
{
	$safe_host = "35.231.54.0";
	$safe_port = "";
	$safe_protocol = "http";
	
	$protocol = parse_url($url,PHP_URL_SCHEME);
	$host = parse_url($url,PHP_URL_HOST);
	$port = parse_url($url,PHP_URL_PORT);
	if($safe_host === $host && $safe_port == $port && $safe_protocol === $protocol)
	{
		return $url;
	}
	die("URL DOESN'T SAFE");
	return 0;
}

function mysqli_select_all($conn)
{
		$skillselect = "";
		$sql = "select * from skill";
		$result = mysqli_query($conn,$sql);
		while($row = mysqli_fetch_assoc($result)) {
			$skillselect .= "
			<tr>
				<td><a href='index.php?id=".$row['id']."'>".$row['id']."</a></td>
				<td>".$row['name']."</td>
				<td>".$row['type']."</td>
				<td>".$row['cooldown']."</td>
				<td>".$row['power']."</td>
			</tr>";				
			
		}
		return $skillselect;
}




















?>
