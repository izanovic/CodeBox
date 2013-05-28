<h3>Selecteer leerling:</h3>

<?php 
	$result = $this->globalfunc->students($studyid);
	$count = 0;
	foreach($result as $row)
	{
		$username_displ = $this->user->getfullnamefromldap($row->username);
		echo("<li><a href='../../overzicht/student/$studyid/$row->username'>$username_displ</a></li>");
		$count++;
	}
	if($count == 0)
	{
		echo("Geen studenten gevonden voor deze opleiding!");
	}
?>

<br/><br/>
<input type = "button" name = "ReturnButton" onclick = "history.go(-1);" value="Terug"/>