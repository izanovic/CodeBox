		<h2><?php echo $title;?></h2>
		<div class="hs-menu">
			<img src = "<?=base_url()?>/images/codeboxwhite.png" heigth = "100px" width = "180px">
			<img src = "<?=base_url()?>/images/nhl_logowhite.png" heigth = "100px" width = "180px">
			<nav>
				<a href="<?=base_url() . 'index.php'?>/home"><span>Home</span></a>
				<a href="<?=base_url() . 'index.php'?>/inleveren"><span>Inleveren</span></a>
				<a href="<?=base_url() . 'index.php'?>/overzicht"><span>Overzicht</span></a>
				<a href="<?=base_url() . 'index.php'?>/profiel"><span>Mijn Profiel</span></a>
				<?php if($rolename == 'docent' || $rolename == 'administrator')
				{		
					$base = base_url() . 'index.php';
					echo("<a href='$base/administratie'><span>Administratie</span></a>");
				} ?>
				<a href="<?=base_url() . 'index.php'?>/logout"><span>Uitloggen</span></a>
			</nav>

		</div>
		<div  class = "content">