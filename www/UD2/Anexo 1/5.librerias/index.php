<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<title>Web Portal - Includes and requires</title>
	<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>

<div id="header" class="container">

	<?php
		include_once("logo.php");
	?>

	<?php
		include_once("menu.php");
	?>
	
</div>

	<?php
		include_once("pictures.php");
	?>

<div id="page">
	<div id="bg1">
		<div id="bg2">
			<div id="bg3">
			
				<?php
					include_once("content.php");
				?>

				<?php
					include_once("sidebar.php");
				?>
								
			</div>
		</div>
	</div>
</div>

<?php
	include_once("footer.php");
?>


</body>
</html>
