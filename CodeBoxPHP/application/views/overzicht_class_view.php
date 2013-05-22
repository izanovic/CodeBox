<h3>Selecteer klas of project:</h3>

<?php 
	$result = $this->globalfunc->classes($studyid);
	foreach($result as $row)
	{
		echo("<li><a href='../../overzicht/students/$studyid/$row->id'>$row->name</a></li>");
	}
?>

<br/>
<input type = "button" name = "ReturnButton" onclick = "history.go(-1);" value="Terug"/>