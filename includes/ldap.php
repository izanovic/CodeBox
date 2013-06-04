<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	define('_ldapServer_','ldapmaster.nhl.nl');
	define('_ldapPort_',380);
	define('_ldapDomains_','o=Noordelijke Hogeschool Leeuwarden,c=nl');
	define('_ldapVersion_',3);
	ini_set('MAX_EXECUTION_TIME', 1800);

	//wanneer deze externe class wordt gebruikt, gebruik je de onderstaande include
	//ini_set("include_path", ";c:/xampp/htdocs/includes"); //-.- slordige oplossing, maar codeigniter staat niet andere mogelijkheden toe
	//syntaxis is anders bij linux-systemen!
	//include("ldap.php");
	class LDAP
	{
		public function __construct()
		{

		}
		public static function authenticate($user,$password)
		{
			if($password == '' || $user == '')
			{
				return false;
			}
			else
			{
				$connection = @ldap_connect(_ldapServer_,_ldapPort_) or die(ldap_error());
				if($connection)
				{
					ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, _ldapVersion_);
					ldap_bind($connection);
				}
				else
				{
					//die('Could not connect to LDAP server');
					return false;
				}
				$search = ldap_search($connection,_ldapDomains_,"uid=" . $user);
				$result = ldap_get_entries($connection,$search);
				$ldapUserString = $result[0]['dn'];
				$ldapResult = ldap_bind($connection,$ldapUserString,$password);
				$ldapAuthInfo = ($ldapResult? $result : false);
				if(count($ldapAuthInfo) < 2)
				{
					return false;
				}
				else
				{
					return true;
				}
			}
		}
		public static function getuserinfo($user)
		{
				$connection = @ldap_connect(_ldapServer_,_ldapPort_) or die(ldap_error());
				if($connection)
				{
					ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, _ldapVersion_);
					ldap_bind($connection);
				}
				else
				{
					die('Could not connect to LDAP server');
				}
				$search = ldap_search($connection,_ldapDomains_,"uid=" . $user);
				$result = ldap_get_entries($connection,$search);
				$ldapUserString = $result[0]['dn'];
				$ldapResult = @ldap_bind($connection,$ldapUserString,$password);
				$ldapAuthInfo = ($ldapResult? $result : false);
				return $ldapAuthInfo;
		}
		public static function isavailable()
		{
			$ds = ldap_connect(_ldapServer_,_ldapPort_);
			$anon = @ldap_bind($ds);
			if (!$anon) 
			{
				return false;
			}
			else 
			{
			    return true;
			}
			ldap_unbind($ds);
		}
		public static function getmail($username)
		{
			$connection = @ldap_connect(_ldapServer_,_ldapPort_) or die(ldap_error());
			if($connection)
			{
				ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, _ldapVersion_);
				ldap_bind($connection);
			}
			else
			{
				die('Could not connect to LDAP server');
			}
			$dn = _ldapDomains_; //ou=voltijd,ou=Informatica BA,ou=Techniek,ou=studenten,
			$filter = "uid=" . $username;
			$search = ldap_search($connection, $dn, $filter) or die ("Search failed");
			$entries = ldap_get_entries($connection, $search);
			return $entries[0]["mail"][0];
		}
		public static function getstudy($username)
		{
			$connection = @ldap_connect(_ldapServer_,_ldapPort_) or die(ldap_error());
			if($connection)
			{
				ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, _ldapVersion_);
				ldap_bind($connection);
			}
			else
			{
				die('Could not connect to LDAP server');
			}
			$dn = _ldapDomains_; //ou=voltijd,ou=Informatica BA,ou=Techniek,ou=studenten,
			$filter = "uid=" . $username;
			$search = ldap_search($connection, $dn, $filter) or die ("Search failed");
			$entries = ldap_get_entries($connection, $search);
			return $entries[0]["ou"][0];
		}
		public static function getldaprole($username)
		{
			$connection = @ldap_connect(_ldapServer_,_ldapPort_) or die(ldap_error());
			if($connection)
			{
				ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, _ldapVersion_);
				ldap_bind($connection);
			}
			else
			{
				return "gast";
			}
			$master = _ldapDomains_;
			$dn = "ou=Engineering, ou=Techniek, ou=personeel," . $master; //ou=voltijd,ou=Informatica BA,ou=Techniek,ou=studenten,
			$filter = "uid=" . $username;
			$search = ldap_search($connection, $dn, $filter);
			$entries = ldap_get_entries($connection, $search);
			if(count($entries) == 1)
			{
				$dn = "ou=Techniek,ou=studenten," . $master; //ou=voltijd,ou=Informatica BA,ou=Techniek,ou=studenten,
				$filter = "uid=" . $username;
				$search = ldap_search($connection, $dn, $filter);
				$entries = ldap_get_entries($connection, $search);
				if(count($entries) == 1)
				{
					return "gast";
				}
				else
				{
					return "student";
				}
			}
			else
			{
				return "docent";
			}
			return "gast";
		}
		public static function getfullusername($username)
		{
			$connection = @ldap_connect(_ldapServer_,_ldapPort_) or die(ldap_error());
			if($connection)
			{
				ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, _ldapVersion_);
				ldap_bind($connection);
			}
			else
			{
				die('Could not connect to LDAP server');
			}
			$dn = _ldapDomains_; //ou=voltijd,ou=Informatica BA,ou=Techniek,ou=studenten,
			$filter = "uid=" . $username;
			$search = ldap_search($connection, $dn, $filter) or die ("Search failed");
			$entries = ldap_get_entries($connection, $search);
			return $entries[0]["cn"][0];
		}
		public static function ldapallusers()
		{
			set_time_limit(1200);
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

			$dn = "ou=Techniek,ou=studenten,o=Noordelijke Hogeschool Leeuwarden,c=nl"; //ou=voltijd,ou=Informatica BA,ou=Techniek,ou=studenten,
			$filter = "uid=*";
			$search = ldap_search($connection, $dn, $filter);
			$entries = ldap_get_entries($connection, $search);
			return $entries;
		}
	}

?>