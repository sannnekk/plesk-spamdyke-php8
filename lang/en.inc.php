<?php

DEFINE("SCP_NO_SPAMDYKE","Spamdyke not installed, or path to spamdyke.conf in config.inc.php is not set!!");

DEFINE("SCP_ADMIN","Spamdyke Control Panel");
DEFINE("SCP_VERSION_OK","Your version is up to date");
DEFINE("SCP_VERSION_NOK","Version {VER} available - download now?");
DEFINE("SCP_VERSION_NOK","Version {VER} available - update now?");

DEFINE("SCP_LEVEL_UP","Level up");
DEFINE("SCP_SAVE_BTN","save");

DEFINE("SCP_NO_LOG_LEVEL","In the spamdyke.conf is no loglevel definied, the SCP can't show anything!<br> Please insert the parameter <u>log-level=info</u> into spamdyke.conf!"); 
DEFINE("SCP_WRONG_LOG_LEVEL","In the spamdyke.conf the loglevel the loglevel isn't correctly set, the SCP can't show anything!<br> Please set the loglevel min. to <u>log-level=info</u>!"); 
DEFINE("SCP_NO_DATABASE_SUPPORT","In the spamdyke.conf is no database definied, you need the patched version of spamdyke from haggybear.de");
DEFINE("SCP_WRONG_FILEPERMS","The wrapper hasn't the needed permissions, the SUID-Bit has to be set '<i>chmod 4755 wrapper</i>' !");

DEFINE("SCP_OVERVIEW","Server overview");
DEFINE("SCP_DOMAINVIEW","Domain overview");
DEFINE("SCP_REFRESH","refresh");
DEFINE("SCP_RESETFILTER","reset all filter");
DEFINE("SCP_EDIT","edit");
DEFINE("SCP_SHOW","show");
DEFINE("SCP_CLOSE","close");
DEFINE("SCP_SETTINGS_LINK","Settings");
DEFINE("SCP_FILTER_LINK","Filter");
DEFINE("SCP_RIGHTS_LINK","Rights");
DEFINE("SCP_OVERVIEW_LINK","Overview");
DEFINE("SCP_ADMIN_LINK","Administration");
DEFINE("SCP_ALL","== All entries ==");
DEFINE("SCP_SET_FILTER","Filter");

DEFINE("SCP_DIRECTION","Direction");
DEFINE("SCP_DIRECTION_IN","in");
DEFINE("SCP_DIRECTION_OUT","out");
DEFINE("SCP_DIRECTION_LOCAL","local");

DEFINE("SCP_SEARCH","Search");
DEFINE("SCP_SEARCH_OF","Searchpattern:");
DEFINE("SCP_SEARCH_DO","search!");

DEFINE("SCP_EVENT_HANDLER_HEAD","Setup the Plesk Event-Handler during the domainadministration to include spamdyke !");
DEFINE("SCP_EVENT_HANDLER_CREATE","After domain-creation, create greylist folder");
DEFINE("SCP_EVENT_HANDLER_UPDATE","After domain-update change greylist folder");
DEFINE("SCP_EVENT_HANDLER_DELETE","After deleting a domain, remove greylist-folder");

DEFINE("SCP_PER_PAGE","Show entries");

DEFINE("SCP_LIVE_COUNTRY","live country detection");
DEFINE("SCP_LIVE_COUNTRY_ALLOW","allow live country");
DEFINE("SCP_LIVE_COUNTRY_NOTICE","Warning: The live detection slows down the view!");

DEFINE("SCP_LOGFROM","Log from");

DEFINE("SCP_SHOW_PASSED","Show DENIED<>ALLOWED greylist-couple");
DEFINE("SCP_SHOW_ALLADDR","Show only existing emails");

DEFINE("SCP_ON_WHITELIST","This entry \"+ip+\" still exists on the whitelist!");
DEFINE("SCP_ON_BLACkLIST","This entry \"+ip+\" still exists on the blacklist!");

