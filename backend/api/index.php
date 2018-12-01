<?php 
	include '../class/content.php';
	$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
	$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
	$content = new Content();
	$data = $content->get_articles($page,$limit);
	$res = [
		'code' => 200,
		'success' => $data,
		'currentPage' => $page,
		'count' => $limit
	];
	echo json_encode($res);

	
