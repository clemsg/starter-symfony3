<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use AppBundle\Form\RegistrationType;
use FOS\UserBundle\Event\FilterUserResponseEvent;

/**
 * Description of RegistrationController
 *
 * @author utilisateur
 */
class RegistrationController extends BaseController {
    
    public function registerAction(Request $request)
    {
        
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        //$formFactory = $this->get('app.form.registration');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->createForm(RegistrationType::class, null, array(
            'container' => $this->get('service_container')
        ));
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('app_user_show');
                $response = new RedirectResponse($url);
            }
            
            $session = $request->getSession();
            $session->getFlashBag()->add('success', $this->get('translator')->trans('registration.flash.user_created', array(), 'FOSUserBundle'));
            
            //$dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, $event);

            return $response;
        }

        return $this->render('AppBundle:Profile:add.html.twig', array(
            'form' => $form->createView(),
            'user' => $user
        ));
    }
    
}
