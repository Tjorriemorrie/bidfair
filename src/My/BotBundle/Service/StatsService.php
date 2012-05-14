<?php

namespace My\BotBundle\Service;

/**
 * Stats
 */
class StatsService
{
	public function getBestDays($auctions)
	{
		$days;
		foreach ($auctions as $auction) {
			if (!$auction->getStatus() || !$auction->getBids()->count()) {
				continue;
			}
			
			$retail = $auction->getProduct()->getRetail();
			$sale = $auction->getBids()->last()->getPrice();
			$ratio = $retail / $sale;
			$day = date('D', $auction->getBids()->last()->getPlacedAt()->getTimestamp());
			
			if ($ratio > 1000) continue;
			$days[$day][] = $ratio;
		}
		
		$calcs = array();
		foreach ($days as $day => $ratios) {
			if (!count($ratios)) {
				$calcs[$day] = '-';
			} else {
				$calcs[$day] = round(array_sum($ratios) / count($ratios));
			}
		}
		
		return http_build_query($calcs);
	}
	
	
	/**
	 * Clean up Auctions
	 * 
	 * - remove if stuck on open
	 * - remove if never opened (no bids)
	 * - remove unnecessary bids
	 */
	public function cleanUp($em)
	{
		$removed = 0;
		$auctions = $em->getRepository('\MyBotBundle:Auction')->findAll();
		
		foreach ($auctions as $auction) {

			// stuck
			if ($auction->getStatus() && $auction->getEndAt() < new \DateTime('-2 days') && $auction->getCreatedAt() < new \DateTime('-2 days')) {
				$em->remove($auction);
				$removed++;
				break;
			}
			
			// never opened
			if ($auction->getBids()->count() < 1) {
				$em->remove($auction);
				$removed++;
				break;
			}
			
			// bids
			if ($auction->getBids()->count() > 1) {
				$lastId = $auction->getBids()->last()->getId();
				foreach ($auction->getBids() as $bid) {
					if ($bid->getId() !== $lastId) {
						$em->remove($bid);
						$removed++;
					}
				}
			}
		}
			
		$em->flush();
		$em->clear();
		return $removed;
	}
}