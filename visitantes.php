<?php
include("core.php");
include("config.php");
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
echo "<head>";
echo "<title>$stitle</title>";
echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style.css\" />";
echo "</head>";
echo "<body>";
bd_connect();
$sid = $_GET["sid"];
$a = $_GET["a"];
if($a=="mp3")
{
echo "<p align=\"center\">";
echo "<b>Buscar MP3</b></p>";
echo "<p>";
echo "Ol� visitante, aqui no $snome voc� baixa milhares de m�sicas da internet totalmente gr�tis!";
echo "<br />Rapidamente voc� faz uma busca pelo cantor/banda/musica e milhares de resultados est�o na tela do seu CELULAR ou PC!";
echo "<br />O que voc� est� esperando? Fa�a j� seu cadastro e aproveite!";
echo "<p align=\"center\">";
echo "<a href=\"index.php\"><img src=\"images/home.gif\">";
echo "P�gina principal</a>";
echo "</p>";
}
?>