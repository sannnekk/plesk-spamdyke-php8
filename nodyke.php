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
<table width="700" border="0" cellspacing="0" cellpadding="3" height="300">
  <tr>
    <td colspan="2" align="center" valign="middle">
      <?php

      if (!$scp->spamDykeConfError) {
        echo "<b>" . SCP_NO_SPAMDYKE . "</b>";
      } else {
        echo "<b>" . $scp->spamDykeConfError . "</b>";
      }

      ?>
    </td>
  </tr>
</table>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr align="center" valign="middle">
    <td height="1" bgcolor="000000"></td>
  </tr>
  <tr align="center" valign="middle">
    <td height="5">&copy; 2008-2018 <a href="http://www.haggybear.de">Matthias Hackbarth</a></td>
  </tr>
</table>
<br>