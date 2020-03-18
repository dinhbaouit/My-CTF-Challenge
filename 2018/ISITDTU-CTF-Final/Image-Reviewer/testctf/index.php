<?php

class CoreControler {
	public $CoreFile;
	function __construct($file_)
	{
		$this -> CoreFile = $file_;
	}
	function Upload($filetoupload_)
	{
		$target_dir = "uploads/";
		$target_file = $target_dir . basename($filetoupload_["fileToUpload"]["name"]);
		$OK = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		$imageFileName = strtolower(pathinfo($target_file,PATHINFO_FILENAME));
		
		if (!preg_match("#^[a-zA-Z0-9]+$#", $imageFileName))
		{
			echo "<div class='alert alert-danger'>You may not use special characters in your username</div>";
			$OK = 0;
		}
		if ($filetoupload_["fileToUpload"]["size"] > 50000) {
			echo "<div class='alert alert-danger'>Sorry, your file is too large.</div>";
			$OK = 0;
		}
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
			echo "<div class='alert alert-danger'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";
			$OK = 0;
		}
		if ($OK == 0) {
			echo "<div class='alert alert-danger'>Sorry, your file was not uploaded.</div>";
		} else {
			if (move_uploaded_file($filetoupload_["fileToUpload"]["tmp_name"], $target_file)) {
				echo "The file ". basename( $filetoupload_["fileToUpload"]["name"]). " has been uploaded.";
			} else {
				echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
			}
		}
		return $target_file;
	}
	function __destruct() {
		if(isset($_GET['debug']))
		{
			show_source($this->CoreFile);
		}
	}
}


class SystemControler {
	public $FileContent;
	function __construct($file_)
	{
		$this -> FileContent = @file_get_contents($file_); 
	}
	function GetContentFile()
	{
		if($_SERVER['REMOTE_ADDR'] === "127.0.0.1" || $_SERVER['REMOTE_ADDR'] === "::1")
		{
			return "<input type='text' name='' value='".htmlentities($this -> FileContent)."'>";
		}
		else
		{
			return "<input type='text' name='' value='Only available in local system'>";
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Report Service</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Report Service</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="index.php">User</a></li>
      <li><a href="index.php?q=admin">System Admin</a></li>
    </ul>
  </div>
</nav>
<?php
if(isset($_GET['q']) and $_GET['q']==="admin")
{
?>
<form action="index.php" method="get">
    <div class="form-group">
	Select file to read:
    <input type="text" name="filetoread" id="filetoread">
	</div>
	<div class="form-group">
	<input type="hidden" name="q" value="admin">
	</div>
    <input type="submit" value="Read" name="submit">
</form>
<?php
	if(isset($_GET['filetoread']) and !empty($_GET['filetoread']))
	{
		$obSystemControl = new SystemControler($_GET['filetoread']);
		$content = $obSystemControl -> GetContentFile();
		echo $content;
	}
}

else 
{
?>
<div class="container jumbotron">
<form action="index.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
	Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
	</div>
    <input type="submit" value="Upload Image" name="submit">
</form>
<br>
<div class="form-group">
<button type="button" class="btn btn-success" onclick="window.open('index.php?debug')">Debug</button>
</div>
<?php
	$obUserControl = new CoreControler(__FILE__);
	if(isset($_POST["submit"])) {
		$pathfileupload = $obUserControl -> Upload($_FILES);
		echo "<div class='alert alert-success'>File is located at ".$pathfileupload."</div>";
	}
}
?>




</body>
</html>


<!-- flag.php -->