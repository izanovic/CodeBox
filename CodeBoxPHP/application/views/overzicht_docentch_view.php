<h3>Maak uw keuze:</h3>

<div class = "datagrid">
<a href="<?=base_url()?>index.php/overzicht/studentlist/<?php echo $studyid; ?>">Overzicht per student.</a><br/>
<a href="<?=base_url()?>index.php/overzicht/subjectlist/<?php echo $studyid; ?>">Overzicht per vak.</a>
</div>
<br/>
<input type = "button" name = "ReturnButton" onclick = "history.go(-1);" value="Terug"/>