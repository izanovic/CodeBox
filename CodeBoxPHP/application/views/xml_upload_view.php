			
		<h3>Invoegen vakken</h3>
		Upload hier de XML van de vakken indien deze geupdate moeten worden:<br/><br/>
		<?php echo $error;?>
		<?php echo form_open_multipart('vakken_updaten/do_upload'); ?>
		<b>XML bestand kiezen:</b><br/><br/>
		<input type="file" name="userfile" size="20" />
		<br /><br />
		<input type="submit" value="Vakken updaten" /> <input type="button" value="Annnuleren" name="CancelButton" onclick="history.go(-1);" />
		</form>