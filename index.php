<?php
/*******************************************************************************************/


				require_once("controller/index.controller.php");


/********************************************************************************************/
?>
<!doctype html>

<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Blog über Essen</title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="css/debug.css">
		<link href="https://fonts.googleapis.com/css?family=Dancing+Script|Roboto:300" rel="stylesheet">
	</head>

	<body>
		<header>
		
			<!------- Begrüßung und Logout-Link --------->
			
			<?php if(isset($_SESSION['usr_id'])):?>
				<div class="hello">	
					<p>Hallo, <?=$user->getUsr_firstname()?>!  |  <a href="?action=logout">Logout</a></p>
					<p><a href="dashboard.php">Zum Dashboard >></a></p>
				</div>
			<?php endif?>
			
			
			<!--------------- Login-Form ----------------->
			
			
			<?php if(!isset($_SESSION['usr_id'])):?>
				<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST" class="login clear">
					<span class="error login-message"><?=$loginMessage?></span><input type="hidden" name="formsentLogin">
					<input type="text" name="email" placeholder="Email">
					<input type="password" name="password" placeholder="Passwort">
					<input type="submit" value="Anmelden">
				</form>
			<?php endif ?>

		
		</header>
		
		<div class="wrapper">
		
			<main>
				<div class="blog-headline">
					<h1>Blog über Essen</h1>
					<h2>(und Trinken)</h2>
				</div>
			
				<?php if($blogsArray):?>
					<?php foreach($blogsArray AS $blog): ?>
						<article>
							<ul class="category-list">
								<li>
									<?=$blog->getCategory()->getCat_name()?>
								</li>
							</ul>
							
							<?php 
								// Convertierung in EU Datum und Zeit:
								$dateTime = isoToEuDateTime($blog->getBlog_date()); 
							?>
							
							<p class="whowrote">
											<?=$blog->getUser()->getUsr_fullname()?>
								aus 		<?=$blog->getUser()->getUsr_city()?> 
								schrieb am 	<?=$dateTime['date']?> 
								um 			<?=$dateTime['time']?> 
								Uhr:
							</p>
							
							<h3 class="headline"><?=$blog->getBlog_headline()?></h3>
							
							<?php if($blog->getBlog_image()): ?>
								<img src="<?=$blog->getBlog_image()?>" class="<?=$blog->getBlog_imageAlignment()?> article-image" />
							<?php endif ?>
							<p class="content"><?=$blog->getBlog_content()?></p>
							<div class="clear"></div>
							
						</article>
						
						<br>
					<?php endforeach ?>
				<?php else: ?>
					<article>
						<h3>Diese Kategorie enthält noch keine Beiträge.</h3>
					</article>
				<?php endif ?>
				
			</main>
			
			
			<!--------------- Kategorienliste ----------------->
			
			<aside>
				<p class="categories-header">Kategorien:</p>
				<ul>
					<li>
						<a href="<?=$_SERVER['SCRIPT_NAME']?>">
							Alle Kategorien
						</a>
					</li>
					<br>
					<?php foreach ($categoriesArray AS $category): ?>					
						<?php if($categoryToShow_id == $category->getCat_id()): ?> 
							<li class='selected-category'>
								<a href='?action=showCategory&categoryToShow=<?=$category->getCat_id()?>'>
									<?=$category->getCat_name()?>
								</a>
							</li>
						<?php else: ?>
								<li>
									<a href='?action=showCategory&categoryToShow=<?=$category->getCat_id()?>'>
										<?=$category->getCat_name()?>
									</a>
								</li>
						<?php endif ?>
					<?php endforeach ?>
					
			
				</ul>
			</aside>
			
			
			<div class="clear"></div>
		</div>
		<footer>
			<p>Copyright Irina Serdiuk</p>
		</footer>

	</body>
		<script>
			var jump2 = document.getElementsByTagName("HEADER");
			jump2[0].scrollIntoView();
		</script>
</html>