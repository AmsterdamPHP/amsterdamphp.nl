<?php
namespace AmsterdamPHP\Bundle\SponsorBundle\Form\Choice;

use Carbon\Carbon;

class MeetingDateChoiceList
{
    const KEY_FORMAT = 'U';

    public static $closest;

    /**
     * @return array
     */
    public static function getChoices()
    {
        $start = Carbon::parse('-18 month');
        $result = [];

        while ($start->lte(Carbon::parse('+1 year'))) {

            /** @var Carbon $date */
            $date = $start->firstOfMonth()->copy()->modify('third Thursday');
            $result[(string) $date->format(self::KEY_FORMAT)] = $date->formatLocalized('%Y - %B, %d');
            $start = $start->addMonthNoOverflow(1);

            self::storeClosest($date->format(self::KEY_FORMAT));

        }
        return $result;
    }

    /**
     * @param $timestamp
     */
    public static function storeClosest($timestamp)
    {
        $now = Carbon::now()->format(self::KEY_FORMAT);
        $currentDistance = abs($now - self::$closest);
        $newDistance = abs($now - $timestamp);

        if ($newDistance < $currentDistance) {
            self::$closest = $timestamp;
        }
    }

    /**
     * @return \DateTime
     */
    public static function getClosestDateTime()
    {
        return \DateTime::createFromFormat(self::KEY_FORMAT, self::$closest);
    }
}