DEFINE("SCP_EXPLAIN_IP","Each IP must be set <b>PER ROW</b>. Individual IP addresses may be given in dotted quad format <b>11.22.33.44</b> or <b>23.34.45.56</b>.<br>IP address ranges may be given in this way<b>11.22.33</b>");
DEFINE("SCP_EXPLAIN_RDNS","Each name must be set <b>PER ROW</b>. Individual names must be set like this <b>mail.example.com</b>.<br>The rDNS names may also use wildcards by beginning with dots (.). For example: <b>.example.com</b>");
DEFINE("SCP_EXPLAIN_SENDER","Each email must be set <b>PER ROW</b>. Individual names must be set like this <b>mail@example.com</b>.<br>The email names may also use wildcards by beginning with dots (.). For example: <b>.example.com</b>");
DEFINE("SCP_EXPLAIN_KEYWORDS","Each keyword must be set <b>PER ROW</b>. All keyword searches are <b>case-insensitive</b>");
DEFINE("SCP_EXPLAIN_HEADER","Each keyword must be set <b>PER ROW</b>. All keyword searches are <b>case-insensitive</b>");
DEFINE("SCP_EXPLAIN_EXAMPLES","Extended help with examples");
DEFINE("SCP_EXPLAIN_EXPIRES","To set an expire date of or more entries, mark them with the mouse");

DEFINE("VALUE_ACT","active");
DEFINE("VALUE_ACT_NOW","activate");
DEFINE("VALUE_YES","yes");
DEFINE("VALUE_DEACT","deactive");
DEFINE("VALUE_DEACT_NOW","deactivate");
DEFINE("VALUE_NO","no");

DEFINE("NO_FULLSCREEN","Disable fullscreen notice until end of the session");

DEFINE("SCP_ADMIN_RIGHTS","Rights of domain owner");

DEFINE("SCP_WHITELISTS","Whitelists");
DEFINE("SCP_WHITELISTS_GLOBAL","Gobal, serverwide whitelist");
DEFINE("SCP_WHITELISTS_CUSTOM","User/domain specific whitelist");
DEFINE("SCP_BLACKLISTS","Blacklists");
DEFINE("SCP_BLACKLISTS_GLOBAL","Gobal, serverwide blacklists");
DEFINE("SCP_BLACKLISTS_CUSTOM","User/domain specific blacklists");
DEFINE("SCP_SENDTIME","Timestamp");
DEFINE("SCP_STATISTIK","Statistics");
DEFINE("SCP_EXPORT","CSV export");
DEFINE("SCP_MAXRECIPIENTS","Limiting numbers of recipients per mail");

DEFINE("SCP_SETTINGS","Settings");
DEFINE("SCP_GREYLISTING","Greylisting for domain"); 	
DEFINE("SCP_DROPPED_MAILS","Mails dropped"); 	
DEFINE("SCP_ALL_MAILS","All requests");
DEFINE("SCP_DROPPED_MAILS_GL","Greylisting"); 	
DEFINE("SCP_DROPPED_MAILS_OTHER","Other method"); 	
DEFINE("SCP_LISTS_IP","IP White/Blacklists");
DEFINE("SCP_LISTS_SENDER","Sender White/Blacklists");  

DEFINE("SCP_ALLOWED","Mails passed");
DEFINE("SCP_LISTS_RDNS","RDNS White/Blacklists"); 
DEFINE("SCP_LISTS_RECIPIENT","Recipient White/Blacklists");
DEFINE("SCP_LISTS_KEYWORDS","IP/RDNS-Keyword blacklist");	
DEFINE("SCP_LISTS_HEADER","Header-Keyword blacklist");
DEFINE("SCP_SPAMRATE","Spamrate"); 	
DEFINE("SCP_SENDER","Sender"); 	
DEFINE("SCP_RECEIVE","Receipient"); 
DEFINE("SCP_REASON","Rating"); 	
DEFINE("SCP_ORG_IP","Sender IP"); 
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
DEFINE("UPDATE_DOWN","Downloading new version"); 	
DEFINE("UPDATE_CONFIG","Use existing config"); 	
DEFINE("UPDATE_INSTALL","Install new Version"); 	
DEFINE("UPDATE_DONE","Finish installation"); 	

DEFINE("UPDATE_SCP_OK","success");
DEFINE("UPDATE_SCP_NOK","failed");

DEFINE("UPDATE_SCP_SUCCESS","Update successfully done. Version {VER} installed");
DEFINE("UPDATE_SCP_FAILED","Update failed, please try again");

