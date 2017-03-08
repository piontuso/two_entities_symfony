<?php

namespace Piontuso\TwoEntitiesBundle\Controller;

use Piontuso\TwoEntitiesBundle\Entity\Comments;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
{
    /**
     * @Route("/comment")
     */
    public function commentAction()
    {
        $comments = new Comments();
        $comments->setTitle('Test title');
        $comments->setContent('Test comment ...');
        $comments->setUser(1);
        $form = $this->createFormBuilder($comments)
          ->add('title', TextType::class)
          ->add('content', TextType::class)
          ->add('save', SubmitType::class, array('label' => 'Add Comment'))
          ->getForm();

        return $this->render('TwoEntitiesBundle:Default:form.html.twig', array(
          'form' => $form->createView(),
        ));
    }
}
