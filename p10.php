<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=7" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="forgery_protection_token" id="forgery_protection_token" content="76f47e05a00f07d175ece375158a5c60" />

	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="icon" href="/favicon.ico" type="image/ico" />







	<link href="/skins/default/css/common/base.css?1285317136" media="screen" rel="stylesheet" type="text/css" />
	<link href="/skins/default/css/common/btns.css?1285317136" media="screen" rel="stylesheet" type="text/css" />

	<link href="/skins/default/css/customer/main.css?1285317136" media="screen" rel="stylesheet" type="text/css" />
	<link href="/skins/default/css/customer/custom.css?1285317136" media="screen" rel="stylesheet" type="text/css" />
	<!--[if lte IE 7]> <link href="/skins/default/css/common/ie.css?1285317136" media="screen" rel="stylesheet" type="text/css" /><![endif]-->
	<!--[if IE 8]> <link href="/skins/default/css/common/ie8.css?1285317136" media="screen" rel="stylesheet" type="text/css" /><![endif]-->
	<script type="text/javascript" src="/smb/externals/prototype.js?1286172799"></script>
	<script type="text/javascript" src="/javascript/jsw.js?1286172771"></script>
	<script type="text/javascript" src="/smb/scripts/smb.js?1286172799"></script>
	<script type="text/javascript" src="/smb/scripts/admin-home.js?1286172799"></script>
	<title>Parallels Plesk Panel 10.0.0</title>
	<script type="text/javascript">
		Jsw.baseUrl = '/smb';
		Jsw.skinUrl = '/skins/default';
		Jsw.showErrorDetails = false;
	</script>

</head>

<body class="admin-home">


	<div id="pathbar-wrapper"></div>
	<?php print_r($this); ?>
	<script type="text/javascript">
		//<![CDATA[
		Jsw.onReady(function() {
			new Jsw.Pathbar({
				id: 'pathbar',
				cls: 'pathbar clearfix',
				renderTo: 'pathbar-wrapper',
				items: <?php echo $this->pathbarItems; ?>
			});
		});
		//]]>
	</script>
</body>

</html> ~