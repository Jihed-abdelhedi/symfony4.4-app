<?php

namespace App\Controller;

use App\Entity\Gamme;
use App\Repository\GammeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GammeController extends AbstractController
{
    /**
     * @Route("/gamme", name="gamme")
     */
    public function index(GammeRepository $rep)
    {
        // $doctrine = $this->getDoctrine();
        // $rep= $doctrine->getRepository(Produit::class);
        $gammes = $rep->findAll();
        //$produits=$rep->findBy(array(),array('id'=>'desc'));
       // $produits= $this->getDoctrine()->getRepository(Produit::class)->findAll();
       //dd($gammes);
        return $this->render('gamme/index.html.twig', compact('gammes'));
    }

        /**
     * @Route("/gamme/add", name="add_gamme")
     */
    public function add(EntityManagerInterface $em,Request $request)
    {
       if($request->isMethod('post'))
       {
        //dd($request);
        $gamme = new Gamme();
        $gamme->setNom($request->get('nom'));
        $gamme->setDescription($request->get('description'));


     

        $em->persist($gamme);
        $em->flush();

       return $this->redirectToRoute('gamme');
       }
        return $this->render('gamme/add.html.twig');
    }

       /**
     * @Route("/gamme/detail/{id}", name="detail_gamme")
     */
    public function detail(Gamme $gamme)
    {
       /*  $doctrine = $this->getDoctrine();
        $rep= $doctrine->getRepository(Produit::class);
        $produit = $rep->find($id); */
        //$produits=$rep->findBy(array(),array('id'=>'desc'));
       // $produits= $this->getDoctrine()->getRepository(Produit::class)->findAll();
        return $this->render('gamme/detail.html.twig', compact('gamme'));
    }
    /**
     * @Route("/gammme/delete/{id}", name="delete_gamme")
     */
    public function delete(Gamme $gamme ,EntityManagerInterface $em)
    {
        // $doctrine = $this->getDoctrine();
        // $produit = $doctrine->getRepository(Produit::class)->find($id);
        // $em=$doctrine->getManager();
        $em->remove($gamme);
        $em->flush();

       
        return $this->redirectToRoute('gamme');
    }
    

     /**
     * @Route("/gamme/edit/{id}", name="gamme_edit", methods={"GET","POST"})
     */
    public function edit(EntityManagerInterface $em,Request $request, Gamme $gamme): Response
    {
        if($request->isMethod('post'))
        {
         //dd($request);
        
         $gamme->setNom($request->get('nom'));
    
         $gamme->setDescription($request->get('description'));
      
 
      
 
         $em->persist($gamme);
         $em->flush();
        
 
        return $this->redirectToRoute('gamme');
        }
         return $this->render('gamme/edit_gamme.html.twig',compact('gamme'));
    }
}
