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

include("./config.inc.php");
include("./lang/" . LANG . ".inc.php");

$derMonat = date("M", $_GET["time"]) . '_';
$derTag = str_pad(date("j", $_GET["time"]), 2, '_', STR_PAD_LEFT) . '_';

$datum = $derMonat . $derTag . date("H:i:s", $_GET["time"]);

exec('./wrapper "1" "analyze ' . LOG_FILE . ' ' . str_replace(" ", "+", $_GET["to"]) . ' ' . str_replace(" ", "+", $_GET["from"]) . ' ' . $datum . '"', $out);

echo "<b>" . SCP_MAIL_ANALYZE . ': ' . $_GET["from"] . ' -> ' . $_GET["to"] . "</b>";
echo '<div style="height:3px;width:100%;background:#000000;margin-top:5px;margin-bottom:5px"></div>';

foreach ($out as $line) {
	echo $line . "<hr>";
}
