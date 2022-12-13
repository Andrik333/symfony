<?php

namespace App\Tests\Extensions\Twig;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Extensions\Twig\ExtensoinTwig;

class ExtensoinTwigTest extends WebTestCase
{
    public function testCreatedAgo()
    {
        $blog = new ExtensoinTwig();

        $this->assertEquals("0 секунд назад", $blog->createdAgo(new \DateTime()));
        $this->assertEquals("1 минуту назад", $blog->createdAgo($this->getDateTime(-60)));
        $this->assertEquals("2 минуты назад", $blog->createdAgo($this->getDateTime(-120)));
        $this->assertEquals("1 час назад", $blog->createdAgo($this->getDateTime(-3600)));
        $this->assertEquals("1 час назад", $blog->createdAgo($this->getDateTime(-3601)));
        $this->assertEquals("2 часа назад", $blog->createdAgo($this->getDateTime(-7200)));

    }

    protected function getDateTime($delta)
    {
        return new \DateTime(date("Y-m-d H:i:s", time()+$delta));
    }
}
