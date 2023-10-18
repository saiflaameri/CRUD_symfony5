<?php

namespace App\Controller;
use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class AuthorsController extends AbstractController
{
    #[Route('/authors', name: 'app_authors')]
    public function index(): Response
    {
        return $this->render('authors/index.html.twig', [
            'controller_name' => 'AuthorsController',
        ]);
    }

    #[Route('/affiche', name: 'app_affiche')]
    public function affiche(ManagerRegistry $doctrine ):Response
    {
        $em=$doctrine->getRepository(Author::class);
        $author=$em->findAll();
        return $this->render('authors/index.html.twig',[
            'author'=>$author
        ]);
    }

    #[Route('/ajouter', name: 'app_ajouter')]
    public function Ajouter(ManagerRegistry $doctrine,Request $request)
    {
        $a=new Author();
        $form=$this->createForm(AuthorType::class,$a);
         $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $em=$doctrine->getManager();
            $em->persist($a);
            $em->flush();
           return $this->redirectToRoute('app_affiche');
        }
       return $this->render('authors/add.html.twig',[
        'form'=>$form->createView()]);

    }
    
    #[Route('/deleteauthor/{id}', name: 'deleteauthor')]
    public function deleteauthor($id,ManagerRegistry $doctrine)
    {
        $em=$doctrine->getRepository(Author::class);
        $Authorr=$em->find($id);
        $em=$doctrine->getManager();
        $em->remove($Authorr);
        $em->flush();
        return $this->redirectToRoute('app_affiche');
    }

    #[Route('/updateauthor/{id}', name: 'updateauthor')]
    public function updateauthor($id,ManagerRegistry $doctrine,Request $request)
    {
        $a=new Author();
        $em=$doctrine->getRepository(Author::class)->find($id);
        $form=$this->createForm(AuthorType::class,$em);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em=$doctrine->getManager();
            $em->flush();

            return $this->redirectToRoute('app_affiche');
        }
        return $this->render('authors/update.html.twig',[
            'form'=>$form->createView()
        ]);

    }
    public function count(ManagerRegistry $doctrine){
        $em=$doctrine->getRepository(Author::class);
        

    }

  /*  #[Route('/ajouterlist', name: 'app_ajouterlist')]
    public function ajouterlist(ManagerRegistry $doctrine):Response
    {
        $a=new Author();
    
            $a->setEmail("firas@esprit.tn");
            $a->setUsername("firas");
            $em=$doctrine->getManager();
            $em->persist($a);
            $em->flush();
           return new Response("succes");
    
    }
*/
}
