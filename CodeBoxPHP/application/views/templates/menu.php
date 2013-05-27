		<h2><?php echo $title;?></h2>
		<img id = "logo" src="/images/nhl_logo.png" alt="Logo">
		<ul class="tabs">
			<li><a href="home">Hoofdpagina</a></li>
			<li><a href="inleveren">Inleveren</a></li>
			<li><a href="overzicht">Overzicht</a></li>
			<li><a href="profiel">Mijn Profiel</a></li>
		<?php if($rolename == 'docent' || $rolename == 'administrator'){		
			echo("<li><a href='administratie'>Beheer</a></li>");
		} ?>
			<li><a href="logout">Uitloggen</a></li>
		</ul>