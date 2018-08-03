<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        // replace this example code with whatever you need hola
        //return $this->render('default/index.html.twig', [
            //'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        //]);
        
        //$em = $this->getDoctrine()->getManager();
	//$pages = $em->getRepository('AppBundle:Page')->findAll();
        //return $this->render('AppBundle:Default:index.html.twig',array('pages'=>$pages));
                
        //return new Response('<html><body>Este es un buen principio</body></html>');
        return $this->render('@App/Default/index.html.twig');
    }
    
    /**
     *
     * @Route("/show/{id}", name="showcase_display")
     */
    public function displayAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('AppBundle:Showcases')->find($id);
        //return $this->render('AppBundle:Default:display.html.twig',array('page'=>$page));
        
        if (!$page) {
        throw $this->createNotFoundException(
            'No showcase found for id '.$id
        );
        }
        // ... do something, like pass the $product object into a template
        // 
        // En este metodo se han de cargar todas las combinaciones del showcase en 
        // el localStorage de javascript e ir a la página principal del menu.
        // 
        //return new Response('<html><body>Probando ruta: '.$id.'</body></html>');
        return new Response(var_dump($page));

    }
}