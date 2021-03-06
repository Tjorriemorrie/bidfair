<?php

namespace My\BotBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * AuctionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AuctionRepository extends EntityRepository
{
	/**
	 * Get hydrated array
	 */
	public function getHydrated($status)
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		if ($status == 'active') {
			$qb->select('a')->from('\MyBotBundle:Auction', 'a')
				->where('a.status = 1')
				->andWhere('a.endAt > ?2')->setParameter(2, date('Y-m-d H:i:s', strtotime('-10 minutes')))
				->orderBy('a.endAt', 'ASC')
				->setMaxResults(6);
		} else {
			$qb->select('a')->from('\MyBotBundle:Auction', 'a')
				->where('a.status = 0')
				->orderBy('a.endAt', 'DESC')
				->setMaxResults(2);
		}

		$info = $qb->getQuery()->getResult();
		$data = $this->hydrate($info);
		return $data;
	}


	/**
	 * Get One Hydrated
	 */
	public function getOneHydrated($auctionId)
	{
		$auction = $this->find($auctionId);
		$hydrated = $this->hydrate(array($auction));
		return $hydrated[0];
	}


	/**
	 * Hydrates auction for ko
	 */
	private function hydrate($info)
	{
		$data = array();
		foreach ($info as $item) {
			$data[] = array(
				'auctionId'	=> $item->getId(),
				'status'	=> ($item->getStatus() ? 'Open' : 'Closed'),
				'step'		=> $item->getStep(),
				'startAt'	=> $item->getStartAt()->format('Y-m-d H:i:s'),
				'endAt'		=> $item->getEndAt()->diff(new \DateTime())->format('%H:%I:%S'),

				'productId'		=> $item->getProduct()->getId(),
				'productName'	=> $item->getProduct()->getName(),
				'productRetail'	=> number_format($item->getProduct()->getRetail()),
				'auctionCount'	=> $item->getProduct()->getAuctions()->count(),
				'avgPrice'		=> number_format($item->getProduct()->getAverageBids() * $item->getStep(), 2),
				'stdDev'		=> number_format($item->getProduct()->getStandardDeviation() * $item->getStep(), 2),
				'startAt'		=> number_format($item->getProduct()->getAverageBids() * $item->getStep() + $item->getProduct()->getStandardDeviation() * $item->getStep(), 2),
				'bidsRemaining'	=> number_format( ($item->getProduct()->getAverageBids() * $item->getStep() + $item->getProduct()->getStandardDeviation() * $item->getStep()) - ($item->getBids()->count() ? $item->getBids()->last()->getPrice() : 0), 2) * 100,
				//'startAt'		=> number_format($item->getProduct()->getAverageBids() * $item->getStep(), 2),
				//'bidsRemaining'	=> number_format( ($item->getProduct()->getAverageBids() * $item->getStep()) - ($item->getBids()->count() ? $item->getBids()->last()->getPrice() : 0), 2) * 100,

				'price'		=> ($item->getBids()->count() ? number_format($item->getBids()->last()->getPrice(), 2) : '-.--'),
				'userId'	=> ($item->getBids()->count() ? $item->getBids()->last()->getUser()->getId() : '-'),
				'userName'	=> ($item->getBids()->count() ? $item->getBids()->last()->getUser()->getUsername() : '-'),
				'source'	=> ($item->getBids()->count() ? $item->getBids()->last()->getSource() : '-'),
			);
		}

		if (count($data) > 1) {
			usort($data, function($a, $b) {return ($a['auctionId'] >= $b['auctionId'] ? 1 : -1);});
		}

		return $data;
	}


	/**
	 * Get Auctions to scrape
	 */
	public function getScrapeIds()
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		$auctions = $qb->select('a')->from('\MyBotBundle:Auction', 'a')
			->where('a.status = 1')
			->getQuery()
			->getResult();

		if (!$auctions) {
			return $this->getNextScrapeIds();
		}

		$data = array();

		foreach ($auctions as $auction) {

			// skip open auctions but that has expired (only if up to date)
			if (!is_null($auction->getUpdatedAt()) && $auction->getUpdatedAt() > new \DateTime('-1 minute')) {
				if ($auction->getEndAt() < new \DateTime('-10 minutes')) {
					continue;
				}
			}

			$data['auction_' . $auction->getId()] = $auction->getId();
		}

		if (count($data) < 8) {
			$nextScrapeIds = $this->getNextScrapeIds();
			$data = array_merge($data, $nextScrapeIds);
		}

		return $data;
	}


	/**
	 * Get Next Scrape IDs
	 * @see getScrapeIds
	 */
	private function getNextScrapeIds()
	{
		$qb = $this->getEntityManager()->createQueryBuilder();
		$lastId = $qb->select('a.id')->from('\MyBotBundle:Auction', 'a')
			->orderBy('a.id', 'DESC')
			->setMaxResults(1)
			->getQuery()
			->getResult();

		if (!$lastId) {
			$lastId[0]['id'] = 1;
		}

		$data = array();
		while (count($data) < 20) {
			$data['auction_' . $lastId[0]['id']] = (string)$lastId[0]['id'];
			$lastId[0]['id']++;
		}

		return $data;
	}
}