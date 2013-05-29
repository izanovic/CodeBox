<h3>Vakkenoverzicht</h3>

<?php
	$result = $this->globalfunc->studysubjects($studyid);
	foreach($result as $row)
	{
		echo("<li><a href='../userlistbysubject/$studyid/$row->subjectid'>$row->name</a></li>");
	}
?>
