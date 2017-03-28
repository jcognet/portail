<?php

namespace TransverseBundle\Tools;


/**
 * Class DateService
 * @package CommunBundle\Service
 */
class DateService
{
    /**
     * Retourne la liste de DateTime entre 2 dates
     * @param $strDateFrom
     * @param $strDateTo
     * @return \DateTime[]
     */
    public static function createDateRangeArray(\DateTime $strDateFrom, \DateTime $strDateTo)
    {
        $aryRange = array();

        $iDateFrom = $strDateFrom;
        $iDateTo   = $strDateTo;

        if ($iDateTo >= $iDateFrom) {
            while ($iDateFrom < $iDateTo) {
                $iDateFrom->add(new \DateInterval('P1D'));
                $date = clone $iDateFrom;
                $aryRange[] = $date;
            }
        }
        return $aryRange;
    }
}