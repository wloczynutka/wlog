<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Image
{

    /**
     * @var integer
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="storeFolder", type="string", length=100, nullable=true)
     */
    private $storeFolder;

    /**
     * @var string
     * @ORM\Column(name="fileExtension", type="string", length=4, nullable=true)
     */
    private $fileExtension;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="Place", inversedBy="images")
     * @ORM\JoinColumn(name="placeId", referencedColumnName="id")
     */
    private $placeId;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="takenDateTime", type="datetime", nullable=true)
     */
    private $takenDateTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="uploadDateTime", type="datetime", nullable=true)
     */
    private $uploadDateTime;


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
     * Set placeId
     *
     * @param Place $placeId
     *
     * @return Image
     */
    public function setPlaceId(Place $placeId)
    {
        $this->placeId = $placeId;

        return $this;
    }

    /**
     * Get placeId
     *
     * @return Place
     */
    public function getPlaceId()
    {
        return $this->placeId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Image
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
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
     * Set description
     *
     * @param string $description
     *
     * @return Image
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set takenDateTime
     *
     * @param \DateTime $takenDateTime
     *
     * @return Image
     */
    public function setTakenDateTime($takenDateTime)
    {
        $this->takenDateTime = $takenDateTime;
        return $this;
    }

    /**
     * Get takenDateTime
     *
     * @return \DateTime
     */
    public function getTakenDateTime()
    {
        return $this->takenDateTime;
    }

    /**
     * Set uploadDateTime
     *
     * @param \DateTime $uploadDateTime
     * @return Image
     */
    public function setUploadDateTime($uploadDateTime)
    {
        $this->uploadDateTime = $uploadDateTime;
        return $this;
    }

    /**
     * Get uploadDateTime
     * @return \DateTime
     */
    public function getUploadDateTime()
    {
        return $this->uploadDateTime;
    }

    public function getStoreFolder()
    {
        return $this->storeFolder;
    }

    public function setStoreFolder($storeFolder)
    {
        $this->storeFolder = $storeFolder;
        return $this;
    }
    
    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    public function setFileExtension($fileExtension)
    {
        $this->fileExtension = $fileExtension;
        return $this;
    }



}

