<h3>Gebruikers en wachtwoorden</h3>

<div id="printablediv" class = "datagrid">
<table>
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
		if($this->user->isactivated($user) == 0)
		{
			$splittest = explode('_',$user);
			if($splittest[0] == "admin") { continue; }
			$fullname = $row->Fullname;
			$studyname = $this->globalfunc->getstudynamefromid($row->studyid);
			$randompassword = $this->user->randompassword(10);
			$this->user->setuserpassword($user,$randompassword);
			echo("<tr><td><b>$studyname</b></td><td>$fullname</td><td>$user</td><td><b>$randompassword</b></td></tr>");
		}
		else
		{
			$splittest = explode('_',$user);
			if($splittest[0] == "admin") { continue; }
			$fullname = $row->Fullname;
			$studyname = $this->globalfunc->getstudynamefromid($row->studyid);
			echo("<tr><td><b>$studyname</b></td><td>$fullname</td><td>$user</td><td><b>Account al in gebruik.</b></td></tr>");	
		}
	}
?>
</table>
</div>
<br/><input type = "button" name = "PrintButton" onclick = "javascript:printDiv('printablediv')" value="Afdrukken"/><input type = "button" name = "ReturnButton" onclick = "history.go(-1);" value="Terug"/> 