<?php

namespace My\BotBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="My\BotBundle\Repository\SettingRepository")
 * @ORM\Table(name="settings")
 * @ORM\HasLifecycleCallbacks
 */
class Setting
{
	/**
	 * @ORM\Id @ORM\GeneratedValue(strategy="NONE")
	 * @ORM\Column(type="integer")
	 */
	private $id;


	/**
	 * @ORM\ManyToOne(targetEntity="Auction", inversedBy="settings")
	 */
	private $auction;

	/**
	 * @ORM\Column(type="string", length=50)
	 */
	private $name;


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
}