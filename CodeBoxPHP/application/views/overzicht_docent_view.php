<h3>Selecteer studierichting:</h3>

<div>
<?php
	$result = $this->globalfunc->studies();
	foreach($result as $row)
	{
		$name = ucfirst(strtolower($row->name));
		echo("<li><a href='overzicht/choice/$row->id'>$name</a></li>");
	}
?>
</div>

<br/>
<input type = "button" name = "ReturnButton" onclick = "history.go(-1);" value="Terug"/>