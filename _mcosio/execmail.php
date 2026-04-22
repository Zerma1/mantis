	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>Envoi mails</title>
		<link rel="stylesheet" href="stats.css" type="text/css" />

		
				
	</head>
	<body>
	
		<?php include ("entete.php"); ?>
	
	
		<div id="banner">
			<div align="left" class="cadre1">
	
				<form action="index.php" method="POST">
	
				<div>
				<?php $output = passthru('cmd /c d:\xampp\htdocs\mantis\scripts\Envoi_de_mails.bat'); ?>
				</div
	
	
				<p align="right"><input type=submit value="Retour"></p>
			</div>
			<hr  style="visibility:hidden;clear:both;">
		</div>
	
	</form>
	</div>
	</body>
	</html>