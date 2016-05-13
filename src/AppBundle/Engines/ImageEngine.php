<?php

namespace AppBundle\Engines;

use \AppBundle\Entity\Image;
use \Eventviva\ImageResize;

/**
 * Description of ImageEngine
 *
 * @author Łza
 */
class ImageEngine
{
    private $storeFolder = 'uploaded_images';
    
    /**
     * variable set in constructor
     * @var string
     */
    private $destinationPath;

    /**
     * image miniature dimension in pixels
     * @var integer
     */
    private $miniatureLongestDimension = 300;

    private $maxDimension = 1400;

    private $entityManager;

    public function __construct($entityManager)
    {
        $this->destinationPath = __DIR__. '/../../../web/'.  $this->storeFolder.'/';
        $this->entityManager = $entityManager;
    }


    public function uploadImage($place)
    {
        if (!empty($_FILES)) {
            $image = new Image();
            $image->setPlaceId($place);
//            $em = $this->getDoctrine()->getManager();
            $em = $this->entityManager;
            $em->persist($image);
            $tempFile = $_FILES['file']['tmp_name'];
            $pathParts = pathinfo($_FILES["file"]["name"]);
            $fileExtension = strtolower($pathParts['extension']);
            $targetFile = $this->destinationPath. $image->getId().'.'.$fileExtension;
            $targetFileTmp = $this->destinationPath. $image->getId().'-tmp.'.$fileExtension;
            $miniatureFile = $this->destinationPath. $image->getId().'-min.'.$fileExtension;
            $exifData = exif_read_data($tempFile);
            $image
                ->setUploadDateTime(new \DateTime())
                ->setStoreFolder($this->storeFolder)
                ->setFileExtension($fileExtension)
                ->setName($place->getName());

            if(isset($exifData['DateTimeOriginal'])){
               $image->setTakenDateTime(new \DateTime($exifData['DateTimeOriginal']));
            }
            /* miniature */
            move_uploaded_file($tempFile,$targetFileTmp);
            $imageResizer = new ImageResize($targetFileTmp);
            $imageResizer->resizeToBestFit(300, 200);
            $imageResizer->save($miniatureFile);

            /* image */
            $imageResizer->resizeToBestFit(901, 400);
            $imageResizer->save($targetFile);

            unlink($targetFileTmp);
            
//            dump($m);

            $em->flush();
        } else {
            $targetFile = 'error';
        }
        return $targetFile;
    }
}
