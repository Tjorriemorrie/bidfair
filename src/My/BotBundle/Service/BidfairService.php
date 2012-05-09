<?php

namespace My\BotBundle\Service;

/**
 * Bidfair
 */
class BidfairService
{
	/**
	 * Are we logged in?
	 * @return true if logged in
	 */
	public function isLoggedIn()
	{
		$url = 'http://www.bidfair.co.za';
		$page = $this->makeRequest($url);

		if (strpos($page, 'Logout') === false) {
			return false;
		} else {
			return true;
		}
	}


	/**
	 * Log in
	 */
	public function logIn()
	{
		$url = 'http://www.bidfair.co.za/users/login';

		$fields = array(
			'data[User][username]'		=> 'bot',
			'data[User][password]'		=> 'cyis2cyis',
			'data[User][remember_me]'	=> '0',
		);

		$page = $this->makeRequest($url, $fields);
		return $page;
	}


	/**
	 * Makes Curl request
	 *
	 */
	private function makeRequest($url, $postFields = null)
	{
		$cookie = realpath(__DIR__) . DIRECTORY_SEPARATOR . 'cookie.txt';
		//die(var_dump($cookie));

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $url);
		// 		curl_setopt($ch, CURLOPT_REFERER, 'http://www.bidfair.co.za/');
		// 		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		// 		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		// 		//curl_setopt($ch, CURLOPT_NOBODY, FALSE);

		// 		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		// 		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		// 		curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

		//curl_setopt($ch, CURLOPT_COOKIESESSION, FALSE);
		//curl_setopt($ch, CURLOPT_COOKIE, '');
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);

		if (!is_null($postFields)) {
			//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded"));
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
		}

		//execute post
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);

		//die($result);
		return $result;
	}

   	/**
   	 * Time ago
   	 */
   	public function convertTimeAgo($tm, $rcs = 0)
   	{
   		$cur_tm = time(); $dif = $cur_tm-$tm;
   		$pds = array('second','minute','hour','day','week','month','year','decade');
   		$lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
   		for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);

   		$no = floor($no); if($no <> 1) $pds[$v] .='s'; $x=sprintf("%d %s ",$no,$pds[$v]);
   		if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
   		return $x;
   	}
}