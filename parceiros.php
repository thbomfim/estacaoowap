<?php

include("core.php");
include("config.php");

bd_connect();

echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";

echo "<head>";


echo "<title>$stitle</title>";
echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style.css\" />";
echo "</head>";
echo "<body>";

$a = $_GET["a"];
$id = $_GET["id"];
$sid = $_GET["sid"];
$uid = getuid_sid($sid);

if(is_logado($sid)==false)
{
echo "<p align=\"center\">";
echo "Voc� n�o est� logado!<br>";
echo "<br><a href=\"index.php\">Login</a><br>";
echo "</p>";
exit();
}
adicionar_online("Parceiros", "", $sid);
if($a=="apagar")
{
if(isadmin($uid))
{
echo "<p align=\"center\">";
$r = mysql_query("DELETE FROM fun_parceiros WHERE id='".$id."'");
if($r)
{
echo "<b>Parceiro apagado com sucesso!</b><br>";
}
else
{
echo "<b>Erro, tente mais tarde!</b><br>";
}
}
}
else if($a=="admin2")
{
if(isadmin($uid))
{
echo "<p align=\"center\">";
$nome = mysql_real_escape_string($_POST["nome"]);
$url = addslashes($_POST["url"]);
if(empty($url)||empty($nome))
{
echo "<b>Nada pode ficar em branco!</b><br>";
}
else
{
mysql_query("INSERT INTO fun_parceiros SET nome='".$nome."', url='".$url."', date='".time()."'");
echo "<b>Parceiro adicionado com sucesso!</b><br>";
}
}
}
else if($a=="admin")
{
echo "<p align=\"center\">";
echo "<form action=\"?a=admin2&sid=$sid\" method=\"post\">";
echo "Nome: <input name=\"nome\"><br>";
echo "URL: <input value=\"http://\" name=\"url\"><br>";
echo "<input value=\"Adicionar\" type=\"submit\"><br></form>";
echo "<br>";
}
else
{
echo "<p align=\"center\">";
echo "<b>Parceiros</b><br></p>";
$sql = mysql_query("SELECT id, nome, url FROM fun_parceiros ORDER BY date");
while($p=mysql_fetch_array($sql))
{
if(isadmin($uid))
{
$del = "<a href=\"?a=apagar&sid=$sid&id=$p[0]\">[X]</a>";
}
else
{
$del = "";
}
echo "$p[1]<br>URL: <a href=\"$p[2]\">$p[2]</a> $del<br><br>";
}
}
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
?>
