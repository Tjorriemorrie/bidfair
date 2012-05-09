<?php

namespace My\BotBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="My\BotBundle\Repository\AuctionRepository")
 * @ORM\Table(name="auctions")
 * @ORM\HasLifecycleCallbacks
 */
class Auction
{
	/**
	 * @ORM\Id @ORM\GeneratedValue(strategy="NONE")
	 * @ORM\Column(type="integer")
	 */
	private $id;


	/**
	 * @ORM\Column(type="boolean")
	 */
	private $status;

	/**
	 * @ORM\Column(type="string", length=50)
	 */
	private $link;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $startAt;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $endAt;

	/**
	 * @ORM\Column(type="decimal", scale=2, precision=4)
	 */
	private $step;

	/**
	 * @ORM\ManyToOne(targetEntity="Product", inversedBy="auctions", fetch="LAZY")
	 */
	private $product;

	/**
	 * @ORM\OneToMany(targetEntity="Bid", mappedBy="auction", cascade={"remove"}, orphanRemoval=true)
	 */
	private $bids;

	/**
	 * @ORM\OneToMany(targetEntity="Setting", mappedBy="auctions", cascade={"remove"}, orphanRemoval=true)
	 */
	private $settings;


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
		$this->bids = new ArrayCollection();
		$this->settings = new ArrayCollection();
		$this->status = 1;
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
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param boolean $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set link
     *
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set startAt
     *
     * @param datetime $startAt
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;
    }

    /**
     * Get startAt
     *
     * @return datetime
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * Set endAt
     *
     * @param datetime $endAt
     */
    public function setEndAt($endAt)
    {
        $this->endAt = $endAt;
    }

    /**
     * Get endAt
     *
     * @return datetime
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * Set step
     *
     * @param decimal $step
     */
    public function setStep($step)
    {
        $this->step = $step;
    }

    /**
     * Get step
     *
     * @return decimal
     */
    public function getStep()
    {
        return $this->step;
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
     * Set modifiedAt
     *
     * @param datetime $modifiedAt
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;
    }

    /**
     * Get modifiedAt
     *
     * @return datetime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * Set product
     *
     * @param My\BotBundle\Entity\Product $product
     */
    public function setProduct(\My\BotBundle\Entity\Product $product)
    {
        $this->product = $product;
    }

    /**
     * Get product
     *
     * @return My\BotBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Add bids
     *
     * @param My\BotBundle\Entity\Bid $bids
     */
    public function addBid(\My\BotBundle\Entity\Bid $bids)
    {
        $this->bids[] = $bids;
    }

    /**
     * Get bids
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getBids()
    {
        return $this->bids;
    }

    /**
     * Add settings
     *
     * @param My\BotBundle\Entity\Setting $settings
     */
    public function addSetting(\My\BotBundle\Entity\Setting $settings)
    {
        $this->settings[] = $settings;
    }

    /**
     * Get settings
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getSettings()
    {
        return $this->settings;
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
}