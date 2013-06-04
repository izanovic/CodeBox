<h3>Gebruikers en wachtwoorden</h3>

<table border="1">
<tr>
<th>Opleiding</th>
<th>Volledige naam</th>
<th>Gebruikersnaam</th>
<th>Wachtwoord</th>
</tr>

<?php
	$result = $this->user->getallusersfromdb();
	foreach($result as $row)
	{
		$user = $row->Username;
		$splittest = explode('_',$user);
		if($splittest[0] == "admin") { continue; }
		$fullname = $row->Fullname;
		$studyname = $this->globalfunc->getstudynamefromid($row->studyid);
		$randompassword = $this->user->randompassword(10);
		$this->user->setuserpassword($user,$randompassword);
		echo("<tr><td><b>$studyname</b></td><td>$fullname</td><td>$user</td><td><b>$randompassword</b></td></tr>");
	}
?>
</table>