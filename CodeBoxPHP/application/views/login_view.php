<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<link href= "../../css/StyleInlog.css" rel="Stylesheet" type="text/css"/>
		<title>Login - CodeBox</title>
	</head>
	<body>
	    <?php echo form_open('verifylogin'); ?>
			<div id = "Form">
				<div id = "padding">
					</br></br>
					<h1>Log in met je NHL account</h1>
					<h2>Gebruikersnaam</h2><img id = "logo" src="../../images/nhl_logo.png" alt="Logo">
					<input id = "username" type="text" size="12" maxlength="15" name="username" Class = "boxes"><br />
					<h2>Wachtwoord</h2>
					<input id = "password" type="password" size="12" maxlength="30" name="password" Class = "boxes"><br />
					</br>
					<button id = "button" type="submit">Inloggen >></button><a href="home/vergeten">wachtwoord vergeten?</a>
					</br>
					<div><b><?php echo validation_errors(); ?></b></div>
					<h3>Storing of vraag? Bel support 058-251 2552</h3>
				</div>
			</div>
		</form>
	</body>
</html>