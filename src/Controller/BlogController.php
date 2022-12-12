<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Blog\Contact;
use App\Entity\Blog\Post;
use App\Form\Blog\ContactFormType;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Blog\PostRepository;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog/", name="blog_index")
     */
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }

    /**
     * @Route("/blog/show/{id}", name="blog_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(PostRepository $postRepository, int $id): Response
    {
        $post = $postRepository->find($id);
        
        if (!$post) {
            throw $this->createNotFoundException('Статья отсутствует.');
        }

        return $this->render('blog/show.html.twig', [
            'post' => $post
        ]);
    }
    // create post
    // public function createPost(ManagerRegistry $doctrine): void
    // {
    //     $entityManager = $doctrine->getManager();
    //     $post = new Post;
    //     $post->setTitle('A day with Symfony2');
    //     $post->setBlog('Lorem ipsum dolor sit d us imperdiet justo scelerisque. Nulla consectetur...');
    //     $post->setImage('beach.png');
    //     $post->setAutor('autor');
    //     $post->setTags('paradise, symblog');
    //     $entityManager->persist($post);
    //     $entityManager->flush();
    // }

    /**
     * @Route("/blog/about", name="blog_about")
     */
    public function about(): Response
    {
        return $this->render('blog/about.html.twig');
    }

    /**
     * @Route("/blog/contact", name="blog_contact", methods={"GET","POST"})
     */
    public function contact(Request $request): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactFormType::class, $contact);
    
        if ($request->isMethod($request::METHOD_POST)) {
            $form->handleRequest($request);
    
            if ($form->isValid()) {
                try {
                    $this->sendEmail($contact);
                    $this->get('session')->getFlashBag()->add('blogger-notice', 'Сообщение отправлено!');
                } catch (TransportExceptionInterface $e) {
                    $this->get('session')->getFlashBag()->add('blogger-notice', 'Произошла ошибка при отправке сообщения!');
                }

                return $this->redirect($this->generateUrl('blog_contact'));
            }
        }

        return $this->render('blog/contact.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/blog/send-email", name="blog_send_email")
     */
    public function sendEmail(Contact $contact): void
    {
        $transport = Transport::fromDsn($this->getParameter('mailer-dsn'));

        $email = (new Email())
            ->from($this->getParameter('contact-email'))
            ->to($this->getParameter('contact-email'))
            ->subject($contact->getSubject())
            ->html($this->renderView('blog/mail.html.twig', ['contact' => $contact]));

        (new Mailer($transport))->send($email);
    }
}
