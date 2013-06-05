<h3>Selecteer studierichting:</h3>
<div class = "datagrid">
<table>
<tr>
<th>Studie</th>
</tr>
<div>
<?php
	$result = $this->globalfunc->studies();
	foreach($result as $row)
	{
		$name = $row->name;
		$base = base_url();
		//echo("<li><a href='$base" . "index.php/overzicht/choice/$row->id'>$name</a></li>");
		echo("<tr><td><a href='$base" . "index.php/overzicht/choice/$row->id'>$name</a></td></tr>");
	}
?>
</table>
</div>

<br/>
<input type = "button" name = "ReturnButton" onclick = "history.go(-1);" value="Terug"/>