<?php

namespace DyloProd\PPSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DyloProd\PPSBundle\Utils\ARPManager;
use DyloProd\PPSBundle\Entity\Guest;
use DyloProd\PPSBundle\Form\Type\GuestType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Form\FormError;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;


class SecurityController extends Controller
{

    /**
     * @Route("/login", name="arp_login")
     */
    public function loginAction(Request $request)
    {   
        $ar_view = array();
        
        $session = $request->getSession();
        $macaddress = "";
        $ip = $request->getClientIp();
        $em = $this->getDoctrine()->getManager();
        $guest_repo = $em->getRepository('DyloProdPPSBundle:Guest');
        
        while($macaddress == "")
        {
            $macaddress = ARPManager::getMacAddress($ip);
        }
        
        $guest_in_db = $guest_repo->findOneByMacaddress($macaddress);
        if($guest_in_db)
        {
            $this->manualLogin($guest_in_db, $request);
            return $this->redirectToRoute('pps_homepage');
        }
        
        $session->set('macaddress', $macaddress);
        
        $guest = new Guest();
        $form = $this->createForm("login", $guest);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $guest->setMacaddress($session->get('macaddress'));
            $guest->setRoles('ROLE_USER');
            
            $guest_with_username = $guest_repo->findOneByUsername($guest->getUsername());
            if($guest_with_username)
            {
                $form->get('username')->addError(new FormError("Je connais déjà ".$guest->getUsername()." et c'est pas toi !!!"));
            }
            else
            {
                $em->persist($guest);
                $em->flush();
                $this->manualLogin($guest, $request);            
                return $this->redirectToRoute('pps_homepage');
            }
        }
        
        $ar_view["form"] = $form->createView();
        
        return $this->render(
            'DyloProdPPSBundle:Security:login.html.twig',
            $ar_view
        );
    }
    
    /**
     * @Route("/update_user", name="arp_update_user")
     */
    public function change(Request $request)
    {
        $ar_view = array();
        $em = $this->getDoctrine()->getManager();
        $guest_repo = $em->getRepository('DyloProdPPSBundle:Guest');
        
        $guest = new Guest();
        $guest->setUsername($this->getUser()->getUsername());
        
        $form = $this->createForm("update_user", $guest);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $the_new_username = $guest->getUsername();
            
            $guest_with_username = $guest_repo->findOneByUsername($the_new_username);
            
            if($guest_with_username && ($the_new_username != $this->getUser()->getUsername()))
            {
                $form->get('username')->addError(new FormError("Je connais déjà ".$the_new_username." et c'est pas toi !!!"));
            }
            elseif ($guest_with_username && ($the_new_username == $this->getUser()->getUsername())) 
            {
                $form->get('username')->addError(new FormError("Si tu veux garder le même blaze, t'as rien à faire là !!!"));
            }
            else
            {
                $current_guest = $this->getUser();
                $current_guest->setUsername($the_new_username);
                $em->merge($current_guest);
                $em->flush();
                           
                return $this->redirectToRoute('pps_homepage');
            }
        }
        
        $ar_view["form"] = $form->createView();
        
        return $this->render(
            'DyloProdPPSBundle:Security:login.html.twig',
            $ar_view
        );
    }
    
    /**
     * @Route("/logout", name="arp_logout")
     */
    public function logout(Request $request)
    {
        $request->getSession()->invalidate();
    }
    
    public function manualLogin(Guest $guest, Request $request)
    {
        $token = new UsernamePasswordToken($guest, null, "main", $guest->getRoles());
        $this->get("security.context")->setToken($token); //now the user is logged in
            
        //now dispatch the login event
        $event = new InteractiveLoginEvent($request, $token);
        $this->get("event_dispatcher")->dispatch("security.interactive_login", $event); 
    }
}
