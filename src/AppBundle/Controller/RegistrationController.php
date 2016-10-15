<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\UserFormType;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     * @Method({"GET", "POST"})
     */
    public function registerAction(Request $request)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        $user = new User();
        $form = $this->createForm(UserFormType::class, $user);

        $formHandler = $this->get('registration_form_handler');

        if ($formHandler->handle($form, $request)) {
            $this->addFlash(
                'success',
                'Confirmation mail was sent to you. Please confirm your account, than log in.'
            );

            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'auth/register.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/register/confirm", name="user_registration_confirmation")
     * @Method("GET")
     */
    public function registerConfirmationAction(Request $request)
    {
        $confirmationCode = $request->query->get('confirmationCode');
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findOneBy(array('confirmationCode' => $confirmationCode));

        if (null === $user) {
            throw new NotFoundHttpException(
                sprintf('The user with confirmation code "%s" does not exist', $confirmationCode)
            );
        }

        $this->get('user_manager')->enableUser($user);
        $this->addFlash(
            'success',
            'Your account is enabled, please log in.'
        );

        return $this->redirectToRoute('login');
    }
}
