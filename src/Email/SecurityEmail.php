<?php
namespace App\Email;

use App\Entity\User;
class SecurityEmail extends EmailBuilder
{
    public function sendCode2faTo(User $user):void 
    {
        $this->from(parent::NO_REPLY, parent::NAME)
            ->to($user->getEmail())
            ->subject('Voici votre code d\'authentification')
            ->text($user->getCode2FA())
            ->send()
            ;
    }

    public function sendConfirmationEmailTo(User $user):void 
    {
        $link = parent::HOST . $this->urlGenerator->generate('security_emailConfirmation', [
            'id' => $user->getId(),
            'token' => $user->getToken()
        ]);
        $this->from(parent::NO_REPLY, parent::NAME)
            ->to($user->getEmail())
            ->subject('Bienvenue chez maison.fr !')
            ->html('email/confirmation_email.html.twig', [
                'link' => $link
            ])
            ->send()
            ;
    }
}