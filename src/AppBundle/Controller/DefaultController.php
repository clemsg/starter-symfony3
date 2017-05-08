<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('AppBundle::index.html.twig');
    }
    
    public function deleteFileAction(Request $request, $id){
        
        $em = $this->getDoctrine()->getManager();
        
        $file = $em->getRepository('AppBundle:FileUpload')->find($id);
        if(null === $file){
            return;
        }
        
        $em->remove($file);
        $em->flush();
        
        return $this->redirect($request->headers->get('referer'));
    }
}
