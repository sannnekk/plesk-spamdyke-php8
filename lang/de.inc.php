<?php

DEFINE("SCP_NO_SPAMDYKE","Spamdyke ist nicht installiert, oder der Pfad zur spamdyke.conf in config.inc.php stimmt nicht!!");

DEFINE("SCP_ADMIN","Spamdyke Control Panel");
DEFINE("SCP_VERSION_OK","Sie haben die aktuelle Version");
DEFINE("SCP_VERSION_NOK","Version {VER} verf&uuml;gbar - jetzt downloaden?");
DEFINE("SCP_VERSION_UPD","Version {VER} verf&uuml;gbar - jetzt updaten?");

DEFINE("SCP_LEVEL_UP","Eine Ebene h&ouml;her");
DEFINE("SCP_SAVE_BTN","speichern");

DEFINE("SCP_NO_LOG_LEVEL","In der spamdyke.conf wurde kein LogLevel definiert, das SCP kann nichts anzeigen!<br> Bitte den Parameter <u>log-level=info</u> in die spamdyke.conf einf&uuml;gen!"); 
DEFINE("SCP_WRONG_LOG_LEVEL","In der spamdyke.conf wurde das LogLevel nicht ausreichend gesetzt, das SCP kann so nichts anzeigen!<br> Bitte das LogLevel auf mind. <u>log-level=info</u> in der spamdyke.conf korrgieren!"); 
DEFINE("SCP_NO_DATABASE_SUPPORT","In the spamdyke.conf ist der keine Datenbank eingetragen. Du brauchst f&uuml;r den MySQL-Support die gepatchte spamdyke Version von haggybear.de");
DEFINE("SCP_WRONG_FILEPERMS","Der wrapper hat nicht die ben&ouml;tigten Rechte, es muss das SUID-Bit gesetzt werden '<i>chmod 4755 wrapper</i>' !");

DEFINE("SCP_OVERVIEW","Gesamt&uuml;bersicht");
DEFINE("SCP_DOMAINVIEW","Domain&uuml;bersicht");
DEFINE("SCP_REFRESH","aktualisieren");
DEFINE("SCP_RESETFILTER","alle Filter zur&uuml;cksetzen");
DEFINE("SCP_EDIT","bearbeiten");
DEFINE("SCP_SHOW","anzeigen");
DEFINE("SCP_CLOSE","schliessen");
DEFINE("SCP_SETTINGS_LINK","Einstellungen");
DEFINE("SCP_FILTER_LINK","Filter");
DEFINE("SCP_RIGHTS_LINK","Rechte");
DEFINE("SCP_OVERVIEW_LINK","&Uuml;bersicht");
DEFINE("SCP_ADMIN_LINK","Administration");
DEFINE("SCP_ALL","== Alle Eintr&auml;ge ==");
DEFINE("SCP_SET_FILTER","Filter");

DEFINE("SCP_DIRECTION","Richtung");
DEFINE("SCP_DIRECTION_IN","eing.");
DEFINE("SCP_DIRECTION_OUT","ausg.");
DEFINE("SCP_DIRECTION_LOCAL","lokal");

DEFINE("SCP_SEARCH","Suche");
DEFINE("SCP_SEARCH_OF","Suchmuster:");
DEFINE("SCP_SEARCH_DO","suchen!");

DEFINE("SCP_EVENT_HANDLER_HEAD","Den Event-Handler von Plesk best&uuml;cken, so da&szlig; bei der Domainverwaltung Spamdyke mit einbezogen wird !");
DEFINE("SCP_EVENT_HANDLER_CREATE","Beim Erstellen Greylist-Ordner anlegen");
DEFINE("SCP_EVENT_HANDLER_UPDATE","Beim Update Greylist-Ordner mit &auml;ndern");
DEFINE("SCP_EVENT_HANDLER_DELETE","Beim L&ouml;schen Greylist-Ordner entfernen");

DEFINE("SCP_PER_PAGE","Anzeige Eintr&auml;ge");

