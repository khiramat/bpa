<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * フォームの定義値 $property[$acs, $inventory, $invoce, $analytics]
 */


if (!function_exists('call_api')) {
	function call_api($body, $mvno_id)
	{
		$api_host = auth_api_host_name;
		log_message("info", "api_host : " . $api_host);
		
		$json_body = json_encode($body);
		log_message("debug", "json_body: " . $json_body);
		
		$options = array(
			// HEADER
			CURLOPT_HTTPHEADER     => array(
				"Host: " . $api_host,
				"Accept-Charset: utf-8",
				"Content-Type: application/json; charset=utf-8",
				"Content-Length: " . strlen($json_body),
				"Connection: close",
				"X-Acs-Mvno-Id: " . $mvno_id,
				"Authorization: " . get_token() // if failed, false will be returned;
			),
			// Method
			CURLOPT_POST           => true, // POST
			// body
			CURLOPT_POSTFIELDS     => $json_body,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_AUTOREFERER    => true,
			CURLOPT_HEADER         => true,
			CURLOPT_TIMEOUT        => 60 // タイムアウト 60s
		);
		
		$curl = curl_init(acs_api_base_url);
		curl_setopt_array($curl, $options);
		curl_setopt($curl, CURLINFO_HEADER_OUT, true);
		
		
		// APIの呼出し
		$result = curl_exec($curl);
		
		$info        = curl_getinfo($curl, CURLINFO_HEADER_OUT);
		$info_url    = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
		$header_body = curl_getinfo($curl);
		$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
		
		// close curl
		curl_close($curl);
		
		$header = substr($result, 0, $header_body["header_size"]);
		$body   = substr($result, $header_body["header_size"]);
		log_message('info', "info: " . $info);
		log_message('info', "info url: " . $info_url);
		log_message('info', "header: " . $header);
		log_message('info', "body: " . $body);
		
		
		$response['header'] = substr($result, 0, $header_size);
		$response['body']   = substr($result, $header_size);
		
		return $response;
	}
}

if (!function_exists('get_token')) {
	/**
	 * @desc get OAuth token
	 * @param string $client_id , $client_sceret, $audience, $grant_type
	 * @return string | false
	 */
	function get_token(): string
	{
		$client_id     = auth_api_client_id;
		$client_secret = auth_api_client_secret;
		$audience      = auth_api_audience;
		$grant_type    = "client_credentials";
		$dq                 = '"';
		$curlopt_postfields = '{"client_id":' . $dq . $client_id . $dq;
		$curlopt_postfields .= ', "client_secret":' . $dq . $client_secret . $dq;
		$curlopt_postfields .= ', "audience":' . $dq . $audience . $dq;
		$curlopt_postfields .= ', "grant_type":' . $dq . $grant_type . $dq . "}";
		
		$curl_auth0 = curl_init();
		curl_setopt_array(
			$curl_auth0, array(
				           CURLOPT_URL            => auth_api_base_url,
				           CURLOPT_RETURNTRANSFER => true,
				           CURLOPT_ENCODING       => "",
				           CURLOPT_MAXREDIRS      => 10,
				           CURLOPT_TIMEOUT        => 30,
				           CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
				           CURLOPT_CUSTOMREQUEST  => "POST",
				           CURLOPT_POSTFIELDS     => $curlopt_postfields,
				           CURLOPT_HTTPHEADER     => array("content-type: application/json"),
			           )
		);
		
		$response = curl_exec($curl_auth0);
		
		$err = curl_error($curl_auth0);
		curl_close($curl_auth0);
		
		if (!empty($err)) {
			log_message("error", sprintf('OAuth token : 取得エラー (%s)', $err));
			return false;
		}
		log_message("info", sprintf('OAuth token : 取得成功 (%s)', $response));
		
		$token_ary        = json_decode($response, true);
		$access_token     = $token_ary["access_token"];
		$token_type       = $token_ary["token_type"];
		$token_httpheader = $token_type . " " . $access_token;
		
		return $token_httpheader;
	}
}

/**
 * 改行
 */
if (!function_exists('br')) {
	function br($count = 1)
	{
		if (is_cli()) {
			return str_repeat(PHP_EOL, $count);
		} else {
			return str_repeat("<br/>", $count);
		}
		
	}
}


