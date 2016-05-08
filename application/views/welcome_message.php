<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>
</head>
<body>

</body>
<script src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
<script>
data = {
	cellphone:"18064078228",
	password:"123456"
}
	$.ajax({
		type:"post",
		url:"api/login",
		contentType: 'application/json',
		dataType: "json",
		data: JSON.stringify(data),
		success:function(data){
			console.log(data);
		}
	});
</script>
</html>