DEFINE("SCP_GREYLIST_SPAMDYKE_CONF","Settings for spamdyke");
DEFINE("SCP_GRAYLIST_LEVEL","Greylisting type");
DEFINE("SCP_GRAYLIST_LEVEL_ALWAYS_CREATE_DIR","Global, whole server");
DEFINE("SCP_GRAYLIST_LEVEL_ALWAYS","domain related");
DEFINE("SCP_GRAYLIST_LEVEL_NONE","completly deactivated");
DEFINE("SCP_GRAYLIST_MIN_SECS","Min. delay");
DEFINE("SCP_GRAYLIST_MAX_SECS","Invalidate graylist entry");
DEFINE("SCP_GRAYLIST_MIN_SECS_HELP","Require a graylist entry to be present for SECS seconds before allowing incoming mail.");
DEFINE("SCP_GRAYLIST_MAX_SECS_HELP","Invalidate graylist entries after they are SECS seconds old");
DEFINE("SCP_GRAYLIST_SECS","secs.");

DEFINE("SCP_ADMIN_GREYLISTING","Greylisting");
DEFINE("SCP_ADMIN_DNSBL_LISTS","DNSBL/DNSWL");
DEFINE("SCP_DNSBL_SPAMDYKE_CONF","DNSBL Blacklists for Spamdyke (to delete, just remove DNSBL name!) - Current lists -> <a href=\"https://wiki.apache.org/spamassassin/DnsBlocklists\" target=\"_blank\">http://spamlinks.net/filter-dnsbl-lists.htm#spamsource</a>");
DEFINE("SCP_DNSBL_SPAMDYKE_LIST","DNSBL name");
DEFINE("SCP_DNSBL_SPAMDYKE_ACTIVE","active");
DEFINE("SCP_DNSBL_SPAMDYKE_NEW","New list");
DEFINE("SCP_DNSWL_SPAMDYKE_CONF","DNSWL Whitelists for Spamdyke (to delete, just remove DNSWL name!) - Current lists -> <a href=\"https://wiki.apache.org/spamassassin/DnsBlocklists\" target=\"_blank\">http://spamlinks.net/filter-dnsbl-lists.htm#whitelists</a>");
DEFINE("SCP_DNSWL_SPAMDYKE_LIST","DNSWL name");
DEFINE("SCP_DNSWL_SPAMDYKE_ACTIVE","active");
DEFINE("SCP_DNSWL_SPAMDYKE_NEW","New list");
DEFINE("SCP_DNSRHSBL_SPAMDYKE_CONF","DNS RHSBL Whitelists for Spamdyke (to delete, just remove DNS RHSBL name!) - Current lists -> <a href=\"https://wiki.apache.org/spamassassin/DnsBlocklists\" target=\"_blank\">http://spamlinks.net/filter-dnsbl-lists.htm#domain</a>");
DEFINE("SCP_DNSRHSBL_SPAMDYKE_LIST","DNS RHSBL name");
DEFINE("SCP_DNSRHSBL_SPAMDYKE_ACTIVE","active");
DEFINE("SCP_DNSRHSBL_SPAMDYKE_NEW","New list");


DEFINE("SCP_ADMIN_ALLG_SETTINGS","Misc. setttings");
DEFINE("SCP_ALLG_SPAMDYKE_INFOS","Misc. spamdyke Informationen");
DEFINE("SCP_ALLG_SPAMDYKE_INFOS_V","Version");  
DEFINE("SCP_ACTIVE_SPAMDYKE_PROC","Active spamdyke processes");
DEFINE("SCP_KILL_ALL_SPAMDYKE","Kill all spamdyke processes");
DEFINE("SCP_ALLG_SPAMDYKE_CONF","Other spamdyke setttings");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER","Variant");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-MISSING-SENDER-MX","Blocked the connection because the sender's domain has no mail exchanger, making the sender address invalid.");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-EMPTY-RDNS","Blocked the connection because the remote server has no rDNS name at all");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-UNRESOLVABLE-RDNS","Blocked the connection because the remote server's rDNS name does not resolve. ");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-IP-IN-CC-RDNS","Blocked the connection because the remote server's IP address was found in the remote server's rDNS name and the remote server's rDNS name ends in a country code.");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-IDENTICAL-SENDER-RECIPIENT","Reject any connection where the sender's email address is the same as the recipient's email address. ");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_WRONG_VERSION","This parameter is only availabe at spamdyke version %s or greater!");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_CONFIG-DIR","Allow domain specific white/blacklisten");
DEFINE("SCP_GREETING_DELAY","Delay sending the opening greeting in order to wait for the sender to send data. If that happens, spamdyke will block the connection");
DEFINE("SCP_CONNECTION_TIMEOUT_SECS"," - An absolute time limit can be imposed on a connection, should be set to a very high value. Large (legitimate) messages can take a very long time to deliver, especially if the link is slow.");
DEFINE("SCP_IDLE_TIMEOUT_SECS"," - An idle time limit can be imposed on a connection, if no data is received within the given number of seconds, the connection is closed");

