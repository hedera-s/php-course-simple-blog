<?php
/*******************************************************************************************/


				require_once("controller/dashboard.controller.php");


/********************************************************************************************/
?>
<!doctype html>

<html>

	<head>
		<meta charset="utf-8">
		<title>Blog über Essen - Dashboard</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="css/debug.css">
		<link href="https://fonts.googleapis.com/css?family=Dancing+Script|Roboto:300" rel="stylesheet">
	</head>

	<body>
		<header>
			<div class="hello">	
			
			<!--------- Begrüßung und Logout-Link ------------>
			
				<p>Hallo, <?=$user->getUsr_firstname()?>!  |  <a href="?action=logout">Logout</a></p>
				<p><a href="index.php"><< Zum Frontend</a></p>
			</div>
			
			
		</header>
		<br>
		<div class="wrapper-dashboard">
			<div class="blog-headline">
				<h1>Blog über Essen - Dashboard</h1>
				<h2>Aktiver Benutzer: <?=$user->getUsr_Fullname()?></h2>
			</div>
			
			
			<!----------- Formular Neuer Eintrag ------------>
			
			<main class="new-entry">
				<h3>Neuen Blog-Eintrag Verfassen</h3>
				
				<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST" enctype="multipart/form-data">
					<span><?=$blogMessage?></span>
					<input type="hidden" name="formsentNewEntry">
					
					<select class="category" name="category">
						<?php foreach($categoriesArray AS $category): ?>
							<?php if($category->getCat_id() == $_POST['category']): ?>
									<option value='<?=$category->getCat_id()?>' selected>
							<?php else: ?>
								<option value='<?=$category->getCat_id()?>'>
							<?php endif ?>
							<?=$category->getCat_name()?>
							</option>
						<?php endforeach ?>
					</select><br><br>
					
					<span class="error"><?=$errorHeadline?></span><br>
					<input type="text" name="headline" placeholder="Überschrift" class="headline" value="<?=$blog->getBlog_headline()?>">
					<br>
					
					<span class="error"><?=$errorImageUpload?></span><br>
					<input type="file" name="image" class="file">
					
					<select class="imageAlignment" name="imageAlignment">
						<?php if($blog->getBlog_imageAlignment() == "right"): ?>
							<option value='left'>Align left</option>
							<option value='right' selected>Align right</option>
						<?php else: ?>
							<option value='left' selected>Align left</option>
							<option value='right'>Align right</option>	
						<?php endif ?>
					</select><br><br>
					
					<span class="error"><?=$errorText?></span><br>
					<textarea name="content" placeholder="Text..."><?=$blog->getBlog_content() ?></textarea><br>
					
					<input type="submit" value="Veröffentlichen" class="button">
					
				</form>
			</main>
			
			<!----------- Formular Neue Kategorie ----------->
			
			<aside class="new-category">
				<h3>Neue Kategorie anlegen</h3>
				
				<br>
				<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST">
					<span><?=$categoryMessage?></span><br>
					<input type="hidden" name="formsentNewCategory">
					<input type="text" name="newCategory" value="<?=$newCategory->getCat_name()?>"><br>
					<input type="submit" value="Kategorie anlegen" class="button">
				</form>
				
			</aside><br>
		</div>
		<footer class="clear">
			<p>Copyright Irina Serdiuk</p>
		</footer>		
		
	</body>
	<script>
		var jump2 = document.getElementsByTagName("HEADER");
		jump2[0].scrollIntoView();
	</script>

</html>