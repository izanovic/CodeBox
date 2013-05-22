<h3>Selecteer uw vak:</h3>
Welkom, deze vakken zijn beschikbaar voor uw opleiding.
<br/><br/>
<div>
<?php
	$result = $this->user->subjects($username);
	$count = 0;
	foreach ($result as $row)
	{
		$vaknaam = $row->name;
		$alreadysend = $this->user->isalreadysend($username,$row->subjectID);
		if($alreadysend)
		{
			echo "<li>$vaknaam - Voldaan. [<a href='inleveren/edit/$row->subjectID/'>aanpassen</a>]</li>";
		}
		else
		{
			echo "<li><a href='inleveren/vak/$row->subjectID'>$vaknaam - Niet voldaan!</a></li>";
		}
		$count++;
    }
	if($count == 0)
	{
		echo("Er zijn geen vakken beschikbaar!");
	}
?>
</div>