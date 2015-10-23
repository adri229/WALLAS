<?php
 //file: view/layouts/default.php
 
 require_once(__DIR__."/../../core/ViewManager.php");
 $view = ViewManager::getInstance();
 $currentuser = $view->getVariable("currentusername");
 $numSolicitudes = $view->getVariable("numSolicitudes");
?><!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
<head>
	<meta charset="utf-8">
	<title><?= $view->getVariable("title", "no title") ?></title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<?= $view->getFragment("css") ?>
    <?= $view->getFragment("javascript") ?>
</head>
<body>
<div class="container">

	<header>
		<a href="index.php?controller=posts&action=viewPosts"> 
			<h1 id="logo">FlaBoo</h1>
		</a>
		<a href="index.php?controller=users&action=logout"><img src="assets/img/logout.png"  alt="LogOut" height="58" width="62" id="logout"></a>
	</header>
	
	<nav class="menusup">
		<a id="busqueda" href="index.php?controller=friends&action=buscaramigos"><input class="botongris" type="submit" value="<?= i18n("Buscar Amigos")?>"></a>	
	</nav>
	
	<aside>
		<div id="asidefoto">
			<img id="fotouser" src="assets/img/usera.jpg" alt="LogOut" height="190" width="150">
		</div>
		<div id="asidenombre">
			<center><a href="index.php?controller=users&action=modificarContrasena"><input class="botongris" type="submit" value="<?= i18n("Modificar contraseña")?>"></a></center>
			<p><?=$currentuser->getEmail()?></p>
			<p><?=$currentuser->getName()?></p>
		</div>
	</aside>
	
	
	<main>
	<?= $view->popFlash() ?>
  
    <?= $view->getFragment(ViewManager::DEFAULT_FRAGMENT) ?>    
 	</main>
	
	<div id="menuderecha">
		<ul>
			<li>
				<div>
					<a href="index.php?controller=friends&action=amigos"><img src="assets/img/amigos.png" alt="Amigos" height="60" width="60"></a>
				</div>
				<div class="letraderech"><?= i18n("Amigos")?></div>
			</li>
			<li>
				<div>
					<a href="index.php?controller=friends&action=solicitudes"><img src="assets/img/solicitudes.png" alt="Solicitudes de amistad" height="60" width="60"></a>
				</div>
				<div class="letraderech"><?= i18n("Solicitudes Amistad")?>(<?=$numSolicitudes?>)</div>
			</li>
		</ul>
	</div>
	
	<footer>
		
		<ul class="idioma">
			<li class="idiomaL">SELECCIONA EL IDIOMA QUE DESEAS:</li>
			<li class="idiomaL"><a href="index.php?controller=language&action=change&lang=es">Español</a></li>
			<li class="idiomaL"><a href="index.php?controller=language&action=change&lang=en">Ingles</a></li>
		</ul>
	
	</footer>
	
</div>
</body>
</html> 