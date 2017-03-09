<?php

namespace Piontuso\TwoEntitiesBundle\Controller;

use Piontuso\TwoEntitiesBundle\Entity\Comments;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class DefaultController extends Controller
{
    /**
     * Default constant user identifier.
     */
    const USER_ID = 1;

    /**
     * @Route("/comment")
     */
    public function commentAction(Request $request)
    {
        $comments = new Comments();
        $comments->setTitle('Comment title');
        $comments->setContent('Your comment ...');
        // Event listener.
        $event_listener = function (FormEvent $event) {
          // Get comment data.
          $comment = $event->getData();
          // Get service.
          $custom_mailer =  $this->get('two_entities.mailer');
          $custom_mailer->sendmail($comment->getTitle(), $comment->getContent());
        };
        // Create form.
        $form = $this->createFormBuilder($comments)
          ->add('title', TextType::class)
          ->add('content', TextareaType::class)
          ->add('save', SubmitType::class, array('label' => 'Add Comment'))
          ->addEventListener(FormEvents::POST_SUBMIT, $event_listener)
          ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $em = $this->getDoctrine()->getManager();
          $user = $this->getDoctrine()
            ->getRepository('TwoEntitiesBundle:User')
            ->find(self::USER_ID);
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
