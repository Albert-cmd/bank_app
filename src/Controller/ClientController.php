<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Client;
use App\Form\ClientCreateType;
use App\Form\ClientEditType;

class ClientController extends AbstractController
{
    /**
     * @Route("/client", name="client")
     */
    public function index(): Response
    {
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }

    /**
     * @Route("/client/list", name="client_list")
     */
    public function list()
    {
        $clients = $this->getDoctrine()
            ->getRepository(Client::class)
            ->findAll();

        //codi de prova per visualitzar l'array de clients
         //dump($clients);exit();

        return $this->render('client/list.html.twig', ['clients' => $clients]);
    }

    /**
     * @Route("/client/new", name="client_new")
     */
    public function new(Request $request)
    {
        $client = new Client();

        //sense la classe ClientType faríem:
        /*$form = $this->createFormBuilder($client)
            ->add('dni', TextType::class)
            ->add('nom', TextType::class)
            ->add('cognoms', TextType::class)
            ->add('dataN', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Crear Client'))
            ->getForm();*/

        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe ClientType
        $form = $this->createForm(ClientCreateType::class, $client, array('submit'=>'Crear Client'));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          // recollim els camps del formulari en l'objecte client
            $client = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Nou client '.$client->getNom().' '.$client->getCognoms().' creat!'
            );

            return $this->redirectToRoute('client_list');
        }

        return $this->render('client/client.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Nou Client',
        ));
    }

    /**
     * @Route("/client/edit/{id<\d+>}", name="client_edit")
     */
    public function edit($id, Request $request)
    {
        $client = $this->getDoctrine()
            ->getRepository(Client::class)
            ->find($id);

        //podem personalitzar el text del botó passant una opció 'submit' al builder de la classe ClientType
        $form = $this->createForm(ClientEditType::class, $client, array('submit'=>'Desar'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // recollim els camps del formulari en l'objecte client
            $client = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Client '.$client->getNom().' '.$client->getCognoms().' desat!'
            );

            return $this->redirectToRoute('client_list');
        }

        return $this->render('client/client.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Editar client',
        ));
    }

    /**
     * @Route("/client/delete/{id}", name="client_delete", requirements={"id"="\d+"})
     */
    public function delete($id, Request $request)
    {
        $client = $this->getDoctrine()
            ->getRepository(Client::class)
            ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($client);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Client '.$client->getNom().' '.$client->getCognoms().' eliminat!'
        );

        return $this->redirectToRoute('client_list');
    }

    /**
     * @Route("/client/cerca", name="client_cerca")
     */
    public function  cerca(Request $request){

        $term = $request->request->get('nom');

        $clients = $this->getDoctrine()
            ->getRepository(Client::class)
            ->findByExampleField($term);

        $this->addFlash(
            'notice',
            'Cercat per terme: '.$term
        );
        
        return $this->render('client/client_cerca.html.twig', ['clients' => $clients]);
    }

}
