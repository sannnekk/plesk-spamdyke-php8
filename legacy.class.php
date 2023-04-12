<?php
/*
Plesk Greylisting Manager (Version see version.php) - GUI for Plesk greylisting implementation by Parallels

Copyright (C) [2009] [Matthias Hackbarth / www.haggybear.de]

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as 
published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses/>.
*/

class legacy
{


        var $authtype;
        var $_login;

        function __construct()
        {
                $this->authtype = $_SESSION["auth"]["type"];
                if (!isset($_SESSION["auth"]["glmlogin"])) $_SESSION["auth"]["glmlogin"] = pm_Session::getClient()->getProperty("login");
                $this->_login = $_SESSION["auth"]["glmlogin"];
        }

        function chkLevel($type)
        {
                if ($type == $this->authtype) return true;
                return false;
        }
}
