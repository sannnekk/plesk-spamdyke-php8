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

error_reporting(1);

exec("whois " . $_GET["dieip"], $output);

if (empty($output)) {
	die("<br><br><font size=\"4\" color=\"FF0000\"><center>No WHOIS on system installed!<br>Please install with \"<b>apt-get install whois</b>\" or \"<b>yast -i whois</b>\"</center>");
}

function findMail($m)
{
	$m = ereg_replace('[-a-z0-9!#$%&\'*+/=?^_`{|}~]+@([.]?[a-zA-Z0-9_/-])*', '<img src="abuse.jpg" title="report spam" alt="report spam"> <a href="javascript://" onClick="abuseReport(\'' . $_GET["row"] . '\',\'\\0\')">\\0</a>', $m);
	return $m;
}

echo "<b><u>IP: " . $_GET["dieip"] . "</u></b><br><br>";
echo "<table width=\"580\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
foreach ($output as $key => $value) {
	echo "<tr>";
	if ($_GET["abuse"]) $value = findMail($value);
	if (stristr($value, ":")) {
		$val = explode(": ", $value);
		if ($val[0] && $val[1] != "") {
			echo "<td width=\"120\">" . $val[0] . "</td>";
			echo "<td width=\"460\">" . $val[1] . "</td>";
		}
	} else {
		if (trim($value) != "") echo "<td colspan =\"2\" width=\"580\">" . $value . "</td>";
	}
	echo "</tr>";
}
echo "</table>";