DEFINE("SCP_LIVE_COUNTRY","Live Land Ermittlung");
DEFINE("SCP_LIVE_COUNTRY_ALLOW","'Live Land' erlauben");
DEFINE("SCP_LIVE_COUNTRY_NOTICE","Achtung: Die Live Ermittlung verlangsamt die Anzeige erheblich!");
DEFINE("SCP_LOGFROM","Log vom");

DEFINE("SCP_SHOW_PASSED","Zeige DENIED<>ALLOWED Greylist-Paar");
DEFINE("SCP_SHOW_ALLADDR","nur vorhandene Adressen");

DEFINE("SCP_ON_WHITELIST","Der Eintrag \"+ip+\" steht schon auf der Whitelist!");
DEFINE("SCP_ON_BLACkLIST","Der Eintrag \"+ip+\" steht schon auf der Blacklist!");

DEFINE("SCP_EXPLAIN_IP","Die IPs m&uuml;ssen <b>ZEILENWEISE</b> eingetragen werden. Individuelle IPs im Format <b>11.22.33.44</b> bzw. <b>23.34.45.56</b>.<br>IP Bereiche sollten im Format <b>11.22.33</b> eingetragen werden");
DEFINE("SCP_EXPLAIN_RDNS","Die Namen m&uuml;ssen <b>ZEILENWEISE</b> eingetragen werden. Individuelle Namen im Format <b>mail.example.com</b>.<br>Es k&ouml;nnen auch Wildcards benutzt werden. Der f&uuml;hrende Punkt ist die Wildcard <b>.example.com</b>");
DEFINE("SCP_EXPLAIN_SENDER","Die Adressen m&uuml;ssen <b>ZEILENWEISE</b> eingetragen werden. Individuelle Namen im Format <b>mail@example.com</b>.<br>Es k&ouml;nnen auch Wildcards benutzt werden. Der f&uuml;hrende Punkt ist die Wildcard <b>.example.com</b>");
DEFINE("SCP_EXPLAIN_KEYWORDS","Die Keywords m&uuml;ssen <b>ZEILENWEISE</b> eingetragen werden. Alle Keywords sind <b>case-insensitive</b>");
DEFINE("SCP_EXPLAIN_HEADER","Die Keywords m&uuml;ssen <b>ZEILENWEISE</b> eingetragen werden. Alle Keywords sind <b>case-insensitive</b>");
DEFINE("SCP_EXPLAIN_EXAMPLES","Ausf&uuml;hrliche Hilfe mit Beispielen");
DEFINE("SCP_EXPLAIN_EXPIRES","Um einem Eintrag ein Ablaufdatum zu setzen, den/die Eintr&auml;ge mit der Maus markieren");

DEFINE("VALUE_ACT","aktiv");
DEFINE("VALUE_ACT_NOW","aktivieren");
DEFINE("VALUE_YES","ja");
DEFINE("VALUE_DEACT","deaktiv");
DEFINE("VALUE_DEACT_NOW","deaktivieren");
DEFINE("VALUE_NO","nein");

DEFINE("NO_FULLSCREEN","Fullscreeninformation bis zum Ende der Sitzung deaktivieren!");
  	
DEFINE("SCP_ADMIN_RIGHTS","Rechte des Domaineigent&uuml;mers");

DEFINE("SCP_WHITELISTS","Whitelisten");
DEFINE("SCP_WHITELISTS_GLOBAL","Gobale, serverweite Whiteliste");
DEFINE("SCP_WHITELISTS_CUSTOM","Benutzer/Domainspezifische Whiteliste");
DEFINE("SCP_BLACKLISTS","Blacklisten");
DEFINE("SCP_BLACKLISTS_GLOBAL","Gobale, serverweite Blackliste");
DEFINE("SCP_BLACKLISTS_CUSTOM","Benutzer/Domainspezifische Blackliste");
DEFINE("SCP_SENDTIME","Zeitstempel");
DEFINE("SCP_STATISTIK","Statistik");
DEFINE("SCP_EXPORT","CSV-Export");
DEFINE("SCP_MAXRECIPIENTS","Begrenzung der Anzahl von Empf&auml;gern pro Mail");


