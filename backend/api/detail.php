<?php 
	include '../class/content.php';

	if(isset($_GET['l'])) {
		$link = $_GET['l'];
		$api = new Content();
		$data = $api->get_detail_articles($link);
		$res = [
			'code' => 200,
			'success' => $data                    
		];
		echo json_encode($res,JSON_UNESCAPED_SLASHES);

	}
	

	
