<h3>Selecteer Studierichting:</h3><br/><br/>

<div>
<?php
	$result = $this->globalfunc->studies();
	foreach($result as $row)
	{
		echo("<li><a href='overzicht/students/$row->id'>$row->name</a></li>");
	}
?>
</div>
<br/>
<input type = "button" name = "ReturnButton" onclick = "history.go(-1);" value="Terug"/>