<h3>Download bestand:</h3>

<div>
<?php
	if($rolename == 'docent' || $rolename == "administrator")
	{
		$version = 1;
		$fileformat = $short_subject_name . "_" . $student_name . "_" . $version;
		$query = $this->db->query("SELECT location FROM files WHERE location LIKE '%$fileformat.%'");
		$ext = "";
		foreach($query->result() as $row)
		{
			$loc = $row->location;
			$ext = explode('.',$loc);
			$ext = $ext[1];
		}
		$data = file_get_contents("files/" . $fileformat . "." . $ext);
		$name = $fileformat . "." . $ext;
		force_download($name, $data);
	}
?>
</div>

<br/>
<input type = "button" name = "ReturnButton" onclick = "history.go(-1);" value="Terug"/>