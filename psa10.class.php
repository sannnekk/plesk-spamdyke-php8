<?php
if(function_exists("apc_clear_cache")) @apc_clear_cache();

require("../../smb/application/controllers/WebController.php");

class psa10 extends WebController{

     function __construct(){
#             parent::init();
             }

     public function grab(){
             print_r($_SESSION);
	           }
      
	     

}
?>