DEFINE("SCP_SETTINGS","Einstellungen");
DEFINE("SCP_GREYLISTING","Greylisting f&uuml;r Domain"); 
DEFINE("SCP_ALL_MAILS","Alle Anfragen");
DEFINE("SCP_DROPPED_MAILS","Abgelehnte Mails"); 	
DEFINE("SCP_DROPPED_MAILS_GL","Greylisting");
DEFINE("SCP_DROPPED_MAILS_OTHER","andere Variante");
DEFINE("SCP_LISTS_IP","IP White/Blacklisten"); 
DEFINE("SCP_LISTS_SENDER","Absender White/Blacklisten"); 

DEFINE("SCP_ALLOWED","Angenommene Mails");
DEFINE("SCP_LISTS_RDNS","RDNS White/Blacklisten"); 	
DEFINE("SCP_LISTS_RECIPIENT","Empf&auml;nger White/Blacklisten");
DEFINE("SCP_LISTS_KEYWORDS","IP/RDNS-Keyword Blackliste");
DEFINE("SCP_LISTS_HEADER","Header-Keyword Blackliste");
DEFINE("SCP_SPAMRATE","Spamrate"); 	
DEFINE("SCP_SENDER","Absender"); 	
DEFINE("SCP_RECEIVE","Empf&auml;nger"); 
DEFINE("SCP_REASON","Bewertung"); 	
DEFINE("SCP_ORG_IP","Absender IP"); 
DEFINE("SCP_ORG_RDNS","Reverse DNS"); 	


DEFINE("GETTING_IP_INFO","Getting IP information");
DEFINE("CHECKING_EMAIL","Checking email");

DEFINE("EMAILCHECK_HEAD_SYNTAX","Checking syntax & MX records");
DEFINE("EMAILCHECK_SYNTAX","Checking email syntax");
DEFINE("EMAILCHECK_SYNTAX_SUCCESS","<font color=\"#006600\">succesful</font>");
DEFINE("EMAILCHECK_SYNTAX_FAILED","<font color=\"#FF0000\">failed</font>");
DEFINE("EMAILCHECK_MX","Checking MX record");
DEFINE("EMAILCHECK_MX_SUCCESS","<font color=\"#006600\">succesful</font>");
DEFINE("EMAILCHECK_MX_FAILED","<font color=\"#FF0000\">failed</font>");
DEFINE("EMAILCHECK_MXRR","Read MX record");
DEFINE("EMAILCHECK_MXRR_SUCCESS","<font color=\"#006600\">{MX}</font>");
DEFINE("EMAILCHECK_MXRR_FAILED","<font color=\"#FF0000\">failed</font>");

DEFINE("EMAILCHECK_HEAD_SERVER","Checking mailserver & email adress");
DEFINE("EMAILCHECK_CONN","Connecting to mailserver");
DEFINE("EMAILCHECK_CONN_SUCCESS","<font color=\"#006600\">connected to {SERVER}</font>");
DEFINE("EMAILCHECK_CONN_FAILED","<font color=\"#FF0000\">failed</font>");
DEFINE("EMAILCHECK_TEST","Testing Mailserver");
DEFINE("EMAILCHECK_TEST_SUCCESS","<font color=\"#006600\">succesful</font>");
DEFINE("EMAILCHECK_TEST_FAILED","<font color=\"#FF0000\">invalid answer</font>");
DEFINE("EMAILCHECK_ADDY","Testing email adress");
DEFINE("EMAILCHECK_ADDY_SUCCESS","<font color=\"#006600\">adress valid</font>");
DEFINE("EMAILCHECK_ADDY_FAILED","<font color=\"#FF0000\">adress invalid</font>");


