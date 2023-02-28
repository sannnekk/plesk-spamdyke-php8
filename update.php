<?php
/*
Plesk Spamdyke Control Panel (Version see version.php) - GUI for Plesk spamdyke implementation

Copyright (C) [2008] [Matthias Hackbarth / www.haggybear.de]

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as 
published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses/>.
*/
?>
<script language="javascript">
<!--

function setStatus(tf,id){
         statText = "<span class=\"fatred\"><?php echo UPDATE_SCP_NOK;?></span>";         
         if(tf) statText = "<span class=\"fatgreen\"><?php echo UPDATE_SCP_OK;?></span>";         
	 document.getElementById(id).innerHTML = statText;
         }

//-->
</script>


<?php 
include("./db.php");

ob_start();
?>
<table width="500" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td colspan="2"><b><?php echo UPDATE_SCP;?></b></td>
  </tr>
  <tr> 
    <td><?php echo UPDATE_DOWN;?></td>
    <td id="down"></td>
  </tr>
  <tr> 
    <td><?php echo UPDATE_CONFIG;?></td>
    <td id="config"></td>
  </tr>
  <tr> 
    <td><?php echo UPDATE_INSTALL;?></td>
    <td id="install"></td>
  </tr>
  <tr> 
    <td><?php echo UPDATE_DONE;?></td>
    <td id="done"></td>
  </tr>
</table>
<?php 
ob_end_flush();
ob_start();
exec('./wrapper "3" "0"');
$weiter = 0;
if(file_exists("scp2_update.zip")) $weiter = 1;
echo "<script language=\"javascript\">setStatus($weiter,'down');</script>";
ob_end_flush();
ob_start();
if($weiter){
   $zip = zip_open("scp2_update.zip");
   if ($zip) {
       while ($zip_entry = zip_read($zip)) {
              $file = basename(zip_entry_name($zip_entry));
    	         if($file == "version.php"){
                    if (zip_entry_open($zip, $zip_entry, "r")) {
                         $aktVer = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                         zip_entry_close($zip_entry);
                       }
                    }
    	         if($file == "config.new.txt"){
                    if (zip_entry_open($zip, $zip_entry, "r")) {
                         $newConf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                         zip_entry_close($zip_entry);
                       }
                    }
	      }		
      }				              
   zip_close($zip);
   echo "<script language=\"javascript\">setStatus(".$weiter.",'config');</script>";
   }
else{
  echo "<script language=\"javascript\">setStatus(0,'config');</script>";
  echo "<script language=\"javascript\">setStatus(0,'install');</script>";
  echo "<script language=\"javascript\">setStatus(0,'done');</script>";
  exec('./wrapper "3" "3"');
  }
ob_end_flush();  
ob_start();
if($weiter){
  exec('./wrapper "3" "2"');
  $fp = fopen ("version.php", "r");
  $insVer = fread ($fp, filesize ("version.php"));
  fclose ($fp);
  if($insVer != $aktVer) $weiter = 0;

  if(!empty($newConf)){
     $fp = fopen ("config.inc.php", "r");
     $conf = fread ($fp, filesize ("config.inc.php"));
     fclose ($fp);
     $conf = str_replace("?>",$newConf."\n?>",$conf);
     exec("./wrapper '3' '2a' '".$conf."'");
     }

  echo "<script language=\"javascript\">setStatus(".$weiter.",'install');</script>";
  }
else{
  echo "<script language=\"javascript\">setStatus(0,'install');</script>";
  echo "<script language=\"javascript\">setStatus(0,'done');</script>";
  exec('./wrapper "3" "3"');
  }
ob_end_flush();
ob_start();
if($weiter){
  exec('./wrapper "3" "3"');
  if(file_exists("scp2_update.zip")) $weiter = 0;
  echo "<script language=\"javascript\">setStatus(".$weiter.",'done');</script>";
  }
else{
  echo "<script language=\"javascript\">setStatus(0,'done');</script>";
 }
ob_end_flush();

if($weiter){
  $infoTxt = file("INSTALL.txt");
  unlink("server.ver");
  $infoTxtOut = false;
  echo "<center class=\"fatgreen\">";
  echo "<br><br>";
  $aktVer =  str_replace("<?php","",$aktVer);
  $aktVer =  str_replace('define("SCP_VERSION","','',$aktVer);
  $aktVer =  str_replace('");','',$aktVer);
  $aktVer =  str_replace("?>","",$aktVer);
  echo str_replace("{VER}","<u>".trim($aktVer)."</u>",UPDATE_SCP_SUCCESS);
  echo "<br><br>";
  echo "<form action=\"index.php?".$scp->queryString."\" method=\"post\">";
  echo "<input type=\"submit\" name=\"forward\" value=\"OK\">";
  echo "<br><br>";
  echo "<div style=\"text-align:left;width:75%;border:1px dotted #FF0000;color:#000000;background:#D6D6D6;font-family:'Courier New'\">";
  for($r=0;$r<count($infoTxt);$r++){
      if($infoTxtOut) echo str_replace(" ","&nbsp;",$infoTxt[$r])."<br>";
      if(trim($infoTxt[$r])== "History:") $infoTxtOut = true;
      if(stristr($infoTxt[$r],"First public BETA")) $infoTxtOut = false;
      }
  echo "</div>";
  
  echo "</center>";
  }
else{
  echo "<center class=\"fatred\">";
  echo "<br><br>";
  echo UPDATE_SCP_FAILED;
  echo "</center>";
  }

 
?>