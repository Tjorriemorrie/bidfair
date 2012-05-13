<?php

namespace My\BotBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
    	return array();
    }


    /**
     * @Route("/run")
     * Scrape Bidfair then return updated auctions
     */
    public function runAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();

    	$scraper = $this->get('scraper');
    	$scrapeIds = $em->getRepository('MyBotBundle:Auction')->getScrapeIds();

    	$data = $scraper->run($scrapeIds);
    	$scraper->process($data, $em);
    	$em->flush();

    	$active = $em->getRepository('MyBotBundle:Auction')->getHydrated('active');
    	$closed = $em->getRepository('MyBotBundle:Auction')->getHydrated('closed');
    	$auctions = array_merge($active, $closed);
    	
    	return new Response(json_encode($auctions));
    }


    /**
     * @Route("/bidfair")
     */
    public function bidfairAction()
    {
    	$bidfair = $this->get('bidfair');
    	$session = $this->get('session');

    	$loggedInAt = $session->get('loggedInAt');

    	$loggedIn = $bidfair->isLoggedIn();
    	if (!$loggedIn) {
    		$loggedInAt = null;
	    	$bidfair->logIn();
    	}

    	if (!$loggedInAt) {
    		$loggedInAt = new \DateTime();
    		$session->set('loggedInAt', $loggedInAt);
    	}

    	$timeAgo = $bidfair->convertTimeAgo($loggedInAt->getTimestamp());

    	return new Response(json_encode($timeAgo));
    }


    /**
     * @Route("/cleanup")
     */
    public function cleanupAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$stats = $this->get('stats');
    	$removed = $stats->cleanUp($em);
    	
    	return new Response(json_encode('Cleaned up ' . $removed));
    }
    
    
    /**
     * @Route("/days")
     * @Template()
     */
    public function daysAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$auctions = $em->getRepository('MyBotBundle:Auction')->findAll();
    	
    	$stats = $this->get('stats');
    	$days = $stats->getBestDays($auctions);
    	
    	return array('days' => $days);
    }
}
