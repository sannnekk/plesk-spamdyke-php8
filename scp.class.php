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
if (!defined("LOG_TYPE")) define("LOG_TYPE", "file");

require("./securityfilter.php");
include("./xtea.class.php");
require("./AdminRights.class.php");
require("./optionboxes.class.php");
require("./log-" . LOG_TYPE . ".class.php");


class scp
{
   public static function factory($sess, $getVars, $db = null)
   {

      return new readlog($sess, $getVars, $db);
   }
}
