<h3>Overzicht:</h3>

<div>
<?php 
	$deadline = $this->globalfunc->getexpiredatafromdb($subjectid);
	$expired = $this->globalfunc->expiredsubject($subjectid);
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
	echo("<b>Deadline: $datedisplay [$expiretxt]</b>")
?>
</div>

<h3>Ingeleverd:</h3>

<div><?php 

	$result = $this->globalfunc->getstudentsinsubject($studyid,$subjectid);
	$count = 0;
	foreach($result as $row)
	{
		$alreadysend = $this->user->isalreadysend($row->username,$subjectid);
		$base = base_url() . "index.php";
		if($alreadysend)
		{
			echo("<li><a href='$base/overzicht/subject/$studyid/$row->username/$subjectid'>$row->username - Download bestand.</a>");
			$count++;
		}
	}
	if($count == 0)
	{
		echo("Niemand heeft iets voor dit vak ingeleverd!");
	}

?></div>

<h3>Nog niet ingeleverd:</h3>

<div><?php 

	$result = $this->globalfunc->getstudentsinsubject($studyid,$subjectid);
	$count = 0;
	foreach($result as $row)
	{
		$alreadysend = $this->user->isalreadysend($row->username,$subjectid);
		if(!$alreadysend)
		{
			echo("<li>$row->username</li>");
			$count++;
		}
	}
	if($count == 0)
	{
		echo("Niemand hoeft meer iets in te leveren.");
	}

?></div>

<br/>
<input type = "button" name = "ReturnButton" onclick = "history.go(-1);" value="Terug"/>