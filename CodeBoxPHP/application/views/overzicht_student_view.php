<h3>Overzicht van al uw vakken:</h3>

<h3><?php echo "Het is nu: " . $datenow; ?></h3>

<div>
<?php
	$result = $this->user->subjects($username);
	$count = 0;
	foreach ($result as $row)
	{
		$vaknaam = $row->name;
		$alreadysend = $this->user->isalreadysend($username,$row->subjectID);
		$deadline = $this->globalfunc->getexpiredatafromdb($row->subjectID);
		$expired = $this->globalfunc->expiredsubject($row->subjectID);
		date_default_timezone_set('Europe/Amsterdam');
		$date = new DateTime();
		$date->setTimestamp($deadline);
		$datedisplay = $date->format('d/m/Y H:i:s');
		$expiretxt = "";
		if($expired)
		{
			$expiretxt = "verlopen";
		}
		else
		{
			$expiretxt = "actief";
		}
		if(!$alreadysend)
		{
			echo "<li>$vaknaam - Niet voldaan [Deadline: $datedisplay - $expiretxt]</li>";
		}
		else
		{
			echo "<li>$vaknaam - Ingeleverd</a></li>";
		}
		$count++;
    }
	if($count == 0)
	{
		echo("Er zijn geen vakken beschikbaar voor deze student!");
	}
?>
</div>