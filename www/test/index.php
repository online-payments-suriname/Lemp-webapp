 <head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="ajax.js"></script>
</head>
<?php
	if(!empty($_POST['test'])){
		require('ajax.php');
		$data= new data();
		$data->createTable('testtable');
		$data->insertData('testtable','value',$_POST['test']);
	}
?>
<body>
	<form method='POST'>
		<input name='test' type='text'>
		<input type='submit'>
	</form>
	<button class='button' value='select'>show latest data</button>
	<button class='button' value='destroy'>clear</button>
	<div id='data'></div>
<?php define('SOME_FILE', str_replace('//', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(__FILE__) . '/index.php')));echo SOME_FILE; ?>
</body>
