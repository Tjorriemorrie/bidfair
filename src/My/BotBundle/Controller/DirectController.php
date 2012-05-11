<?php

namespace My\BotBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/direct")
 */
class DirectController extends Controller
{
    /**
     * @Route("/auction/{auctionId}")
     * @Template()
     */
    public function indexAction($auctionId)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$auction = $em->getRepository('MyBotBundle:Auction')->getOneHydrated($auctionId);

    	$session = $this->get('session');
    	$session->set('direct', $auctionId);

    	return array('auction' => $auction);
    }


    /**
     * @Route("/fetch")
     */
    public function fetchAction()
    {
    	$session = $this->get('session');
    	$auctionId = $session->get('direct', 0);

    	$scraper = $this->get('scraper');
    	$data = $scraper->run(array('auction_' . $auctionId => $auctionId));
    	//die(var_dump($data));

    	return new Response(json_encode(array('Auction' => $data[0]->Auction, 'LastBid' => $data[0]->LastBid, 'Product' => $data[0]->Product)));
    }


    /**
     * @Route("/bid")
     */
    public function bidAction()
    {
    	$session = $this->get('session');
    	$auctionId = $session->get('direct', 0);

    	sleep(1);

    	return new Response(json_encode('Slept 1'));
    }
}
