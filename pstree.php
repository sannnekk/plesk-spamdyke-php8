<?php
exec("ps aux | grep whois", $out);
echo count($out);
