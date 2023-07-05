<?php

//SCRIPT CRIADO POR ÁLVARO
//CONTATO: suporte@alvarowap.com

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
$uid = getuid_sid($sid);
if(is_logado($sid)==false)
{
echo "<p align=\"center\">";

echo "Você não está logado!<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
    }
function pbanco($uid)
{
$banco = mysql_fetch_array(mysql_query("SELECT banco FROM fun_users WHERE id='".$uid."'"));
return $banco[0];
}
$uab = mysql_fetch_array(mysql_query("SELECT value FROM fun_settings WHERE name='banco'"));
$dia = date("Y-m-d");
if($dia!=$uab[0])
{
$usuarios = mysql_query("SELECT banco, id FROM fun_users WHERE banco>'99'");
while($usuario=mysql_fetch_array($usuarios))
{
$rda = (1/100) * $usuario[0];
$renda = explode(".", $rda);
$add = $renda[0] + $usuario[0];
mysql_query("UPDATE fun_users SET banco='".$add."' WHERE id='".$usuario[1]."'");
}
mysql_query("UPDATE fun_settings SET value='".$dia."' WHERE name='banco'");
}
adicionar_online(getuid_sid($sid),"Banco $snome","");
echo "<p align=\"center\"><b>Banco $snome</b><br/><br/>Bem vindo(a) ao banco $snome aqui você deposita suas moedas e tem um juro de 1% por dia!<br/><br/>Você tem ".getplusses($uid)." no perfil e ".pbanco($uid)." moedas no banco!<br/><br/>";
$pontos = $_POST["pontos"];
if(empty($pontos))
{
}
else{
$acao = $_POST["acao"];
if($acao=="add")
{
if(getplusses(getuid_sid($sid))<$pontos)
{
echo "Você não tem $pontos moedas no perfil!";
}
else{
echo "Você transferiu $pontos moedas para o banco com sucesso!";
$pb = pbanco($uid) + $pontos;
$pp = getplusses($uid) - $pontos;
mysql_query("UPDATE fun_users SET banco='".$pb."', plusses='".$pp."' WHERE id='".$uid."'");
}
}
else{
if(pbanco(getuid_sid($sid))<$pontos)
{
echo "Você não tem $pontos moedas no banco!";
}
else{
echo "Você transferiu $pontos moedas para o perfil com sucesso!";
$pb = pbanco($uid) - $pontos;
$pp = getplusses($uid) + $pontos;
mysql_query("UPDATE fun_users SET banco='".$pb."', plusses='".$pp."' WHERE id='".$uid."'");
}
}
echo "<br/><br/>";
}
echo "<form action=\"banco.php?sid=$sid\" method=\"post\">Moedas: <input name=\"pontos\"/><br/>Ação: <select name=\"acao\"><option value=\"add\">Depositar</option><option value=\"tirar\">Retirar</option></select><br/><input type=\"submit\" value=\"Enviar\"/></form>";
echo "<p align=\"center\"><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"\"/>Página principal</a>";
?>