<?php

namespace AppBundle\Provider;


class DateProvider
{
    public function calculateBeginAndEndOfSearchDay(\DateTime $from, \DateTime $to, string $fromParam, string $toParam): array
    {
        $fromStrToTime = strtotime($fromParam, date_timestamp_get($from));
        $beginDateTimeObject = new \DateTime();
        $beginDateTimeObject->setTimestamp($fromStrToTime);

        $toStrToTime = strtotime($toParam, date_timestamp_get($to));
        $endDateTimeObject = new \DateTime();
        $endDateTimeObject->setTimestamp($toStrToTime);

        return [$beginDateTimeObject, $endDateTimeObject];
    }

    public function generateCacheKeys(\DateTime $beginDateTime, \DateTime $endDateTime): array
    {
        $oneDayInterval = new \DateInterval('P1D');
        $twoDaysInterval = new \DateInterval('P2D');
        $fiveDaysInterval = new \DateInterval('P6D');
        $weekInterval = new \DateInterval('P7D');

        $endDateTimeSub = $endDateTime->sub($twoDaysInterval);

        $cacheKeys = [];
        while ($beginDateTime < $endDateTimeSub){
            $clonedDate = clone $beginDateTime;

            foreach (new \DatePeriod($beginDateTime, $oneDayInterval, $clonedDate->add($fiveDaysInterval)) as $day){
                $cacheKeys[] = $day->format('Y-m-d');
            }

            $beginDateTime->add($weekInterval);
        }

        return $cacheKeys;

    }

}