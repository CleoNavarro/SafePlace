<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php echo $titulo; ?></title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width; initial-scale=1.0;">
	<!--<link rel="stylesheet" id="mapa" type="text/css" href="/estilos/mapa.css" /> -->
	<link rel="stylesheet" type="text/css" href="/estilos/main.css" />
	<!-- <link rel="stylesheet" type="text/css" href="/estilos/accesibilidad.css" /> -->
	<link rel="icon" type="image/png" href="/imagenes/favicon.png" />
	<?php
	if (isset($this->textoHead))
		echo $this->textoHead;
	?>
</head>
<body>

		<header>
			 <div class="logocontainer">
				<div class="logo">
				<a href="/index.php"><img id="logo" src="/imagenes/logo.png" height="100px" /></a>
				</div>
			</div>
		</header>

		<nav class="barraNav">
			<ul>
				<?php
					if (isset($this->menu)) {
						foreach ($this->menu as $opcion) {
							echo CHTML::dibujaEtiqueta(
								"li",
								array(),
								"",
								false
							);
							echo CHTML::link(
								$opcion["texto"],
								$opcion["enlace"]
							);
							echo CHTML::dibujaEtiquetaCierre("li");
							echo CHTML::dibujaEtiqueta("br") . "\r\n";
						}
					}

				?>
			</ul>
		</nav>

		<?php echo $contenido; ?><!-- #content -->


		

		

	</div><!-- #wrapper -->
</body>

</html>