<h3>Selecteer leerling:</h3>

<?php 
	$result = $this->globalfunc->students($studyid);
	$count = 0;
	foreach($result as $row)
	{
		//$username_displ = $this->user->getfullnamefromldap($row->username);
		$fulluser = ucfirst($row->fullname);
		$base = base_url();
		echo("<li><a href='$base" . "index.php/overzicht/student/$studyid/$row->username'>$fulluser</a></li>");
		$count++;
	}
	if($count == 0)
	{
		echo("Geen studenten gevonden voor deze opleiding!");
	}
?>

<br/><br/>
<input type = "button" name = "ReturnButton" onclick = "history.go(-1);" value="Terug"/>