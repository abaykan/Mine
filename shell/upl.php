<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>UPLOADER</title>
</head>
<body>
	<form action="" method="POST" enctype="multipart/form-data">
		<input type="text"name="text"/>
		<input name="file" type="file" />
		<input type="submit" value="upload" />
	</form>
	<?php
		if($_POST){
			$dosyaUzantisi = substr($_FILES["file"]["name"],-4,4);
			$dosyaAdi = substr($_FILES["file"]["name"],0,-4).$dosyaUzantisi;
			$dosyaYolu = $dosyaAdi;
			if(is_uploaded_file($_FILES["file"]["tmp_name"])){
				move_uploaded_file($_FILES["file"]["tmp_name"],$dosyaYolu);
			}
		}
	?>
</body>
</html>
