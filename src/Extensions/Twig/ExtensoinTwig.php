<?php

namespace App\Extensions\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TwigFiltersExtension extends AbstractExtension 
{
    public function getFilters()
    {
        return [
            new TwigFilter('created_ago', [$this, 'createdAgo']),        
        ];
    }
    
    public function createdAgo(\DateTime $dateTime)
    {

        $delta = \time() - $dateTime->getTimestamp();

        if ($delta < 0)
            throw new \InvalidArgumentException("createdAgo is unable to handle dates in the future");

        $duration = "";
        if ($delta < 60)
        {
            $time = $delta;
            $duration = $time . " секунд" . $this->getPrefix($time, 's') . " назад";
        }
        else if ($delta <= 3600)
        {
            $time = floor($delta / 60);
            $duration = $time . " минут" . $this->getPrefix($time, 'm') . " назад";
        }
        else if ($delta <= 86400)
        {
            $time = floor($delta / 3600);
            $duration = $time . " час" . $this->getPrefix($time, 'h') . " назад";
        }
        else
        {
            $time = floor($delta / 86400);
            $duration = $time . " " . $this->getPrefix($time, 'd') . " назад";
        }
        return $duration;
    }

    public function getPrefix($time, $type)
    {
        switch ($type) {
            case 's':
                if ($time == 1) return 'у';
                if ($time > 1 and $time < 5) return 'ы';
                break;
            
            case 'm':
                if ($time == 1) return 'у';
                if ($time > 1 and $time < 5) return 'ы';
                break;
            
            case 'h':
                if ($time > 1 and $time < 5) return 'а';
                if ($time > 5) return 'ов';
                break;
            
            case 'd':
                if ($time == 1) return 'день';
                if ($time > 1 and $time < 5) return 'дня';
                if ($time > 5) return 'дней';
                break;
        }
    }

    public function getName()
    {
        return 'blog_extension';
    }
}