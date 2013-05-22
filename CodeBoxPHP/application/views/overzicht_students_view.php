<h3>Selecteer leerling:</h3>

<?php 
	$result = $this->globalfunc->students($studyid,$classid);
	$count = 0;
	foreach($result as $row)
	{
		echo("<li><a href='../../../overzicht/subject/$studyid/$classid/$row->id'>$row->username</a></li>");
		$count++;
	}
	if($count == 0)
	{
		echo("Geen studenten gevonden voor deze opleiding!");
	}
?>

<br/><br/>
<input type = "button" name = "ReturnButton" onclick = "history.go(-1);" value="Terug"/>