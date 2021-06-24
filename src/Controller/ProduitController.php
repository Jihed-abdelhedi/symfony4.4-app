<?php

namespace App\Controller;

use App\Entity\Gamme;
use App\Entity\Produit;
use App\Repository\ProduitRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
//use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class ProduitController extends AbstractController
{
 /**
     * @Route("/", name="home")
     */
    public function home()
    {
        $user=$this->getUser();
        
        return $this->render('index.html.twig',compact('user'));
    }

    /**
     * @Route("/produit", name="produit")
     */
    public function index(ProduitRepository $rep)
    {
        // $doctrine = $this->getDoctrine();
        // $rep= $doctrine->getRepository(Produit::class);
        $produits = $rep->findAll();
        //$produits=$rep->findBy(array(),array('id'=>'desc'));
       // $produits= $this->getDoctrine()->getRepository(Produit::class)->findAll();
        return $this->render('produit/index.html.twig', ['produits'=>$produits]);
    }
     /**
     * @Route("/produit/detail/{id}", name="detail")
     */
    public function detail(Produit $produit)
    {
       /*  $doctrine = $this->getDoctrine();
        $rep= $doctrine->getRepository(Produit::class);
        $produit = $rep->find($id); */
        //$produits=$rep->findBy(array(),array('id'=>'desc'));
       // $produits= $this->getDoctrine()->getRepository(Produit::class)->findAll();
        return $this->render('produit/detail.html.twig', ['produit'=>$produit]);
    }
     

     /**
     * @Route("/produit/delete/{id}", name="delete_produit")
     */
    public function delete(Produit $produit ,EntityManagerInterface $em)
    {
        // $doctrine = $this->getDoctrine();
        // $produit = $doctrine->getRepository(Produit::class)->find($id);
        // $em=$doctrine->getManager();
        $em->remove($produit);
        $em->flush();

       
        return $this->redirectToRoute('produit');
    }

    

     /**
     * @Route("/produit/edit/{id}", name="produit_edit", methods={"GET","POST"})
     */
    public function edit(EntityManagerInterface $em,Request $request, Produit $produit): Response
    {
        $doctrine = $this->getDoctrine();
        $rep= $doctrine->getRepository(Gamme::class);
        $gammes = $rep->findAll();
        if($request->isMethod('post'))
        {
         //dd($request);
        $gamme = $rep->find($request->get('gamme_id'));
         $produit->setGamme($gamme);
         $produit->setLibelle($request->get('libelle'));
         $produit->setPrix($request->get('prix'));
         $produit->setDescription($request->get('description'));
         $produit->setQuantite($request->get('quantite'));
 
      
 
         $em->persist($produit);
         $em->flush();
        
 
        return $this->redirectToRoute('produit');
        }
         return $this->render('produit/edit.html.twig',compact('produit','gammes'));
    }

     /**
     * @Route("/produit/add", name="add_prod")
     */
    public function add(EntityManagerInterface $em,Request $request)
    {
        //$gammes= new Gamme();
        $doctrine = $this->getDoctrine();
        $rep= $doctrine->getRepository(Gamme::class);
        $gammes = $rep->findAll();
       if($request->isMethod('post'))
       {
        //dd($request);
        $produit= new Produit();
        //dd($rep->find($request->get('gamme_id')));
        $produit->setGamme( $rep->find($request->get('gamme_id')));
        $produit->setLibelle($request->get('libelle'));
        $produit->setPrix($request->get('prix'));
        $produit->setDescription($request->get('description'));
        $produit->setQuantite($request->get('quantite'));

     

        $em->persist($produit);
        $em->flush();

       return $this->redirectToRoute('produit');
       }
        return $this->render('produit/add1.html.twig',compact('gammes'));
    }

    /**
     * @Route("/produit/add1", name="add_produit1")
     */
    public function add1(EntityManagerInterface $em,Request $request)
    {

        $produit = new Produit();
        $form = $this->createFormBuilder($produit)->add('gamme',EntityType::class,['class'=> Gamme::class,'choice_label'=>'nom','label'=>'Gamme'])
                                                ->add('libelle')
                                                ->add('description')
                                                ->add('prix')
                                                ->add('quantite')
                                                ->add('Ajouter',SubmitType::class)
                                                ->getForm();
       if($request->isMethod('post'))
       {
            $form->handleRequest($request);
            if($form->isValid())
            {
                $em->persist($produit);
                $em->flush();
        
               return $this->redirectToRoute('produit');
            }
       }
        return $this->render('produit/add2.html.twig',['formProduit'=>$form->createView()]);
    }

}
