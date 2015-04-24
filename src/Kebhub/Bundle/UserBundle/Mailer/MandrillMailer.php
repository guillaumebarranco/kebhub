<?php
/*
 * This file is adapted from the Wrep\FOSUserBundleMandrillMailer
 *
 * (c) Rick Pastoor <rick@wrep.nl>
 * Edited by: James Moughon <jmoughon@gmail.com>
 *
 */
namespace Kebhub\Bundle\UserBundle\Mailer;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\MailerInterface;
use Hip\MandrillBundle\Message;
use Hip\MandrillBundle\Dispatcher;

/**
 * Mailer implementation for the FOSUserBundle
 *
 * @author Mathijs Kadijk <mathijs@wrep.nl>
 */
class MandrillMailer implements MailerInterface
{
    protected $router;
    protected $templating;
    protected $dispatcher;
    protected $message;
    protected $parameters;
    /**
     * Constructor
     *
     * @param RouterInterface   $router
     * @param EngineInterface   $templating
     * @param Dispatcher        $dispatcher
     * @param Message           $message
     * @param array             $parameters
     */
    public function __construct($dispatcher, UrlGeneratorInterface  $router, EngineInterface $templating)
    {
        $this->templating = $templating;
        $this->dispatcher = $dispatcher;
        $this->router = $router;

    }
    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        
        $url = $this->router->generate('fos_user_security_login', array(), true);
        $rendered = $this->templating->render('::mail/confirm.html.twig', array(
            'user' => $user,
            'confirmationUrl' =>  $url
        ));
        $this->sendEmailMessage($rendered,"Confirm account creating" ,"kebhub@gmail.com", $user->getEmail());
    }
    public function sendResettingEmailMessage(UserInterface $user)
    {

        $url = $this->router->generate('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), true);
        $rendered = $this->templating->render('::mail/reset.html.twig', array(
            'user'  => $user,
            'confirmationUrl' => $url
        ));
        $this->sendEmailMessage($rendered,"Reset Password" ,"kebhub@gmail.com",$user->getEmail());
    }
    /**
     * This will configure the message and send it.
     *
     * @param string $renderedTemplate
     * @param string $toEmail
     */
    protected function sendEmailMessage($body, $subject, $fromEmail, $toEmail)
    {
    	
        $message = new Message();

        $message
            ->setFromEmail($fromEmail)
            ->setFromName("Le boss")
            ->addTo($toEmail)
            ->setSubject($subject)
            ->setHtml($body)
            ->setSubaccount('Kebhub');

        $result = $this->dispatcher->send($message);
    }
}