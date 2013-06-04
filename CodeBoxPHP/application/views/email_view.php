<br />
<?php echo form_open('verifymail/handle/' . $subjectid); ?>
	<input type = "Text" VALUE ="<?php echo('Codebox Inleveringsherinnering - ' . $this->globalfunc->getsubjectnamefromid($subjectid)); ?>"  NAME = "onderwerp" size = "80"/>
	<br /><br />

	<textarea Type ="Text" VALUE= "Voer hier een bericht in." Name = "mail" style="height:200" rows="8" cols="80" ><?php echo $this->globalfunc->getsubjectnamefromid($subjectid); ?> is nog niet ingeleverd!</textarea>

	<br /><br />
	<input type="submit" VALUE="Send Email" /> <input type="button" value="Annnuleren" name="CancelButton" onclick="history.go(-1);" />
	<br/>
	<div><b><?php echo validation_errors(); ?></b></div>
</form>