<?php

namespace MailBundle\Controller;
use MailBundle\Entity\Mail;
use MailBundle\Form\MailType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Swift_Message;
use Symfony\Component\HttpFoundation\Response;

class MailController extends Controller
{public function indexAction(Request $request, $mail1)
{
    $mail = new Mail();
    $form= $this->createForm(MailType::class, $mail);
    $form->handleRequest($request) ;
    if ($form->isValid()) {
        $message = \Swift_Message::newInstance()
            ->setSubject('Accusé de réception')
            ->setFrom($mail1)
            ->setTo($mail->getEmail())
            ->setBody(
                $this->renderView(
                    '@MailBundle/Default/email.html.twig',
                    array('nom' => $mail->getNom(), 'prenom'=>$mail->getPrenom())
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);
        return $this->redirect($this->generateUrl('my_app_mail_accuse'));
    }
    return $this->render('@Mail/Default/index.html.twig',
        array('form'=>$form->createView()));
}
    public function successAction(){
        return new Response("email envoyé avec succès, Merci de vérifier votre boite
mail.");
    }
}