DEFINE("UPDATE_SCP","Greylisting Control Panel Updater");
DEFINE("UPDATE_DOWN","Downloading neue Version"); 	
DEFINE("UPDATE_CONFIG","&Uuml;bernehme bestehende Config"); 	
DEFINE("UPDATE_INSTALL","Installiere neue Version"); 	
DEFINE("UPDATE_DONE","Anschluss der Installation"); 	

DEFINE("UPDATE_SCP_OK","erfolgreich");
DEFINE("UPDATE_SCP_NOK","fehlgeschlagen");

DEFINE("UPDATE_SCP_SUCCESS","Update erfolgreich auf {VER} durchgef&uuml;hrt");
DEFINE("UPDATE_SCP_FAILED","Update fehlgeschlagen, bitte erneut versuchen");

DEFINE("SCP_GREYLIST_SPAMDYKE_CONF","Einstellungen f&uuml;r Spamdyke");
DEFINE("SCP_GRAYLIST_LEVEL","Greylisting Variante");
DEFINE("SCP_GRAYLIST_LEVEL_ALWAYS_CREATE_DIR","Global, Serverweit");
DEFINE("SCP_GRAYLIST_LEVEL_ALWAYS","Domainbezogen");
DEFINE("SCP_GRAYLIST_LEVEL_NONE","Generell deaktiviert");
DEFINE("SCP_GRAYLIST_MIN_SECS","Mindestabstand");
DEFINE("SCP_GRAYLIST_MAX_SECS","Verfall des Greylistingeintrages");
DEFINE("SCP_GRAYLIST_MIN_SECS_HELP","Der Mindestabstand zwischen zwei Zustellversuchen, damit die Mail akzeptiert wird");
DEFINE("SCP_GRAYLIST_MAX_SECS_HELP","Der Verfall des Eintrages, bevor die Mail erneut gep&uuml;ft wird");
DEFINE("SCP_GRAYLIST_SECS","Sekunden");

DEFINE("SCP_ADMIN_GREYLISTING","Greylisting");
DEFINE("SCP_ADMIN_DNSBL_LISTS","DNSBL/DNSWL");
DEFINE("SCP_DNSBL_SPAMDYKE_CONF","DNSBL-Blacklisten f&uuml;r Spamdyke (zum L&ouml;schen, DNSBL-Name entfernen!) - Aktuelle Listen -> <a href=\"https://wiki.apache.org/spamassassin/DnsBlocklists\" target=\"_blank\">http://spamlinks.net/filter-dnsbl-lists.htm#spamsource</a>");
DEFINE("SCP_DNSBL_SPAMDYKE_LIST","DNSBL-Name");
DEFINE("SCP_DNSBL_SPAMDYKE_ACTIVE","aktiv");
DEFINE("SCP_DNSBL_SPAMDYKE_NEW","Neue Liste");
DEFINE("SCP_ADMIN_DNSWL_LISTS","DNSWL-Whitelisten");
DEFINE("SCP_DNSWL_SPAMDYKE_CONF","DNSWL-Whitelisten f&uuml;r Spamdyke (zum L&ouml;schen, DNSWL-Name entfernen!) - Aktuelle Listen -> <a href=\"https://wiki.apache.org/spamassassin/DnsBlocklists\" target=\"_blank\">http://spamlinks.net/filter-dnsbl-lists.htm#whitelists</a>");
DEFINE("SCP_DNSWL_SPAMDYKE_LIST","DNSWL-Name");
DEFINE("SCP_DNSWL_SPAMDYKE_ACTIVE","aktiv");
DEFINE("SCP_DNSWL_SPAMDYKE_NEW","Neue Liste");
DEFINE("SCP_DNSRHSBL_SPAMDYKE_CONF","DNS RHSBL-Listen f&uuml;r Spamdyke (zum L&ouml;schen, DNS RHSBL-Name entfernen!) - Aktuelle Listen -> <a href=\"https://wiki.apache.org/spamassassin/DnsBlocklists\" target=\"_blank\">http://spamlinks.net/filter-dnsbl-lists.htm#domain</a>");
DEFINE("SCP_DNSRHSBL_SPAMDYKE_LIST","DNS RHSBL-Name");
DEFINE("SCP_DNSRHSBL_SPAMDYKE_ACTIVE","aktiv");
DEFINE("SCP_DNSRHSBL_SPAMDYKE_NEW","Neue Liste");

