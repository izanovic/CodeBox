<h3>Vakken voor deze leerling:</h3>

<div class = "datagrid">
<?php
	$result = $this->user->subjects($studentname);
	$count = count($result);
	if($count == 0)
	{
		echo("Er zijn geen vakken beschikbaar voor deze student!");
	}
	else
	{
		echo("<table border='1'><tr><th>Leerling</th><th>Status</th><th>Deadline</th><th>Download</th></tr>");
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
			$basecss = base_url();
			if($expired) { $expiredtext = "verlopen"; } else { $expiredtext = "open"; }
			if(!$alreadysend)
			{
				//echo "<li>$vaknaam - Niet voldaan. [Deadline: $datedisplay - $expiredtext]</li>";
				echo("<tr><td>$vaknaam</td><td>Niet voldaan <img src='$basecss/images/notdone.jpg' alt='Niet voldaan'></td><td>$datedisplay</td><td>Niet beschikbaar</td></tr>");
			}
			else
			{
				$base = base_url() . "index.php";
				//echo "<li><a href='$base/overzicht/subject/$studyid/$studentname/$row->subjectID'>$vaknaam - Download bestand.</a>  [Deadline: $datedisplay - $expiredtext]</li>";
				echo("<tr><td>$vaknaam</td><td>Voldaan <img src='$basecss/images/done.jpg' alt='Voldaan'></td><td>$datedisplay</td><td><a href='$base/overzicht/subject/$studyid/$studentname/$row->subjectID'>Download</a></td></tr>");
			}
	    }
	 }
?>
</table>
</div>
<br/>
<input type = "button" name = "ReturnButton" onclick = "history.go(-1);" value="Terug"/>