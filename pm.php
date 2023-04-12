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

if (isset($_GET["sa"])) {
   exec('./wrapper "1" "togglespamassassin ' . $_GET["addy"] . ' ' . $_GET["sa"] . '"');
}

exec('./wrapper "1" "readmailprops ' . $_GET["addy"] . '"', $mailOut);

$out = true;
?>
<?php if (!empty($mailOut)) : ?>
   <table width="630" border="0" cellspacing="0" cellpadding="0">
      <tr>
         <?php
         for ($i = 0; $i < count($mailOut); $i++) {
            $data = explode(":", $mailOut[$i]);
            if (trim($data[0]) == "SUCCESS") $out = false;

            if (stristr($data[0], "Status") && !$out) {
               $out = true;
               $data[1] = (stristr($data[0], "false")) ? VALUE_DEACT . ' [<a href="#" onclick="document.getElementById(\'iplook\').style.display=\'none\';getPageContent(\'pm.php?sa=true&addy=' . $_GET["addy"] . '\',\'' . $_GET["addy"] . '\',\'mail\')">' . VALUE_ACT_NOW . '</a>?]' : VALUE_ACT . ' [<a href="#" onclick="document.getElementById(\'iplook\').style.display=\'none\';getPageContent(\'pm.php?sa=false&addy=' . $_GET["addy"] . '\',\'' . $_GET["addy"] . '\',\'mail\')">' . VALUE_DEACT_NOW . '</a>?]';
               $data[0] = "Spamassassin";
            }
            if ((substr($data[0], 0, 1) == " " || empty($data[0])) || !$out) continue;
         ?>
            <td width="250" style="vertical-align:top;padding-bottom:10px;"><?php echo $data[0]; ?>:</td>
            <td class="fett" style="vertical-align:top;padding-bottom:10px;"><?php echo $data[1]; ?></td>
      </tr>
   <?php
            if (trim($data[0]) == "Spamassassin") $out = false;
         } ?>
<?php else : ?>
   <h3 style="color:#FF0000">
      <center><?php echo sprintf(SCP_NO_MAILBOX, $_GET["addy"]); ?></center>
   </h3>

<?php endif; ?>