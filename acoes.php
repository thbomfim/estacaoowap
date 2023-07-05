<?php
//include core.php and config.php files
include("core.php");
include("config.php");
//html cod
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>";
echo "<!DOCTYPE html PUBLIC \"-//WAPFORUM//DTD XHTML Mobile 1.0//EN\"\"http://www.wapforum.org/DTD/xhtml-mobile10.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
echo "<head>";
echo "<title>$stitle</title>";
echo "<link rel=\"StyleSheet\" type=\"text/css\" href=\"style.css\" />";
echo "</head>";
echo "<body>";
//db connect
bd_connect();
//get 
$sid = $_GET["sid"];
$a = $_GET["a"];
$who = $_GET["who"];
$uid = getuid_sid($sid);
//islogged 
if(is_logado($sid)==false)
{
echo "<p align=\"center\">";
echo "Voc� n�o est� logado!<br/><br/>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}
//isuser
if($who==""||$who==0||isuser($who)==false)
{
echo "<p align=\"center\">";
echo "Usu�rio n�o existe!<br><br>";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}

if($a=="a")
{
$p = $_GET["p"];
adicionar_online("Vendo a��es", "", $sid);
echo "<p align=\"center\">";
$nick = getnick_uid($who);
echo "<b>A��es de $nick</b><br></p>";
if($p==""||$p<=0)$p=1;
$n = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_acoes WHERE who='".$who."'"));
$num_itens = $n[0];
$itens_per_page = 5;
$num_pages = ceil($num_itens/$itens_per_page);
if(($p>$num_pages) AND $p!=1)$p = $num_itens;
$limit_start = ($p-1)*$itens_per_page;
$sql = "SELECT id, uid, acao, who, date FROM fun_acoes WHERE who='".$who."' ORDER BY date DESC LIMIT $limit_start,$itens_per_page";

$reg = mysql_query($sql);
while($acoes=mysql_fetch_array($reg))
{ 
$n1 = getnick_uid($acoes[1]);
$n2 = getnick_uid($acoes[3]);
$acao = htmlspecialchars($acoes[2]);
$data = date("d/m/y - H:i:s", $acoes[4]);
echo "$n1 $acao $n2<br/><small>$data</small><br><br>";
}
echo "<p align=\"center\">";
if($p>1)
{
$np = $p-1;
echo "<a href=\"?a=a&sid=$sid&who=$who&p=$np\">&#171;Voltar</a> ";
}
if($p<$num_pages)
{
$pp = $p+1;
echo "<a href=\"?a=a&sid=$sid&who=$who&p=$pp\">Mais&#187;</a>";
}
echo "<br>$p/$num_pages<br><br>";
if($uid!=$who)
{
$n = getnick_uid($who);
echo "<a href=\"?a=enviar&sid=$sid&who=$who\">Enviar a��o para $n</a><br>";
}
echo "</p>";
}
else if($a=="enviar2")
{
adicionar_online("Enviando a��o", "", $sid);
$acao = $_POST["acao"];
echo "<p align=\"center\">";
if(empty($who)||$who==0||!isuser($who))
{
echo "<img src=\"images/notok.gif\" alt=\"\"/>Usu�rio n�o existe!<br>";
}
else if($uid==$who)
{
echo "<img src=\"images/notok.gif\" alt=\"\"/>Voc� n�o pode enviar a��es para voc� mesmo!<br>";
}
else if(getplusses($uid)<149)
{
echo "<img src=\"images/notok.gif\" alt=\"\"/>Voc� deve ter 150 pontos para enviar uma a��o!<br>";
}
else
{
$res = mysql_query("INSERT INTO fun_acoes SET acao='".$acao."', who='".$who."', uid='".$uid."', date='".time()."'");
if($res)
{
$de = getnick_uid2($uid);
$sms = "Ol� /reader, voc� recebeu uma a��o do usu�rio[b] $de [/b], para visualiza-la v� at� seu perfil![br/][small]Torpedo Altom�tico![/small]";
autopm($sms, $who);
echo "<img src=\"images/ok.gif\" alt=\"\"/>A��o enviada com sucesso!<br>";
}
else
{
echo "<img src=\"images/notok.gif\" alt=\"\"/>Erro, tente mais tarde!<br>";
}
}
}
else if($a=="enviar")
{
adicionar_online("Enviando a��o", "", $sid);
echo "<p align=\"center\">";
echo "<b>Enviar A��es</b><br/></p>";
echo "<form action=\"?a=enviar2&sid=$sid&who=$who\" method=\"post\">";
echo "A��o: <select name=\"acao\">";
//nick  voce
echo "<option value=\"um abra�o em\">Abra�o</option>";
echo "<option value=\"cutucou\">Cutucar</option>";
echo "<option value=\"deu um pis�o em\">Pis�o</option>";
echo "<option value=\"deu tapa em\">Tapa</option>";
echo "<option value=\"deu um selino em\">Selinho</option>";
echo "<option value=\"beliscou\">Beliscar</option>";
echo "<option value=\"piscou para\">Piscar</option>";
echo "<option value=\"gritou com\">Gritar</option>";
echo "<option value=\"apertou a m�o de\">Aperto de m�o</option>";
echo "<option value=\"mandou uma cantada para\">Cantada</option>";
echo "<option value=\"puxou o cabelo de\">Puxar Cabelo</option>";
echo "<option value=\"deu uma rasteira em\">Rasteira</option>";
echo "<option value=\"mandou uma flor para\">Flor</option>";
echo "</select><br>";
echo "<input value=\"Enviar\" type=\"submit\"></form><br>";
}
else
{
}
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\">P�gina principal</a></p>";
?>