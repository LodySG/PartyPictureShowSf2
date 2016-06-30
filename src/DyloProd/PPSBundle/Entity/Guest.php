<?php

namespace DyloProd\PPSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Guest
 *
 * @ORM\Table(name="guest")
 * @ORM\Entity(repositoryClass="DyloProd\PPSBundle\Repository\GuestRepository")
 */
class Guest implements UserInterface, EquatableInterface, \Serializable
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
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="username", type="string", length=30, unique=true)
     */
    private $username;
    
    /**
     * @var string
     *
     * @ORM\Column(name="macaddress", type="string", length=30)
     */
    private $macaddress;
    
    /**
     * @var string
     *
     * @ORM\Column(name="roles", type="string", length=50)
     */
    private $roles;
    
    /**
     * @ORM\ManyToMany(targetEntity="Event")
     * @ORM\JoinTable(name="guests_events",
     *      joinColumns={@ORM\JoinColumn(name="guest_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")}
     *  )
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="Photo", mappedBy="guest")
     */
    private $photos;

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
     * Constructor
     */
    public function __construct()
    {
        $this->events = new \Doctrine\Common\Collections\ArrayCollection();
        $this->photos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add events
     *
     * @param \DyloProd\PPSBundle\Entity\Event $events
     * @return Guest
     */
    public function addEvent(\DyloProd\PPSBundle\Entity\Event $events)
    {
        $this->events[] = $events;

        return $this;
    }

    /**
     * Remove events
     *
     * @param \DyloProd\PPSBundle\Entity\Event $events
     */
    public function removeEvent(\DyloProd\PPSBundle\Entity\Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Add photos
     *
     * @param \DyloProd\PPSBundle\Entity\Photo $photos
     * @return Guest
     */
    public function addPhoto(\DyloProd\PPSBundle\Entity\Photo $photos)
    {
        $this->photos[] = $photos;

        return $this;
    }

    /**
     * Remove photos
     *
     * @param \DyloProd\PPSBundle\Entity\Photo $photos
     */
    public function removePhoto(\DyloProd\PPSBundle\Entity\Photo $photos)
    {
        $this->photos->removeElement($photos);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhotos()
    {
        return $this->photos;
    }
    
    /**
     * Set username
     *
     * @param string $username
     * @return Guest
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set macaddress
     *
     * @param string $macaddress
     * @return Guest
     */
    public function setMacaddress($macaddress)
    {
        $this->macaddress = $macaddress;

        return $this;
    }
    
    public function getMacaddress()
    {
        return $this->macaddress;
    }

    /**
     * Set roles
     *
     * @param string $roles
     * @return Guest
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }
    
    public function getRoles()
    {
        return array($this->roles);
    }

    public function getPassword()
    {
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
    }
    
    public function __toString()
    {
        return $this->username;
    }
    
    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof Guest) {
            return false;
        }

        if ($this->macaddress !== $user->getMacaddress()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }
    
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->macaddress
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->macaddress
        ) = unserialize($serialized);
    }
}
