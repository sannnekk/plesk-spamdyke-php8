<?php
if (php_sapi_name() !== 'cli') die("Can only be run from shell...");

function isEmptyDir($dir){ 
     return (($files = @scandir($dir)) && count($files) <= 2); 
}

$GLOBALS["delEntries"] = array();

function deleteEntries($f){
	 if ($handle = @opendir($f)) {
             while (false !== ($entry = readdir($handle))) {
                    if(!stristr($entry,"~~"))continue;
                    $fEntries = @file($f.'/'.$entry);
                    $entryClean = str_replace("~~","/",$entry); 
                    $fSrc = file_get_contents($entryClean);
                    for($i=0;$i<count($fEntries);$i++){
                        $fSrc=str_replace($fEntries[$i],"",$fSrc);
                        $GLOBALS["delEntries"][$entryClean][] = $fEntries[$i];
                        }
                    file_put_contents($entryClean,$fSrc);
                    unlink($f.'/'.$entry);
                    
             }

         closedir($handle);
         }
}

$currentDate = time();
$scandir = $_SERVER["PWD"].'/EXPIRES';

if ($handle = opendir($scandir)) {
    while (false !== ($entry = readdir($handle))) {
           if(isEmptyDir($scandir."/".$entry)) @rmdir($scandir."/".$entry);
           $folderdate=strtotime($entry. '23:59:59');
           if($currentDate>$folderdate) deleteEntries($scandir.'/'.$entry);
           }

    closedir($handle);
}

$reports = unserialize(file_get_contents("reports.txt"));

$email = $reports["admin"][0];

if(count($GLOBALS["delEntries"])>0 && !empty($email)){
$Header = "";
$Header.= "From: <$email>\n";	
$Header.= "Reply-To: <$email>\n"; 
$Header.= "Return-path: <$email>\n";
$Header.= "Mailer: SCP Mailer\n"; 
$text = "";
while(list($k,$v)=each($GLOBALS["delEntries"])){
      $text.=$k."\n";
      $text.="- ".implode("- ",$v);
      $text.="++++++++++++++++++++++++++++++++++++++++++++++++++\n";
      }
mail($email,"SCP White/Blacklist expired and deleted entries",$text,$Header);
}

 






?>