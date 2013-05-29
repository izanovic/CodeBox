<!doctype html>
<html lang="nl">
<head>
	<meta charset=utf-8>
	<title>LDAP Unit test: Authenticate</title>
</head>
<body>


<?php
/*
		//$this->config->load('ldap',true);
		$ldap_ip = "141.252.8.105";//$this->config->item('ldap_ip');
		$ldap_port = 380;//$this->config->item('ldap_port');
		$userid = 'leps1200';
		$ldapconnection = @ldap_connect($ldap_ip,$ldap_port);
	    $ldaprdn  = 'uid=' . $userid;     // ldap rdn or dn
	    $ldappass = '';  // associated password
        if(!$ldapconnection)
        {
			die("Boooee!");
        }
		else
        {
		    ldap_set_option($ldapconnection, LDAP_OPT_PROTOCOL_VERSION, 3);
		    ldap_set_option($ldapconnection, LDAP_OPT_REFERRALS, 0);
            $result = ldap_bind($ldapconnection,$ldaprdn,$ldappass);
            if($result)
            {
				die("JAAAA!");
            }
            else
            {
				die("Boeee kwadraat!");
			}
      	}


      	// $ds is a valid link identifier for a directory server

		// $person is all or part of a person's name, eg "Jo"
*/
      	/*
		$dn = "o=Noordelijke Hogeschool Leeuwarden,c=nl";

		set_time_limit(30);
		error_reporting(E_ALL);
		ini_set('error_reporting', E_ALL);
		ini_set('display_errors',1);

		// config
		$ldapserver = '141.252.8.105';
		$ldapport = 380;
		$ldapuser      = ''; 
		$ldappass     = '';
		$ldaptree    = "ou=voltijd,ou=Informatica BA,ou=Techniek,ou=studenten,o=Noordelijke Hogeschool Leeuwarden,c=nl";
		// connect
		$ldapconn = ldap_connect($ldapserver,$ldapport) or die("Could not connect to LDAP server.");
  		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
    	ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
		if($ldapconn) 
		{
		    // binding to ldap server
		    $ldapbind = ldap_bind($ldapconn) or die ("Error trying to bind: " . ldap_error($ldapconn));
		    // verify binding
		    $filter="(|(sn=*)(givenname=*))";
			$justthese = array("ou", "sn", "givenname", "mail");

			$sr=ldap_search($ldapconn, $ldaptree, $filter, $justthese);

			$info = ldap_get_entries($ldapconn, $sr);

			echo $info["count"]." entries returned\n";
		}

		$connection = @ldap_connect('ldapmaster.nhl.nl',380) or die(ldap_error());
		if($connection)
		{
			ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_bind($connection);
		}
		else
		{
			die('Could not connect to LDAP server');
		}

		$user = "leps1200";
		$pass = "";

		$search = ldap_search($connection,'ou=voltijd,ou=Informatica BA,ou=Techniek,ou=studenten,o=Noordelijke Hogeschool Leeuwarden,c=nl',"uid=" . $user);
		$result = ldap_get_entries($connection,$search);
		$ldapUserString = $result[0]['dn'];
		$ldapResult = ldap_bind($connection,$ldapUserString,$pass);
		$ldapAuthInfo = ($ldapResult? $result : false);
		return $ldapAuthInfo;
*/
		//$username = "oosterha";
		$connection = @ldap_connect("ldapmaster.nhl.nl",380) or die(ldap_error());
		if($connection)
		{
			ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_bind($connection);
		}
		else
		{
			die("error");
		}

		$dn = "ou=voltijd,ou=Informatica BA,ou=Techniek,ou=studenten,o=Noordelijke Hogeschool Leeuwarden,c=nl"; //ou=voltijd,ou=Informatica BA,ou=Techniek,ou=studenten,
		$filter = "uid=*";
		$search = ldap_search($connection, $dn, $filter);
		$entries = ldap_get_entries($connection, $search);
		//echo("gast");
?>

<pre><?php 
	foreach($entries as $row)
	{
		var_dump($row["uid"][0]);
	}

?></pre>
</body>
</html>