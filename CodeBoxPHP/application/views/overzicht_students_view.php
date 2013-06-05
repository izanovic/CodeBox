<h3>Selecteer leerling:</h3>


<?php 
	$result = $this->globalfunc->students($studyid);
	$count = count($result);
	if($count == 0)
	{
		echo("Geen studenten gevonden voor deze opleiding!");
	}
	else
	{
		echo("<table border='1'><tr><th>Leerling</th></tr>");
		foreach($result as $row)
		{
			//$username_displ = $this->user->getfullnamefromldap($row->username);
			$fulluser = ucfirst($row->fullname);
			$base = base_url();
			//echo("<li><a href='$base" . "index.php/overzicht/student/$studyid/$row->username'>$fulluser</a></li>");
			echo("<tr><td><a href='$base" . "index.php/overzicht/student/$studyid/$row->username'>$fulluser</a></td></tr>");
		}
		echo("</table>");
	}
?>
<br/><br/>
<input type = "button" name = "ReturnButton" onclick = "history.go(-1);" value="Terug"/>