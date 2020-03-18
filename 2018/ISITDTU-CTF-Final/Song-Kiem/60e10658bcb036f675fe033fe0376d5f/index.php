<?php
if($_SERVER['REMOTE_ADDR']!="115.75.223.42" and $_SERVER['REMOTE_ADDR']!="127.0.0.1" and $_SERVER['REMOTE_ADDR']!="::-1" and $_SERVER['REMOTE_ADDR']!="35.231.54.0")
{
	die("local chall only");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Song Đao Bão Táp</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script>
    Object.freeze(document.location);
  </script>
</head>
<body>

<?php

include "config.php";
session_start();
$_SESSION = array();
include("simple-php-captcha.php");
$_SESSION['captcha'] = simple_php_captcha();

if(isset($_GET['id']) && !empty($_GET['id']))
{
	$id = mysqli_filter($_GET['id']);
	$sql = "select * from skill where id=".$id;
	$result = mysqli_query($conn,$sql);
	if (@mysqli_num_rows($result) === 1) {
		$row = mysqli_fetch_assoc($result);
        $skillselect = "
		<tr>
			<td>".$row['id']."</td>
			<td>".$row['name']."</td>
			<td>".$row['type']."</td>
			<td>".$row['cooldown']."</td>
			<td>".$row['power']."</td>
		</tr>";
	}
	else
	{
		$skillselect = "<tr><td>Id: <strong>".$id."</strong> Error</td></tr>";
	}
}
else
{
	$skillselect = mysqli_select_all($conn);
}

if(isset($_POST['url']) && !empty($_POST['url']))
{
	if($_POST['captcha'] === $_SESSION['captcha']['code'] or strlen($_POST['captcha'])=== 5 )
	{
	$url = safe_url($_POST['url']);
	$url = escapeshellarg($url);
	// bot check
	$cmd = "node bot/0d24c869e31b89433ef19b91406af0f6d66bc7ed.js ".$url;
	system($cmd);	
	echo "<h3 class='alert alert-success'>Airi Chrome Bot đã làm nhiệm vụ</h3>";
	}
	else
	{
        echo "<h3 class='alert alert-danger'>Sai Captcha</h3>";
	}
}

?>

<div class="container">
<div class="jumbotron">
    <h1>Bí kíp Nguyệt Tộc</h1> 
    <p>Để lấy được Bí kíp nguyệt tộc, Ryomar và Airi hợp tác đánh bại maloch. Tuy nhiên sau khi lấy được kho báu, cả hai đã bị nakroth sát hại. Trước khi chết, cả hai tách cuốn bí kíp thành 2 mảnh và truyền cho thế hệ sau. Cách đây không lâu, có một người đã tìm thấy bí kíp và quyết định đem giấu vào một nơi khác đồng thời bảo vệ kho báu bằng một tấm chắn chỉ cho phép gia đình và người thân có thể lấy được nó.
	</p>
</div>
<img src="images/ngau.jpg" width="800" height="300">
<h3>Dưới đây là những chiêu thức thất truyền từ cuốn bí kíp:</h3>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>SKILLNAME</th>
        <th>TYPE</th>
		<th>COOLDOWN</th>
		<th>POWER</th>
      </tr>
    </thead>
    <tbody>
<?php echo $skillselect;?>
    </tbody>
  </table>
  
  <form class="form-horizontal" action="index.php" method="post">
  <h4>Airi Chrome Bot ( Truyền nhân của airi, nơi lưu giữ một nửa của cuốn bí kíp ) ( VD: http://35.231.54.0/* ) </h4>
    <div class="form-group">
      <label class="control-label col-sm-1" for="bot">URL:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="bot" placeholder="" name="url">
      </div>
    </div>
<?php	echo '<img src="' . $_SESSION['captcha']['image_src'] . '" alt="CAPTCHA code">'; ?>
      <label class="control-label col-sm-1" for="captcha">Captcha:</label>
	<div class="col-sm-10">
        <input type="text" class="form-control" id="captcha" placeholder="" name="captcha">
      </div>
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-default">Submit</button>
      </div>
    </div>
  </form>
</div>


</body>
</html>