DEFINE("SCP_ADMIN_ALLG_SETTINGS","Div. Einstellungen");
DEFINE("SCP_ALLG_SPAMDYKE_INFOS","Diverse Spamdyke Informationen");
DEFINE("SCP_ALLG_SPAMDYKE_INFOS_V","Version");  	
DEFINE("SCP_ACTIVE_SPAMDYKE_PROC","Aktive Spamdyke Prozesse");
DEFINE("SCP_KILL_ALL_SPAMDYKE","Alle Spamdyke Prozesse t&ouml;ten");
DEFINE("SCP_ALLG_SPAMDYKE_CONF","Diverse Spamdyke Einstellungen");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER","Variante");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-MISSING-SENDER-MX","Verbindung blocken wenn die Domain keinen MX-Eintrag hat.");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-EMPTY-RDNS","Verbindung blocken wenn der Remote-Server keinen rDNS Namen hat");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-UNRESOLVABLE-RDNS","Verbindung blocken wenn der rDNS des Remote-Servers nicht aufl&ouml;sbar ist. ");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_CONFIG-DIR","Domainspezifische White/Blacklisten zulassen");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-IP-IN-CC-RDNS","Verbindung blocken wenn die IP oder der Landescode im rDNS gefunden wurde, scheint eine dynamische IP zu sein!");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-IDENTICAL-SENDER-RECIPIENT","Jede Verbindung zur&uuml;ckweisen in der Sender und Empf&auml;nger E-Mail Adresse gleich sind!");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_WRONG_VERSION","Dieser Parameter ist erst ab Spamdyke Version %s verf&uuml;gbar!");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_RELAY-LEVEL_BLOCK-ALL","Alles blocken");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_RELAY-LEVEL_NORMAL","Normal, authentifiziere User");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_RELAY-LEVEL_ALLOW-ALL","Alles erlauben");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_RELAY-LEVEL","SMTP Relay level");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-RECIPIENT_NONE","deaktiviert");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-RECIPIENT_SAME-AS-SENDER","gleicher Absender wie Empf&auml;nger");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-RECIPIENT_INVALID","ung&uuml;ltiger Empf&auml;nger");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-RECIPIENT_UNAVAILABLE","nicht verf&uuml;gbare Empf&auml;nger");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-RECIPIENT","E-Mails mit bestimmten Eigenschaften blocken");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-SENDER_NONE","deaktiviert");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-SENDER_NO-MX","ung&uuml;ltiger MX-Record");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-SENDER_NOT-LOCAL","Nur von lokalen Absenders akzeptieren");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-SENDER","E-Mails von bestimmen Absendern blocken");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_IP-RELAY-FILE","IP Adressen die diesen Server als Relay verwenden d&uuml;rfen");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_RDNS-RELAY-FILE","Server die diesen rDNS Namen haben d&uuml;rfen diesen Server als Relay verwenden");
DEFINE("SCP_CONFIG_UPDATE","Es wurde Spamdyke in der Version %s gefunden. Das SCP hat die Konfiguration angepasst und ben&ouml;tigt ab sofort mindestens diese Version!");

DEFINE("SCP_GREETING_DELAY","Verz&ouml;gerung, bis der SMTP die Willkommens-Nachricht sendet. Wird vor der Willkommens-Nachricht gesendet, blockt Spamdyke. Earlytaker!!");
DEFINE("SCP_CONNECTION_TIMEOUT_SECS"," - Zeit bis die SMTP-Verbindung automatisch geschlossen wird. Ein hoher Wert (720) ist empfehlenswert, falls die Verbindung langsam ist!!");
DEFINE("SCP_IDLE_TIMEOUT_SECS"," - Zeit bis die SMTP-Verbidung geschlossen wird, wenn keine Daten mehr fliessen. Sch&uuml;tzt vor unn&ouml;tigen Spamdyke-Prozessen (H&auml;ngende Spamdyke-Prozesse)");

