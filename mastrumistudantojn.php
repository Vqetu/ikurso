<?php
include "lingvo.inc.php";
include "db.inc.php";
include "webui.inc.php";
include "forum/includes/forum.lib.php";
malfermiDatumbazon();
// ER 05.10.2015 : correction pour passage en PHP 5.4
//session_register("persono_id");
$_SESSION['persono_id']=$persono_id;
if ($persono_id=="") {header("Location:index.php?erarkodo=8");}
$persono = apartigiPersonon($persono_id);
if (($persono["rajtoj"]!='A')&&($persono["rajtoj"]!='K')) {header("Location:index.php?erarkodo=4");}
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D,d M Y H:i:s")." GMT");
header("Cache-Control: no-store,no-cache, must-revalidate"); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

// tiu funkcio konstruas la liston de cxiuj studantojn
function listiStudantojn() {
     global $lingvo,$persono_id,$lgv_nekomencita,$lgv_haltita,$lgv_finita;
     $i=1;
     $demando =  "select nuna_kurso.id,personoj.enirnomo,personoj.personnomo,personoj.familinomo,nuna_kurso.nunleciono,nuna_kurso.kurso from personoj,nuna_kurso where nuna_kurso.studanto=personoj.id and nuna_kurso.korektanto=$persono_id and (nuna_kurso.stato='K' or nuna_kurso.stato='N')";
     mysql_select_db( "ikurso");
     $result = mysql_query($demando) or die (  "INSERT : malbona demando :".$demando);
     echo "<input type=\"hidden\" name=\"maksimumo\" value=\"".mysql_num_rows($result)."\">";
     while($row = mysql_fetch_array($result)) {
        echo "<tr>";
        echo "<td class=\"normala\" width=\"50%\">";
        echo $row["enirnomo"]." (".$row["personnomo"]." ".$row["familinomo"].")";
        echo "</td><td><select name=\"studanto".$i."\">";
        echo "<option value=\"".$row["id"]."-N\" >N’ont pas encore commencé</option>";
        $demando2="select lecionoj.titolo,lecionoj.numero from lecionoj where lecionoj.kurso='".$row["kurso"]."' and lecionoj.lingvo='$lingvo'";
        $result2=mysql_query($demando2) or die (  "SELECT : malbona demando :".$demando);
        while($row2 = mysql_fetch_array($result2)) {
                echo "<option value=\"".$row["id"]."-".$row2["numero"]."\" ";
                if ($row["nunleciono"]==$row2["numero"]) { echo "selected";}
                echo ">".$row2["titolo"]."</option>\n";
           }
        echo "<option value=\"".$row["id"]."-H\" >Ont abandonné</option>";
        echo "<option value=\"".$row["id"]."-F\" >Ont fini le cours</option>";
        $i++;
        echo "</select></td></tr>";
     }
}


?>
<html>
<head>
<title>ikurso</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" <?php if ($validi=="jes") { echo "onLoad=\"window.alert('Vos données ont été enregistrées');\""; } ?>>
<?php
	pagxkapo();
	menuo($persono["enirnomo"],$persono["rajtoj"]);
?>
<? 
/*
<table align="center" width="80%">
<tr><td style="color:red">
    ATTENTION : En raison d'op&eacute;rations de maintenance sur le site, 
    la gestion des &eacute;l&egrave;ves est momentan&eacute;ment indisponible.<br>
    Nous nous effor&ccedil;ons de remettre en place cette page le plus rapidement possible.<br>
    Merci de votre compr&eacute;hension.<br>
    L'&eacute;quipe d'I-kurso.<br>
</td></tr></table>
*/
?>
<center>
  <table border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td nowrap>
  <div align="center" class="titolo">Mes &eacute;l&egrave;ves</div>
  </td>
  </tr>
  <tr>
  <td bgcolor="#d0d8df">&nbsp;</td>
  </tr>
  <tr>
    <td nowrap>
	<form name="studantojlisto" action="mastrumistudantojn2.php" method="POST">
  <table border="0" cellspacing="0" cellpadding="0" width="100%">

  <? listiStudantojn(); ?>
  <td align="center"  colspan="2">
		<input type="submit" value="Envoyer">

  </td>
  </table>
    </form>

  </td>

   </tr>

   <tr>

   <td bgcolor="#d0d8df">&nbsp;</td>

   </tr>

   </table>
  <p>&nbsp;</p>
</center>
</body>


</html>




                    
                    
                    
                    
