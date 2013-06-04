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
		$isexpired = $this->globalfunc->expiredsubject($row->subjectID);
		$base = base_url() . "index.php";
		$basecss = base_url();
		if(!$isexpired)
		{
			if($alreadysend)
			{
				echo "<li><img src='$basecss/images/done.jpg' alt='Voldaan'> $vaknaam - Voldaan. [<a href='$base/inleveren/edit/$row->subjectID/'>aanpassen</a>]</li>";
			}
			else
			{
				echo "<li><img src='$basecss/images/notdone.jpg' alt='Niet voldaan'> $vaknaam - Niet voldaan! <a href='$base/inleveren/vak/$row->subjectID'>[Inleveren]</a></li>";
			}
		}
		else
		{
			echo("<li><img src='$basecss/images/expired.jpg' alt='Verlopen!'>$vaknaam - Deadline is overschreden. [Inleveren en aanpassen niet mogelijk]</li>");
		}
		$count++;
    }
	if($count == 0)
	{
		echo("Er zijn geen vakken beschikbaar!");
	}
?>
</div>