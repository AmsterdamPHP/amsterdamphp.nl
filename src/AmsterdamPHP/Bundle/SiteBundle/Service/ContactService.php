<?php

namespace AmsterdamPHP\Bundle\SiteBundle\Service;

use Ornicar\AkismetBundle\Akismet\AkismetInterface;

/**
 * Class ContactService
 *
 * @package AmsterdamPHP\Bundle\SiteBundle\Service
 */
class ContactService
{

    /**
     * @var \Ornicar\AkismetBundle\Akismet\AkismetInterface
     */
    protected $akismet;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @param AkismetInterface $akismet
     * @param \Swift_Mailer $mailer
     */
    public function __construct(AkismetInterface $akismet, \Swift_Mailer $mailer)
    {
        $this->akismet = $akismet;
        $this->mailer  = $mailer;
    }

    /**
     * @param $data
     * @return bool
     */
    public function sendContact($data)
    {

        //Validate Spam
        $isSpam = $this->akismet->isSpam([
                'comment_author' => $data['name'],
                'comment_author_email' => $data['email'],
                'comment_content' => $data['subject'] . ' ' . $data['message'],
            ]);

        if ($isSpam) {
            return false;
        }

        $message = \Swift_Message::newInstance()
            ->setSubject("[AmsPHP Contact] ". $data['subject'])
            ->setFrom($data['email'], $data['name'])
            ->setReplyTo($data['email'], $data['name'])
            ->setTo('contact@amsterdamphp.nl')
            ->setBody($this->buildBody($data['message']));

        return ($this->mailer->send($message) > 0);
    }

    /**
     * @param $content
     * @return string
     */
    protected function buildBody($content)
    {
        $body = <<<EOD
This message was sent using the contact form on amsterdamphp.nl
----------------------

$content

----------------------
End of content.
EOD;
        return $body;
    }
}
