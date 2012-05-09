<?php

namespace My\BotBundle\Service;

class ScraperService
{
	private $url_bidfair_update = 'http://www.bidfair.co.za/getstatus.php';
	// products
	private $products = array(
		496 => 'JVC DVD Digital Theater System',
		415 => 'BidFair Deluxe Sports Pack',
		409 => 'Beach Luxurious Sun Proof Canopies',
		403 => 'Boogie Board',
		299 => 'Nikon Travelite',
		239 => 'TaylorMade Tour Preferred MB Irons',
		186 => 'PLATINUM Voucher 80 FREE Bids',
		155 => 'GOLD Voucher 40 FREE Bids',
		 78 => 'GOLD Voucher 40 FREE Bids',
	);
	
	
	public function run($scrapeIds)
	{
		list($junk, $ms) = explode(' ', microtime());
		$ms .= rand(100, 999);
		$query = http_build_query(array('ms' => $ms));
		$url = implode('?', array($this->url_bidfair_update, $query));

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, count($scrapeIds));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $scrapeIds);

		//execute post
		$result = curl_exec($ch);

		//close connection
		curl_close($ch);

		$result = json_decode($result);
		//die(var_dump($result));

		return $result;
	}


	/**
	 * Process scraped data
	 */
	public function process($data, $em)
	{
		if (!$data) {
			return;
		}

		//die(var_dump($data));
		foreach ($data as $item) {

			// if no auction id, it does not exist
			if (!isset($item->Auction->id)) {
				continue;
			}

			// auction
			$auction = $em->getRepository('\MyBotBundle:Auction')->find($item->Auction->id);
			if (!$auction) {
				$auction = new \My\BotBundle\Entity\Auction();
				$auction->setId($item->Auction->id);
				$auction->setStartAt(new \DateTime($item->Auction->start_time));
				$auction->setStep(substr($item->Auction->price_increment, 3));
				$auction->setLink($item->Auction->element);
				$em->persist($auction);

				// product
				$product = $em->getRepository('\MyBotBundle:Product')->find($item->Product->id);
				if (!$product) {
					$product = new \My\BotBundle\Entity\Product();
					$product->setId($item->Product->id);
					if (array_key_exists($item->Product->id, $this->products)) {
						$product->setName($this->products[$item->Product->id]);
					} else {
						$product->setName('???');
					}
					$product->setRetail((float)$item->Product->rrp);
					$em->persist($product);
				}
				$auction->setProduct($product);
			}

			// still open?
			if ($auction->getStatus() == $item->Auction->closed) {
				$auction->setStatus(!$item->Auction->closed);
			}

			// time left?
			if ($item->Auction->time_left == 1 && $item->Auction->end_time_string == '00:00:00' && new \DateTime($item->Auction->closes_on) < new \DateTime()) {
				$endAt = new \DateTime('-11 minutes');
			} else {
				$endAt = new \DateTime($item->Auction->time_left . ' seconds');
			}
			$auction->setEndAt($endAt);

			// bid
			if (isset($item->LastBid->id)) {
				$bid = $em->getRepository('\MyBotBundle:Bid')->find($item->LastBid->id);
				if (!$bid) {
					$bid = new \My\BotBundle\Entity\Bid();
					$bid->setId($item->LastBid->id);
					$bid->setAuction($auction);
					$bid->setSource($item->LastBid->description);
					$bid->setPlacedAt(new \DateTime($item->LastBid->created));
					$bid->setPrice(substr($item->Auction->price, 3));
					$em->persist($bid);

					// user
					$user = $em->getRepository('\MyBotBundle:User')->find($item->LastBid->user_id);
					if (!$user) {
						$user = new \My\BotBundle\Entity\User();
						$user->setId($item->LastBid->user_id);
						$user->setUsername($item->LastBid->username);
						$em->persist($user);
					}
					$bid->setUser($user);
				}

				if ($auction->getBids()->count() && $auction->getBids()->last()->getId() != $bid->getId()) {
					$auction->addBid($bid);
				}
			}

		} // end loop
	}
}


// array
// 0 =>
// object(stdClass)[252]

// public 'Product' =>
// object(stdClass)[253]
// public 'id' => string '112' (length=3)
// public 'rrp' => int 35000
// public 'fixed' => string '0' (length=1)
// public 'fixed_price' => int 0
// public 'buy_now' => string '0.00' (length=4)
// public 'is_seat' => string '0' (length=1)
// public 'no_of_seat' => string '0' (length=1)

// public 'LastBid' =>
// object(stdClass)[254]
// public 'id' => string '6975142' (length=7)
// public 'user_id' => string '453' (length=3)
// public 'description' => string 'Single Bid' (length=10)
// public 'created' => string '2012-05-06 12:07:31' (length=19)
// public 'username' => string 'Rubix' (length=5)

// public 'Auction' =>
// object(stdClass)[249]
// public 'id' => string '7259' (length=4)
// public 'product_id' => string '112' (length=3)
// public 'start_time' => string '2012-05-06 12:00:04' (length=19)
// public 'end_time' => int 1336381210
// public 'seat_end_time' => string '2012-05-04 09:49:49' (length=19)
// public 'price' => string 'ZAR0.01' (length=7)
// public 'peak_only' => string '0' (length=1)
// public 'closed' => string '0' (length=1)
// public 'seat_status' => string '0' (length=1)
// public 'price_increment' => string 'ZAR0.01' (length=7)
// public 'closes_on' => string 'May 7th, 11:00:10' (length=17)
// public 'element' => string 'auction_7259' (length=12)
// public 'buy_it_now' => string '0' (length=1)
// public 'serverTimestamp' => int 1336310015
// public 'serverTimeString' => string 'May 6th, 03:13:35' (length=17)
// public 'time_left' => int 71195
// public 'isPeakNow' => int 0
// public 'end_time_string' => string '19:46:35' (length=8)