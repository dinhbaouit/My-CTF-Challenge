function EatFruit()
{
	  var name = document.getElementById("name").value;
	  var url = "http://"+location.host+"/index.php?eat="+name;
	  location = url;
}