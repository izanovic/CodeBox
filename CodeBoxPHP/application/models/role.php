<?php
Class Role extends CI_Model
{
	//Returns the role from the database.
	function rolename($roleid)
	{
		$this -> db -> select('rolename');
		$this -> db -> from('roles');
		$this -> db -> where('roleid', $roleid);
		$this -> db -> limit(1);
		$query = $this -> db -> get();

	    if($query -> num_rows() == 1)
		{
			$result = $query->result();
			if($result)
			{
				foreach($result as $row)
				{
					return $row->rolename;
				}
			}
			else
			{
				return "undefined";
			}
	    }
	    else
	    {
			return "undefined";
	    }
	}
}
?>