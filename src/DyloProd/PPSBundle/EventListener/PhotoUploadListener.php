<?php 

namespace DyloProd\PPSBundle\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use DyloProd\PPSBundle\Entity\Photo;
use DyloProd\PPSBundle\Utils\FileUploader;
use Doctrine\ORM\EntityManager;

class PhotoUploadListener
{
    private $uploader;
    
    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        
        if ($entity instanceof Photo) {
            $event_repo = $args->getEntityManager()->getRepository('DyloProdPPSBundle:Event');
            $current_event = $event_repo->findCurrentEvent();
            $entity->setEvent($current_event);
        }
        
        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }
    
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $fileName = $entity->getPhoto();

        $entity->setPhoto(new File($this->targetPath.'/'.$fileName));
    }
    
    private function uploadFile($entity)
    {
        // upload only works for Photo entities
        if (!$entity instanceof Photo) {
            return;
        }

        $file = $entity->getPhoto();

        // only upload new files
        if (!$file instanceof UploadedFile) {
            return;
        }

        $fileName = $this->uploader->upload($file);
        $entity->setPhoto($fileName);
    }
}