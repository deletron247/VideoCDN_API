<?php

function search($data)
{
	$apiToken = 'ANCg5OcIUSP5vKaIv9wQETxeebxM2U5D';
	// Собираем API запрос
	$url      = 'https://videocdn.tv/api/short?api_token=' . $apiToken . '&' . http_build_query($data);
	// Делаем запрос
	$ch       = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	$results = curl_exec($ch);
	curl_close($ch);
	// Расшифровываем JSON ответ
	$json = json_decode(($results) , true);
	// Упрощаем путь к данным
	$json = $json['data']['0'];
	// Смотрим получили ли мы ссылку на плеер
	if ($json['iframe_src'] != '')
	{
		// Заносим их в более понятный масив для дальнейшей работы с ним
		$response['title']          = $json['title'];
		$response['orig_title']     = $json['orig_title'];
		$response['kinopoisk_id']   = $json['kp_id'];
		$response['released']       = $json['year'];
		$response['type']           = $json['type'];
		$response['src']            = $json['iframe_src'];
	}
	else
	{
		$response['status']         = 'fail';
		$response['title']          = '';
		$response['orig_title']     = '';
		$response['kinopoisk_id']   = '';
		$response['released']       = '';
		$response['type']           = '';
		$response['src']            = '';
	}
	return json_encode($response);
}

// Какими данннымы будем делать запрос
$search = [
    //'id'            => '8'              // Внутренний ID
    //'title'         => 'Резидент'       // По названию
    //'kinopoisk_id'  => '1013917'        // По id кинопоиска
    'imdb_id'       => 'tt10068916'     // По id imdb
];
//Делаем запрос и расшифровываем данные
$data   = json_decode(search($search) , true);
// Выводим полученый масив данных и показываем плеер
print_r($data);
echo '<iframe src="' . $data['src'] . '" width="100%" height="420" frameborder="0" allowfullscreen></iframe>';

