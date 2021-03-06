<?php

namespace My\BotBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/fokus")
 */
class FokusController extends Controller
{
    /**
     * @Route("/auction/{auctionId}")
     * @Template()
     */
    public function indexAction($auctionId)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$auction = $em->getRepository('MyBotBundle:Auction')->find($auctionId);

    	$session = $this->get('session');
    	$session->set('fokus', $auctionId);

    	return array('auction' => $auction);
    }


    /**
     * @Route("/run")
     */
    public function runAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();

    	$session = $this->get('session');
    	$auctionId = $session->get('fokus', 0);

    	$scraper = $this->get('scraper');
    	$data = $scraper->run(array('auction_' . $auctionId => $auctionId));
    	$scraper->process($data, $em);
    	$em->flush();

    	$auction = $em->getRepository('MyBotBundle:Auction')->getOneHydrated($auctionId);

    	return new Response(json_encode($auction));
    }
}
