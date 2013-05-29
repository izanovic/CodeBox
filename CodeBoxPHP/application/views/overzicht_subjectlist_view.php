<h3>Vakkenoverzicht</h3>

<div>
<?php
	$result = $this->globalfunc->studysubjects($studyid);
	if(count($result) == 0) { echo ("Er zijn geen vakken om weer te geven!"); }
	foreach($result as $row)
	{
		echo("<li><a href='../userlistbysubject/$studyid/$row->subjectid'>$row->name</a></li>");
	}
?>
</div>

<br/>
<input type = "button" name = "ReturnButton" onclick = "history.go(-1);" value="Terug"/>