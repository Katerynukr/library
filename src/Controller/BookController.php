<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Author;
use App\Entity\Books;

class BookController extends AbstractController
{
    /**
     * @Route("/book", name="book_index", methods={"GET"})
     */
    public function index(Request $r): Response
    {
        $authors =  $this->getDoctrine()
        ->getRepository(Author::class)
        ->findAll();
        
        $books = $this->getDoctrine()
        ->getRepository(Books::class);

        if($r->query->get('filter') !== null ){
            $books = $books->findBy(['author_id'=> $r->query->get('filter')]);
        }else{
            $books= $books->findAll();
        }


        return $this->render('book/index.html.twig', [
            'books' => $books,
            'authors'=>$authors,
            'authorID'=>$r->query->get('filter') ?? 0,
            'errors' => $r->getSession()->getFlashBag()->get('errors', [])
        ]);
    }

    /**
     * @Route("/book/create", name="book_create", methods={"GET"})
     */
    public function create(Request $r): Response
    {
        
        $authors = $this->getDoctrine()
        ->getRepository(Author::class)
        ->findAll();
        
        
        return $this->render('book/create.html.twig', [
            'authors' => $authors,
            'errors' => $r->getSession()->getFlashBag()->get('errors', [])
        ]);
    }

     /**
     * @Route("/book/create", name="book_store", methods={"POST"})
     */
    public function store(Request $r, ValidatorInterface $validator): Response
    {
        $submittedToken = $r->request->get('token');

        if (!$this->isCsrfTokenValid('create_author_hidden', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Blogas Tokenas CSRF');
            return $this->redirectToRoute('author_create');
        }

        $author = $this->getDoctrine()
        ->getRepository(Author::class)
        ->find($r->request->get('book_author_id'));
        
        $book = new Books;

        $book->
        setTitle($r->request->get('book_title'))->
        setIsbn($r->request->get('book_isbn'))->
        setPages($r->request->get('book_pages'))->
        setAbout($r->request->get('book_about'))->
        setAuthor($author);

        $errors = $validator->validate($book);
        if (count($errors) > 0){
            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
            return $this->redirectToRoute('book_create');
        }

        //creating entity manager sending data to database
        $entityManager = $this->getDoctrine()->getManager();
        //organizing data to be send
        $entityManager->persist($book);
        //wrting
        $entityManager->flush();

        return $this->redirectToRoute('book_index');
    }

    /**
     * @Route("/book/edit/{id}", name="book_edit", methods= {"GET"})
     */
    public function edit(int $id): Response
    {
        $book = $this->getDoctrine()
        ->getRepository(Books::class)
        ->find($id);

        $authors = $this->getDoctrine()
        ->getRepository(Author::class)
        ->findAll();
        
        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'authors' => $authors
        ]);
    }

     /**
     * @Route("/book/update/{id}", name="book_update", methods= {"POST"})
     */
    public function update(Request $r, int $id): Response
    {
        $submittedToken = $r->request->get('token');

        if (!$this->isCsrfTokenValid('create_author_hidden_update', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Blogas Tokenas CSRF');
            return $this->redirectToRoute('book_edit');
        }
        
        $book = $this->getDoctrine()
        ->getRepository(Books::class)
        ->find($id);
        
        $author = $this->getDoctrine()
        ->getRepository(Author::class)
        ->find($r->request->get('books_author'));

        $book->
        setTitle($r->request->get('book_title'))->
        setIsbn($r->request->get('book_isbn'))->
        setPages($r->request->get('book_pages'))->
        setAbout($r->request->get('book_about'))->
        setAuthor($author);

        //creating entity manager sending data to database
        $entityManager = $this->getDoctrine()->getManager();
        //organizing data to be send
        $entityManager->persist($author);
        //wrting
        $entityManager->flush();

        return $this->redirectToRoute('book_index');
    }

    /**
     * @Route("/book/delete/{id}", name="book_delete", methods= {"POST"})
     */
    public function delete(Request $r, int $id): Response
    {
        $submittedToken = $r->request->get('token');
       
        if (!$this->isCsrfTokenValid('create_author_hidden_index', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Blogas Tokenas CSRF');
            return $this->redirectToRoute('book_index');
        }

        $book = $this->getDoctrine()
        ->getRepository(Books::class)
        ->find($id);

        //creating entity manager sending data to database
        $entityManager = $this->getDoctrine()->getManager();
        //organizing data to be send
        $entityManager->remove($book);
        //wrting
        $entityManager->flush();

        return $this->redirectToRoute('book_index');
    }
    
}
