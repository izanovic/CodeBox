<h3>Vakken voor deze leerling:</h3>

<div>
<?php
	$result = $this->user->subjects($studentname);
	$count = 0;
	foreach ($result as $row)
	{
		$vaknaam = $row->name;
		$alreadysend = $this->user->isalreadysend($studentname,$row->subjectID);
		$deadline = $this->globalfunc->getexpiredatafromdb($row->subjectID);
		$expired = $this->globalfunc->expiredsubject($row->subjectID);
		date_default_timezone_set('Europe/Amsterdam');
		$date = new DateTime();
		$date->setTimestamp($deadline);
		$datedisplay = $date->format('d/m/Y H:i:s');
		$expiredtext = "";
		if($expired) { $expiredtext = "verlopen"; } else { $expiredtext = "open"; }
		if(!$alreadysend)
		{
			echo "<li>$vaknaam - Niet voldaan. [Deadline: $datedisplay - $expiredtext]</li>";
		}
		else
		{
			echo "<li><a href='../../../overzicht/subject/$studyid/$studentname/$row->subjectID'>$vaknaam - Download bestand.</a>  [Deadline: $datedisplay - $expiredtext]</li>";
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