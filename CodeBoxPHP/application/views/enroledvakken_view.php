<h3>Selecteer uw vak:</h3>
Welkom, deze vakken zijn beschikbaar voor uw opleiding.
<br/><br/>
<div class = "datagrid">

<?php
	$result = $this->user->subjects($username);
	$count = count($result);
	if($count == 0)
	{
		echo("Er zijn geen vakken beschikbaar!");
	}
	else
	{
		echo("<table border='1'><tr><th>Vak</th><th>Inleveren</th><th>Status</th></tr>");
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
					//echo "<li><img src='$basecss/images/done.jpg' alt='Voldaan'> $vaknaam - Voldaan. [<a href='$base/inleveren/edit/$row->subjectID/'>aanpassen</a>]</li>";
					echo("<tr><td>$vaknaam</td><td><a href='$base/inleveren/edit/$row->subjectID/'>aanpassen</a></td><td>Voldaan <img src='$basecss/images/done.jpg' alt='Voldaan'></td></tr>");
				}
				else
				{
					echo("<tr><td>$vaknaam</td><td><a href='$base/inleveren/vak/$row->subjectID'>Inleveren</a></td><td>Niet voldaan <img src='$basecss/images/notdone.jpg' alt='Niet voldaan'></td></tr>");
					//echo "<li><img src='$basecss/images/notdone.jpg' alt='Niet voldaan'> $vaknaam - Niet voldaan! <a href='$base/inleveren/vak/$row->subjectID'>[Inleveren]</a></li>";
				}
			}
			else
			{
				echo("<tr><td>$vaknaam</td><td>Inleveren en aanpassen niet meer mogelijk</td><td>Verlopen <img src='$basecss/images/expired.jpg' alt='Verlopen'></td></tr>");
				//echo("<li><img src='$basecss/images/expired.jpg' alt='Verlopen!'>$vaknaam - Deadline is overschreden. [Inleveren en aanpassen niet mogelijk]</li>");
			}
			$count++;
	    }
	    echo("</table>");
	}
?>
</div><br/>
<input type = "button" name = "ReturnButton" onclick = "history.go(-1);" value="Terug"/>