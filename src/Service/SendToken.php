<?php
namespace App\Service;


use App\Entity\User;

class SendToken
{
    
    public const RENEW_MESSAGE = 'Renouveller votre mot de passe depuis: %s \n Pseudo: %s';
    public const ACCOUNT_MESSAGE = 'Veuillez activer votre compte snowtrick depuis ce lien: %s. \n Pseudo: %s \n Mot de passe: ******';
    
    private const ROOT_URL = 'http://localhost:8080';
    
    public function send(User $recipient, $subject, $href, $message) {
    
        $url = sprintf('<a href="%s%s">Cliquez-ici</a>',static::ROOT_URL, $href);
        $mail_body = sprintf($message, $url, $recipient->getUsername());
        $name = 'admin';
        $email = 'admin@snowtricks.com';
        $headers[] = sprintf('From: %s <%s>',$name, $email);
        $headers[] = 'Content-type: text/html; charset=utf-8';
        mail($recipient->getEmail(), $subject, $mail_body, implode("\r\n", $headers));
    }
}