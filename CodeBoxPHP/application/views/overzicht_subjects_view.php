<h3>Vakken voor deze leerling:</h3>

<div>
<?php
	$result = $this->user->subjects($student_name);
	$count = 0;
	foreach ($result as $row)
	{
		$vaknaam = $row->name;
		$alreadysend = $this->user->isalreadysend($student_name,$row->subjectID);
		if(!$alreadysend)
		{
			echo "<li>$vaknaam - Niet voldaan.</li>";
		}
		else
		{
			echo "<li><a href='../../../../overzicht/subject/$studyid/$classid/$studentid/$row->subjectID'>$vaknaam - Download bestand.</a></li>";
		}
		$count++;
    }
	if($count == 0)
	{
		echo("Er zijn geen vakken beschikbaar voor deze student!");
	}
?>
</div>

<br/>
<input type = "button" name = "ReturnButton" onclick = "history.go(-1);" value="Terug"/>