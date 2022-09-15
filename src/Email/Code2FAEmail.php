<?php
namespace App\Email;

use App\Entity\User;
class Code2FAEmail extends EmailBuilder
{
    public function sendTo(User $user):void 
    {
        $this->from(parent::NO_REPLY, parent::NAME)
            ->to($user->getEmail())
            ->subject('Voici votre code d\'authentification')
            ->text($user->getCode2FA())
            ->send()
            ;
    }
}