<?php

namespace AmsterdamPHP\Bundle\MeetupBundle\Command;

use CL\Slack\Payload\ChatPostMessagePayload;
use Codeliner\ArrayReader\ArrayReader;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateJoindinPlaceholderCommand
 */
class CreateJoindinPlaceholderCommand extends ContainerAwareCommand
{
    /**
     * @var \AmsterdamPHP\Bundle\MeetupBundle\Service\EventService
     */
    protected $meetup;

    /**
     * @var \CL\Slack\Transport\ApiClientInterface
     */
    protected $slack;

    /**
     * @var \GuzzleHttp\Command\Guzzle\GuzzleClient
     */
    protected $joindin;

    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('amsphp:joindin:create-placeholder')
            ->setDescription('Creates the monthly meeting event at joind.in');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->meetup = $this->getContainer()->get('meetup.events');
        $this->slack = $this->getContainer()->get('cl_slack.api_client');
        $this->joindin = $this->getContainer()->get('joindin.api')->getService(new \Joindin\Api\Description\Events());
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $meetings = $this->meetup->getCurrentMonthlyMeeting();

        if ($meetings->count() > 1) {
            $this->sendSlackMsg('Too many monthly meetings found, I was confused, sorry.', 'construction');
            $output->writeln("<error>Too many monthly meetings</error>");
            return;
        }

        $meeting = new ArrayReader($meetings->current());

        $time = $meeting->integerValue('time') / 1000;
        $date = \DateTime::createFromFormat('U', $time);

        $output->writeln(sprintf(
            "<comment>=> Current meeting found: </comment><info>%s</info>",
            $meeting->stringValue('name')
        ));

        $event = [
            'name'         => sprintf('AmsterdamPHP Monthly Meeting - %s', strftime('%B/%Y', $date->format('U')) ),
            'description'  => 'Every month AmsterdamPHP holds a monthly meeting with a speaker a social event. You can find more info and signup at http://meetup.amsterdamphp.nl',
            'start_date'   => $date->format('Y-m-d'),
            'end_date'     => $date->format('Y-m-d'),
            'tz_continent' => 'Europe',
            'tz_place'     => 'Amsterdam',
            'href'         => $meeting->stringValue('event_url'),
            'location'     => $meeting->stringValue('venue.name'),
            'tags'         => 'php, amsterdam'
        ];

        $result = $this->joindin->submit($event);

        $output->writeln(sprintf("<comment>=> Joind.in event created, awaiting approval</comment>"));

        $this->sendSlackMsg(sprintf(
            'Joind.in event created succesfully, its awaiting approval. Find it here: https://joind.in/search/q:%s',
            urlencode($event['name'])
        ));

        $output->writeln("<comment>=> Payload sent to Slack.</comment>");
    }

    /**
     * @param string $msg
     */
    protected function sendSlackMsg($msg, $icon = 'date')
    {
        $payload  = new ChatPostMessagePayload();
        $payload->setChannel('#monthly-meetings');   // Channel names must begin with a hash-sign '#'
        $payload->setText($msg);  // also supports Slack formatting
        $payload->setUsername('joind.in bot');      // can be anything you want
        $payload->setIconEmoji($icon); // check out emoji.list-payload for a list of available emojis

        $this->slack->send($payload);
    }
}

