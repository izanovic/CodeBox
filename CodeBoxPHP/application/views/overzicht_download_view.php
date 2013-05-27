<h3>Download bestand:</h3>

<div>
<?php
	if($rolename == 'docent' || $rolename == "administrator")
	{
		$this->load->helper('download');
		$fileformat = $short_subject_name . "_" . $student_name . "_";
		$result = glob ("files/$fileformat*.*");
		$version = 1;
		$ext = "";
		foreach($result as $row)
		{
			$str = explode('_',$row);
			$version = $str[2];
		}
		$fileformat = $short_subject_name . "_" . $student_name . "_" . $version;
		$result2 = glob ("files/$fileformat.*");
		foreach($result2 as $row)
		{
			$str = explode('.',$row);
			$ext = $str[1];
		}
		if(file_exists("files/$fileformat.$ext"))
		{
			$data = file_get_contents("files/" . $fileformat . "." . $ext);
			$name = $fileformat;
			force_download($name, $data);
			ob_clean();
			exit($data);
		}
		else
		{
			echo("Oeps dit bestand bestaat niet!");
		}
	}
?>
</div>

<br/>
<input type = "button" name = "ReturnButton" onclick = "history.go(-1);" value="Terug"/>