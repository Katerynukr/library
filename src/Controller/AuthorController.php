<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Author;

class AuthorController extends AbstractController
{
    /**
     * @Route("/author", name="author_index", methods= {"GET"})
     */
    public function index(Request $r): Response
    {
        $authors = $this->getDoctrine()
        ->getRepository(Author::class);

        if($r->query->get('sort_by') == 'sort_by_name_asc'){
            $authors = $authors->findBy([],['name' => 'asc']);
        }elseif($r->query->get('sort_by') == 'sort_by_name_desc'){
            $authors = $authors->findBy([],['name' => 'desc']);
        }elseif($r->query->get('sort_by') == 'sort_by_surname_asc'){
            $authors = $authors->findBy([],['surname' => 'asc']);
        }elseif($r->query->get('sort_by') == 'sort_by_surname_desc'){
            $authors = $authors->findBy([],['surname' => 'desc']);
        }else{
            $authors = $authors->findAll();
        }
        
        return $this->render('author/index.html.twig', [
            'authors' => $authors,
            'sortBy' => $r->query->get('sort_by') ?? 'default',
            'success' => $r->getSession()->getFlashBag()->get('success', [])
        ]);

    }

 
     /**
     * @Route("/author/create", name="author_create", methods= {"GET"})
     */
    public function create(Request $r): Response
    {
        return $this->render('author/create.html.twig', [
            'errors' => $r->getSession()->getFlashBag()->get('errors', [])
        ]);
    }

     /**
     * @Route("/author/store", name="author_store", methods= {"POST"})
     */
    //validator reads constrains of the class and checks does obj sutisfies them
    public function store(Request $r, ValidatorInterface $validator): Response
    {
        
        $author = new Author;
        $author->
        setName($r->request->get('author_name'))->
        setSurname($r->request->get('author_surname'));

        $errors = $validator->validate($author);

        // dd(count($errors));
        if (count($errors) > 0){
            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
            return $this->redirectToRoute('author_create');
        }

        //creating entity manager sending data to database
        $entityManager = $this->getDoctrine()->getManager();
        //organizing data to be send
        $entityManager->persist($author);
        //wrting
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'Author was successfully created');

        return $this->redirectToRoute('author_index');
    }

     /**
     * @Route("/author/edit/{id}", name="author_edit", methods= {"GET"})
     */
    public function edit(int $id): Response
    {
        $authors = $this->getDoctrine()
        ->getRepository(Author::class)
        ->find($id);
        
        return $this->render('author/edit.html.twig', [
            'author' => $authors,
        ]);
    }

     /**
     * @Route("/author/update/{id}", name="author_update", methods= {"POST"})
     */
    public function update(Request $r, int $id): Response
    {
        $author = $this->getDoctrine()
        ->getRepository(Author::class)
        ->find($id);
        
        $author->
        setName($r->request->get('author_name'))->
        setSurname($r->request->get('author_surname'));

        //creating entity manager sending data to database
        $entityManager = $this->getDoctrine()->getManager();
        //organizing data to be send
        $entityManager->persist($author);
        //wrting
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'Author '. $author->getName() .' '. $author->getSurname().' was successfully modified');

        return $this->redirectToRoute('author_index');
    }

    /**
     * @Route("/author/delete/{id}", name="author_delete", methods= {"POST"})
     */
    public function delete(Request $r, int $id): Response
    {
        $author = $this->getDoctrine()
        ->getRepository(Author::class)
        ->find($id);

        //creating entity manager sending data to database
        $entityManager = $this->getDoctrine()->getManager();
        //organizing data to be send
        $entityManager->remove($author);
        //wrting
        $entityManager->flush();

        return $this->redirectToRoute('author_index');
    }
    
}
