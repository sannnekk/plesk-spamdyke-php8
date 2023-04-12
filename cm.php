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

$cArr = validateEmail($_GET["addy"]);


function validateEmail($Email)
{
    global $HTTP_HOST;
    $ret = array();

    if (!preg_match("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$", $Email)) {
        $ret["syntax"] = EMAILCHECK_SYNTAX_FAILED;
    } else {
        $ret["syntax"] = EMAILCHECK_SYNTAX_SUCCESS;
    }
    list($Username, $Domain) = explode("@", $Email);

    if (checkdnsrr($Domain, "MX")) {
        $ret["mx"] = EMAILCHECK_MX_SUCCESS;
        if (getmxrr($Domain, $MXrec)) {
            $Mailserver = $MXrec[0];
            $ret["mxrr"] = str_replace("{MX}", $Mailserver, EMAILCHECK_MXRR_SUCCESS);
        } else {
            $ret["mxrr"] = EMAILCHECK_MXRR_FAILED;
        }
    } else {
        $ret["mx"] = EMAILCHECK_MX_FAILED;
        $Mailserver = $Domain;
    }

    if ($Connection = fsockopen($Mailserver, 25)) {
        $ret["conn"] = str_replace("{SERVER}", $Mailserver, EMAILCHECK_CONN_SUCCESS);
        if (ereg("^220", $Rubbish = fgets($Connection, 1024))) {
            $ret["test"] = EMAILCHECK_TEST_SUCCESS;

            fputs($Connection, "HELO $HTTP_HOST\r\n");
            $Rubbish = fgets($Connection, 1024);

            fputs($Connection, "MAIL FROM: <{$Email}>\r\n");
            $Fromstring = fgets($Connection, 1024);

            fputs($Connection, "RCPT TO: <{$Email}>\r\n");
            $Tostring = fgets($Connection, 1024);

            fputs($Connection, "QUIT\r\n");
            fclose($Connection);

            if (ereg("^250", $Fromstring) && ereg("^250", $Tostring)) {
                $ret["valid"] = EMAILCHECK_ADDY_SUCCESS;
            } else {
                $ret["valid"] = EMAILCHECK_ADDY_FAILED;
            }
        } else {
            $ret["test"] = EMAILCHECK_TEST_FAILED;
        }
    } else {
        $ret["conn"] = "Could not estalish connection to $Mailserver<br>";
    }
    return $ret;
}

?>
<table width="630" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td colspan="2"><u><b><?php echo EMAILCHECK_HEAD_SYNTAX; ?> </b></u></td>
    </tr>
    <tr>
        <td width="250"><?php echo EMAILCHECK_SYNTAX; ?></td>
        <td class="fett"><?php echo $cArr["syntax"]; ?></td>
    </tr>
    <tr>
        <td width="250"><?php echo EMAILCHECK_MX; ?></td>
        <td class="fett"><?php echo $cArr["mx"]; ?></td>
    </tr>
    <tr>
        <td width="250"><?php echo EMAILCHECK_MXRR; ?></td>
        <td class="fett"><?php echo $cArr["mxrr"]; ?></td>
    </tr>
    <tr>
        <td width="250">&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2"><u><b><?php echo EMAILCHECK_HEAD_SERVER; ?></b></u></td>
    </tr>
    <tr>
        <td width="250"><?php echo EMAILCHECK_CONN; ?></td>
        <td class="fett"><?php echo $cArr["conn"]; ?></td>
    </tr>
    <tr>
        <td width="250"><?php echo EMAILCHECK_TEST; ?></td>
        <td class="fett"><?php echo $cArr["test"]; ?></td>
    </tr>
    <tr>
        <td width="250"><?php echo EMAILCHECK_ADDY; ?></td>
        <td class="fett"><?php echo $cArr["valid"]; ?></td>
    </tr>
</table>