if (!function_exists('create_orderbango')) {
	/**
	 * オーダー番号の生成
	 * @param number $length
	 * @return string
	 */
	function create_orderbango($length = 15)
	{
		$str    = '0123456789';
		$result = '';
		for ($i = 0; $i < $length; $i++) {
			$result .= $str[rand(0, 9)];
		}
		return $result;
	}
}


if (!function_exists('convert_sousaservice')) {
	/**
	 * 割引プラン(string)からarrayに変更
	 * @param string $sousaservice
	 * @return array [service_code => tukehaiFlg]
	 */
	function convert_sousaservice(string $sousaservice = null): array
	{
		$result = array();
		
		while ($sousaservice and strlen($sousaservice) >= 6) {
			$result[substr($sousaservice, 0, 5)] = substr($sousaservice, 5, 1);
			$sousaservice                        = substr($sousaservice, 6);
		}
		
		return $result;
	}
}

if (!function_exists('str_empty')) {
	/**
	 * string is empty
	 * @param string $str
	 * @return bool
	 */
	function str_empty(string $str = null, bool $trim = false): bool
	{
		if ($str === null)
			return true;
		if ($trim)
			$str = trim($str);
		return $str === '' ? true : false;
	}
}

if (!function_exists('call_pcc_api')) {
	/**
	 * pccサーバーの開通APIの呼び出す
	 * @param array $data 送信ボディー
	 * @return array 実行結果
	 */
	function call_pcc_api(array $data): array
	{
		$data_json = json_encode($data);

		// オップション
		$options = array(
			// HEADER
			CURLOPT_HTTPHEADER     => array(
				"Content-Type: application/json",
			),
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => $data_json,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_AUTOREFERER    => true,
			CURLOPT_HEADER         => true,
			//                 CURLOPT_USERPWD => 'proapi:Today123', // テスト用
			CURLOPT_USERPWD        => 'rsapi:A29S2B',      // 商用
			CURLOPT_TIMEOUT        => 30                   // タイムアウト 30s
		);

		// TODO
		//$curl = curl_init('http://192.168.200.36/proweb/api/contract');
		$curl = curl_init(pcc_api_base_url);
		curl_setopt_array($curl, $options);

		// APIの呼び出す
		$result = curl_exec($curl);

		// 返却結果
		$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

		// close curl
		curl_close($curl);

		$response['header'] = substr($result, 0, $header_size);
		$response['body']   = substr($result, $header_size);

		return $response;
	}
}

if (!function_exists('call_pcc_api_2')) {
	/**
	 * pccサーバーの開通APIの呼び出す
	 * @param array $data 送信ボディー
	 * @return array 実行結果
	 */
	function call_pcc_api_2(array $data): array
	{
		$data_json = json_encode($data);

		// オップション
		$options = array(
			// HEADER
			CURLOPT_HTTPHEADER     => array(
				"Content-Type: application/json",
			),
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => $data_json,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_AUTOREFERER    => true,
			CURLOPT_HEADER         => true,
			//                 CURLOPT_USERPWD => 'proapi:Today123', // テスト用
			CURLOPT_USERPWD        => 'rsapi:A29S2B',      // 商用
			CURLOPT_TIMEOUT        => 30                   // タイムアウト 30s
		);

		// TODO
		//$curl = curl_init('http://192.168.200.36/proweb/api/contract');
		$curl = curl_init(pcc_api_base_url_2);
		curl_setopt_array($curl, $options);

		// APIの呼び出す
		$result = curl_exec($curl);

		// 返却結果
		$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

		// close curl
		curl_close($curl);

		$response['header'] = substr($result, 0, $header_size);
		$response['body']   = substr($result, $header_size);

		return $response;
	}
}