DEFINE("SCP_ALLG_SPAMDYKE_OTHER_RELAY-LEVEL_BLOCK-ALL","block all");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_RELAY-LEVEL_NORMAL","normal, authenticated user");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_RELAY-LEVEL_ALLOW-ALL","allow all");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_RELAY-LEVEL","SMTP relay level");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-RECIPIENT_NONE","deactivated");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-RECIPIENT_SAME-AS-SENDER","same sender as receipient");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-RECIPIENT_INVALID","invalid receipient");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-RECIPIENT_UNAVAILABLE","receipient unavailable");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-RECIPIENT","block e-mails with specific parameters");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-SENDER_NONE","deactivated");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-SENDER_NO-MX","unvalid MX record");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-SENDER_NOT-LOCAL","accept only local senders");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_REJECT-SENDER","block e-mail from specific senders");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_IP-RELAY-FILE","IP addresses who can use the server as a reley");
DEFINE("SCP_ALLG_SPAMDYKE_OTHER_RDNS-RELAY-FILE","Servers with thise rDNS can use this Server as a relay");
DEFINE("SCP_CONFIG_UPDATE","Spamdyke in version %s found. The SCP has modified the configuration angepasst and requires now spamdyke in this version!");


DEFINE("SCP_ADMIN_MULTI_RIGHTS","Multi domain rights");
DEFINE("SCP_MULTI_RIGHTS_SPAMDYKE_CONF","Set multi domain rights");
DEFINE("SCP_MULTI_RIGHTS_DOMAIN","Domain");
DEFINE("SCP_ADMIN_ALLNONE_SELECTION","activate/deactivate all");
DEFINE("SCP_ADMIN_TOGGLE_SELECTION","toggle selection");

DEFINE("SCP_GRAYLIST_MIN","min");
DEFINE("SCP_GREYLIST_DELAY","DELIVERY DELAY:");
DEFINE("SCP_GREYLIST_DELAY_AVERAGE","Average delivery delay through greylisting:");
DEFINE("SCP_GREYLIST_DELAY_MAX","Maximum delivery delay through greylisting:");
DEFINE("SCP_GREYLIST_DELAY_MIN","Minimum delivery delay through greylisting");

DEFINE("SCP_MYSQL_DAY_0","Sun");
DEFINE("SCP_MYSQL_DAY_1","Mon");
DEFINE("SCP_MYSQL_DAY_2","Tue");
DEFINE("SCP_MYSQL_DAY_3","Wed");
DEFINE("SCP_MYSQL_DAY_4","Thu");
DEFINE("SCP_MYSQL_DAY_5","Fri");
DEFINE("SCP_MYSQL_DAY_6","Sat");
DEFINE("SCP_MYSQL_VIEW_DATE_FORMAT","y-m-d");

