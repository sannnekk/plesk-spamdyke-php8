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

#include("./config.inc.php");
#include("./lang/".LANG.".inc.php");

#$dat =exec('./wrapper "1" "proz4stat '$GET_["dom"]'._log",$out');

?>
<html>

<head>
    <title>Prozent</title>
    <meta http-equiv='refresh' content='1; URL=proz4stat.php?dom=<?php #echo $GET_["dom"];
                                                                    ?>'>
</head>

<body bgcolor="#FFFFFF">
    <?php #print_r($out);
    ?>
</body>

</html>