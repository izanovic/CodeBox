<h3>Selecteer Opleiding:</h3><br/><br/>

<div>
<?php
	$result = $this->globalfunc->studies();
	foreach($result as $row)
	{
		echo("<li><a href='overzicht/sclass/$row->id'>$row->name</a></li>");
	}
?>
</div>
<br/>
<input type = "button" name = "ReturnButton" onclick = "history.go(-1);" value="Terug"/>