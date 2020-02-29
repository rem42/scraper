<?php

namespace Scraper\Scraper\Utils;

class Tools
{
    /**
     * @param $date
     *
     * @return mixed|string|string[]|null
     */
    public static function frenchDateToEnglish($date)
    {
        $date = mb_strtolower($date);
        $date = ucwords($date);

        $frenchDays  = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $englishDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $date        = str_replace($frenchDays, $englishDays, $date);

        $frenchMonths  = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        $englishMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $date          = str_replace($frenchMonths, $englishMonths, $date);

        return $date;
    }
}
