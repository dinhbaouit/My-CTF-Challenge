<?php


$hosts = "localhost";
$dbusername = "root";
$dbpasswd = "";
$dbname = "ssrf";
$dbport = 3306;

$conn = mysqli_connect($hosts,$dbusername,$dbpasswd,$dbname,$dbport);

function initdb($conn)
{
	$dbinit = "create table if not exists flag(secret varchar(100));";
	if(mysqli_query($conn,$dbinit)) return 1;
	else return 0;
}

function safe($url)
{
	$url = parse_url($url, PHP_URL_HOST);
	if($url != "localhost" and $url != "127.0.0.1")
	{
		var_dump($url);
		die("<h1>On ly access to localhost</h1>");
	}
}

function getUrlContent($url){
	$url = safe($url);
	$url = escapeshellarg($url);
	$content = exec("curl ".$url);
	return $content;
}
initdb($conn);
?>