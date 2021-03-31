<?php
function videocdn_get($query, $type) {
	$apiToken = 'ApiKey';
	// Собираем API запрос
	$url = 'https://videocdn.tv/api/' . $type . '?api_token=' . $apiToken . '&' . http_build_query($query);
	// Делаем запрос
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	$results = curl_exec($ch);
	curl_close($ch);
	// Расшифровываем JSON ответ
	$json = json_decode(($results), true);
	if (isset($json['data']) && $json['data'][0] != NULL) {
		foreach ($json['data'] as $json) {
			if ($json['ru_title'] == $query['query']) {
				// Заносим их в более понятный масив для дальнейшей работы с ним
				$response['status'] = 'true';
				$response['data'] = $json;
				break;
			}
			else {
				$response['status'] = 'false';
			}
		}
	}
	else {
		$response['status'] = 'false';
	}
	return json_encode($response);
}

function videocdn($title, $type, $year = '') {
	if ($type == 'tv') {
		//Проверка для сериалов
		$json_tv_series = json_decode(videocdn_get(['query' => $title, 'year' => $year], 'tv-series'), true);
		$json_tv_show = json_decode(videocdn_get(['query' => $title, 'year' => $year], 'show-tv-series'), true);
		$json_tv_anime = json_decode(videocdn_get(['query' => $title, 'year' => $year], 'anime-tv-series'), true);
		if ($json_tv_series['status'] == 'true') {
			$response = $json_tv_series;
		}
		elseif ($json_tv_show['status'] == 'true') {
			$response = $json_tv_show;
		}
		elseif ($json_tv_anime['status'] == 'true') {
			$response = $json_tv_anime;
		}
	}
	else {
		//Проверка для фильмов
		$json_movie = json_decode(videocdn_get(['query' => $title, 'year' => $year], 'movies'), true);
		$json_anime = json_decode(videocdn_get(['query' => $title, 'year' => $year], 'animes'), true);
		if ($json_movie['status'] == 'true') {
			$response = $json_movie;
		}
		elseif ($json_anime['status'] == 'true') {
			$response = $json_anime;
		}
	}
	return json_encode($response);
}

//print_r (videocdn('Евангелион', 'tv', 1995));
print_r (videocdn('Призрак в доспехах', 'movie', 1995));

