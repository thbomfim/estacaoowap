<?php
include("core.php");
include("config.php");
bd_connect();
$id = $_GET["id"];
$info = mysql_fetch_array(mysql_query("SELECT url, visitas FROM fun_downloads WHERE id='".$id."'"));
if(empty($info[0]))
{
echo "Erro arquivo não encontrado!";
}
else{
$mais = $info[1] + 1;
mysql_query("UPDATE fun_downloads SET visitas='".$mais."' WHERE id='".$id."'");
header("Location: downloads/".$info[0]);
}
exit();
?>
