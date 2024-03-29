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

@ini_set("memory_limit", '256M');
require("./config.inc.php");
require("./lang/" . LANG . ".inc.php");
require("./scp.class.php");
require("./lang/tld.inc.php");
require("./stat.class.php");

class s
{
  function chkLevel($i)
  {
    return true;
  }
}

$file = fopen("reports.txt", 'r');
$reports = unserialize(trim(fread($file, filesize("reports.txt"))));
fclose($file);

if (is_array($reports["admin"])) doReport(true, $reports["admin"][0], "", $tld);

while (list($key, $val) = each($reports["user"])) {
  doReport(false, $val, $key, $tld);
}


function doReport($isAdm, $email, $dom, $tld)
{
  if (empty($email)) return;
  $_GET["dom_name"] = $dom;
  $scp = scp::factory(new s(), $_GET, array(DB_HOST, DB_NAME, DB_USR, DB_PWD));
  $scp->getAdmin()->isAllView = $isAdm;
  $scp->setLogrotates();

  $datum = getCorrectLog($scp->logRotates[1]);

  $scp->anzVars["anz_Von"] = $datum[0];
  $scp->readQmailLogs();

  $titleTime = $datum[1];

  $statObj = new stat(5, WHOIS_DETECT, $scp->getAdmin()->isAllView);
  $statObj->setTlds($tld);
  $statObj->setIsReport();
  $stat = $scp->topStat($statObj);

  ob_start();
  echo '<table width="640" border="0" cellspacing="0" cellpadding="0">';
  $scp->getStat();
  echo '</table>';
?>
  <hr size="1" width="640" align="left">
  <table width="640" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="center" valign="top" height="200"><?php $stat->getTopRec(); ?></td>
      <td align="center" valign="top" height="200"><?php $stat->getTopIp(); ?></td>
    </tr>
    <tr>
      <td align="center" valign="top" height="200"><?php $stat->getTopCountry(); ?></td>
      <td align="center" valign="top" height="200"><?php $stat->getTopTime(); ?></td>
    </tr>
  </table>
<?php
  $html = ob_get_contents();
  ob_end_clean();

  $titelSuffix = ($isAdm) ? " " : " (" . $_GET["dom_name"] . ")";

  $header = $header ?? "";
  $text = $text ?? "";
  $body = $body ?? "";

  $header .= "From: <$email>\n";
  $header .= "Reply-To: <$email>\n";
  $header .= "Return-path: <$email>\n";
  $header .= "Mailer: SCP Mailer\n";
  $text .= "<HTML><HEAD>";
  $text .= '<style type="text/css"><!--td{font-size:10px;}#stat{border:1px outset #000000;float:left;}//--></style>';
  $text .= "</HEAD><BODY>";
  $text .= "<font size='-1' face='VERDANA,ARIAL,HELVETICA'>";
  $text .= str_replace("src=\"flags/", "src=\"cid:flags-", $html);
  $text .= "</BODY></HTML>";

  $Trenner = md5(uniqid(time()));

  $header .= "MIME-Version: 1.0";
  $header .= "\n";
  $header .= "Content-Type: multipart/mixed; boundary=$Trenner";

  $body .= "This is a multi-part message in MIME format";
  $body .= "\n";
  $body .= "--$Trenner";
  $body .= "\n";
  $body .= "Content-Type: text/html";
  $body .= "\n";
  $body .= "Content-Transfer-Encoding: 8bit";
  $body .= "\n\n";
  $body .= $text;

  for ($rr = 0; $rr < 5; $rr++) {
    $Dateiname = 'flags/' . $stat->topCountrySave[$rr] . '.png';
    $DateinameMail = $stat->topCountrySave[$rr] . '.png';
    $body .= "\n";
    $body .= "--$Trenner";
    $body .= "\n";
    $body .= "Content-Type: image/png";
    $body .= "\n";
    $body .= "Content-ID: <flags-" . $stat->topCountrySave[$rr] . ".png>";
    $body .= "\n";
    $body .= "Content-Transfer-Encoding: base64";
    $body .= "\n";
    $body .= "Content-Disposition: inline; filename=\"$DateinameMail\"\n\n";
    $body .= "\n\n";
    $Dateiinhalt = fread(fopen($Dateiname, "r"), filesize($Dateiname));
    $body .= chunk_split(base64_encode($Dateiinhalt));
    $body .= "\n";
  }
  $Dateiname = 'help_ico.gif';
  $DateinameMail = 'help_ico.gif';
  $body .= "\n";
  $body .= "--$Trenner";
  $body .= "\n";
  $body .= "Content-Type: image/gif";
  $body .= "\n";
  $body .= "Content-ID: <help_ico.gif>";
  $body .= "\n";
  $body .= "Content-Transfer-Encoding: base64";
  $body .= "\n";
  $body .= "Content-Disposition: inline; filename=\"$DateinameMail\"\n\n";
  $body .= "\n\n";
  $Dateiinhalt = fread(fopen($Dateiname, "r"), filesize($Dateiname));
  $body .= chunk_split(base64_encode($Dateiinhalt));
  $body .= "\n";
  $body .= "--$Trenner--";

  //if (mail($email, "SCP report ".$titleTime.$titelSuffix, $body, $header)) {
  //    echo "mail send ... OK\n\n";
  //} else {
  //    echo "mail send ... ERROR\n\n";
  //}

  mail($email, "SCP report " . $titleTime . $titelSuffix, $body, $header);
}

function getCorrectLog($fomat)
{

  if (LOG_TYPE == "mysql") {
    $ret[0] = date("Y-m-d", strtotime($fomat));
    $ret[1] = date("d.m.Y", strtotime($fomat));
  } else {
    $splits = explode(" ", str_replace("  ", " ", $fomat));
    $tmpVal = "";
    $max = count($splits) - 1;
    $anz = $max - 5;
    $ret[0] = $splits[$max];
    for ($ii = 0; $ii < $anz; $ii++) {
      $tmpVal .= $splits[5 + $ii] . " ";
    }
    $ret[1] = date("d.m.Y", strtotime(substr($tmpVal, 0, 10)));
  }
  return $ret;
}
?>