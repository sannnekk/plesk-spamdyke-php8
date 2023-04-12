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
if (function_exists("apc_clear_cache")) @apc_clear_cache();

require("./version.php");
require("./lang/" . LANG . ".inc.php");
require("./scp.class.php");

$xtea = new XTEA(CRYPT_KEY);

$file = fopen("remote.txt", 'r');
$remoteStates = unserialize(trim(fread($file, filesize("remote.txt"))));
fclose($file);
$do = false;
$adminView = false;

if (strlen(trim($_COOKIE["TOKEN"])) == 6) {
   while (list($key, $val) = each($remoteStates["user"])) {
      if (trim($_COOKIE["TOKEN"]) == trim($xtea->Decrypt($val))) {
         $do = true;
         $_GET["dom_name"] = $key;
      }
   }
} else if (strlen(trim($_COOKIE["TOKEN"])) == 7) {
   if (trim($_COOKIE["TOKEN"]) == trim($xtea->Decrypt($remoteStates["admin"]))) {
      $do = true;
      $adminView = true;
   }
}

if (!$do) {
   die("<scp>\n <version>" . SCP_VERSION . "</version>\n <error>Invalid token!</error>\n</scp>");
}

$scp = scp::factory($session, $_GET, array(DB_HOST, DB_NAME, DB_USR, DB_PWD));
$scp->getAdmin()->isAllView = $adminView;
$scp->setLogrotates();

$datum = getCorrectLog($scp->logRotates[0]);

$scp->anzVars["anz_Von"] = $datum[0];
if (!empty($_GET["perPage"])) $scp->anzVars["anz_PerPage"] = $_GET["perPage"];
$scp->readQmailLogs();
$scp->getAllBlocked();
$xml = "<?xml version=\"1.0\"?>\n";
$xml .= "<scp>\n";
$xml .= " <version>" . SCP_VERSION . "</version>\n";
$xml .= " <entrys>" . $scp->anzVars["anz_PerPage"] . "</entrys>\n";
$xml .= " <datum>" . $datum[1] . "</datum>\n";
$xml .= " <statistik>\n";
$xml .= "  <name>" . SCP_ALL_MAILS . "</name>\n";
$xml .= "  <value>" . ($scp->blocked + $scp->allowed) . "</value>\n";
$xml .= " </statistik>\n";
$xml .= " <statistik>\n";
$xml .= "  <name>" . SCP_DROPPED_MAILS . "</name>\n";
$xml .= "  <value>" . $scp->blocked . "</value>\n";
$xml .= " </statistik>\n";
$xml .= " <statistik>\n";
$xml .= "  <name>" . SCP_DROPPED_MAILS_GL . "</name>\n";
$xml .= "  <value>" . ($scp->blockedType["DENIED_GRAYLISTED"] * 1) . "</value>\n";
$xml .= " </statistik>\n";
$xml .= " <statistik>\n";
$xml .= "  <name>" . SCP_DROPPED_MAILS_OTHER . "</name>\n";
$xml .= "  <value>" . ($scp->blocked - $scp->blockedType["DENIED_GRAYLISTED"]) . "</value>\n";
$xml .= " </statistik>\n";
$xml .= " <statistik>\n";
$xml .= "  <name>" . SCP_ALLOWED . "</name>\n";
$xml .= "  <value>" . $scp->allowed . "</value>\n";
$xml .= " </statistik>\n";
$xml .= " <statistik>\n";
$xml .= "  <name>" . SCP_SPAMRATE . "</name>\n";
$xml .= "  <value>" . $scp->getSpamrate() . "</value>\n";
$xml .= " </statistik>\n";
$count = count($scp->logTable);
$xml .= " <logtabelle>\n";
for ($i = 0; $i < $count; $i++) {
   $xml .= "  <entries>\n";
   $xml .= "   <entry>\n";
   $xml .= "    <key>" . SCP_DIRECTION . "</key>\n";
   $xml .= "    <value>" . $scp->logTable[$i]["direction"] . "</value>\n";
   $xml .= "   </entry>\n";
   $xml .= "   <entry>\n";
   $xml .= "    <key>" . SCP_SENDER . "</key>\n";
   $xml .= "    <value>" . strip_tags($scp->logTable[$i]["from"]) . "</value>\n";
   $xml .= "   </entry>\n";
   $xml .= "   <entry>\n";
   $xml .= "    <key>" . html_entity_decode(SCP_RECEIVE) . "</key>\n";
   $xml .= "    <value>" . strip_tags($scp->logTable[$i]["to"]) . "</value>\n";
   $xml .= "   </entry>\n";
   $xml .= "   <entry>\n";
   $xml .= "    <key>" . SCP_REASON . "</key>\n";
   $xml .= "    <value>" . $scp->logTable[$i]["reason"] . "</value>\n";
   $xml .= "   </entry>\n";
   $xml .= "   <entry>\n";
   $xml .= "    <key>" . SCP_ORG_IP . "</key>\n";
   $xml .= "    <value>" . strip_tags($scp->logTable[$i]["ip"]) . "</value>\n";
   $xml .= "   </entry>\n";
   $xml .= "   <entry>\n";
   $xml .= "    <key>" . SCP_ORG_RDNS . "</key>\n";
   $xml .= "    <value>" . strip_tags($scp->logTable[$i]["rdns"]) . "</value>\n";
   $xml .= "   </entry>\n";
   $xml .= "   <entry>\n";
   $xml .= "    <key>" . SCP_SENDTIME . "</key>\n";
   $xml .= "    <value>" . date("d. M. H:i:s", $scp->logTable[$i]["time"]) . "</value>\n";
   $xml .= "   </entry>\n";
   $xml .= "  </entries>\n";
}
$xml .= " </logtabelle>\n";
$xml .= "</scp>\n";
header("content-type: text/xml; charset=utf-8");
echo utf8_encode($xml);

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
