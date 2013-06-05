<h3>Overzicht van al uw vakken:</h3>

<h3><?php echo "Het is nu: " . $datenow; ?></h3>

<div class = "datagrid">
<?php
	$result = $this->user->subjects($username);
	$count = count($result);
	if($count == 0)
	{
		echo("Er zijn geen vakken beschikbaar voor deze student!");
	}
	else
	{
		echo("<table border='1'><tr><th>Studie</th><th>Status</th><th>Deadline</th></tr>");
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
			$basecss = base_url();
			if($expired)
			{
				$expiretxt = "<img src='$basecss/images/expired.jpg' alt='Verlopen'>";
			}
			else
			{
				$expiretxt = "actief";
			}
			if(!$alreadysend)
			{
				//echo "<li>$vaknaam - Niet voldaan [Deadline: $datedisplay - $expiretxt]</li>";
				echo("<tr><td>$vaknaam</td><td>Niet voldaan <img src='$basecss/images/notdone.jpg' alt='Niet voldaan'></td><td>$datedisplay $expiretxt</td></tr>");
			}
			else
			{
				//echo "<li>$vaknaam - Ingeleverd</a></li>";
				echo("<tr><td>$vaknaam</td><td>Voldaan <img src='$basecss/images/done.jpg' alt='Voldaan'></td><td>$datedisplay $expiretxt</td></tr>");
			}
	    }
	    echo("</table>");
	}
?>
</div>