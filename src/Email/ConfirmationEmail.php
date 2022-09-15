<?php
namespace App\Email;

use App\Entity\User;

class ConfirmationEmail extends EmailBuilder
{
    public function sendTo(User $user):void 
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