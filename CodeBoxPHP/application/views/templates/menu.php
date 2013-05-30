		<h2><?php echo $title;?></h2>
		<img id = "logo" src="<?=base_url()?>/images/nhl_logo.png" alt="Logo">
		<ul class="tabs">
			<li><a href="<?=base_url() . 'index.php'?>/home">Hoofdpagina</a></li>
			<li><a href="<?=base_url() . 'index.php'?>/inleveren">Inleveren</a></li>
			<li><a href="<?=base_url() . 'index.php'?>/overzicht">Overzicht</a></li>
			<li><a href="<?=base_url() . 'index.php'?>/profiel">Mijn Profiel</a></li>
		<?php if($rolename == 'docent' || $rolename == 'administrator')
		{		
			$base = base_url() . 'index.php';
			echo("<li><a href='$base/administratie'>Beheer</a></li>");
		} ?>

			<li><a href="logout">Uitloggen</a></li>
		</ul>