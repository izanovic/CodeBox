<h3>Administratie mogelijkheden</h3>
Hier kunnen functies worden gebruikt om de website te onderhouden, zoals het bijwerken van de gehele userbase. Let op!<br/>
Wanneer alle gebruikers gesynchroniseerd worden met de LDAP server, is de gehele server bezig, dit kan ervoor zorgen dat <br/>
alles heel traag verloopt, wacht tot de browser een melding geeft dat het inladen succesvol is verlopen.
<br/>
<br/>
<a href="<?=base_url()?>index.php/administratie/addusers" onclick="return confirm('Zeker weten? Dit proces neemt minimaal 10 minuten in beslag!')">Alle gebruikers synchroniseren met LDAP.</a><br/>
<a href="<?=base_url()?>index.php/vakken_updaten">Vakken met behulp van XML inladen.</a><br/>
<a href="<?=base_url()?>index.php/administratie/cleanupdatabase" onclick="return confirm('Zeker weten? Deze functie zorgt ervoor dat bestanden die niet meer in de files folder staan, worden opgeschoond in de database.')">Inactieve database entries opschonen.</a><br/>
<a href="<?=base_url()?>index.php/administratie/generaterandompasswords" onclick="return confirm('Alle wachtwoorden worden op deze manier gewijzigd, zeker weten?')">Wachtwoorden opnieuw instellen en op beeld tonen.</a><br/>