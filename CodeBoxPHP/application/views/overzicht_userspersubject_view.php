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

<div class = "datagrid"><?php 

	$result = $this->globalfunc->getstudentsinsubject($studyid,$subjectid);
	$count = 0;
	echo("<table border='1'><tr><th>Leerling</th><th>Download</th></tr>");
	foreach($result as $row)
	{
		$alreadysend = $this->user->isalreadysend($row->username,$subjectid);
		$fullname = $this->user->getfullnamefromdb($row->username);
		$base = base_url() . "index.php";
		if($alreadysend)
		{
			//echo("<li><a href='$base/overzicht/subject/$studyid/$row->username/$subjectid'>$row->username - Download bestand.</a>");
			echo("<tr><td>$fullname</td><td><a href='$base/overzicht/subject/$studyid/$row->username/$subjectid'>Download</a></td></tr>");
			$count++;
		}
	}
	if($count == 0)
	{
		echo("Niemand heeft iets voor dit vak ingeleverd!");
		echo("<tr><td>Leeg</td><td>-</td></tr></table><br/>");	
	}
	echo("</table>");	

?></div>

<h3>Nog niet ingeleverd:</h3>

<div id = "printablediv" class = "datagrid"><?php 

	$result = $this->globalfunc->getstudentsinsubject($studyid,$subjectid);
	$count = 0;
	echo("<table border='1'><tr><th>Leerling</th></tr>");
	foreach($result as $row)
	{
		$alreadysend = $this->user->isalreadysend($row->username,$subjectid);
		$fullname = $this->user->getfullnamefromdb($row->username);
		if(!$alreadysend)
		{
			echo("<tr><td>$fullname</td></tr>");
			$count++;
		}
	}
	if($count == 0)
	{
		echo("<tr><td>Leeg</td><td>-</td></tr></table><br/>");
		echo("Niemand hoeft meer iets in te leveren.<br/>");
	}
	echo("</table>");
	$base = base_url() . "index.php";
	echo("<br/><div><a href='$base/overzicht/mailusers/$subjectid'>Stuur herinnering</a></div>");

?></div>
<br/>
<input type = "button" name = "PrintButton" onclick = "javascript:printDiv('printablediv')" value="Lijst afdrukken"/><input type = "button" name = "ReturnButton" onclick = "history.go(-1);" value="Terug"/>