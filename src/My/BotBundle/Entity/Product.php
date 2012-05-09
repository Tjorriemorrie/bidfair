<?php

namespace My\BotBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="My\BotBundle\Repository\ProductRepository")
 * @ORM\Table(name="products")
 * @ORM\HasLifecycleCallbacks
 */
class Product
{
	/**
	 * @ORM\Id @ORM\GeneratedValue(strategy="NONE")
	 * @ORM\Column(type="integer")
	 */
	private $id;


	/**
	 * @ORM\Column(type="string", length=250, nullable=true)
	 */
	private $name;

	/**
	 * @ORM\Column(type="decimal", scale=2, precision=4)
	 */
	private $retail;

	/**
	 * @ORM\OneToMany(targetEntity="Auction", mappedBy="product")
	 */
	private $auctions;


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
		$this->auctions = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set retail
     *
     * @param decimal $retail
     */
    public function setRetail($retail)
    {
        $this->retail = $retail;
    }

    /**
     * Get retail
     *
     * @return decimal 
     */
    public function getRetail()
    {
        return $this->retail;
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
     * Add auctions
     *
     * @param My\BotBundle\Entity\Auction $auctions
     */
    public function addAuction(\My\BotBundle\Entity\Auction $auctions)
    {
        $this->auctions[] = $auctions;
    }

    /**
     * Get auctions
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getAuctions()
    {
        return $this->auctions;
    }
}