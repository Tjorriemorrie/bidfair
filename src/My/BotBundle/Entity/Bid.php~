<?php

namespace My\BotBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="My\BotBundle\Repository\BidRepository")
 * @ORM\Table(name="bids")
 * @ORM\HasLifecycleCallbacks
 */
class Bid
{
	/**
	 * @ORM\Id @ORM\GeneratedValue(strategy="NONE")
	 * @ORM\Column(type="bigint")
	 */
	private $id;


	/**
	 * @ORM\ManyToOne(targetEntity="Auction", inversedBy="bids", fetch="EAGER")
	 */
	private $auction;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $placedAt;

	/**
	 * @ORM\Column(type="decimal", scale=2)
	 */
	private $price;

	/**
	 * @ORM\Column(type="string", length=50)
	 */
	private $source;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="bids", fetch="EAGER")
	 */
	private $user;


	/** @ORM\Column(type="datetime") */
	private $createdAt;

	/** @ORM\Column(type="datetime", nullable=true) */
	private $updatedAt;

	////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////

	/** Construct */
	public function __construct()
	{
		$this->createdAt = new \DateTime();
	}

	/** @ORM\PreUpdate */
	public function preUpdate()
	{
		$this->setUpdatedAt(new \DateTime());
	}

	////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////

    /**
     * Set id
     *
     * @param bigint $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return bigint 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set placedAt
     *
     * @param datetime $placedAt
     */
    public function setPlacedAt($placedAt)
    {
        $this->placedAt = $placedAt;
    }

    /**
     * Get placedAt
     *
     * @return datetime 
     */
    public function getPlacedAt()
    {
        return $this->placedAt;
    }

    /**
     * Set price
     *
     * @param decimal $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Get price
     *
     * @return decimal 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set source
     *
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * Get source
     *
     * @return string 
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return datetime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set auction
     *
     * @param My\BotBundle\Entity\Auction $auction
     */
    public function setAuction(\My\BotBundle\Entity\Auction $auction)
    {
        $this->auction = $auction;
    }

    /**
     * Get auction
     *
     * @return My\BotBundle\Entity\Auction 
     */
    public function getAuction()
    {
        return $this->auction;
    }

    /**
     * Set user
     *
     * @param My\BotBundle\Entity\User $user
     */
    public function setUser(\My\BotBundle\Entity\User $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return My\BotBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}