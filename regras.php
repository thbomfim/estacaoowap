<?php

echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";

	echo "<head>";
	include("config.php");
	echo "<title>$snome</title>";
	echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style.css\" />";
	echo "</head>";
echo "<body>";
echo "<p align=\"center\"><b>Regras do Site</b><br/></p>";
echo "- � proibido toda ou qualquer forma de divulga��o (spam), de outras comunidades com as mesmas caracter�sticas da comunidade $snome.<br/>";
echo "- Postagens no f�rum fora do contexto que o t�pico abordar, ou postagens nocivas ao site, ser�o apagadas pelos moderadores sem aviso pr�vio.<br/>";
echo "- N�o s�o permitidos postagens ou arquivos com apologias, racismo ou pornografia infantil.<br/>";
echo "- Cadastros duplicados acarreta-r� no cancelamento de um dos cadastros.<br/>";
echo "- Nao sao permitidos nicks vulgares ou com ofensas a outros usu�rios.<br/>";
echo "- Obrigado, divirta-se!";
echo "<p align=\"center\">";
$sid = $_GET["sid"];
if(empty($sid))
{
echo "<a href=\"index.php\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina de entrada</a></p>";
}
else
{
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a></p>";
}
?>