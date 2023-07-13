<?php
//include files core.php and config.php
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

cleardata();//erases all old data
//database off
if(!$bcon)
{
echo "<p align=\"center\">";
echo "<img src=\"images/notok.gif\" alt=\"*\"/><br />";
echo "ERRO! banco de dados sem conex�o!<br /><br />";
echo "Aguarde alguns instantes e tente novamente!<br />";
echo "</p>";
exit();
}
$action = $_GET["action"];
$sid = $_GET["sid"];
$page = $_GET["page"];
$who = $_GET["who"];
$uid = getuid_sid($sid);
//ip banned
if(ip_ban(ver_ip(), navegador()))
{
echo "<p align=\"center\">";
echo "<img src=\"images/notok.gif\" alt=\"\">Desculpe, mais voc� teve o BAN m�ximo(<b>IP</b>)!";
echo "<br />";
echo "<br />";
$infos_ban = mysql_fetch_array(mysql_query("SELECT tempo, motivo FROM fun_ban WHERE ip='".ver_ip()."' AND browser='".navegador()."' AND tipoban='2'"));
echo "Tempo para acabar sua penalidade: " . tempo_msg($infos_ban[0]);
echo "<br />";
echo "Motivo da sua penalidade: <b>".htmlspecialchars($infos_ban[1])."</b>";
exit();
}
//banned normal
if(is_banido($uid))
{
echo "<p align=\"center\">";
echo "<img src=\"images/notok.gif\" alt=\"\">Desculpe, mais voc� foi banido do site!";
echo "<br />";
echo "<br />";
$infos_ban = mysql_fetch_array(mysql_query("SELECT tempo, motivo FROM fun_ban WHERE uid='".$uid."' AND (tipoban='1' OR tipoban='2')"));
echo "Tempo para acabar sua penalidade: " . tempo_msg($infos_ban[0]);
echo "<br />";
echo "Motivo da sua penalidade: <b>".htmlspecialchars($infos_ban[1])."</b>";
exit();
}
//logged
if((is_logado($sid)==false)||($uid==0) AND $action!="")
{
echo "<p align=\"center\">";
echo "Voc� n�o est� logado!<br /><br />";
echo "<a href=\"index.php\">Login</a>";
echo "</p>";
exit();
}
//update ip and browser
mysql_query("UPDATE fun_users SET browserm='".navegador()."', ipadd='".ver_ip()."' WHERE id='".getuid_sid($sid)."'");
////////////////////////////////////////main page
if($action=="main")
{
addvisitor();//add visit in site
adicionar_online(getuid_sid($sid),"P�gina principal","");
$uid = getuid_sid($sid);
echo "<p align=\"center\">";
echo "".date("D d M y - H:i:s")."<br />";
echo "<img src=\"images/logo.png\" alt=\"*\"/><br />";
$mural = scan_msg(htmlspecialchars(mural_admin()), $sid);
echo "$mural<br />";
$Hour = date("G",time());
$nick = getnick_uid($uid);
if ($Hour <= 4) { $saldacao = "Boa Madrugada <a href=\"index.php?action=perfil&who=$uid&sid=$sid\">$nick</a>!"; }
else if ($Hour <= 11) { $saldacao = "Bom Dia <a href=\"index.php?action=perfil&who=$uid&sid=$sid\">$nick</a>!"; }
else if ($Hour <= 12) { $saldacao = "Bom Almo�o <a href=\"index.php?action=perfil&who=$uid&sid=$sid\">$nick</a>!"; }
else if ($Hour <= 17) { $saldacao = "Boa Tarde <a href=\"index.php?action=perfil&who=$uid&sid=$sid\">$nick</a>!"; }
else if ($Hour <= 22) { $saldacao = "Boa Noite <a href=\"index.php?action=perfil&who=$uid&sid=$sid\">$nick</a>!"; }
else if ($Hour <= 24) { $saldacao = "Boa Madrugada <a href=\"index.php?action=perfil&who=$uid&sid=$sid\">$nick</a>!"; }
echo "<center>$saldacao</center>";
echo "</p>";
//count pm
$tmsg = getpmcount(getuid_sid($sid));
$umsg = getunreadpm(getuid_sid($sid));
echo "<a href=\"inbox.php?action=main&sid=$sid\"><img src=\"images/torpedos.gif\" alt=\"*\"/>";
echo "Torpedos($umsg/$tmsg)</a><br />";
$recados = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_gbook WHERE gbowner='".$uid."'"));
echo "<a href=\"lists.php?action=gbook&sid=$sid&who=$uid\"><img src=\"images/recados.gif\" alt=\"*\"/>Recados($recados[0])</a><br />";
echo "<a href=\"index.php?action=forum&sid=$sid\"><img src=\"images/folder.gif\" alt=\"*\"/>F�rum</a><br />";
$chs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs"));
echo "<a href=\"index.php?action=clmenu&sid=$sid\"><img src=\"images/comunidades.gif\" alt=\"*\"/>Comunidades($chs[0])</a><br />";
$chs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_chonline"));
echo "<a href=\"index.php?action=chat&sid=$sid\"><img src=\"images/batepapo.gif\">Chat($chs[0])</a><br />";
$mybuds = getnbuds($uid);
$onbuds = getonbuds($uid);
echo "<a href=\"lists.php?action=buds&sid=$sid\"><img src=\"images/amigos.gif\" alt=\"*\"/>Amigos($onbuds/$mybuds)</a>";
$reqs = getnreqs($uid);
if($reqs>0)
{
echo ": <a href=\"lists.php?action=reqs&sid=$sid\">$reqs</a>";
}
echo "<br />";
echo "<a href=\"index.php?action=cpanel&sid=$sid\"><img src=\"images/config.gif\" alt=\"*\"/>Configura��es</a><br />";
$alb = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_albums"));
echo "<a href=\"album.php?&a=albums&sid=$sid\"><img src=\"images/galeria.gif\" alt=\"*\"/>�lbuns($alb[0])</a><br />";
$down = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_downloads"));
echo "<a href=\"downloads.php?sid=$sid\"><img src=\"images/downloads.gif\" alt=\"*\"/>Downloads($down[0])</a><br />";
echo "<a href=\"index.php?action=funm&sid=$sid\"><img src=\"images/diversao.gif\" alt=\"*\"/>Divers�o</a><br />";
echo "<a href=\"index.php?action=sub&sid=$sid\"><img src=\"images/mextra.gif\" alt=\"*\"/>Menu extra</a><br />";
/////////////////menu admin
if (isadmin(getuid_sid($sid)))
{
echo "<a href=\"admincp.php?action=main&sid=$sid\"><img src=\"images/admn.gif\" alt=\"*\"/>Admin CP</a><br />";
}
/////////////menu mod
if(ismod($uid))
{
$tnor = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE reported='tk'"));
$tot = $tnor[0];
$tnor = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE reported='1'"));
$tot += $tnor[0];
$tnor = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE reported='1'"));
$tot += $tnor[0];
$tnol = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_log"));
$tol = $tnol[0]; 
echo "<a href=\"modcp.php?action=main&sid=$sid\"><img src=\"images/den.gif\" alt=\"*\"/>Mod R/L($tot/$tol)</a><br />";
}
echo "<p align=\"center\">";
echo "<b>Mural de recados</b><br />";
echo getshoutbox($sid);
echo "<br /><br />";
echo "Usu�rios online: <a href=\"index.php?action=online&sid=$sid\">".getnumonline()."</a><br />";
$timeout = 600;
$timeon = time()-$timeout;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE perm>'0' AND lastact>'".$timeon."'"));
echo "Equipe online: <a href=\"index.php?action=stfol&sid=$sid\">".$noi[0]."</a><br />";
$timeout2 = 600;
$timeon2 = time()-$timeout2;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE vip='1' AND lastact>'".$timeon2."'"));
echo "VIPs online: <a href=\"index.php?action=vipol&sid=$sid\">".$noi[0]."</a><br />";
$memid = mysql_fetch_array(mysql_query("SELECT id, name  FROM fun_users ORDER BY regdate DESC LIMIT 0,1"));
echo "Novo usu�rio: <b><a href=\"index.php?action=perfil&who=$memid[0]&sid=$sid\">".getnick_uid($memid[0])."</a></b><br /><br />";
echo "<a href=\"index.php?action=sair&sid=$sid\"><img src=\"teks/hit.gif\" alt=\"*\"/>";
echo "Sair</a>";
echo "</p>";
}
/////////////////////////////////////menu extra
else if($action=="sub")
{
adicionar_online(getuid_sid($sid),"Menu extra","");
echo "<p align=\"center\">";
echo "<b>Menu extra</b>";
echo "</p>";
echo "<p align=\"left\">";
$trofeus = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_trofeus"));
echo "<a href=\"relacionamento.php?sid=$sid\"><img src=\"images/coracao.gif\" alt=\"\">Relacionamento</a><br />";
echo "<a href=\"trofeus.php?sid=$sid\"><img src=\"images/trofeus.gif\" alt=\"*\">Trof�us(".$trofeus[0].")</a><br />";
echo "<a href=\"banco.php?sid=$sid\"><img src=\"images/banco.png\" alt=\"*\"/>Banco $snome</a><br />";
echo "<a href=\"loja.php?a=main&sid=$sid\"><img src=\"images/loja.png\" alt=\"*\"/>Loja de presentes</a><br />";
echo "<a href=\"horoscopo.php?sid=$sid\"><img src=\"images/star.gif\" alt=\"*\"/>Hor�scopo</a><br />";
echo "<a href=\"novelas.php?sid=$sid\"><img src=\"images/novelas.gif\" alt=\"*\">Novelas</a><br />";
echo "<a href=\"index.php?action=stats&sid=$sid\"><img src=\"images/top.gif\" alt=\"*\"/>Estat�sticas</a><br />";
$sml = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_smilies"));
echo "<a href=\"paginas.php?p=sml&sid=$sid\"><img src=\"images/bug.gif\" alt=\"*\"/>Smilies($sml[0])</a><br />";
echo "<a href=\"index.php?action=search&sid=$sid\"><img src=\"images/buscar.gif\" alt=\"*\"/>Buscar</a><br />";
$paceiros = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_parceiros"));
echo "<a href=\"parceiros.php?sid=$sid\"><img src=\"images/parceiros.gif\" alt=\"*\">Paceiros(".$paceiros[0].")</a><br />";
echo "<a href=\"regras.php?sid=$sid\"><img src=\"images/mextra.gif\" alt=\"*\"/>Regras do site</a>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a><br />";
echo "</p>";
}
////////////////////////////////////////forum menu
else if($action=="forum")
{
echo "<p align=\"center\">";
echo "<b>F�rum $snome</b>";
echo "</p>";
$fcats = mysql_query("SELECT id, name FROM fun_fcats ORDER BY position, id");
$iml = "<img src=\"images/1.gif\" alt=\"*\"/>";
while($fcat=mysql_fetch_array($fcats))
{
$catlink = "<a href=\"index.php?action=viewcat&sid=$sid&cid=$fcat[0]\">$iml$fcat[1]</a>";
echo "$catlink<br />";
$forums = mysql_query("SELECT id, name FROM fun_forums WHERE cid='".$fcat[0]."' AND clubid='0' ORDER BY position, id, name");
if(flood_forum()==0)
{
echo "";
while($forum=mysql_fetch_array($forums))
{
if(canaccess(getuid_sid($sid),$forum[0]))
{
}
}
echo "";
}
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a><br />";
echo "</p>";
}
//////////////////////////////////sair do site
else if($action=="sair")
{
adicionar_online(getuid_sid($sid),"Saiu do site","");
echo "<p align=\"center\">";
mysql_query("DELETE FROM fun_ses WHERE id = '".$sid."' ");
echo "<img src=\"images/ok.gif\" alt=\"*\"/>Que pena, ja saiu? Volta outra vez t�?!";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "Pagina de entrada</a><br />";
echo "</p>";
}
/////////////////////////////////moderar membro de comunidade
else if($action=="clmop")
{
$clid = $_GET["clid"];
$who = $_GET["who"];
adicionar_online(getuid_sid($sid),"Moderando Comunidade","");
echo "<p align=\"center\">";
$whnick = getnick_uid($who);
echo "<b>Moderar Membro</b>";
echo "</p>";
echo "<p>";
$exs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='".$who."' AND clid=".$clid.""));
$cow = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE owner='".$uid."' AND id=".$clid.""));
if($exs[0]>0 && $cow[0]>0)
{
echo "<a href=\"genproc.php?action=dcm&sid=$sid&who=$who&clid=$clid\">&#187;Expulsar $whnick</a><br />";
//echo "<a href=\"index.php?action=gcp&sid=$sid&who=$who&clid=$clid\">&#187;Pontos de $whnick</a><br />";
//echo "<a href=\"index.php?action=gpl&sid=$sid&who=$who&clid=$clid\">&#187;Dar $whnick</a><br />";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Informacao errada!";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
else if($action=="gcp")
{
$clid = $_GET["clid"];
$who = $_GET["who"];
$whnick = getnick_uid($who);
adicionar_online(getuid_sid($sid),"Moderando Comunidade","");
$exs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='".$who."' AND clid=".$clid.""));
$cow = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE owner='".$uid."' AND id=".$clid.""));
if($exs[0]>0 && $cow[0]>0)
{
echo "<p align=\"center\">";
echo "<b>Moderar Pontos</b>";
echo "</p>";
echo "<form action=\"genproc.php?action=gcp&sid=$sid&who=$who&clid=$clid\" method=\"post\">";
echo "A��o: <select name=\"giv\">";
echo "<option value=\"1\">Adicionar</option>";
echo "<option value=\"0\">Remover</option>";
echo "</select><br />";
echo "Valor de Pontos: <input name=\"pnt\" format=\"*N\" size=\"2\" maxlength=\"2\"/><br />";
echo "<input type=\"submit\" value=\"Enviar\"/>";
echo "</form>";
echo "<p align=\"center\">";
echo "Aten��o, os pontos que voc� vai dar para $whnick ser�o removidos da comunidade!";
echo "</p>";
}else
{
}
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
else if($action=="gpl")
{
$clid = $_GET["clid"];
$who = $_GET["who"];
adicionar_online(getuid_sid($sid),"Editando comunidade","");
echo "<p align=\"center\">";
$whnick = getnick_uid($who);
echo "<b>$whnick</b>";
echo "</p>";
echo "<p>";
$exs = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='".$who."' AND clid=".$clid.""));
$cow = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE owner='".$uid."' AND id=".$clid.""));
if($exs[0]>0 && $cow[0]>0)
{
echo "<img src=\"images/point.gif\" alt=\"!\"/>Voce nao pode enviar,receber,trocar,etc...os creditos<br />";
$cpl = mysql_fetch_array(mysql_query("SELECT plusses FROM fun_clubs WHERE id='".$clid."'"));
echo "<img src=\"images/point.gif\" alt=\"!\"/>Creditos da comunidade: $cpl[0]<br />";
echo "<img src=\"images/point.gif\" alt=\"!\"/>Nao abuse dando creditos aos usuarios ou perdera a comunidade!<br /><br />";
echo "<form action=\"genproc.php?action=gpl&sid=$sid&who=$who&clid=$clid\" method=\"post\">";
echo "Pontos: <input name=\"pnt\" format=\"*N\" size=\"2\" maxlength=\"2\"/><br />";
echo "<input type=\"submit\" value=\"IR\"/>";
echo "</form>";
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Faltando informa��es!";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
///////////////////////////////////Control Panel
else if($action=="cpanel")
{
adicionar_online(getuid_sid($sid),"Configura��es","");
echo "<p align=\"center\">";
echo "<b>Configura��es</b>";
echo "</p>";
echo "<p>";
$tmsg = getpmcount(getuid_sid($sid));
$umsg = getunreadpm(getuid_sid($sid));
echo "<a href=\"inbox.php?action=main&sid=$sid\">&#187;Torpedos($umsg/$tmsg)</a><br />";
$uid = getuid_sid($sid);
echo "<a href=\"index.php?action=myclub&sid=$sid\">&#187;Minhas comunidades</a><br />";
echo "<a href=\"index.php?action=perfil&sid=$sid&who=$uid\">&#187;Meu perfil</a><br />";
echo "<a href=\"index.php?action=uset&sid=$sid\">&#187;Editar perfil</a><br />";
echo "<a href=\"rename.php?sid=$sid\">&#187;Trocar meu nick</a><br />";
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_ignore WHERE name='".$uid."'"));
echo "<a href=\"lists.php?action=ignl&sid=$sid\">&#187;Lista negra($noi[0])</a><br />";
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_gbook WHERE gbowner='".$uid."'"));
echo "<a href=\"lists.php?action=gbook&sid=$sid&who=$uid\">&#187;Recados($noi[0])</a><br />";
echo "<a href=\"paginas.php?p=humor&sid=$sid\">&#187;Alterar humor</a><br />";
echo "<a href=\"lists.php?action=bbcode&sid=$sid\">&#187;BBCode</a><br />";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
///////////////////////////////////Control Panel
else if($action=="clmenu")
{
adicionar_online(getuid_sid($sid),"Vendo Comunidades","");
echo "<p align=\"center\">";
echo "<b>Menu Comunidades</b>";
echo "</p>";
echo "<p>";
$myid = getuid_sid($sid);
echo "<a href=\"index.php?action=clubs&sid=$sid\">&#187;Todas as comunidades</a><br />";
echo "<a href=\"index.php?action=myclub&sid=$sid\">&#187;Minhas comunidades</a><br />";
echo "<a href=\"lists.php?action=clm&who=$myid&sid=$sid&who=$uid\">&#187;Que sou membro</a><br />";
echo "<a href=\"lists.php?action=pclb&sid=$sid&who=$uid\">&#187;Mais populares</a><br />";
echo "<a href=\"lists.php?action=aclb&sid=$sid&who=$uid\">&#187;Com mais postagens</a><br />";
echo "<a href=\"lists.php?action=rclb&sid=$sid&who=$uid\">&#187;5 aleat�rias</a><br /><br />";
$ncl = mysql_fetch_array(mysql_query("SELECT id, name FROM fun_clubs ORDER BY created DESC LIMIT 1"));
echo "Nova comunidade: <a href=\"index.php?action=gocl&clid=$ncl[0]&sid=$sid\">".htmlspecialchars($ncl[1])."</a><br />";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
///////////////////////////////////My Clubs
else if($action=="myclub")
{
adicionar_online(getuid_sid($sid),"Minhas comunidade","");
echo "<p align=\"center\">";
echo "<b>Minhas Comunidades</b>";
echo "</p>";
echo "<p>";
$uid = getuid_sid($sid);
if(getplusses($uid)<349)
{
echo "<p align=\"left\">";
echo "Comunidades s�o grupos criados por usu�rios que tem o prop�sito de debater algum assunto interessante, e para criar a sua, voc� deve ter um total de 350 Pontos!";
echo "</p>";
}else
{
$uclubs = mysql_query("SELECT id, name FROM fun_clubs WHERE owner='".$uid."'");
while($club=mysql_fetch_array($uclubs))
{
echo "<a href=\"index.php?action=gocl&clid=$club[0]&sid=$sid\">$club[1]</a>";
echo ", <a href=\"genproc.php?action=dlcl&clid=$club[0]&sid=$sid\">[APAGAR]</a><br /><br />";
}
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE owner='".$uid."'"));
if($noi[0]<8)
{
echo "<a href=\"index.php?action=addcl&sid=$sid\">Nova comunidade!</a>";
}
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=clmenu&sid=$sid\">Comunidades</a><br /><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
///////////////////////////////////My Clubs
else if($action=="clubs")
{
adicionar_online(getuid_sid($sid),"Todas as comunidades","");
echo "<p align=\"center\">";
echo "<b>Todas as Comunidades</b>";
echo "</p>";
//////ALL LISTS SCRIPT <<
if($page=="" || $page<=0)$page=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs"));
$num_items = $noi[0]; //changable
$items_per_page= 5;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT id, name, owner, description, created, tipo FROM fun_clubs ORDER BY created DESC LIMIT $limit_start, $items_per_page";
echo "<p>";
$items = mysql_query($sql);
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
if($item[5]==0)
{
$tipo = "<img src=\"images/aberto.gif\" alt=\"\" />";
}
else
{
$tipo = "<img src=\"images/fexado.gif\" alt=\"\" />";
}
$item[1]=htmlspecialchars($item[1]);
$mems = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='".$item[0]."' AND accepted='1'"));
$lnk = "$tipo<a href=\"index.php?action=gocl&clid=$item[0]&sid=$sid\">$item[1](".$mems[0].")</a> Dono: <a href=\"index.php?action=perfil&who=$item[2]&sid=$sid\">".getnick_uid($item[2])."</a>";
echo "$lnk<br />";
echo htmlspecialchars($item[3])."<br />Data de cria��o: (".date("d/m/y", $item[4]).")<br /><br />";
}
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"index.php?action=$action&page=$ppage&sid=$sid&view=$view\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"index.php?action=$action&page=$npage&sid=$sid&view=$view\">Proxima&#187;</a>";
}
echo "<br />$page/$num_pages<br />";
if($num_pages>2)
{
$rets = "<form action=\"index.php\" method=\"get\">";
$rets .= "Pular para p�gina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=clmenu&sid=$sid\">Comunidades</a><br /><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
else if($action=="editcl")
{
adicionar_online(getuid_sid($sid),"Editando Comunidade","");
$clid = $_GET["clid"];
$clinfo = mysql_fetch_array(mysql_query("SELECT name, owner, description, rules, logo, plusses, created, subdono, tipo, id FROM fun_clubs WHERE id='".$clid."'"));
echo "<p align=\"center\">";
if(empty($clid)||!is_numeric($clid)||$clinfo[9]==0)//Verifica se a comunidade existe
{
echo "<img src=\"images/notok.gif\" alt=\"\">Essa comunidade n�o existe!";
echo "<br /><br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\">P�gina principal</a>";
echo "</p>";exit();
}
if(!ismod($uid))
{
echo "<img src=\"images/notok.gif\" alt=\"\">Voc� n�o tem permi��o para est� aqui!";
}
else
{
echo "<b>Editando Comunidade</b>";
echo "</p>";
echo "<form action=\"genproc.php?action=editcl&sid=$sid&clid=$clid\" method=\"post\" enctype=\"multipart/form-data\">";
echo "Nome: <input name=\"nome\" type=\"text\" value=\"$clinfo[0]\"><br />";
echo "Regras e Termos: <input name=\"regras\" type=\"text\" value=\"$clinfo[3]\"><br />";
echo "Descri��o: <br /><textarea name=\"descricao\">$clinfo[2]</textarea><br />";
echo "Tipo da Comunidade: <select name=\"tipo\">";
echo "<option value=\"0\">P�blica</option>";
echo "<option value=\"1\">Privada</option>";
echo "</select><br />";
echo "Subdono: <select name=\"subdono\">";
echo "<option value=\"0\">Padr�o</option>";
$usuarios = mysql_query("SELECT uid FROM fun_clubmembers WHERE clid = '".$clid."' AND accepted='1', owner!='".$uid."'");
while($users = mysql_fetch_array($usuarios))
{
echo "<option value=\"$users[0]\">".getnick_uid2($users[0])."</option>";
}
echo "</select><br />";
echo "Logo: <input name=\"logo\" type=\"file\"><br />";
echo "<input type=\"submit\" value=\"Atualizar\">";
echo "</form>";
if(isadmin($uid))
{
echo "<p align=\"center\">";
echo "<a href=\"genproc.php?action=dlcl&sid=$sid&clid=$clid\">APAGAR COMUNIDADE</a>";
echo "</p>";
}
echo "<p align=\"center\">";
echo "S� � permitido no logo imagens do tipo <b>JPG, JPEG, GIF E PNG</b>!";
echo "</p>";
}
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=clubs&sid=$sid\">";
echo "Comunidades</a><br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
else if($action=="gocl")
{
$clid = addslashes($_GET["clid"]);
$clinfo = mysql_fetch_array(mysql_query("SELECT name, owner, description, rules, logo, plusses, created, subdono, tipo, id FROM fun_clubs WHERE id='".$clid."'"));
adicionar_online(getuid_sid($sid),"Vendo uma Comunidade","");
$clnm = htmlspecialchars($clinfo[0]);
echo "<p align=\"center\">";
if(empty($clid)||!is_numeric($clid)||$clinfo[9]==0)//Verifica se a comunidade existe
{
echo "<img src=\"images/notok.gif\" alt=\"\">Essa comunidade n�o existe!";
echo "<br /><br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\">P�gina principal</a>";
echo "</p>";exit();
}
echo "<b>$clnm</b><br />";
if(trim($clinfo[4])==""||!file_exists($clinfo[4]))//Verificar se existe o logo na table ou se est� dir do site
{
echo "<img src=\"images/logo.png\" alt=\"logo\"/>";
}else
{
echo "<img src=\"$clinfo[4]\" alt=\"logo\" width=\"150x150\"/>";
}
echo "</p>";
echo "<p>";
echo "ID: <b>$clid</b><br />";
$uid = getuid_sid($sid);
$cango = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='".$clid."' AND uid='".$uid."' AND accepted='1'"));
echo "Dono: <a href=\"index.php?action=perfil&who=$clinfo[1]&sid=$sid\">".getnick_uid($clinfo[1])."</a><br />";
$mems = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='".$clid."' AND accepted='1'"));
echo "Total de Membros: <a href=\"lists.php?action=clmem&sid=$sid&clid=$clid\">$mems[0]</a><br />";
echo "Criada em: ".date("d/m/y", $clinfo[6])."<br />";
echo "Pontos: $clinfo[5]<br />";
$fid = mysql_fetch_array(mysql_query("SELECT id FROM fun_forums WHERE clubid='".$clid."'"));
$rid = mysql_fetch_array(mysql_query("SELECT id FROM fun_rooms WHERE clubid='".$clid."'"));
$tps = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE fid='".$fid[0]."'"));
$pss = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts a INNER JOIN fun_topics b ON a.tid = b.id WHERE b.fid='".$fid[0]."'"));
if(($cango[0]>0)||ismod($uid))
{
$noa = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_announcements WHERE clid='".$clid."'"));
echo "<br /><a href=\"lists.php?action=annc&sid=$sid&clid=$clid\"><img src=\"teks/cmt.gif\" alt=\"!\"/>An�ncios($noa[0])</a><br />";
$noa = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_chat WHERE rid='".$rid[0]."'"));
echo "<a href=\"chat.php?sid=$sid&rid=$rid[0]\"><img src=\"images/batepapo.gif\" alt=\"*\"/>Chat($noa[0])</a><br />";
echo "<a href=\"index.php?action=viewfrm&sid=$sid&fid=$fid[0]\"><img src=\"images/folder.gif\" alt=\"*\"/>F�rum($tps[0]/$pss[0])</a><br />";
if($clinfo[1]==$uid)
{
$mems = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='".$clid."' AND accepted='0'"));
echo "<a href=\"lists.php?action=clreq&sid=$sid&clid=$clid\">Pedidos pendentes($mems[0])</a><br />";
}
$ismem = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE clid='".$clid."' AND uid='".getuid_sid($sid)."'"));
if($ismem[0]>0)
{
//unjoin
if($clinfo[1]!=$uid)
{
echo "<a href=\"genproc.php?action=unjc&sid=$sid&clid=$clid\"><img src=\"teks/hit.gif\" alt=\"\">Sair da comunidade</a>";
}
}else
{
echo "<a href=\"genproc.php?action=reqjc&sid=$sid&clid=$clid\">Entrar agora!</a>";
}
if(ismod(getuid_sid($sid)))
{
echo "<br />";
echo "<a href=\"index.php?action=editcl&sid=$sid&clid=$clid\"><img src=\"images/cp.png\" alt=\"\">Editar comunidade</a>";
}
}else{
echo "T�picos: <b>$tps[0]</b>, Postagens: <b>$pss[0]</b><br />";
echo "<b>Descri��o: </b>";
echo htmlspecialchars($clinfo[2]);
echo "<br /><br />";
echo "<b>Regras e Termos: </b>";
echo htmlspecialchars($clinfo[3]);
echo "<br /><br />";
echo "Gostou dessa Comunidade? <a href=\"genproc.php?action=reqjc&sid=$sid&clid=$clid\">Participe!</a>";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=clubs&sid=$sid\">";
echo "Comunidades</a><br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
else if($action=="vipol")
{
adicionar_online(getuid_sid($sid),"VIPs online","");
//////ALL LISTS SCRIPT <<
if($page=="" || $page<=0)$page=1;
$timeout = 600;
$timeon = (time() - $timeadjust)  - $timeout;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE vip='1' AND lastact>'".$timeon."'"));
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if($page>$num_pages)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
if($limit_start<0)$limit_start=0;
//changable sql
$sql = "SELECT name, specialid, id FROM fun_users WHERE vip='1' AND lastact>'".$timeon."' LIMIT $limit_start, $items_per_page";
echo "<p><small>";
$items = mysql_query($sql);
echo mysql_error();
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"index.php?action=perfil&who=$item[2]&sid=$sid\">".getnick_uid($item[2])."</a>";
echo "$lnk<br />";
}
echo "</small></p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"index.php?action=$action&page=$ppage&sid=$sid\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"index.php?action=$action&page=$npage&sid=$sid\">Proximo&#187;</a>";
}
echo "<br />$page/$num_pages<br />";
if($num_pages>2)
{
echo getjumper($action, $sid,"index");
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<img src=\"images/home.gif\" alt=\"*\"/><a href=\"index.php?action=main&sid=$sid\">";
echo "P�gina principal</a>";
echo "</p>";
}
else if($action=="addcl")
{
adicionar_online(getuid_sid($sid),"Criando nova Comunidade","");
echo "<p align=\"center\">";
echo "<b>Nova Comunidade</b>";
echo "</p>";
echo "<p>";
if(getplusses($uid)>=350)
{
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE owner='".$uid."'"));
if($noi[0]<5)
{
echo "<img src=\"images/point.gif\" alt=\"*\"/>Todas as informa��es s�o necess�rias!<br />";
echo "<img src=\"images/point.gif\" alt=\"*\"/>Al�m de voc�, os moderadores e administradores s�o liberados para moderar sua comunidade!<br />";
echo "<img src=\"images/point.gif\" alt=\"*\"/>N�o � permitido comunidades iguais!<br />";
echo "<img src=\"images/point.gif\" alt=\"*\"/>Em caso de abuso sua comunidade ser� apagada!<br />";
echo "<form action=\"genproc.php?action=adicionarcl&sid=$sid\" method=\"post\" enctype=\"multipart/form-data\">";
echo "Nome: <input name=\"nome\" maxlength=\"30\"/><br />";
echo "Descri��o: <input name=\"descricao\" maxlength=\"200\"/><br />";
echo "Regras: <input name=\"regras\" maxlength=\"500\"/><br />";
echo "Tipo da comunidade: <select name=\"tipo\">";
echo "<option value=\"0\">P�blica</option>";
echo "<option value=\"1\">Privada</option>";
echo "</select><br />";
echo "Logo: <input type=\"file\" name=\"logo\" /><br />";
echo "<input type=\"submit\" value=\"Enviar\"/>";
echo "</form>";
echo "<p align=\"center\">";
echo "S� � permitido no logo imagens do tipo <b>JPG, JPEG, GIF E PNG</b>!";
echo "</p>";
}else
{
echo "Voc� atingiu o m�ximo de comunidades permitidas(5 comunidades)!";
}
}else{
echo "Para criar uma comunidade voc� deve ter mais de 350 pontos!";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
///////////////////////////////////Search
else if($action=="search")
{
adicionar_online(getuid_sid($sid),"Buscar","");
echo "<p align=\"center\">";
echo "<img src=\"images/search.gif\" alt=\"*\"/><br />";
echo "<b>Menu de busca</b>";
echo "</p>";
echo "<p>";
echo "<a href=\"search.php?action=tpc&sid=$sid\">&#0187;Em T�picos</a><br />";
echo "<a href=\"search.php?action=blg&sid=$sid\">&#0187;Em blogs</a><br />";
echo "<a href=\"search.php?action=nbx&sid=$sid\">&#0187;Em meus torpedos</a><br />";
echo "<a href=\"search.php?action=clb&sid=$sid\">&#0187;Em Comunidades</a><br /><br />";
echo "Buscar usu�rios<br />";
echo "<a href=\"search.php?action=mbrn&sid=$sid\">&#0187;Em nicks</a><br />";
//echo "<a href=\"search.php?action=mbrl&sid=$sid\">&#0187;In Location</a><br />";
//echo "<a href=\"search.php?action=mbrs&sid=$sid\">&#0187;By sex orientation</a><br />";
echo "Digite o nick do usu�rio abaixo para ver o perfil<br />";
echo "<form action=\"index.php?action=perfil&sid=$sid\" method=\"post\">";
echo "<br />Nick <input name=\"mnick\" maxlength=\"15\"/><br />";
echo "<input type=\"submit\" value=\"Ver perfil\"/>";
echo "</form>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
///////////////////////////////////Settings
else if($action=="uset")
{
adicionar_online(getuid_sid($sid),"Editando perfil","");
$uid = getuid_sid($sid);
$avat = getavatar($uid);
$email = mysql_fetch_array(mysql_query("SELECT email FROM fun_users WHERE id='".$uid."'"));
$bdy = mysql_fetch_array(mysql_query("SELECT birthday FROM fun_users WHERE id='".$uid."'"));
$uloc = mysql_fetch_array(mysql_query("SELECT location FROM fun_users WHERE id='".$uid."'"));
$sx = mysql_fetch_array(mysql_query("SELECT sex FROM fun_users WHERE id='".$uid."'"));
$uloc[0] = htmlspecialchars($uloc[0]);
echo "<p align=\"center\">";
echo "<b>Editar Perfil</b>";
echo "</p>";
echo "<p>";
echo "<form action=\"genproc.php?action=uprof&sid=$sid\" method=\"post\">";
echo "E-Mail: <input name=\"semail\" maxlength=\"100\" value=\"$email[0]\"/><br />";
echo "Aniversario EXEMPLO:1989-08-14 : <input name=\"ubday\" maxlength=\"50\" value=\"$bdy[0]\"/><br />";
echo "Localidade: <input name=\"uloc\" maxlength=\"50\" value=\"$uloc[0]\"/><br />";
echo "Sexo: <select name=\"usex\" value=\"$sx[0]\">";
echo "<option value=\"M\">Masculino</option>";
echo "<option value=\"F\">Feminino</option>";
echo "<option value=\"G\">GLS</option>";
echo "</select><br />";
echo "<input type=\"submit\" value=\"Editar\"/>";
echo "</form>";
echo "<br />";
$sml = mysql_fetch_array(mysql_query("SELECT hvia FROM fun_users WHERE id='".getuid_sid($sid)."'"));
if($sml[0]=="1")
{
echo "<a href=\"genproc.php?action=sml&a=nao&sid=$sid\">N�o quero ver smilies!</a>";
}else{
echo "<a href=\"genproc.php?action=sml&a=sim&sid=$sid\">Quero ver smilies!</a>";
}
echo "<br /><br />";
echo "<form action=\"genproc.php?action=upwd&sid=$sid\" method=\"post\">";
echo "Senha: <input type=\"password\" name=\"npwd\" format=\"*x\" maxlength=\"15\"/><br />";
echo "Repita a senha: <input type=\"password\" name=\"cpwd\" format=\"*x\" maxlength=\"15\"/><br />";
echo "<input type=\"submit\" value=\"Mudar\"/>";
echo "</form>";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
else if($action=="stats")
{
adicionar_online(getuid_sid($sid),"Estatisticas","");
echo "<p align=\"center\">";
$norm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users"));
echo "Usu�rios registrados: <b>$norm[0]</b><br />";
$memid = mysql_fetch_array(mysql_query("SELECT id, name  FROM fun_users ORDER BY regdate DESC LIMIT 0,1"));
echo "Novo usu�rio: <b><a href=\"index.php?action=perfil&who=$memid[0]&sid=$sid\">".getnick_uid($memid[0])."</a></b><br />";
$mols = mysql_fetch_array(mysql_query("SELECT name, value FROM fun_settings WHERE id='2'"));
echo "Maior n�mero online: <b>$mols[1]</b> em $mols[0]<br />";
echo "Maior n�mero online(<a href=\"lists.php?action=moto&sid=$sid\">De hoje</a>): <b>$mols[0]</b><br />";
$tm24 = time() - (24*60*60);
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE lastact>'".$tm24."'"));
echo mysql_error();
echo "Usu�rios online hoje <b>$aut[0]</b><br />";
$notc = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics"));
$nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts"));
echo "Topicos: <b>$notc[0]</b> - Postagens: <b>$nops[0]</b><br />";
$nopm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private"));
echo "Total de Torpedos: <b>$nopm[0]</b><br />";
$nopm = mysql_fetch_array(mysql_query("SELECT value FROM fun_settings WHERE name='Counter'"));
echo "Visitas: <b>$nopm[0]</b>";
echo "";
echo "</p>";
echo "<p>";
echo "";
/////menu das estatisticas
echo "<a href=\"index.php?action=l24&sid=$sid\">&#187;O que aconteceu nas �ltimas 24 horas?</a><br />";
echo "<a href=\"lists.php?action=members&sid=$sid\">&#187;Todos os usu�rios($norm[0])</a><br />";
$norm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE sex='M'"));
echo "<a href=\"lists.php?action=males&sid=$sid\">&#187;Homens($norm[0])</a><br />";
$norm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE sex='F'"));
echo "<a href=\"lists.php?action=fems&sid=$sid\">&#187;Mulheres($norm[0])</a><br />";
$tbday=mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users where month(`birthday`) = month(curdate()) and dayofmonth(`birthday`) = dayofmonth(curdate());"));
echo "<a href=\"lists.php?action=bdy&sid=$sid\">&#187;Aniversariantes($tbday[0])</a><br />";
echo "<a href=\"lists.php?action=topp&sid=$sid\">&#187;Top Postadores</a><br />";
echo "<a href=\"lists.php?action=tchat&sid=$sid\">&#187;Top Chatistas</a><br />";
echo "<a href=\"lists.php?action=longon&sid=$sid\">&#187;Mais Tempo Online</a><br />";
echo "<a href=\"lists.php?action=tshout&sid=$sid\">&#187;Top Muralistas</a><br />";
$nobr=mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT browserm) FROM fun_users WHERE browserm IS NOT NULL "));
echo "<a href=\"lists.php?action=brows&sid=$sid\">&#187;Navegadores($nobr[0])</a><br />";
$noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_users WHERE perm>'0'"));
echo "<a href=\"lists.php?action=staff&sid=$sid\">&#187;Membros da equipe($noi[0])</a><br />";
$noi = mysql_fetch_array(mysql_query("SELECT count(*) FROM fun_ban WHERE tipoban='1' OR tipoban='2'"));
echo "<a href=\"lists.php?action=banned&sid=$sid\">&#187;Banidos($noi[0])</a><br />";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
else if($action=="l24")
{
adicionar_online(getuid_sid($sid),"Estatisticas","");
echo "<p>";
echo "";
/////
echo "O que aconteceu no site nas ultimas 24 horas<br /><br />";
$tm24 = time() - (24*60*60);
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE lastact>'".$tm24."'"));
echo "Usuarios ativos: <b>$aut[0]</b><br />";
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE regdate>'".$tm24."'"));
echo "Registrados: <b>$aut[0]</b><br />";
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE joined>'".$tm24."' AND accepted='1'"));
echo "Entraram em comunidades: <b>$aut[0]</b><br />";
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE created>'".$tm24."'"));
echo "Comunidades criadas: <b>$aut[0]</b><br />";
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_buddies WHERE reqdt>'".$tm24."' AND agreed='1'"));
echo "Amigos adicionados: <b>$aut[0]</b><br />";
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_gbook WHERE dtime>'".$tm24."'"));
echo "Recados enviados: <b>$aut[0]</b><br />";
if(ismod(getuid_sid($sid)))
{
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_log WHERE data>'".$tm24."'"));
echo "A��es da equipe: <b>$aut[0]</b><br />";
}
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE dtpost>'".$tm24."'"));
echo "Postagens: <b>$aut[0]</b><br />";
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE timesent>'".$tm24."'"));
echo "Torpedos enviados: <b>$aut[0]</b><br />";
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shouts WHERE shtime>'".$tm24."'"));
echo "Recados no mural: <b>$aut[0]</b><br />";
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE crdate>'".$tm24."'"));
echo "Topicos Criados: <b>$aut[0]</b><br />";
$aut = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_downloads WHERE data>'".$tm24."'"));
echo "Downloads adicionados: <b>$aut[0]</b><br />";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=stats&sid=$sid\"><img src=\"images/stat.gif\" alt=\"*\"/>";
echo "Estatisticas</a><br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
else if($action=="shout")
{
adicionar_online(getuid_sid($sid),"Escrevendo recado no mural","");
echo "<p align=\"left\">";
if(getplusses(getuid_sid($sid))<20)
{
echo "<center>Voc� deve ter no m�nimo 21 $smoeda para deixar um recado no mural!</center>";
}
else
{
$nick = getnick_uid(getuid_sid($sid));
echo "Ol� $nick, antes de postar no mural leia as informa��es abaixo: <br />";
echo "<img src=\"images/point.gif\" alt=\"\"> Se postar propagandas de outros sites(spam) voc� vai ser banido!<br />";
echo "<img src=\"images/point.gif\" alt=\"\"> N�o � permitido postar apenas smilies no mural de recados!<br />";
echo "<img src=\"images/point.gif\" alt=\"\"> Dependendo do conte�do interpretado pela equipe, seu post estar� sujeita a edi��o!<br />";
echo "<img src=\"images/point.gif\" alt=\"\"> N�o � permitido brigas no mural!<br />";
echo "<img src=\"images/point.gif\" alt=\"\"> Voc� pode enviar at� 3 smilies!<br />";
echo "<form action=\"genproc.php?action=shout&sid=$sid\" method=\"post\">";
echo "Texto: <input name=\"shtxt\" maxlength=\"200\"/><br />";
echo "Cor: <select name=\"cor\">";
echo "<option value=\"#000000\">Padr�o</option>";
echo "<option value=\"#ff0000\">Vermelho</option>";
echo "<option value=\"#00ff00\">Lim�o</option>";
echo "<option value=\"#ff00ff\">Pink</option>";
echo "<option value=\"#006600\">Verde</option>";
echo "<option value=\"#33ffff\">Aqua</option>";
echo "<option value=\"#ff9900\">Laranja</option>";
echo "<option value=\"#0000ff\">Azul Marinho</option>";
echo "<option value=\"#393939\">Cinza</option>";
echo "<option value=\"#ececec\">Prata</option>";
echo "<option value=\"#660000\">Chocolate</option>";
echo "<option value=\"#6600cc\">Lilas</option>";
echo "<option value=\"#666600\">Dourado</option>";
echo "</select><br />";
echo "<input type=\"submit\" value=\"Enviar Recado\"/>";
echo "</form>";
}
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
else if($action=="shout2")
{
adicionar_online(getuid_sid($sid),"Escrevendo recado no mural","");
echo "<p align=\"center\">";
echo "Mural da equipe<br /><br /><b>limite de smileys: 4</b><br />";
echo "<form action=\"genproc.php?action=shout2&sid=$sid\" method=\"post\">";
echo "<b>Texto:</b><input name=\"shtxt\" maxlength=\"300\"/><br />";
echo "<b>cor do texto:</b><br /><select name=\"cor\">";
echo "<option value=\"\">sem cor</option>";
echo "<option value=\"red\">vermelho</option>";
echo "<option value=\"#00868b\">turquoise</option>";
echo "<option value=\"#00ff00\">green</option>";
echo "<option value=\"#551a8b\">purple</option>";
echo "<option value=\"#330066\">roxo1</option>";
echo "<option value=\"#660066\">roxo2</option>";
echo "<option value=\"#990066\">borbon</option>";
echo "<option value=\"#cc0066\">rosa</option>";
echo "<option value=\"#ff3300\">laranja</option>";
echo "</select><br />";
echo "<input type=\"submit\" value=\"Gravar recado\"/>";
echo "</form>";
echo "<br /><br /><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
//////////////////////////////////////////shout
else if($action=="annc")
{
adicionar_online(getuid_sid($sid),"Adicionando um aviso","");
$clid = $_GET["clid"];
echo "<p align=\"center\">";
$cow = mysql_fetch_array(mysql_query("SELECT owner FROM fun_clubs WHERE id='".$clid."'"));
$uid = getuid_sid($sid);
if($cow[0]!=$uid)
{
echo "<img src=\"images/notok.gif\" alt=\"\">Essa comunidade n�o � sua!";
echo "<br /><br />";
}else
{
echo "<b>Adicionar Aviso</b><br />";
echo "<form action=\"genproc.php?action=annc&sid=$sid&clid=$clid\" method=\"post\">";
echo "Texto: <input name=\"antx\" maxlength=\"200\"/><br />";
echo "<input type=\"submit\" value=\"Adicionar\"/>";
echo "</form>";
echo "<p align=\"center\">";
echo "Est� liberado apenas o uso de <b>BBCodes</b>, todos os smilies desse an�ncio n�o ser�o adicionados!";
echo "</p>";
}
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
//////////////////////////////////////////escrevendo recado para usuario
else if($action=="signgb")
{
$who = addslashes($_GET["who"]);
adicionar_online(getuid_sid($sid),"Escrevendo recado","");
if(!cansigngb(getuid_sid($sid), $who))
{
echo "<p align=\"center\">";
echo "<img src=\"images/notok.gif\" alt=\"\">Voc� n�o tem permi��o para enviar recados para esse usu�rio!<br /><br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\">P�gina principal</a>";
echo "</p>";
exit();
}
echo "<p align=\"center\">";
echo "<b>Novo Recado</b><br />";
echo "<br />";
echo "Est� liberado o uso apenas de <b>bbcode</b>, todo e qualquer smilie n�o ser� visualizado nos recados!";
echo "</p>";
echo "<form action=\"genproc.php?action=signgb&sid=$sid\" method=\"post\">";
echo "Texto: <input name=\"msgtxt\" maxlength=\"500\"/><br />";
echo "Cor: <select name=\"cor\">";
echo "<option value=\"#000000\">Padr�o</option>";
echo "<option value=\"#ff0000\">Vermelho</option>";
echo "<option value=\"#00ff00\">Lim�o</option>";
echo "<option value=\"#ff00ff\">Pink</option>";
echo "<option value=\"#006600\">Verde</option>";
echo "<option value=\"#33ffff\">Aqua</option>";
echo "<option value=\"#ff9900\">Laranja</option>";
echo "<option value=\"#0000ff\">Azul Marinho</option>";
echo "<option value=\"#393939\">Cinza</option>";
echo "<option value=\"#ececec\">Prata</option>";
echo "<option value=\"#660000\">Chocolate</option>";
echo "<option value=\"#6600cc\">Lilas</option>";
echo "<option value=\"#666600\">Dourado</option>";
echo "</select><br />";
echo "<input type=\"hidden\" name=\"who\" value=\"$who\"/>";
echo "<input type=\"submit\" value=\"Enviar\"/>";
echo "</form>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
else if($action=="online")
{
adicionar_online(getuid_sid($sid),"Usu�rios online","");
//////ALL LISTS SCRIPT <<
if($page=="" || $page<=0)$page=1;
$timeout = 600;
$num_items = getnumonline(); //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if($page>$num_pages)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT
a.name, b.place, b.userid, sex FROM fun_users a
INNER JOIN fun_online b ON a.id = b.userid
GROUP BY 1,2
LIMIT $limit_start, $items_per_page
";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
while ($item = mysql_fetch_array($items))
{
if ($item[3]=="M"){
$icon = "";
}else
if ($item[3]=="F"){
$icon = "";
}
$lnk = "<a href=\"index.php?action=perfil&who=$item[2]&sid=$sid\">".getnick_uid($item[2])."</a>";
echo "$icon $lnk - $item[1] <br />";
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"index.php?action=online&page=$ppage&sid=$sid\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"index.php?action=online&page=$npage&sid=$sid\">Proximo&#187;</a>";
}
echo "<br />$page/$num_pages<br />";
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
else if($action=="stfol")
{
adicionar_online(getuid_sid($sid),"Equipe online","");
//////ALL LISTS SCRIPT <<
if($page=="" || $page<=0)$page=1;
$timeout = 600;
$timeon = time()-$timeout;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users WHERE perm>'0' AND lastact>'".$timeon."'"));
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if($page>$num_pages)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
if($limit_start<0)$limit_start=0;
//changable sql
$sql = "
SELECT name, perm, id FROM fun_users WHERE perm>'0' AND lastact>'".$timeon."'
LIMIT $limit_start, $items_per_page
";
echo "<p>";
$items = mysql_query($sql);
echo mysql_error();
while ($item = mysql_fetch_array($items))
{
$lnk = "<a href=\"index.php?action=perfil&who=$item[2]&sid=$sid\">".getnick_uid($item[2])."</a>";
if($item[1]==1)
{
$item[1] = "Mod";
}else if($item[1]==2)
{
$item[1] = "Admin";
}
echo "$lnk - $item[1] <br />";
}
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"index.php?action=$action&page=$ppage&sid=$sid\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"index.php?action=$action&page=$npage&sid=$sid\">Proxima&#187;</a>";
}
echo "<br />$page/$num_pages<br />";
if($num_pages>2)
{
echo getjumper($action, $sid,"index");
}
echo "</p>";
////// UNTILL HERE >>
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
else if($action=="chbmsg")
{
adicionar_online(getuid_sid($sid),"Frase de amizade","");
$cmsg = htmlspecialchars(getbudmsg(getuid_sid($sid)));
echo "<p align=\"center\">";
echo "<b>Frase de Amizade</b>";
echo "</p>";
echo "<form action=\"genproc.php?action=upbmsg&sid=$sid\" method=\"post\">";
echo "Texto:<input name=\"bmsg\" maxlength=\"100\" value=\"$cmsg\"/><br />";
echo "<input type=\"submit\" value=\"Editar\"/>";
echo "</form><br />";
echo "<a href=\"lists.php?action=buds&sid=$sid\">";
echo "<img src=\"images/pen.png\" alt=\"*\">Meus Amigos</a><br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
/////////////////////////////////perfil profile
else if($action=="perfil")
{
$perfil = getnick_uid2($who);
adicionar_online(getuid_sid($sid),"Vendo Perfil de $perfil","");
echo "<p align=\"center\">";
if($who==""||$who==0)
{
$mnick = $_POST["mnick"];//la do sis de busca
$who = getuid_nick($mnick);
}
$whonick = getnick_uid($who);
if($whonick!="")
{
$vip = mysql_fetch_array(mysql_query("SELECT vip FROM fun_users WHERE id='".$who."'"));
if(ismod($who) OR $vip[0]=="1")
{
if($who=="1")
{
$cor = "#000";
}
else if(isadmin($who))
{
$cor = "red";
}
else if(ismod($who))
{
$cor = "green";
}
else{
$cor = "#ff4500";
}
echo "<b style=\"color: $cor\">".getstatus($who)."</b><br />";
}
else{
}
$avlink = getavatar($who);
if ($avlink=="")
{
$sex = mysql_fetch_array(mysql_query("SELECT sex FROM fun_users WHERE id='".$who."'"));
if($sex[0]=='M')
{
echo "<br /><img src=\"teks/nophotoboy.gif\" alt=\"avatar\"/><br />";
}else if($sex[0]=='F')
{
echo "<br /><img src=\"teks/nophotogirl.gif\" alt=\"avatar\"/><br />";
}else
{
echo "<br /><img src=\"images/nopic.jpg\" alt=\"avatar\"/><br />";
}
}else
{
echo "<br /><img src=\"$avlink\" width=\"150x150\" alt=\"avatar\"/>";
}
$humor = mysql_fetch_array(mysql_query("SELECT humor FROM fun_users WHERE id='".$who."'"));
if(!empty($humor[0]))
{
echo "<br />";
echo "<img src=\"images/humor/$humor[0].gif\" alt=\"humor\">";
echo "<br />";
echo "Humor: <b>$humor[0]</b>";
}
echo "</p>";
echo "<p>";
echo "<left>ID: <b>$who</b></left><br />";
echo "Nick: $whonick<br />";
$nopl = mysql_fetch_array(mysql_query("SELECT sex, birthday, location FROM fun_users WHERE id='".$who."'"));
$uage = getage($nopl[1]);
if($nopl[0]=='M')
{
$usex = "Masculino";
}else if($nopl[0]=='F')
{
$usex = "Feminino";
}else if($nopl[0]=='G'){
$usex = "GLS";
}else
{
$usex = "N�o indentificado!";
}
$nopl[2] = htmlspecialchars($nopl[2]);
echo "<left>Idade: <b>$uage</b></left><br />";
echo "Sexo: <b>$usex</b><br />";
$nopl = mysql_fetch_array(mysql_query("SELECT plusses, vip FROM fun_users WHERE id='".$who."'"));
echo "$smoeda: <b>$nopl[0]</b><br />";
if(ismod($who) OR $nopl[1]=="1")
{
}
else{
echo "Status: <b>".getstatus($who)."</b><br />";
}
if($uid==$who)
{
}
else{
mysql_query("INSERT INTO visitantes SET vid='".$uid."', uid='".$who."', hora='".time()."'");
}
$nv = mysql_fetch_array(mysql_query("SELECT visitas FROM fun_users WHERE id='".$who."'"));
$soma = $nv[0] + 1;
mysql_query("UPDATE fun_users SET visitas='".$soma."' WHERE id='".$who."'");
$visitas = mysql_fetch_array(mysql_query("SELECT visitas FROM fun_users WHERE id='".$who."'"));
echo "Visitas: <a href=\"visitas.php?who=$who&sid=$sid\">$visitas[0]</a><br />";
$tempo = mysql_fetch_array(mysql_query("SELECT tottimeonl FROM fun_users WHERE id='".$who."'"));
$tempo = floor($tempo[0]/60);
echo "Tempo online: <b>$tempo minutos</b><br />";
$xd = mysql_fetch_array(mysql_query("SELECT sex, birthday, location, specialid FROM fun_users WHERE id='".$who."'"));
$uage = getage($nopl[1]);
$nopll = mysql_fetch_array(mysql_query("SELECT location FROM fun_users WHERE id='".$who."'"));
echo "Localidade: <b>$nopll[0]</b><br />";
$rperm = mysql_fetch_array(mysql_query("SELECT rperm, ruser FROM fun_users WHERE id='".$uid."'"));
if($rperm[0]==0||$rperm[0]=="")
{
echo "Relacionamento: <b>Solteiro(a)</b><br />";
}
else 
{
echo "Relacionamento: Namorando com ".getnick_uid($rperm[1])."!<br />";
}
$pontos = mysql_fetch_array(mysql_query("SELECT lastplreas FROM fun_users WHERE id='".$who."'"));
echo "�ltima raz�o de pontos: <b>$pontos[0]</b><br />";
///liks profile
echo "<br /><a href=\"index.php?action=viewusrmore&sid=$sid&who=$who\">&#187;Mais informa��es</a><br />";
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_albums WHERE uid='".$who."'"));
if($noi[0]!=0)
{
$id = mysql_fetch_array(mysql_query("SELECT max(id) FROM fun_albums WHERE uid='".$who."'"));
echo "<a href=\"album.php?a=ver&id=$id[0]&sid=$sid\">&#187;Meu �lbum</a><br />";
}
$acoes = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_acoes WHERE who='".$who."'"));
echo "<a href=\"acoes.php?a=a&who=$who&sid=$sid\">&#187;A��es(".$acoes[0].")</a><br />";
echo "<a href=\"inbox.php?action=sendpm&who=$who&sid=$sid\">&#187;Enviar Torpedo</a><br />";
$pre = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM presentes WHERE uid='".$who."'"));
echo "<a href=\"loja.php?a=presentes&who=$who&sid=$sid\">&#187;Meus presentes($pre[0])</a><br />";
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_fas WHERE vid='".$who."'"));
echo "<a href=\"fas.php?a=ver&id=$who&sid=$sid\">&#187;Meus f�s($noi[0])</a><br />";
$uid = getuid_sid($sid);
if(budres($uid, $who)==0)
{
echo "<a href=\"genproc.php?action=bud&who=$who&sid=$sid&todo=add\">&#187;Adicionar Amigo</a><br />";
}else if(budres($uid, $who)==1)
{
echo "Pedido em espera<br />";
}
else if(budres($uid, $who)==2)
{
echo "<a href=\"genproc.php?action=bud&who=$who&sid=$sid&todo=del\">&#187;Tirar da lista de amigos</a><br />";
}
$ires = ignoreres($uid, $who);
if($ires==2)
{
echo "<a href=\"genproc.php?action=ign&who=$who&sid=$sid&todo=del\">&#187;Remover da Lista Negra</a><br />";
}else if($ires==1)
{
echo "<a href=\"genproc.php?action=ign&who=$who&sid=$sid&todo=add\">&#187;Adicionar a Lista negra</a><br />";
}
if(ismod(getuid_sid($sid)))
{
echo "<a href=\"modcp.php?action=user&who=$who&sid=$sid&who=$who\">&#187;Moderar usu�rio</a><br />";
}
if(isadmin(getuid_sid($sid)))
{
$vip = mysql_fetch_array(mysql_query("SELECT vip FROM fun_users WHERE id='".$who."'"));
if($vip[0]==1)
{
echo "<a href=\"modcp.php?action=addvip&a=r&who=$who&sid=$sid&ddd=not&who=$who\">&#187;Retirar VIP</a><br />";
}
else 
{
echo "<a href=\"modcp.php?action=addvip&a=a&who=$who&sid=$sid&ddd=add&who=$who\">&#187;Adicionar VIP</a><br />";
}
}
}else{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Usu�rio n�o existe!<br />";
}
echo "<br /><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>P�gina principal</a>";
echo "</p>";
}
///////////////////////////////////mais info perfil
else if($action=="viewusrmore")
{
$perfil = getnick_uid2($who);
adicionar_online(getuid_sid($sid),"Informa��es de $perfil","");
echo "<p align=\"center\">";
if($who==""||$who==0)
{
$mnick = $_POST["mnick"];
$who = getuid_nick($mnick);
}
$whonick = getnick_uid($who);
if($whonick!="")
{
echo "</p>";
echo "<p>";
//topicos criados
$unol = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE authorid='".$who."'"));
$tlink = "<a href=\"lists.php?action=tbuid&sid=$sid&who=$who\">$unol[0]</a>";
echo "Topicos: <b>$tlink</b><br />";
//postagens feitas
$unop = mysql_fetch_array(mysql_query("SELECT posts FROM fun_users WHERE id='".$who."'"));
$unol = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE uid='".$who."'"));
$plink = "<a href=\"lists.php?action=uposts&sid=$sid&who=$who\">$unol[0]</a>";
echo "Postagens: <b>$plink/$unop[0]</b><br />";
//torpedos recebidos
$noin = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE touid='".$who."'"));
$nout = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE byuid='".$who."'"));
echo "Torpedos Recebidos: <b>$noin[0]</b> - Enviados: <b>$nout[0]</b><br />";
//posts no chat
$nopl = mysql_fetch_array(mysql_query("SELECT chmsgs FROM fun_users WHERE id='".$who."'"));
echo "Postagens no chat: <b>$nopl[0]</b><br />";
//pontos no banco
$nopl = mysql_fetch_array(mysql_query("SELECT banco FROM fun_users WHERE id='".$who."'"));
echo "Pontos no banco: <b>$nopl[0]</b><br />";
//recados
$nout = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_shouts WHERE shouter='".$who."'"));
$nopl = mysql_fetch_array(mysql_query("SELECT shouts FROM fun_users WHERE id='".$who."'"));
echo "Recados no mural: <b><a href=\"lists.php?action=shouts&sid=$sid&who=$who\">$nout[0]</a>/$nopl[0]</b><br />";
//regdate cadastrou
$nopl = mysql_fetch_array(mysql_query("SELECT regdate FROM fun_users WHERE id='".$who."'"));
$jdt = date("d m y-H:i:s",$nopl[0]);
echo "Cadastrou-se em: <b>$jdt</b><br />";
//ultima vez online
$nopl = mysql_fetch_array(mysql_query("SELECT lastact FROM fun_users WHERE id='".$who."'"));
$jdt = date("d m y-H:i:s",$nopl[0]);
echo "�ltima vez ativo: <b>$jdt</b><br />";
//ultima visita ao site
$nopl = mysql_fetch_array(mysql_query("SELECT lastvst FROM fun_users WHERE id='".$who."'"));
$jdt = date("d m y-H:i:s",$nopl[0]);
echo "�ltima visita: <b>$jdt</b><br />";
//navegador
$nopl = mysql_fetch_array(mysql_query("SELECT browserm FROM fun_users WHERE id='".$who."'"));
echo "Navegador: <b>$nopl[0]</b><br />";
//email de contato
$nopl = mysql_fetch_array(mysql_query("SELECT email FROM fun_users WHERE id='".$who."'"));
echo "E-mail: <b>$nopl[0]</b><br />";
//ip
if(ismod(getuid_sid($sid)))
{
$uipadd = mysql_fetch_array(mysql_query("SELECT ipadd FROM fun_users WHERE id='".$who."'"));
echo "IP de ".getnick_uid($who).": <a href=\"lists.php?action=byip&sid=$sid&who=$who\">$uipadd[0]</a><br />";
}
//amigos
$nob = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_buddies WHERE (uid='".$who."' OR tid='".$who."') AND agreed='1'"));
echo "Amigos: <a href=\"paginas.php?p=amigos&who=$who&sid=$sid\">$nob[0]</a><br />";
echo "</p>";
echo "<p align=\"center\">";
//comunidades
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubs WHERE owner='".$who."'"));
if($noi[0]>0)
{
echo "<a href=\"lists.php?action=ucl&who=$who&sid=$sid\">Comunidades($noi[0])</a><br />";
}
//comunidades onde e membro
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_clubmembers WHERE uid='".$who."'"));
if($noi[0]>0)
{
echo "<a href=\"lists.php?action=clm&who=$who&sid=$sid\">Membro em $noi[0] comunidades</a><br />";
}
//recados
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_gbook WHERE gbowner='".$who."'"));
echo "<a href=\"lists.php?action=gbook&who=$who&sid=$sid\">Recados($noi[0])</a><br />";
}else
{
echo "<img src=\"images/notok.gif\" alt=\"X\"/>Usu�rio n�o existe!<br />";
}
echo "<br /><a href=\"index.php?action=perfil&sid=$sid&who=$who\">Voltar</a>";
echo "<br /><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
else if ($action=="chat")
{
adicionar_online(getuid_sid($sid),"Entrando no chat","");
echo "<p align=\"center\">";
echo "<b>Chat $snome</b><br />";
echo "<br />";
$unreadinbox=mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE unread='1' AND touid='".getuid_sid($sid)."'"));
$pmtotl=mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_private WHERE touid='".getuid_sid($sid)."'"));
$unrd="(".$unreadinbox[0]."/".$pmtotl[0].")";
echo "<a href=\"inbox.php?action=main&sid=$sid&page=1\">Torpedos$unrd</a><br /><br /> ";
$rooms = mysql_query("SELECT id, name, perms, mage, chposts FROM fun_rooms WHERE static='1' AND clubid='0'");
while ($room= mysql_fetch_array($rooms))
{
if(canenter($room[0], $sid))
{
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_chonline WHERE rid='".$room[0]."'"));
echo "<a href=\"chat.php?sid=$sid&rid=$room[0]\">$room[1]($noi[0])</a><br />";
}
}
echo "<br /><a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>P�gina principal</a><br />";
echo "</p>";
}
else if ($action=="funm")
{
adicionar_online(getuid_sid($sid),"Divers�o","");
echo "<p align=\"center\"><b>Divers�o</b><br />";
echo "</p>";
echo "<p>";
echo "<a href=\"cassino.php?sid=$sid\">&#187;Cassino</a><br />";
echo "<a href=\"cds.php?sid=$sid\">&#187;Cores da sorte</a><br />";
echo "<a href=\"virtual_pet.php?action=main&sid=$sid\">&#187;Virtual pet</a><br />";
echo "<a href=\"jockey.php?sid=$sid\">&#187;Jockey club</a><br />";
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>P�gina principal</a>";
echo "</p>";
}
else if($action=="viewcat")
{
$cid = $_GET["cid"];
adicionar_online(getuid_sid($sid),"Vendo categoria do f�rum","");
$cinfo = mysql_fetch_array(mysql_query("SELECT name from fun_fcats WHERE id='".$cid."'"));
echo "<p align=\"center\">";
echo getshoutbox($sid);
echo "</p>";
echo "<p>";
$forums = mysql_query("SELECT id, name FROM fun_forums WHERE cid='".$cid."' AND clubid='0' ORDER BY position, id, name");
echo "";
while($forum = mysql_fetch_array($forums))
{
if(canaccess(getuid_sid($sid), $forum[0]))
{
$notp = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE fid='".$forum[0]."'"));
$nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts a INNER JOIN fun_topics b ON a.tid = b.id WHERE b.fid='".$forum[0]."'"));
$iml = "<img src=\"images/1.gif\" alt=\"*\"/>";
echo "<a href=\"index.php?action=viewfrm&sid=$sid&fid=$forum[0]\">$iml$forum[1]($notp[0]/$nops[0])</a><br />";
$lpt = mysql_fetch_array(mysql_query("SELECT id, name FROM fun_topics WHERE fid='".$forum[0]."' ORDER BY lastpost DESC LIMIT 0,1"));
$nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE tid='".$lpt[0]."'"));
if($nops[0]==0)
{
$pinfo = mysql_fetch_array(mysql_query("SELECT authorid FROM fun_topics WHERE id='".$lpt[0]."'"));
$tluid = $pinfo[0];
}else{
$pinfo = mysql_fetch_array(mysql_query("SELECT  uid  FROM fun_posts WHERE tid='".$lpt[0]."' ORDER BY dtpost DESC LIMIT 0, 1"));
$tluid = $pinfo[0];
}
$tlnm = htmlspecialchars($lpt[1]);
$tlnick = getnick_uid($tluid);
$tpclnk = "<a href=\"index.php?action=viewtpc&sid=$sid&tid=$lpt[0]&go=last\">$tlnm</a>";
$vulnk = "<a href=\"index.php?action=perfil&sid=$sid&who=$tluid\">$tlnick</a>";
echo "�ltima postagem: $tpclnk, Por: $vulnk<br /><br />";
}
}
echo "";
echo "</p>";
echo "<p align=\"center\">";
$tmsg = getpmcount(getuid_sid($sid));
$umsg = getunreadpm(getuid_sid($sid));
if($umsg>0)
{
echo "<a href=\"inbox.php?action=main&sid=$sid\">Torpedos($umsg/$tmsg)</a><br />";
}
echo "<a href=\"index.php?action=forum&sid=$sid\"><img src=\"teks/folder.gif\" alt=\"*\"/>F�rum</a><br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
//////////////////////////////////View Topicc
else if($action=="viewtpc")
{
adicionar_online(getuid_sid($sid),"Vendo t�pico do f�rum","");
$tid = $_GET["tid"];
$go = $_GET["go"];
$tfid = mysql_fetch_array(mysql_query("SELECT fid FROM fun_topics WHERE id='".$tid."'"));
if(!canaccess(getuid_sid($sid), $tfid[0]))
{
echo "<p align=\"center\">";
echo "Voc� n�o tem permi��o para ver esse t�pico!<br /><br />";
echo "<a href=\"index.php?action=main&sid=$sid\">P�gina principal</a>";
echo "</p>";
exit();
}
$tinfo = mysql_fetch_array(mysql_query("SELECT name, text, authorid, crdate, views, fid, pollid from fun_topics WHERE id='".$tid."'"));
$tnm = htmlspecialchars($tinfo[0]);
echo "<p align=\"center\">";
$num_pages = getnumpages($tid);
if($page==""||$page<1)$page=1;
if($go!="")$page=getpage_go($go,$tid);
$posts_per_page = 5;
if($page>$num_pages)$page=$num_pages;
$limit_start = $posts_per_page *($page-1);
echo "<a href=\"index.php?action=post&sid=$sid&tid=$tid\">Postar resposta</a>";
$lastlink = "<a href=\"index.php?action=$action&tid=$tid&sid=$sid&go=last\">Ultima pagina</a>";
$firstlink = "<a href=\"index.php?action=$action&tid=$tid&sid=$sid&page=1\">Primeira pagina</a> ";
$golink = "";
if($page>1)
{
$golink = $firstlink;
}
if($page<$num_pages)
{
$golink .= $lastlink;
}
if($golink !="")
{
echo "<br />$golink";
}
echo "</p>";
echo "<p>";
$vws = $tinfo[4]+1;
$rpls = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE tid='".$tid."'"));
echo "Postagens: $rpls[0] - Visitas: $vws<br />";
///fm here
if($page==1)
{
$posts_per_page=4;
mysql_query("UPDATE fun_topics SET views='".$vws."' WHERE  id='".$tid."'");
$ttext = mysql_fetch_array(mysql_query("SELECT authorid, text, crdate, pollid FROM fun_topics WHERE id='".$tid."'"));
$unick = getnick_uid($ttext[0]);
if(isonline($ttext[0]))
{
$iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
}else{
$iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
}
$usl = "<br /><a href=\"index.php?action=perfil&sid=$sid&who=$ttext[0]\">$iml$unick</a>";
$topt = "<a href=\"index.php?action=tpcopt&sid=$sid&tid=$tid\">*</a>";
if($go==$tid)
{
$fli = "<img src=\"images/flag.gif\" alt=\"!\"/>";
}else{
$fli ="";
}
$pst = scan_msg_other($ttext[1],$sid);
echo "$usl: $fli$pst $topt<br />";
$dtot = date("d-m-y - H:i:s",$ttext[2]);
echo $dtot;
echo "<br />";
}
if($page>1)
{
$limit_start--;
}
$sql = "SELECT id, text, uid, dtpost, quote FROM fun_posts WHERE tid='".$tid."' ORDER BY dtpost LIMIT $limit_start, $posts_per_page";
$posts = mysql_query($sql);
while($post = mysql_fetch_array($posts))
{
$unick = getnick_uid($post[2]);
if(isonline($post[2]))
{
$iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
}else{
$iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
}
$usl = "<br /><a href=\"index.php?action=perfil&sid=$sid&who=$post[2]\">$iml$unick</a>";
$pst = scan_msg_other($post[1], $sid);
$topt = "<a href=\"index.php?action=pstopt&sid=$sid&pid=$post[0]&page=$page&fid=$tinfo[5]\">*</a>";
if($go==$post[0])
{
$fli = "<img src=\"images/flag.gif\" alt=\"!\"/>";
}else{
$fli ="";
}
echo "$usl: $fli$pst $topt<br />";
$dtot = date("d-m-y - H:i:s",$post[3]);
echo $dtot;
echo "<br />";
}
///to here
echo "</p>";
echo "<p align=\"center\">";
$tmsg = getpmcount(getuid_sid($sid));
$umsg = getunreadpm(getuid_sid($sid));
if($umsg>0)
{
echo "<a href=\"inbox.php?action=main&sid=$sid\">Torpedos($umsg/$tmsg)</a><br />";
}
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"index.php?action=viewtpc&page=$ppage&sid=$sid&tid=$tid\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"index.php?action=viewtpc&page=$npage&sid=$sid&tid=$tid\">Proxima&#187;</a>";
}
echo "<br />$page/$num_pages<br />";
if($num_pages>2)
{
$rets = "<form action=\"index.php\" method=\"get\">";
$rets .= "Pular para p�gina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"tid\" value=\"$tid\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "<br />";
echo "<form action=\"genproc.php?action=post&sid=$sid\" method=\"post\">";
echo "Texto: <input name=\"reptxt\" maxlength=\"2000\"/><br />";
echo "<input type=\"hidden\" name=\"tid\"value=\"$tid\"/>";
echo "<input type=\"hidden\" name=\"qut\" value=\"$qut\"/>";
echo "<input type=\"submit\" value=\"Enviar\"/>";
echo "</form>";
echo "</p>";
echo "<p>";
$fid = $tinfo[5];
$fname = getfname($fid);
$cid = mysql_fetch_array(mysql_query("SELECT cid FROM fun_forums WHERE id='".$fid."'"));
$cinfo = mysql_fetch_array(mysql_query("SELECT name FROM fun_fcats WHERE id='".$cid[0]."'"));
$cname = $cinfo[0];
echo "<a href=\"index.php?action=main&sid=$sid\">";
echo "P�gina principal</a>&gt;";
$cid = mysql_fetch_array(mysql_query("SELECT cid FROM fun_forums WHERE id='".$fid."'"));
if($cid[0]>0)
{
$cinfo = mysql_fetch_array(mysql_query("SELECT name FROM fun_fcats WHERE id='".$cid[0]."'"));
$cname = htmlspecialchars($cinfo[0]);
echo "<a href=\"index.php?action=viewcat&sid=$sid&cid=$cid[0]\">";
echo "$cname</a><br />";
}else{
$cid = mysql_fetch_array(mysql_query("SELECT clubid FROM fun_forums WHERE id='".$fid."'"));
$cinfo = mysql_fetch_array(mysql_query("SELECT name FROM fun_clubs WHERE id='".$cid[0]."'"));
$cname = htmlspecialchars($cinfo[0]);
echo "<a href=\"index.php?action=gocl&sid=$sid&clid=$cid[0]\">";
echo "$cname</a><br />";
}
$fname = htmlspecialchars($fname);
echo "&gt;<a href=\"index.php?action=viewfrm&sid=$sid&fid=$fid\">$fname</a>&gt;$tnm";
echo "</p>";
}
//////////////////////////////////View Forum
else if($action=="viewfrm")
{
$fid = $_GET["fid"];
$view = $_GET["view"];
if(!canaccess(getuid_sid($sid), $fid))
{
adicionar_online(getuid_sid($sid),"Vendo topico da equipe","");
echo "<p align=\"center\">";
echo "Voc� n�o possue permi��o para ver este conte�do!<br /><br />";
echo "<a href=\"index.php?action=main&sid=$sid\">P�gina principal</a>";
echo "</p>";
exit();
}
adicionar_online(getuid_sid($sid),"Vendo t�picos","");
$finfo = mysql_fetch_array(mysql_query("SELECT name from fun_forums WHERE id='".$fid."'"));
$fnm = htmlspecialchars($finfo[0]);
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=newtopic&sid=$sid&fid=$fid\">Novo T�pico</a><br />";
echo "<form action=\"index.php\" method=\"get\">";
echo "Ver: <select name=\"view\">";
echo "<option value=\"all\">Todos</option>";
echo "<option value=\"new\">Recentes</option>";
echo "<option value=\"myps\">Que postei</option>";
echo "</select>";
echo "<input type=\"submit\" value=\"Ver\"/>";
echo "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
echo "<input type=\"hidden\" name=\"fid\" value=\"$fid\"/>";
echo "<input type=\"hidden\" name=\"sid\"  value=\"$sid\"/>";
echo "</form>";
echo "<br />";
if($view=="new")
{
echo "Vendo t�picos mais recentes..";
}else if($view=="myps")
{
echo "Vendo t�picos que participei...";
}else {
echo "Vendo todos t�picos...";
}
echo "</p>";
echo "<p>";
echo "";
if($page=="" || $page<=0)$page=1;
if($page==1)
{
///////////pinned topics
$topics = mysql_query("SELECT id, name, closed, views, pollid FROM fun_topics WHERE fid='".$fid."' AND pinned='1' ORDER BY lastpost DESC, name, id LIMIT 0,5");
while($topic = mysql_fetch_array($topics))
{
$iml = "<img src=\"images/normal.gif\" alt=\"*\"/>";
$iml = "<img src=\"images/pin.gif\" alt=\"!\"/>";
$atxt ="";
if($topic[2]=='1')
{
//closed
$atxt = "<img src=\"images/closed.gif\" alt=\"!\"/>";
}
if($topic[4]>0)
{
$pltx = "(P)";
}else{
$pltx = "";
}
$tnm = htmlspecialchars($topic[1]);
$nop = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE tid='".$topic[0]."'"));
echo "<a href=\"index.php?action=viewtpc&sid=$sid&tid=$topic[0]\">$iml$pltx$tnm($nop[0])$atxt</a><br />";
}
echo "<br />";
}
$uid = getuid_sid($sid);
if($view=="new")
{
$ulv = mysql_fetch_array(mysql_query("SELECT lastvst FROM fun_users WHERE id='".$uid."'"));
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE fid='".$fid."' AND pinned='0' AND lastpost >='".$ulv[0]."'"));
}
else if($view=="myps")
{
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT a.id) FROM fun_topics a INNER JOIN fun_posts b ON a.id = b.tid WHERE a.fid='".$fid."' AND a.pinned='0' AND b.uid='".$uid."'"));
}
else{
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE fid='".$fid."' AND pinned='0'"));
}
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if($page>$num_pages)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
if($limit_start<0)$limit_start=0;
if($view=="new")
{
$ulv = mysql_fetch_array(mysql_query("SELECT lastvst FROM fun_users WHERE id='".$uid."'"));
$topics = mysql_query("SELECT id, name, closed, views, moved, pollid FROM fun_topics WHERE fid='".$fid."' AND pinned='0' AND lastpost >='".$ulv[0]."' ORDER BY lastpost DESC, name, id LIMIT $limit_start, $items_per_page");
}
else if($view=="myps"){
$topics = mysql_query("SELECT a.id, a.name, a.closed, a.views, a.moved, a.pollid FROM fun_topics a INNER JOIN fun_posts b ON a.id = b.tid WHERE a.fid='".$fid."' AND a.pinned='0' AND b.uid='".$uid."' GROUP BY a.id ORDER BY a.lastpost DESC, a.name, a.id  LIMIT $limit_start, $items_per_page");
}
else{
$topics = mysql_query("SELECT id, name, closed, views, moved, pollid FROM fun_topics WHERE fid='".$fid."' AND pinned='0' ORDER BY lastpost DESC, name, id LIMIT $limit_start, $items_per_page");
}
while($topic = mysql_fetch_array($topics))
{
$nop = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE tid='".$topic[0]."'"));
$iml = "<img src=\"images/normal.gif\" alt=\"*\"/>";
if($nop[0]>24)
{
$iml = "<img src=\"images/hot.gif\" alt=\"*\"/>";
}
if($topic[4]=='1')
{
$iml = "<img src=\"images/moved.gif\" alt=\"*\"/>";
}
if($topic[2]=='1')
{
$iml = "<img src=\"images/closed.gif\" alt=\"*\"/>";
}
if($topic[5]>0)
{
$iml = "<img src=\"images/poll.gif\" alt=\"*\"/>";
}
$atxt ="";
if($topic[2]=='1')
{
//closed
$atxt = "(X)";
}
$tnm = htmlspecialchars($topic[1]);
echo "<a href=\"index.php?action=viewtpc&sid=$sid&tid=$topic[0]\">$iml$tnm($nop[0])$atxt</a><br />";
}
echo "";
echo "</p>";
echo "<p align=\"center\">";
$tmsg = getpmcount(getuid_sid($sid));
$umsg = getunreadpm(getuid_sid($sid));
if($umsg>0)
{
echo "<a href=\"inbox.php?action=main&sid=$sid\">Torpedos($umsg/$tmsg)</a><br />";
}
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"index.php?action=viewfrm&page=$ppage&sid=$sid&fid=$fid&view=$view\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"index.php?action=viewfrm&page=$npage&sid=$sid&fid=$fid&view=$view\">Proxima&#187;</a>";
}
echo "<br />$page/$num_pages<br />";
if($num_pages>2)
{
$rets = "<form action=\"index.php\" method=\"get\">";
$rets .= "Pular pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"Ir\"/>";
$rets .= "<input type=\"hidden\" name=\"action\" value=\"$action\"/>";
$rets .= "<input type=\"hidden\" name=\"fid\" value=\"$fid\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "<input type=\"hidden\" name=\"view\" value=\"$view\"/>";
$rets .= "</form>";
echo $rets;
}
echo "<br /><br /><a href=\"index.php?action=newtopic&sid=$sid&fid=$fid\">Novo T�pico</a><br />";
$cid = mysql_fetch_array(mysql_query("SELECT cid FROM fun_forums WHERE id='".$fid."'"));
if($cid[0]>0)
{
$cinfo = mysql_fetch_array(mysql_query("SELECT name FROM fun_fcats WHERE id='".$cid[0]."'"));
$cname = htmlspecialchars($cinfo[0]);
echo "<a href=\"index.php?action=viewcat&sid=$sid&cid=$cid[0]\">";
echo "Voltar para $cname</a><br />";
}else{
$cid = mysql_fetch_array(mysql_query("SELECT clubid FROM fun_forums WHERE id='".$fid."'"));
$cinfo = mysql_fetch_array(mysql_query("SELECT name FROM fun_clubs WHERE id='".$cid[0]."'"));
$cname = htmlspecialchars($cinfo[0]);
echo "<a href=\"index.php?action=gocl&sid=$sid&clid=$cid[0]\">";
echo "$cname</a><br />";
}
//echo "<a href=\"index.php?action=forum&sid=$sid\"><img src=\"teks/folder.gif\" alt=\"*\"/>F�rum</a><br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
else if($action=="newtopic")
{
$fid = $_GET["fid"];
if(!canaccess(getuid_sid($sid), $fid))
{
echo "<p align=\"center\">";
echo "Voc� n�o possue permi��o para ver este conte�do!<br /><br />";
echo "<a href=\"index.php?action=main&sid=$sid\">P�gina principal</a>";
echo "</p>";
exit();
}
adicionar_online(getuid_sid($sid),"Criando novo t�pico","");
echo "<p align=\"center\">";
echo "<b>Novo T�pico</b>";
echo "</p>";
echo "<form action=\"genproc.php?action=newtopic&sid=$sid\" method=\"post\">";
echo "Tit�lo do t�pico: <input name=\"ntitle\" maxlength=\"30\"/><br />";
echo "Texto: <input name=\"tpctxt\" maxlength=\"2000\"/><br />";
echo "<input type=\"hidden\" name=\"fid\" value=\"$fid\"/>";
echo "<input type=\"submit\" value=\"Criar T�pico\"/>";
echo "<form>";
echo "<p align=\"center\">";
echo "Est� liberado o uso de <b>BBcodes</b> e <b>Smilies</b>!";
echo "</p>";
echo "<a href=\"index.php?action=viewfrm&sid=$sid&fid=$fid\">";
$fname = getfname($fid);
echo "Voltar para $fname</a><br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
//////////////////////////////////////////Post reply
else if($action=="post")
{
$tid = $_GET["tid"];
$tfid = mysql_fetch_array(mysql_query("SELECT fid FROM fun_topics WHERE id='".$tid."'"));
$fid = $tfid[0];
if(!canaccess(getuid_sid($sid), $fid))
{
echo "<p align=\"center\">";
echo "Vc nao tem permissao<br /><br />";
echo "<a href=\"index.php?action=main&sid=$sid\">P�gina principal</a>";
echo "</p>";
exit();
}
adicionar_online(getuid_sid($sid),"Escrevendo post","");
$qut = $_GET["qut"];
echo "<p align=\"center\">";
echo "<form action=\"genproc.php?action=post&sid=$sid\" method=\"post\">";
echo "Texto:<input name=\"reptxt\" maxlength=\"500\"/><br />";
echo "<input type=\"hidden\" name=\"tid\" value=\"$tid\"/>";
echo "<input type=\"hidden\" name=\"qut\" value=\"$qut\"/>";
echo "<input type=\"submit\" value=\"Gravar\"/>";
echo "</form>";
$fid = getfid($tid);
$fname = getfname($fid);
echo "<br /><br /><a href=\"index.php?action=viewtpc&sid=$sid&tid=$tid\">";
echo "Voltar ao topico</a>";
echo "<br /><a href=\"index.php?action=viewfrm&sid=$sid&fid=$fid\">";
echo "$fname</a><br />";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
//////////////////////////////////////////Post Options
else if($action=="pstopt")
{
$pid = $_GET["pid"];
$page = $_GET["page"];
$fid = $_GET["fid"];
adicionar_online(getuid_sid($sid),"Op��es da postagem","");
$pinfo= mysql_fetch_array(mysql_query("SELECT uid,tid, text  FROM fun_posts WHERE id='".$pid."'"));
$trid = $pinfo[0];
$tid = $pinfo[1];
$ptext = htmlspecialchars($pinfo[2]);
echo "<p align=\"center\">";
echo "<b>Op��es da Postagem</b>";
echo "</p>";
echo "<p>";
$trnick = getnick_uid($trid);
echo "<a href=\"inbox.php?action=sendpm&sid=$sid&who=$trid\">&#187;Enviar torpedo a $trnick</a><br />";
echo "<a href=\"index.php?action=perfil&sid=$sid&who=$trid\">&#187;Ver perfil de $trnick</a><br />";
echo "<a href=\"genproc.php?action=rpost&sid=$sid&pid=$pid\">&#187;Reportar para a equipe</a><br />";
echo "<a href=\"index.php?action=viewtpc&sid=$sid&tid=$tid&page=$page\">&#171;Voltar ao topico</a><br />";
if(ismod(getuid_sid($sid)))
{
echo "<a href=\"modproc.php?action=delp&sid=$sid&pid=$pid\">&#187;Apagar Postagem</a><br />";
echo "<form action=\"modproc.php?action=edtpst&sid=$sid&pid=$pid\" method=\"post\">";
echo "Texto: <input name=\"ptext\" value=\"$ptext\" maxlength=\"2000\" value=\"$pmtext\"/> ";
echo "<br /><input type=\"submit\" value=\"Editar\"/>";
echo "</form>";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
else if($action=="tpcopt")
{
$tid = $_GET["tid"];
adicionar_online(getuid_sid($sid),"Op��es do t�pico","");
$tinfo= mysql_fetch_array(mysql_query("SELECT name,fid, authorid, text, pinned, closed  FROM fun_topics WHERE id='".$tid."'"));
//log
$msg = "%$uid% est� moderando o t�pico ".$tinfo[0]."!";
addlog($msg);
$trid = $tinfo[2];
$ttext = htmlspecialchars($tinfo[3]);
$tname = htmlspecialchars($tinfo[0]);
echo "<p align=\"center\">";
echo "<b>Op��es do t�pico</b>";
echo "</p>";
echo "<p>";
echo "ID: <b>$tid</b><br />";
$trnick = getnick_uid($trid);
echo "<a href=\"inbox.php?action=sendpm&sid=$sid&who=$trid\">&#187;Enviar torpedo para $trnick</a><br />";
echo "<a href=\"index.php?action=perfil&sid=$sid&who=$trid\">&#187;Ver perfil de $trnick</a><br />";
echo "<a href=\"genproc.php?action=rtpc&sid=$sid&tid=$tid\">&#187;Reportar para a equipe</a><br />";
echo "<a href=\"index.php?action=viewtpc&sid=$sid&tid=$tid&page=1\">&#171;Voltar ao t�pico</a><br />";
if(ismod(getuid_sid($sid)))
{
echo "<br />";
if($tinfo[5]=='1')
{
$ctxt = "Abrir t�pico";
$cact = "0";
}else{
$ctxt = "Fechar t�pico";
$cact = "1";
}
echo "<a href=\"modproc.php?action=clot&sid=$sid&tid=$tid&tdo=$cact\">&#187;$ctxt</a><br />";
if($tinfo[4]=='1')
{
$ptxt = "N�o destacar";
$pact = "0";
}else{
$ptxt = "Destacar t�pico";
$pact = "1";
}
echo "<a href=\"modproc.php?action=pint&sid=$sid&tid=$tid&tdo=$pact\">&#187;$ptxt</a><br />";
echo "<a href=\"modproc.php?action=delt&sid=$sid&tid=$tid\">&#187;Apagar t�pico</a><br />";
echo "<form action=\"modproc.php?action=rentpc&sid=$sid&tid=$tid\" method=\"post\">";
echo "Titulo: <input name=\"tname\" value=\"$tname\" maxlength=\"25\" value=\"$tname\"/> ";
echo "<br /><input type=\"submit\" value=\"Renomear\"/>";
echo "</form>";
echo "<br />";
echo "<form action=\"modproc.php?action=edttpc&sid=$sid&tid=$tid\" method=\"post\">";
echo "Texto: <input name=\"ttext\" value=\"$ttext\" maxlength=\"2000\" value=\"$pmtext\"/> ";
echo "<br /><input type=\"submit\" value=\"Editar\"/>";
echo "</form>";
echo "<br />Mover t�pico para: ";
$forums = mysql_query("SELECT id, name FROM fun_forums WHERE clubid='0'");
echo "<form action=\"modproc.php?action=mvt&sid=$sid&tid=$tid\" method=\"post\">";
echo "<select name=\"mtf\">";
while ($forum = mysql_fetch_array($forums))
{
echo "<option value=\"$forum[0]\">$forum[1]</option>";
}
echo "</select><br />";
echo "<input type=\"submit\" value=\"Mover\"/>";
echo "</form>";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?action=main&sid=$sid\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
else
{
//pagina de entrada
echo "<p align=\"center\">";
echo "<img src=\"images/logo.png\" alt=\"*\"/><br />N�o importa a <b>esta��o</b>, amizades s�o para sempre!";
echo "</p>";
echo "<br />";
echo "<form action=\"logar.php\" method=\"get\">";
echo "Usu�rio/ID: <br /> <input name=\"usuario\" format=\"*x\" size=\"8\" maxlength=\"30\"/>";
echo "<br />";
echo "Senha: <br /> <input type=\"password\" name=\"senha\" size=\"8\" maxlength=\"30\"/>";
echo "<br />";
echo "<input type=\"submit\" value=\"Login&#187;\"/>";
echo "</form>";
echo "<p align=\"center\">";
echo "<a href=\"forum.php?\"><b>Visitar F�rum</b></a>";
echo "<br />";
echo "<br />";
echo "N�o � membro?<br />";
echo "<a href=\"register.php?formulario\">Cadastre-se agora!</a></b>";
echo "</p>";
echo "<p align=\"center\">";
echo "Usu�rios online: <b>".getnumonline()."</b><br />";
$norm = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_users"));
echo "Cadastrados: <b>$norm[0]</b><br />";
$memid = mysql_fetch_array(mysql_query("SELECT id FROM fun_users ORDER BY regdate DESC LIMIT 0,1"));
$novo_usuario = getnick_uid($memid[0]);
echo "Novo usu�rio: ".$novo_usuario."<br />";
$nopm = mysql_fetch_array(mysql_query("SELECT value FROM fun_settings WHERE name='Counter'"));
echo "Visitas: <b>$nopm[0]</b>";
echo "<br />";
echo "<br />";
echo "<b>Esta��oWAP.COM</b>";
echo "</p>";
}
?>
