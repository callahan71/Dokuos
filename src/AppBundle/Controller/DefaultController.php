<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        // replace this example code with whatever you need
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
     * @Route("/show/{token}", name="showcase_display")
	 * @Security("has_role('ROLE_SHOW')")
     */
    public function displayAction($token)
    {
        $em = $this->getDoctrine()->getManager();
        $show = $em->getRepository('AppBundle:Showcases')->findOneBy(['token' => $token]);
        //return $this->render('AppBundle:Default:display.html.twig',array('page'=>$page));
        
        if (!$show) {
        throw $this->createNotFoundException(
            'No showcase found for token '.$token
        );
        } else {
			$user = $em->getRepository('AppBundle:Users')->findOneBy(['id' => $show->getUserid()]);
			$models = $em->getRepository('AppBundle:Models')->findUserModelsOrderedByCategory($user);
			$combinations = $em->getRepository('AppBundle:Combinations')->findShowcaseCombinationsOrderedByKey($show);
			$categories = $em->getRepository('AppBundle:Models')->findUserModelsCategories($user);
			
			$arrayCombinations = array();
			foreach ($combinations as $combination){
				$arrayCombinations[$combination->getKeychar()]=$combination->getMaterialid()->getRef();
			}
		}
        // ... do something, like pass the $product object into a template
        // 
        // En este metodo se han de cargar todas las combinaciones del showcase en 
        // el localStorage de javascript e ir a la página principal del menu.
        // 
        //return new Response('<html><body>Probando ruta: '.$id.'</body></html>');
        //return new Response(var_dump($show,$models,$combinations));
		return $this->render('main/index.html.twig', array(
			'show' => $show,
            'models' => $models,
			'combinations' => $combinations,
			'array' => $arrayCombinations,
			'categories' => $categories
        ));

    }
}
