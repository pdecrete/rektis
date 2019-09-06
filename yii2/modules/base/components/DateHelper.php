<?php
namespace app\modules\base\components;

use DateTime;

class DateHelper
{
    public static function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    /* Get start and end date of the week with number $week of the year $year.
     * 
     * https://stackoverflow.com/questions/4861384/php-get-start-and-end-date-of-a-week-by-weeknumber */    
    public static function getStartAndEndDate($week, $year)
    {
        $dto = new DateTime();
        $dto->setISODate($year, $week);
        $ret['week_start'] = $dto->format('Y-m-d');
        $dto->modify('+6 days');
        $ret['week_end'] = $dto->format('Y-m-d');
        return $ret;
    }
}
