<?php

namespace DyloProd\PPSBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DyloProd\PPSBundle\Entity\Photo;
use DyloProd\PPSBundle\Entity\Event;
use DyloProd\PPSBundle\Form\Type\PhotoType;
use DyloProd\PPSBundle\Form\Type\EventType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="pps_homepage")
     */
    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        
        $session->getFlashBag()->add('error', 'Does Not Exist');
        
        return $this->render('DyloProdPPSBundle:Default:index.html.twig');
    }
    
    /**
     * @Route("/user/{user_id}", name="pps_user")
     */
    public function userAction()
    {
        
        return $this->render('DyloProdPPSBundle:Default:user.html.twig');
    }
    
    /**
     * @Route("/show", name="pps_show")
     */
    public function showAction()
    {
        $ar_view = array();
        
        $em = $this->getDoctrine()->getManager();
        $event_repo = $em->getRepository('DyloProdPPSBundle:Event');
        
        $current_event = $event_repo->findCurrentEvent();
        //dump($current_event->getPhotos());
        //die();
        
        $ar_view["event"] = $current_event;
        return $this->render('DyloProdPPSBundle:Default:show.html.twig', $ar_view);
    }
    
    /**
     * @Route("/add_photo", name="pps_add_photo")
     */
    public function addphotoAction(Request $request)
    {
        $ar_view = array();
        
        $em = $this->getDoctrine()->getManager();
        
        $sentences = $this->getParameter("photo_sentences");
        $photo_sentence  = $sentences[array_rand($sentences)];
        $ar_view["photo_sentence"] = $photo_sentence;
        
        $photo = new Photo();
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $photo->setGuest($this->getUser());
            
            $em->persist($photo);
            $em->flush();
            
            $session = $request->getSession();
            $session->getFlashBag()->add('message', 'Cimer !!!'); 
            return $this->redirectToRoute('pps_homepage');
        }
        
        $ar_view["form"] = $form->createView();
        return $this->render('DyloProdPPSBundle:Default:photo.html.twig',$ar_view);
    }
    
    /**
     * @Route("/set_event", name="pps_set_event")
     */
    public function seteventAction(Request $request)
    {
        $ar_view = array();
        $em = $this->getDoctrine()->getManager();
        $event_repo = $em->getRepository('DyloProdPPSBundle:Event');
        
        $current_event = $event_repo->findCurrentEvent();
        //dump($current_event);
        //die();
        
        $form = $this->createForm(EventType::class, $current_event);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) 
        {
            $em->merge($current_event);
            $em->flush();
            $session = $request->getSession();
            $session->getFlashBag()->add("message", "Evénement '".$current_event->getTitre()."' paramétré"); 
            return $this->redirectToRoute('pps_homepage');
        }
        
        $ar_view["form"] = $form->createView();
        return $this->render("DyloProdPPSBundle:Default:event.html.twig",$ar_view);
    }
}
