<?php
session_start();
include "config.php";
if(isset($_GET['debug']))
{
	show_source(__FILE__);
	die("...");
}

if(!isset($_SESSION['token']))
{
	$_SESSION['token'] = sha1(time().rand().$salt);
}

if(isset($_REQUEST['secret']) and !empty($_REQUEST['secret']))
{
	define("THISISSECRETKEY",$_GET['secret']);
}
else
{
	define("THISISSECRETKEY","THISISSECRETKEY");
}

class Hidden extends php_user_filter {
	function xor_string($string, $key) {
		for($i = 0; $i < strlen($string); $i++) 
			$string[$i] = ($string[$i] ^ $key[$i % strlen($key)]);
		return $string;
	}
  function filter($in, $out, &$consumed, $closing) {
    while ($bucket = stream_bucket_make_writeable($in)) {
      @$bucket->data = $this->xor_string($bucket->data,THISISSECRETKEY);
      $consumed += $bucket->datalen;
      stream_bucket_append($out, $bucket);
    }
    return PSFS_PASS_ON;
  }
}
class Map
{
	public $len_file_name_accept;
	public $returnimage;
	public $way;
	public function __construct($way)
	{
		$this -> way = $way;
		$this -> len_file_name_accept = 100;
		if(strlen($way) > $this -> len_file_name_accept)
		{
			die("Too Long");
		}
	}
	public function __destruct()
	{
		if(exif_imagetype($this->way))
		{
			$image = file_get_contents($this -> way);
			$this -> returnimage = base64_encode($image);
			echo '<img src="data:images/png;base64,'.$this -> returnimage.'" height="300" width="800">';
		}
	}
}
class UserControl
{
	public $place;
	public $yourtreasure;
	public function __construct()
	{
		global $salt;
		stream_filter_register("treasures", "Hidden") or die("Failed to register filter");
		$this -> place = "resources/".$_SESSION['token'].".txt";
	}
	public function Store($save)
	{
		$treas = "--------------BEGIN--------------\n".$save;
		if(file_put_contents($this -> place,$treas) > 0)
		{
			return 1;
		}
		else
		{
			die("Failed to store");
		}
	}
	public function Show()
	{
		$tre = fopen($this -> place, 'r');
		stream_filter_append($tre, 'treasures');
		$this -> yourtreasure = fread($tre, filesize($this -> place));
		fclose($tre);
	}

	public function ShowMap()
	{
		return <<<EOT
	<svg width="100" height="100" onclick="window.open('?way=images/newworld.jpg')">
   <circle cx="50" cy="50" r="40" stroke="green" stroke-width="4" fill="white" />
	</svg><br> 
	<svg width="100" height="100" onclick="window.open('?way=images/sabaody.png')">
   <circle cx="50" cy="50" r="40" stroke="red" stroke-width="4" fill="yellow" />
	</svg>
	<svg width="100" height="100" onclick="window.open('?way=images/rafel.png')">
   <circle cx="50" cy="50" r="40" stroke="red" stroke-width="4" fill="yellow" />
	</svg>
	<svg width="100" height="100" onclick="window.open('?way=/var/www/html/e9941d1621bdf00ef6a17c1e5176c1bcbb966b71/flag.php')">
   <circle cx="50" cy="50" r="40" stroke="red" stroke-width="4" fill="red" />
	</svg><br>
	<svg width="100" height="100" onclick="window.open('?way=images/newworld.jpg')">
   <circle cx="50" cy="50" r="40" stroke="green" stroke-width="4" fill="white" />
	</svg>			
EOT;
	}
}



$user = new UserControl();

if(isset($_POST['save'])&&!empty($_POST['save']))
{
	$saved = $user -> Store($_POST['save']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>DD 's Treasure</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">

  <!-- jQuery library -->
  <script src="js/jquery.min.js"></script>

  <!-- Latest compiled JavaScript -->
  <script src="js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
<div class="jumbotron">
	<h1>Map</h1> 

	<?php
	if(isset($_GET["way"]))
	{
		switch ($_GET['way'])
		{
			case "images/sabaody.png": $map = new Map("images/sabaody.png");$map -> __destruct();break;
			case "images/newworld.jpg": $map = new Map("images/newworld.jpg");$map -> __destruct();break;
			case "images/rafel.png": $map = new Map("images/rafel.png");$map -> __destruct();break;
			case "/var/www/html/e9941d1621bdf00ef6a17c1e5176c1bcbb966b71/flag.php": $map = new Map("/var/www/html/e9941d1621bdf00ef6a17c1e5176c1bcbb966b71/flag.php");$map -> __destruct();break;
		}

		?>
<?php
	}
?>
	<br>
<?=$user -> ShowMap()?>
</div>
	<h1>New World</h1> 
    <p>Welcome to New World, You are in the way to become Pirate King, so this area is the place to save your Treasures </p> 

<form method="post" action="index.php" class="form-group">
Secret:<input type="text" name="secret" placeholder="Set your secret" class="form-control">
<br>Tresure:<input type="text" name="save" placeholder="Save your treasures" class="form-control">        
<input type="submit" value="Store">
</form>
<br>
<?php


if (isset($saved) && $saved === 1)
{
?>

<div class="alert alert-success">Success save your treasures, If your want to get it, tell me <a href="?mytresure">Give me my treasures</a>
</div>
<?php
}

if(isset($_GET['mytresure']))
{
	$user -> Show();
?>
Your Treasure but I encrypt to protect it. 
<textarea class="form-control" rows="5"><?=base64_encode($user -> yourtreasure)?></textarea>
<div class="alert alert-warning">Your original treasure stored at <?=$user -> place?> </div>
<form method="get" action="index.php" class="form-group">
<input type="text" name="friendtresure" placeholder="If you want to see other treasures, you need to know their secret link like resources/[id] (except .txt)" class="form-control">        
<input type="submit" value="Show">
</form>
	<?php
}
if(isset($_GET['friendtresure']))
{
	$user -> place = $_GET['friendtresure'].".txt";
	$user -> Show();
	?>

<textarea class="form-control" rows="5"><?=base64_encode($user -> yourtreasure)?></textarea>
<?php
}
?>

</body>
</html>
<!-- ?debug -->
