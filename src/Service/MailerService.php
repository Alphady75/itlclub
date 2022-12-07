<?php

namespace App\Service;

use App\Entity\Immobilier;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;

class MailerService
{

	private EmailVerifier $emailVerifier;

	public function __construct(private MailerInterface $mailer, EmailVerifier $emailVerifier)
	{
		$this->emailVerifier = $emailVerifier;
	}

	public function sendMail($from, $to, $sujet, $username, $template)
	{


		$email = (new TemplatedEmail())
			->from($from)
			->to($to)
			->subject($sujet)
			->htmlTemplate($template)
			->context([
				'username' => $username,
			]);

		return $this->mailer->send($email);
	}

	public function sendSolipacpail($solipac, $from, $to, $sujet)
	{

		$email = (new TemplatedEmail())
			->from($from)
			->to($to)
			->subject($sujet)
			->htmlTemplate('emails/_solipac_mail.html.twig')
			->context([
            'solipac' => $solipac,
            'societemail' => $from
			]);

		return $this->mailer->send($email);
	}
}
