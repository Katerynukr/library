<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Author;
use App\Entity\Books;

class BookController extends AbstractController
{
    /**
     * @Route("/book", name="book_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    /**
     * @Route("/book/create", name="book_create", methods={"GET"})
     */
    public function create(): Response
    {
        
        $authors = $this->getDoctrine()
        ->getRepository(Author::class)
        ->findAll();
        
        
        return $this->render('book/create.html.twig', [
            'authors' => $authors
        ]);
    }

     /**
     * @Route("/book/create", name="book_store", methods={"POST"})
     */
    public function store(Request $r): Response
    {
        
        $book = new Books;
        $book->
        setTitle($r->request->get('book_title'))->
        setIsbn($r->request->get('book_isbn'))->
        setPages($r->request->get('book_pages'))->
        setAbout($r->request->get('book_about'))->
        setAuthorId($r->request->get('book_author_id'));

        //creating entity manager sending data to database
        $entityManager = $this->getDoctrine()->getManager();
        //organizing data to be send
        $entityManager->persist($book);
        //wrting
        $entityManager->flush();

        return $this->redirectToRoute('book_index');
    }
}
