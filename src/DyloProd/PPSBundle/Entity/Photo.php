<?php

namespace DyloProd\PPSBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Photo
 *
 * @ORM\Table(name="photo")
 * @ORM\Entity(repositoryClass="DyloProd\PPSBundle\Repository\PhotoRepository")
 */
class Photo
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Euh ... si t'as rien à envoyer !!! Sors de là !!")
     * @Assert\File(mimeTypes={ "image/jpeg", "image/pjpeg", "image/png" }, maxSize = "8M")
     */
     private $photo;
     
    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="photos")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $event;
    
    /**
     * @ORM\ManyToOne(targetEntity="Guest", inversedBy="photos")
     * @ORM\JoinColumn(name="guest_id", referencedColumnName="id")
     */
    private $guest;
    
    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updated;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Set event
     *
     * @param \DyloProd\PPSBundle\Entity\Event $event
     * @return Photo
     */
    public function setEvent(\DyloProd\PPSBundle\Entity\Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \DyloProd\PPSBundle\Entity\Event 
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set guest
     *
     * @param \DyloProd\PPSBundle\Entity\Guest $guest
     * @return Photo
     */
    public function setGuest(\DyloProd\PPSBundle\Entity\Guest $guest = null)
    {
        $this->guest = $guest;

        return $this;
    }

    /**
     * Get guest
     *
     * @return \DyloProd\PPSBundle\Entity\Guest 
     */
    public function getGuest()
    {
        return $this->guest;
    }
    
    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }
    
}