DEFINE("SCP_ADMIN_MULTI_RIGHTS","Multi-Domain Rechte");
DEFINE("SCP_MULTI_RIGHTS_SPAMDYKE_CONF","Multi-Domain Rechte verwalten");
DEFINE("SCP_MULTI_RIGHTS_DOMAIN","Domain");
DEFINE("SCP_ADMIN_ALLNONE_SELECTION","Alles aktivieren/deaktivieren");
DEFINE("SCP_ADMIN_TOGGLE_SELECTION","Auswahl umkehren");

DEFINE("SCP_GRAYLIST_MIN","Min.");
DEFINE("SCP_GREYLIST_DELAY","ZUSTELL-VERZ&Ouml;GERUNG:");
DEFINE("SCP_GREYLIST_DELAY_AVERAGE","Durchschn. Verz&ouml;gerung durch Greylisting:");
DEFINE("SCP_GREYLIST_DELAY_MAX","Gr&ouml;&szlig;te Verz&ouml;gerung durch Greylisting:");
DEFINE("SCP_GREYLIST_DELAY_MIN","Kleinste Verz&ouml;gerung durch Greylisting:");

DEFINE("SCP_MYSQL_DAY_0","So");
DEFINE("SCP_MYSQL_DAY_1","Mo");
DEFINE("SCP_MYSQL_DAY_2","Di");
DEFINE("SCP_MYSQL_DAY_3","Mi");
DEFINE("SCP_MYSQL_DAY_4","Do");
DEFINE("SCP_MYSQL_DAY_5","Fr");
DEFINE("SCP_MYSQL_DAY_6","Sa");
DEFINE("SCP_MYSQL_VIEW_DATE_FORMAT","d.m.y");

DEFINE("SCP_TAKES_LONG_TO_GENERATE","Die genaue Landbestimmung per WHOIS-Abfrage ben%F6tigt bei #IPS# IP%27s sehr lange. Die Ermittlung wird ca. #MIN# Min betragen. Soll diese Art der Landbestimmung wirklich durchgef%FChrt werden%3F.\\n\\nAndernfalls wird die Bestimmung anhand der RDNS-Domain durchgef%FChrt, was aber keine sehr genauen Ergebnisse liefert!");
DEFINE("SCP_DAILYREPORT","t&auml;gl. Bericht");
DEFINE("SCP_REMOTE_ACCESS","Remotezugriff");
DEFINE("SCP_REMOTE_ACCESS_EXAMPLE","Beispielscript f&uuml;r Remotezugriff runterladen");
DEFINE("SCP_ADMIN_REPORTS","t&auml;gl. E-Mail Berichte");
DEFINE("SCP_REPORT_SPAMDYKE_SETTINGS","T&auml;gliche E-Mail Berichte verwalten");
DEFINE("SCP_REPORT_SPAMDYKE_ONOFF","T&auml;gliche E-Mail Berichte aktivieren");
DEFINE("SCP_REPORT_SPAMDYKE_TO_ADMIN","T&auml;glichen,Serverweiten E-Mail Berichte an den Administrator");
DEFINE("SCP_REPORT_SPAMDYKE_TO_ADMIN_EMAIL","an folgende E-Mail");
DEFINE("SCP_REPORT_SPAMDYKE_TO_USER","Den Usern erlauben sich einen Domainbericht senden zu lassen");
DEFINE("SCP_REMOTE_SPAMDYKE_TO_USER","Den Usern erlauben eine Remote-Verbindung aufzubauen");
DEFINE("SCP_REMOTE_SPAMDYKE_TO_ADMIN","Admin Remotezugriff mit Supertoken erlauben!");
DEFINE("SCP_RESELLER_RIGHTS","Reseller die selbstst&auml;ndig Domainrechte vergeben d&uuml;rfen!");
DEFINE("SCP_STATS_PROZ","Prozentuale Statistik");
DEFINE("SCP_STATS_TOP","TOP-Werte Statistik");
DEFINE("SCP_GENERATATE_TOP_STATS","Die Statistik wird erstellt, bitte einen Moment Geduld!");
DEFINE("SCP_STAT_NO","Anzahl");
DEFINE("SCP_STAT_IPS","IPs");
DEFINE("SCP_STAT_EMAIL","TOP bespammte E-Mail");
DEFINE("SCP_STAT_DOMAIN","TOP bespammte Domain");
DEFINE("SCP_STAT_IP","TOP spamming IP");
DEFINE("SCP_STAT_COUNTRY","TOP spamming Land");
DEFINE("SCP_STAT_STD","TOP Spam-Stunde");
DEFINE("SCP_STAT_HOUR","Uhr");
DEFINE("SCP_STAT_GFX","Grafik");
DEFINE("SCP_STAT_COUNTRY_DISABLED","Landbestimmung deaktiviert!");
DEFINE("SCP_STATUS_GOES_TO","Wird versendet an");

