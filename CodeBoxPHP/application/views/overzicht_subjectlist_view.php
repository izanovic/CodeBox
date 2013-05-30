<h3>Vakkenoverzicht</h3>

<div>
<?php
	$result = $this->globalfunc->studysubjects($studyid);
	if(count($result) == 0) { echo ("Er zijn geen vakken om weer te geven!"); }
	foreach($result as $row)
	{
		$base = base_url() . "index.php";
		echo("<li><a href='$base/overzicht/userlistbysubject/$studyid/$row->subjectid'>$row->name</a></li>");
	}
?>
</div>

<br/>
<input type = "button" name = "ReturnButton" onclick = "history.go(-1);" value="Terug"/>