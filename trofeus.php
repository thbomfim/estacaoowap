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

$a = $_GET["a"];
$who = $_GET["who"];
$sid = $_GET["sid"];
$uid = getuid_sid($sid);

if(is_logado($sid)==false)
{
echo "<p align=\"center\">";
echo "Voc� n�o est� logado!<br><br>";
echo "<a href=\"index.php\">Login</a><br></p>";
exit;
}
adicionar_online("Vendo trof�us", "", $sid);
if($a=="add")
{
$id = $_POST["id"];
$trofeu = $_POST["tipo"];
$motivo = $_POST["motivo"];
$em = date("d/m/y - H:i:s");
echo "<p align=\"center\">";
if(empty($id)||empty($motivo))
{
echo "<b>Nada pode ficar em branco!</b><br>";
}
else if(empty($id)||isuser($id)==false)
{
echo "<b>Usu�rio n�o existe!</b><br>";
}
else if(!isadmin($uid))
{
echo "<b>Voc� n�o � da equipe!</b><br>";
}
else
{
$insert = mysql_query("INSERT INTO fun_trofeus SET who='".$id."', motivo='".$motivo."', hora='".$em."', tipo='".$trofeu."'");
if($insert)
{
echo "<b>Trof�u adicionado com sucesso!</b><br>";
}
else
{
echo "<b>Erro, tente novamente mais tarde!</b><br>";
}
}
}
else if($a=="admin")
{
echo "<p align=\"center\">";
echo "<b>Adicionar Trof�u</b></p>";
echo "<form action=\"?a=add&sid=$sid\" method=\"POST\">";
echo "ID: <input name=\"id\"><br>";
echo "Motivo: <input name=\"motivo\"><br>";
echo "Trof�u de: <select name=\"tipo\">";
echo "<option value=\"1\">Ouro</option>";
echo "<option value=\"2\">Prata</option>";
echo "<option value=\"3\">Bronze</option>";
echo "</select><br/>";
echo "<input value=\"Adicionar\" type=\"submit\"></form>";
}
else if($a=="cf")
{
echo "<p align=\"center\">";
echo "<b>Como ganhar trof�us?</b><br><br>";
echo "Para ganhar trof�us, voc� deve se destacar no site... participe de concursos, brincadeiras, jogos e muito mais! Os trof�us s�o <b>ouro, prata, bronze</b> aproveite e ganhe j� o seu!";
echo "<br></p>";
}
else if($a=="meus")
{
$p = $_GET["p"];
echo "<p align=\"center\">";
echo "<b>Meus trof�us</b></p>";
if($p==""||$p<=0)$p=1;
$todos = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_trofeus WHERE who='".$who."'"));
$num_itens = $todos[0];
$itens_per_page = 5;
$num_pages = ceil($num_itens/$itens_per_page);
if(($p>$num_pages) AND $p!=1)$p = $num_itens;
$limit_start = ($p-1)*$itens_per_page;

$sql = mysql_query("SELECT tipo, motivo, hora, who FROM fun_trofeus WHERE who='".$who."'");

while($t = mysql_fetch_array($sql))
{
$t[1] = htmlspecialchars($t[1]);
$nick = getnick_uid($t[3]);
echo "<img src=\"images/t/$t[0].gif\"><br>";
echo "<b>$t[1]</b><br>Para: $nick<br/><small>$t[2]</small><br><br>";
}

echo "<p align=\"center\">";
if($p>1)
{
$np = $p-1;
echo "<a href=\"?a=meus&sid=$sid&who=$who&p=$np\">&#171;Voltar</a> ";
}
if($p<$num_pages)
{
$pp = $p+1;
echo "<a href=\"?a=meus&sid=$sid&who=$who&p=$pp\">Mais&#187;</a>";
}
echo "<br>$p/$num_pages<br><br>";
echo "</p>";
}

else if($a=="todos")
{
$p = $_GET["p"];
echo "<p align=\"center\">";
echo "<b>Todos trof�us</b></p>";
if($p==""||$p<=0)$p=1;
$todos = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_trofeus"));
$num_itens = $todos[0];
$itens_per_page = 5;
$num_pages = ceil($num_itens/$itens_per_page);
if(($p>$num_pages) AND $p!=1)$p = $num_itens;
$limit_start = ($p-1)*$itens_per_page;

$sql = mysql_query("SELECT tipo, motivo, hora, who FROM fun_trofeus");

while($t = mysql_fetch_array($sql))
{
$t[1] = htmlspecialchars($t[1]);
$nick = getnick_uid($t[3]);
echo "<img src=\"images/t/$t[0].gif\"><br>";
echo "<b>$t[1]</b><br>Para: $nick<br/><small>$t[2]</small><br><br>";
}

echo "<p align=\"center\">";
if($p>1)
{
$np = $p-1;
echo "<a href=\"?a=todos&sid=$sid&who=$who&p=$np\">&#171;Voltar</a> ";
}
if($p<$num_pages)
{
$pp = $p+1;
echo "<a href=\"?a=todos&sid=$sid&who=$who&p=$pp\">Mais&#187;</a>";
}
echo "<br>$p/$num_pages<br><br>";
echo "</p>";
}
else
{
echo "<p align=\"center\">";
echo "<b>Trof�us $snome</b><br></p>";
$meus = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_trofeus WHERE who='".$uid."'"));
echo "<a href=\"?a=meus&sid=$sid&who=$uid\">&#187;Meus trof�us($meus[0])</a><br>";
$todos = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_trofeus"));
echo "<a href=\"?a=todos&sid=$sid\">&#187;Todos os trof�us($todos[0])</a><br>";
echo "<a href=\"?a=cf&sid=$sid\">&#187;Como ganhar trof�us?</a><br>";
}

echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\">P�gina principal</a></p>";
?>