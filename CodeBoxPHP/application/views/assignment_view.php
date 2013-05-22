<h3>Selecteer de opdracht</h3>

<div>
	<?php 	
		$result = $this->user->assignments($username,$subjectid);
		$count = 0;
		foreach ($result as $row)
		{
			$query = $this->db->query("SELECT title FROM assignment WHERE id = '$row->id'");
			foreach($query->result() as $row2)
			{
				$opdrachtid = $row2->title;
			}
			echo "<li><a href='../assignment/$subjectid/$row->id'>$opdrachtid</a></li>";
			$count++;
		}
		if($count == 0)
		{
			echo("Er zijn geen opdrachten voor dit vak beschikbaar!");
		}
	?>
</div>