if (!function_exists('call_mvno_api')) {
	function call_mvno_api(array $body, string $mvno_id, string $path): array
	{
		$host = constant('mvno_base_domain_' . $mvno_id);
		if (!$host) {
			// 返却するMVNOがない場合処理を中止
			$api_logmsg = "MVNO への送信先情報がありません。BPA 内で処理を完了します。";
			log_message('info', $api_logmsg);
			$response['header'] = "OK";
			$response['body']   = "INNER BPA";
			return $response;
		} else {
			$api_host      = $host . constant('mvno_base_path_'. $mvno_id);
			$api_path      = $api_host . $path;
			$curl_send_url = "https://" . $api_path;
//      $curl_send_url = urlencode($curl_send_url);
			
			$json_body = json_encode($body);
			log_message("debug", $json_body);
			
			
			$options = array(
				// HEADER
				CURLOPT_HTTPHEADER     => array(
					"Host: " . $host,
					"Accept-Charset: utf-8",
					"Content-Type: application/json; charset=utf-8",
					"Content-Length: " . strlen($json_body),
					"Connection: close",
					"X-Acs-Mvno-Id: " . $mvno_id
				),
				// Method
				CURLOPT_POST           => true, // POST
				// URL
				//CURLOPT_URL            => $curl_send_url,
				// body
				CURLOPT_POSTFIELDS     => $json_body,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_AUTOREFERER    => true,
				CURLOPT_HEADER         => true,
				CURLOPT_TIMEOUT        => 30 // タイムアウト 30s
			);
			
			
			$curl = curl_init($curl_send_url);// baseURLを後ほど設定
			curl_setopt_array($curl, $options);
			curl_setopt($curl, CURLINFO_HEADER_OUT, true);
			
			// APIの呼出し
			$result = curl_exec($curl);
			
			$info        = curl_getinfo($curl, CURLINFO_HEADER_OUT);
			$info_url    = curl_getinfo($curl, CURLINFO_EFFECTIVE_URL);
			$header_body = curl_getinfo($curl);
			$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
			
			
			// close curl
			curl_close($curl);
			
			$header = substr($result, 0, $header_body["header_size"]);
			$body   = substr($result, $header_body["header_size"]);
			log_message('info', $info);
			log_message('info', $info_url);
			log_message('info', $header);
			log_message('info', $body);
			
			$response['header'] = substr($result, 0, $header_size);
			$response['body']   = substr($result, $header_size);
			
			return $response;
		}
	}
}

if (!function_exists('call_pcc_delete_api')) {
	/**
	 * pccサーバーの解約APIの呼び出す
	 * @param array $data 送信ボディー
	 * @return array 実行結果
	 */
	function call_pcc_delete_api(string $data): array
	{

		// オプション
		$options = array(
			// HEADER
			CURLOPT_HTTPHEADER     => array(
				"Content-Type: application/json",
			),
			CURLOPT_CUSTOMREQUEST  => 'DELETE',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_AUTOREFERER    => true,
			CURLOPT_HEADER         => true,
			//                 CURLOPT_USERPWD => 'proapi:Today123', // テスト用
			CURLOPT_USERPWD        => 'rsapi:A29S2B',      // 商用
			CURLOPT_TIMEOUT        => 30                   // タイムアウト 30s
		);

		// TODO
		//$curl = curl_init('http://192.168.200.36/proweb/api/contract');
		$curl = curl_init(pcc_api_base_url . "?login_name=" . $data);
		curl_setopt_array($curl, $options);

		// APIの呼び出す
		$result = curl_exec($curl);

		// 返却結果
		$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

		// close curl
		curl_close($curl);

		$response['header'] = substr($result, 0, $header_size);
		$response['body']   = substr($result, $header_size);

		return $response;
	}

	if (!function_exists('call_pcc_delete_api_2')){
		/**
		 * pccサーバーの解約APIの呼び出す
		 * @param array $data 送信ボディー
		 * @return array 実行結果
		 */
		function call_pcc_delete_api_2(string $data): array{

			// オプション
			$options = array(
				// HEADER
				CURLOPT_HTTPHEADER     => array(
					"Content-Type: application/json",
				),
				CURLOPT_CUSTOMREQUEST  => 'DELETE',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_AUTOREFERER    => true,
				CURLOPT_HEADER         => true,
				//                 CURLOPT_USERPWD => 'proapi:Today123', // テスト用
				CURLOPT_USERPWD        => 'rsapi:A29S2B',      // 商用
				CURLOPT_TIMEOUT        => 30                   // タイムアウト 30s
			);

			// TODO
			//$curl = curl_init('http://192.168.200.36/proweb/api/contract');
			$curl = curl_init(pcc_api_base_url_2."?login_name=".$data);
			curl_setopt_array($curl, $options);

			// APIの呼び出す
			$result = curl_exec($curl);

			// 返却結果
			$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

			// close curl
			curl_close($curl);

			$response['header'] = substr($result, 0, $header_size);
			$response['body'] = substr($result, $header_size);

			return $response;
		}
	}
}