DEFINE("SCP_SPAM_ABUSE_REPORT","Spam-Abuse Report Einstellungen");
DEFINE("SCP_SPAM_ABUSE_REPORT_SENDER","Absender");
DEFINE("SCP_SPAM_ABUSE_REPORT_TITEL","Betreff");
DEFINE("SCP_SPAM_ABUSE_REPORT_TPL","Template");
DEFINE("SCP_SPAM_ABUSE_REPORT_SPAMMAIL","Daten der beanstandeten Mail");
DEFINE("SCP_SPAM_ABUSE_REPORT_DO","M%F6chten Sie eine Abuse-Mail mit den Details an diesen Emfp%E4nger senden%3F");
DEFINE("SCP_SPAM_ABUSE_REPORT_DO_OK","Die E-Mail wurde gesendet, soll das Fenster nun geschlossen werden?");
DEFINE("SCP_SPAM_ABUSE_REPORT_DO_NOK","Die E-Mail konnte nicht gesendet werden\\n=================================\\n");
DEFINE("TOGGLE_FULLSCREEN","Vollbildmodus");
DEFINE("SCP_EVENT_HANDLER_RIGHTS_HEAD","Den Event-Handler von Plesk best&uuml;cken, so da&szlig; bei der Domainverwaltung Defaultrechte angelegt werden");
DEFINE("SCP_EVENT_HANDLER_RIGHTS_CREATE","Beim Erstellen Defaultrechte anlegen");
DEFINE("SCP_EVENT_HANDLER_RIGHTS_UPDATE","Beim Update Defaultrechte &auml;ndern");
DEFINE("SCP_EVENT_HANDLER_RIGHTS_DELETE","Beim L&ouml;schen Defaultrechte entfernen");
DEFINE("SCP_PLESK_MENU_INTEGRATION","Plesk Menu Integration");
DEFINE("SCP_PSA_ADMIN_LINK","Im linken Plesk Menu einen Direktlink zur Gesamt&uuml;bersicht und Administration anlegen");
DEFINE("SCP_SYSTEM_INTEGRATION","SCP System Integration");
DEFINE("SCP_SPAMDYKE_WATCHDOG","Systemwatchdog zur &Uuml;berpr&uuml;fung ob Spamdyke in die QMail-Verarbeitung eingebunden ist. Pr&uuml;ft alle 30 Minuten ob Spamdyke noch integriert ist und f&uuml;hrt eine Integration neu aus wenn es nicht der Fall ist (z.B. nach einem Plesk Update)<br>Schreibt ein Log nach /var/log/spamdyke.conf.log");
DEFINE("SCP_MAIL_ANALYZE","Analyse der Mailkommunikation");
DEFINE("SCP_NO_MAILBOX","Die E-Mail <i>%s</i> hat kein Postfach");
?>