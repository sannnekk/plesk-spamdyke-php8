<?php

/*
 * by sannnekk
 * [https://github.com/sannnekk]
 * 
 */

if (!defined('DB_USR')) {
    require 'config.inc.php';
}

class LegacyResolver
{
    /**
     * reconnectDB and return mysqli instance
     *
     * @return mysqli
     */
    public static function reconnectDB()
    {
        $db_server = DB_HOST;
        $db_user = DB_USR;
        $db_passwort = DB_PWD;
        $db_name = DB_NAME;

        return mysqli_connect($db_server, $db_user, $db_passwort, $db_name);
    }
}
