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
$page = $_GET["page"];
$go = $_GET["go"];
if($a=="viewcat")///ver a categoria
{
$cid = $_GET["cid"];
$cinfo = mysql_fetch_array(mysql_query("SELECT name from fun_fcats WHERE id='".$cid."'"));
echo "<p>";
$forums = mysql_query("SELECT id, name FROM fun_forums WHERE cid='".$cid."' AND clubid='0' ORDER BY position, id, name");
while($forum = mysql_fetch_array($forums))
{
$notp = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_topics WHERE fid='".$forum[0]."'"));
$nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts a INNER JOIN fun_topics b ON a.tid = b.id WHERE b.fid='".$forum[0]."'"));
$iml = "<img src=\"images/1.gif\" alt=\"*\"/>";///icone do link
///
echo "<a href=\"forum.php?a=viewfrm&fid=$forum[0]\">$iml$forum[1]($notp[0]/$nops[0])</a><br/>";
///
$lpt = mysql_fetch_array(mysql_query("SELECT id, name FROM fun_topics WHERE fid='".$forum[0]."' ORDER BY lastpost DESC LIMIT 0,1"));
$nops = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE tid='".$lpt[0]."'"));
if($nops[0]==0)
{
$pinfo = mysql_fetch_array(mysql_query("SELECT authorid FROM fun_topics WHERE id='".$lpt[0]."'"));
$tluid = $pinfo[0];
}else
{
$pinfo = mysql_fetch_array(mysql_query("SELECT  uid  FROM fun_posts WHERE tid='".$lpt[0]."' ORDER BY dtpost DESC LIMIT 0, 1"));
$tluid = $pinfo[0];
}
$tlnm = htmlspecialchars($lpt[1]);
$tlnick = getnick_uid($tluid);
$tpclnk = "<a href=\"forum.php?a=viewtpc&tid=$lpt[0]&go=last\">$tlnm</a>";
$vulnk = "$tlnick";
echo "�ltima postagem: $tpclnk, Por: $vulnk<br/><br/>";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"forum.php?\"><img src=\"teks/folder.gif\" alt=\"*\"/>F�rum</a><br/>";
echo "<a href=\"index.php?\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
else if($a=="viewtpc")////ver topico
{
$tid = $_GET["tid"];
$tinfo = mysql_fetch_array(mysql_query("SELECT name, text, authorid, crdate, views, fid, pollid from fun_topics WHERE id='".$tid."'"));
$tnm = htmlspecialchars($tinfo[0]);
echo "<p align=\"center\">";
$num_pages = getnumpages($tid);
if($page==""||$page<1)$page=1;
if($go!="")$page=getpage_go($go,$tid);
$posts_per_page = 5;
if($page>$num_pages)$page=$num_pages;
$limit_start = $posts_per_page *($page-1);
$lastlink = "<a href=\"forum.php?a=$a&tid=$tid&go=last\">Ultima pagina</a>";
$firstlink = "<a href=\"forum.php?a=$a&tid=$tid&page=1\">Primeira pagina</a> ";
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
echo "<br/>$golink";
}
echo "</p>";
echo "<p>";
$rpls = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM fun_posts WHERE tid='".$tid."'"));
echo "Postagens: $rpls[0] - Visitas: $vws<br/>";
///fm here
if($page==1)
{
$posts_per_page=4;
$ttext = mysql_fetch_array(mysql_query("SELECT authorid, text, crdate, pollid FROM fun_topics WHERE id='".$tid."'"));
$unick = getnick_uid($ttext[0]);
if(isonline($ttext[0]))
{
$iml = "<img src=\"images/onl.gif\" alt=\"+\"/>";
}else
{
$iml = "<img src=\"images/ofl.gif\" alt=\"-\"/>";
}
$usl = "<br/>$iml$unick";
if($go==$tid)
{
$fli = "<img src=\"images/flag.gif\" alt=\"!\"/>";
}else{
$fli ="";
}
$pst = scan_msg_other($ttext[1],$sid);
echo "$usl: $fli$pst $topt<br/>";
$dtot = date("d-m-y - H:i:s",$ttext[2]);
echo $dtot;
echo "<br/>";
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
$usl = "<br/>$iml$unick";
$pst = scan_msg_other($post[1], $sid);
if($go==$post[0])
{
$fli = "<img src=\"images/flag.gif\" alt=\"!\"/>";
}else{
$fli ="";
}
echo "$usl: $fli$pst $topt<br/>";
$dtot = date("d-m-y - H:i:s",$post[3]);
echo $dtot;
echo "<br/>";
}
///to here
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"forum.php?a=viewtpc&page=$ppage&tid=$tid\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"forum.php?a=viewtpc&page=$npage&tid=$tid\">Proxima&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form a=\"forum.php\" method=\"get\">";
$rets .= "Pular para p�gina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"IR\"/>";
$rets .= "<input type=\"hidden\" name=\"a\" value=\"$a\"/>";
$rets .= "<input type=\"hidden\" name=\"tid\" value=\"$tid\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "</form>";
echo $rets;
}
echo "</p>";
echo "<p>";
$fid = $tinfo[5];
$fname = getfname($fid);
$cid = mysql_fetch_array(mysql_query("SELECT cid FROM fun_forums WHERE id='".$fid."'"));
$cinfo = mysql_fetch_array(mysql_query("SELECT name FROM fun_fcats WHERE id='".$cid[0]."'"));
$cname = $cinfo[0];
echo "<a href=\"index.php?\">";
echo "P�gina principal</a>&gt;";
$cid = mysql_fetch_array(mysql_query("SELECT cid FROM fun_forums WHERE id='".$fid."'"));
if($cid[0]>0)
{
$cinfo = mysql_fetch_array(mysql_query("SELECT name FROM fun_fcats WHERE id='".$cid[0]."'"));
$cname = htmlspecialchars($cinfo[0]);
echo "<a href=\"forum.php?a=viewcat&cid=$cid[0]\">";
echo "$cname</a><br/>";
}
$fname = htmlspecialchars($fname);
echo "&gt;<a href=\"forum.php?a=viewfrm&fid=$fid\">$fname</a>&gt;$tnm";
echo "</p>";
}
//////////////////////////////////View Forum
else if($a=="viewfrm")
{
$fid = $_GET["fid"];
$view = "all";
$finfo = mysql_fetch_array(mysql_query("SELECT name from fun_forums WHERE id='".$fid."'"));
$fnm = htmlspecialchars($finfo[0]);
echo "<p align=\"center\">";
echo "<b>F�rum ".$snome."</b>";
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
echo "<a href=\"forum.php?a=viewtpc&tid=$topic[0]\">$iml$pltx$tnm($nop[0])$atxt</a><br/>";
}
echo "<br/>";
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
$topics = mysql_query("SELECT id, name, closed, views, moved, pollid FROM fun_topics WHERE fid='".$fid."' AND pinned='0' ORDER BY lastpost DESC, name, id LIMIT $limit_start, $items_per_page");
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
echo "<a href=\"forum.php?a=viewtpc&tid=$topic[0]\">$iml$tnm($nop[0])$atxt</a><br/>";
}
echo "";
echo "</p>";
echo "<p align=\"center\">";
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"forum.php?a=viewfrm&page=$ppage&fid=$fid&view=$view\">&#171;Anterior</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"forum.php?a=viewfrm&page=$npage&fid=$fid&view=$view\">Proxima&#187;</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "<form a=\"forum.php\" method=\"get\">";
$rets .= "Pular pagina: <input name=\"page\" format=\"*N\" size=\"3\"/>";
$rets .= "<input type=\"submit\" value=\"Ir\"/>";
$rets .= "<input type=\"hidden\" name=\"a\" value=\"$a\"/>";
$rets .= "<input type=\"hidden\" name=\"fid\" value=\"$fid\"/>";
$rets .= "<input type=\"hidden\" name=\"sid\" value=\"$sid\"/>";
$rets .= "<input type=\"hidden\" name=\"view\" value=\"$view\"/>";
$rets .= "</form>";
echo $rets;
}
echo "<br />";
echo "<a href=\"forum.php?\"><img src=\"teks/folder.gif\" alt=\"*\"/>F�rum</a><br/>";
echo "<a href=\"index.php?\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a>";
echo "</p>";
}
else
{
echo "<p align=\"center\">";
echo "<b>F�rum $snome</b>";
echo "<br />";
echo "</p>";
$fcats = mysql_query("SELECT id, name FROM fun_fcats ORDER BY position, id");
$iml = "<img src=\"images/1.gif\" alt=\"*\"/>";//icone do link
while($fcat=mysql_fetch_array($fcats))
{
$categoria = "<a href=\"forum.php?a=viewcat&cid=$fcat[0]\">$iml$fcat[1]</a>";
echo "$categoria";
echo "<br />";
}
echo "</p>";
echo "<p align=\"center\">";
echo "<a href=\"index.php?\"><img src=\"images/home.gif\" alt=\"*\"/>";
echo "P�gina principal</a><br/>";
echo "</p>";
}
?>