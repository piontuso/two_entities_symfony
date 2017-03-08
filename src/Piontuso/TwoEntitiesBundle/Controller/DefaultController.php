<?php

namespace Piontuso\TwoEntitiesBundle\Controller;

use Piontuso\TwoEntitiesBundle\Entity\Comments;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/comment")
     */
    public function commentAction(Request $request)
    {
        $comments = new Comments();
        $comments->setTitle('Comment title');
        $comments->setContent('Your comment ...');
        $form = $this->createFormBuilder($comments)
          ->add('title', TextType::class)
          ->add('content', TextType::class)
          ->add('save', SubmitType::class, array('label' => 'Add Comment'))
          ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $em = $this->getDoctrine()->getManager();
          $user = $this->getDoctrine()
            ->getRepository('TwoEntitiesBundle:User')
            ->find(1);
          if (is_object($user)) {
            $comments->setUser($user);
            $em->persist($comments);
            $em->flush();

            return new Response('Saved new comment with id ' . $comments->getId());
          }
        }

        return $this->render('TwoEntitiesBundle:Default:form.html.twig', array(
          'form' => $form->createView(),
        ));
    }
}
