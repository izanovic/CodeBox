<h3>Vakkenoverzicht</h3>


<div class = "datagrid">
<?php
	$result = $this->globalfunc->studysubjects($studyid);
	if(count($result) == 0) { echo ("Er zijn geen vakken om weer te geven!"); } else
	{
		echo("<table border='1'><tr><th>Vak</th></tr>");
		foreach($result as $row)
		{
			$base = base_url() . "index.php";
			//echo("<li><a href='$base/overzicht/userlistbysubject/$studyid/$row->subjectid'>$row->name</a></li>");
			echo("<tr><td><a href='$base/overzicht/userlistbysubject/$studyid/$row->subjectid'>$row->name</a></td></tr>");
		}
		echo("</table>");
	}
?>
</div>
<br/>
<input type = "button" name = "ReturnButton" onclick = "history.go(-1);" value="Terug"/>