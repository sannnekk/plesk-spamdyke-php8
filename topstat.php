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
require("./version.php");
require("./lang/" . LANG . ".inc.php");
require("./lang/tld.inc.php");
require("./stat.class.php");
require("./scp.class.php");
require("./dynstat.class.php");



$scp = scp::factory($session, $_GET, array(DB_HOST, DB_NAME, DB_USR, DB_PWD));

if (!$scp->getPleskAllowed()) {
  die("Permisson denied...");
}

if ($_GET["action"] == "allview") {
  if ($scp->plesk_session->chkLevel(IS_ADMIN)) $scp->getAdmin()->isAllView = true;
}

$statObj = new stat(5, $_POST["land"], $scp->getAdmin()->isAllView);
$statObj->setTlds($tld);

$scp->anzVars["anz_Von"] = $_POST["anzeige"];
$stat = $scp->topStat($statObj);
?>
<script language="javascript">
  function getMiniIp(k) {
    divId = "miniIp_" + k.replace(/\./g, "");
    document.getElementById(divId).className = "vis";
    $("#" + divId).load("ident.php?dieip=" + k, function() {
      document.getElementById(divId).style.width = "285px";
      document.getElementById(divId).style.height = "300px";
    });
  }

  function closeMiniIp(k) {
    divId = "miniIp_" + k.replace(/\./g, "");
    document.getElementById(divId).className = "invis";
  }
</script>
<table width="640" border="0" cellspacing="0" cellpadding="0" id="topStatBody">
  <tr>
    <td align="center" height="30" colspan="2"></td>
  </tr>
  <tr>
    <td align="center" height="200"><?php $stat->getTopRec(); ?></td>
    <td align="center" height="200"><?php $stat->getTopIp(); ?></td>
  </tr>
  <tr>
    <td align="center" height="200"><?php $stat->getTopCountry(); ?></td>
    <td align="center" height="200"><?php $stat->getTopTime(); ?></td>
  </tr>
  <tr>
    <td align="center" height="30" colspan="2"></td>
  </tr>
</table>