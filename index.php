<?php
session_start();
?>
<html>
<head><title>whatever</title>
<meta charset="UTF-8">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <link rel="stylesheet" href="style.css">
    
</head>
<body>
<?php
switch ($_GET["p"]){
	case "displist":
		include "include/curlf.php";
		include "include/displist.php";
		break;	
	case "editlist":
		include "include/curlf.php";
		include "include/editlist.php";
		break;
	default:
		include "include/avaleht.php";
		break;
}
?>
</body>
</html>