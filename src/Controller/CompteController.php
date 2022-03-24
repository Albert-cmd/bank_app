<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Compte;
use App\Form\CompteType;

class CompteController extends AbstractController
{

    /**
     * @Route("/compte/list", name="compte_list")
     */
    public function list()
    {
        $comptes = $this->getDoctrine()
            ->getRepository(Compte::class)
            ->findAll();

        return $this->render('compte/list.html.twig', ['comptes' => $comptes]);
    }

    /**
     * @Route("/compte/new", name="compte_new")
     */
    public function new(Request $request)
    {
        $compte = new Compte();

        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe CompteType
        $form = $this->createForm(CompteType::class, $compte, array('submit'=>'Crear Compte'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          // recollim els camps del formulari en l'objecte compte
            $compte = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($compte);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Nou compte '.$compte->getCodi().' creat!'
            );

            return $this->redirectToRoute('compte_list');
        }

        return $this->render('compte/compte.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Nou Compte',
        ));
    }

    /**
     * @Route("/compte/edit/{id<\d+>}", name="compte_edit")
     */
    public function edit($id, Request $request)
    {
        $compte = $this->getDoctrine()
            ->getRepository(Compte::class)
            ->find($id);

        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe CompteType
        $form = $this->createForm(CompteType::class, $compte, array('submit'=>'Desar'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // recollim els camps del formulari en l'objecte compte
            $compte = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($compte);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Compte '.$compte->getCodi().' desat!'
            );

            return $this->redirectToRoute('compte_list');
        }

        return $this->render('compte/compte.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Editar compte',
        ));
    }

    /**
     * @Route("/compte/delete/{id}", name="compte_delete", requirements={"id"="\d+"})
     */
    public function delete($id, Request $request)
    {
        $compte = $this->getDoctrine()
            ->getRepository(Compte::class)
            ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($compte);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Compte '.$compte->getCodi().' eliminat!'
        );

        return $this->redirectToRoute('compte_list');
    }

    /**
     * @Route("/compte/order", name="compte_order")
     */
    public function order(Request $request)
    {
        $ordre = $request->request->get('ordre');

        $comptes=[];
        switch ($ordre){

            case 1:
                $comptes = $this->getDoctrine()
                    ->getRepository(Compte::class)
                    ->filterID('ASC');
                break;
            case 2:
                $comptes = $this->getDoctrine()
                    ->getRepository(Compte::class)
                    ->filterID('DESC');
                break;
            case 3:
                $comptes = $this->getDoctrine()
                    ->getRepository(Compte::class)
                    ->filterSaldo('DESC');
                break;
            case 4:
                $comptes = $this->getDoctrine()
                    ->getRepository(Compte::class)
                    ->filterSaldo('ASC');
                break;
        }


        return $this->render('compte/list.html.twig', ['comptes' => $comptes]);

    }
}
