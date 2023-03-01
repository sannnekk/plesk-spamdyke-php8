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

define("DB_USR", "DB_USER");
define("DB_PWD", trim(exec ("cat /etc/psa/.psa.shadow")));
define("DB_NAME", "psa");
define("DB_HOST", "localhost");
define("QMAIL_PATH", "/var/qmail/");
define("LOG_FILE","/var/log/mail.info");
define("SPAMDYKE_CONFIG","/etc/spamdyke.conf");

//Only if you want to use the autoupdate function
define("AUTOUPDATE",true);

// define your language file, ex: en; ro; de;
define("LANG", "de");

define("PSA_PATH",trim(exec ("grep PRODUCT_ROOT_D /etc/psa/psa.conf | sed s/^[t]*[A-Z_]*[t]*//"))."/");
define("PSA_VERSION",doubleval(substr(trim(exec ("cat ".PSA_PATH."version")),0,4)));

//Default view - entries per page [25,50,100,200]
define("DEFAULT_PERPAGE",100);

//Logtype file/mysql (mysql only for the patched spamdyke-version from haggybear.de)
define("LOG_TYPE","mysql");

//Use whois detection for the daily reports! If false then the RDNS-detection will be used!
define("WHOIS_DETECT",false);

//Show flattr Button in Headline
define("SHOW_FLATTR",true);

//Master-Crypt-Key for Token encryption!
define("CRYPT_KEY",DB_PWD);
 
?>





