<?php

namespace CarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CarImage
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class CarImage
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


     /**
     * @var \CarBundle\Entity\Car
     * @ORM\ManyToOne(targetEntity="Car", inversedBy="image")
     * @ORM\JoinColumn(name="car_id", referencedColumnName="id")
     */
    private $car;

    /**
     * @var \DateTime
     * @ORM\Column(name="dateTime", type="datetime")
     */
    private $dateTime;

    /**
     * @var boolean
     * @ORM\Column(name="isAvatar", type="boolean")
     */
    private $isAvatar;

    /**
     * @var string
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(name="file", type="string", length=255)
     */
    private $file;

    public function __construct()
    {

    }

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateTime
     *
     * @param \DateTime $dateTime
     * @return CarCost
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    /**
     * Get dateTime
     *
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return CarCost
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get Car
     * @return CarBundle\Entity\Car
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * Set Car
     * @param CarBundle\Entity\Car $car
     * @return CarCost
     */
    public function setCar(Car $car = null)
    {
        $this->car = $car;
        return $this;
	}

    /**
     * @return boolean
     */
    public function isIsAvatar()
    {
        return $this->isAvatar;
    }

    /**
     * @param boolean $isAvatar
     * @return CarImage
     */
    public function setIsAvatar($isAvatar)
    {
        $this->isAvatar = $isAvatar;
        return $this;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file
     * @return CarImage
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

}