DEFINE("SCP_TAKES_LONG_TO_GENERATE","The country detection per whois-query takes very long to check #IPS# IP%27s. The detection will be run approx. #MIN# min. Do you really want to continue?\\n\\nOtherwise the country detection will be checked with the RDNS-Domain, but this detection is very unaccurate!");
DEFINE("SCP_DAILYREPORT","daily report");
DEFINE("SCP_ADMIN_REPORTS","daily email reports");
DEFINE("SCP_REMOTE_ACCESS","remote access");
DEFINE("SCP_REMOTE_ACCESS_EXAMPLE","download example script for remote access");
DEFINE("SCP_REPORT_SPAMDYKE_SETTINGS","Manage daily email reports");
DEFINE("SCP_REPORT_SPAMDYKE_ONOFF","Activate daily email reports");
DEFINE("SCP_REPORT_SPAMDYKE_TO_ADMIN","Send daily,serverwide email report to admin");
DEFINE("SCP_REPORT_SPAMDYKE_TO_ADMIN_EMAIL","to following email");
DEFINE("SCP_REPORT_SPAMDYKE_TO_USER","Allow users to active a daily report for their domain");
DEFINE("SCP_REMOTE_SPAMDYKE_TO_USER","Allow users to establish a remote connection");
DEFINE("SCP_REMOTE_SPAMDYKE_TO_ADMIN","Allow admin remote connection with super token!");
DEFINE("SCP_RESELLER_RIGHTS","Reseller who can grant access to domain rights!");
DEFINE("SCP_STATS_PROZ","Percentage statistics");
DEFINE("SCP_STATS_TOP","TOP value statistics");
DEFINE("SCP_GENERATATE_TOP_STATS","The statistic is generating, please be patient!");
DEFINE("SCP_STAT_NO","Count");
DEFINE("SCP_STAT_IPS","IPs");
DEFINE("SCP_STAT_EMAIL","TOP spammend email");
DEFINE("SCP_STAT_DOMAIN","TOP spammend domain");
DEFINE("SCP_STAT_IP","TOP spamming IP");
DEFINE("SCP_STAT_COUNTRY","TOP spamming country");
DEFINE("SCP_STAT_STD","TOP spam time");
DEFINE("SCP_STAT_HOUR","O'clock");
DEFINE("SCP_STAT_GFX","Graphic");
DEFINE("SCP_STAT_COUNTRY_DISABLED","Country detection disabled!");
DEFINE("SCP_STATUS_GOES_TO","Will be send to");

DEFINE("SCP_SPAM_ABUSE_REPORT","Spam abuse report stetting");
DEFINE("SCP_SPAM_ABUSE_REPORT_SENDER","Sender");
DEFINE("SCP_SPAM_ABUSE_REPORT_TITEL","Subject");
DEFINE("SCP_SPAM_ABUSE_REPORT_TPL","Template");
DEFINE("SCP_SPAM_ABUSE_REPORT_SPAMMAIL","Data of the queried mail");
DEFINE("SCP_SPAM_ABUSE_REPORT_DO","Do you want to send an abuse mail to the receipient with the details");
DEFINE("SCP_SPAM_ABUSE_REPORT_DO_OK","The mail was sent, do you want to close the window now?");
DEFINE("SCP_SPAM_ABUSE_REPORT_DO_NOK","The mail was not sent\\n=================================\\n");
DEFINE("TOGGLE_FULLSCREEN","Fullscreen mode");
DEFINE("SCP_EVENT_HANDLER_RIGHTS_HEAD","Setup the Plesk Event-Handler during the domainadministration to include create default rights !");
DEFINE("SCP_EVENT_HANDLER_RIGHTS_CREATE","After domain-creation, create default rights");
DEFINE("SCP_EVENT_HANDLER_RIGHTS_UPDATE","After domain-update change default rights");
DEFINE("SCP_EVENT_HANDLER_RIGHTS_DELETE","After deleting a domain, remove default rights");
DEFINE("SCP_PLESK_MENU_INTEGRATION","Plesk menu integration");
DEFINE("SCP_PSA_ADMIN_LINK","Create a direct link to the administration and overview in the left menu.");
DEFINE("SCP_SYSTEM_INTEGRATION","SCP system integration");
DEFINE("SCP_SPAMDYKE_WATCHDOG","Systemwatchdog to check if spamdyke is embedded into to qmail queue. Checks every 30 minutes if spamdyke is activated and if not (e.g. after a Plesk update) it will be reactivated.<br> Writes a log to /var/log/spamdyke.conf.log");
DEFINE("SCP_MAIL_ANALYZE","Communication analysis");
DEFINE("SCP_NO_MAILBOX","The E-Mail <i>%s</i> has no mailbox");
?>