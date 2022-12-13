<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogControllerTest extends WebTestCase
{
    public function testAbout()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'blog/about');

        $this->assertEquals(1, $crawler->filter('h1:contains("О блоге")')->count());
    }

    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'blog/');

        $blogLink = $crawler->filter('h2 a')->first();
        $blogTitle = $blogLink->text();
        $crawler = $client->click($blogLink->link());

        $this->assertEquals(1, $crawler->filter('h2:contains("'. $blogTitle .'")')->count());
    }

    public function testContact()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'blog/contact');

        // $this->assertEquals(1, $crawler->filter('h1:contains("Контакты")')->count());

        $form = $crawler->selectButton('Отправить')->form();

        $form['contact_form[name]']       = 'name';
        $form['contact_form[email]']      = 'email@email.com';
        $form['contact_form[subject]']    = 'Subject';
        $form['contact_form[body]']       = 'The comment body must be at least 50 characters long as there is a validation constrain on the Enquiry entity';

        $crawler = $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertEquals(1, $crawler->filter('.blogger-notice:contains("Сообщение отправлено!")')->count());
    }

    public function testAddBlogComment()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'blog/show/3/a-day-with-symfony2');

        $this->assertEquals(1, $crawler->filter('h2:contains("A day with Symfony2")')->count());

        $form = $crawler->selectButton('Отправить')->form();

        $crawler = $client->submit($form, array(
            'comment_form[user]'          => 'name',
            'comment_form[comment]'       => 'comment',
        ));

        // Need to follow redirect
        $crawler = $client->followRedirect();

        // Check comment is now displaying on page, as the last entry. This ensure comments
        // are posted in order of oldest to newest
        $articleCrawler = $crawler->filter('section .previous-comments article')->first();

        $this->assertEquals('name', $articleCrawler->filter('header span.highlight')->text());
        $this->assertEquals('comment', $articleCrawler->filter('p')->last()->text());

        // Check the sidebar to ensure latest comments are display and there is 10 of them

        $this->assertEquals(5, $crawler->filter('aside.sidebar section')->last()
                                        ->filter('article')->count()
        );

        $this->assertEquals('name', $crawler->filter('aside.sidebar section')->last()
                                            ->filter('article')->first()
                                            ->filter('header span.highlight')->text()
        );
    }
}