<?php 
 require_once(__DIR__."/../../core/ViewManager.php");
 $view = ViewManager::getInstance();
 $errors = $view->getVariable("errors");
 $currentuser = $view->getVariable("currentusername");
 $view->setVariable("title", "Flaboo -- Modificar Contraseña");
?>
<div>
	<h1 class="txtsolicitudes"><?= i18n("Modificar contraseña")?></h1>
</div>
<div id="registration">
	<form action="index.php?controller=users&action=modificarContrasena" method="POST">
		<fieldset id="fieldset"> 
			<div class="inputTextReg"> <?= i18n("Nueva Contraseña:")?> <input type="text" name="password"
				value="<?= isset($_POST["password"])?$_POST["password"]:$currentuser->getPassword() ?>">
				<br><?= isset($errors["password"])?$errors["password"]:"" ?><br> 
			</div>
			<div class="inputTextReg"> <?= i18n("Repetir contraseña:")?> <input type="text" name="password2"
				value="<?= isset($_POST["password2"])?$_POST["password2"]:$currentuser->getPassword() ?>">
				<br><?= isset($errors["password2"])?$errors["password2"]:"" ?><br> 
			</div>
			<input class="botongris" type="submit" value="<?= i18n("Guardar Cambios")?>">
		</fieldset>
	</form>	
</div>