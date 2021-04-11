<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AddressBookController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     */
    public function index(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Address::class);

        $addresses = $repository->findAll();

        return $this->render('address_book/index.html.twig', [
            'address' => $addresses,
        ]);
    }


    public function create(Request $request)
    {
        return $this->render('address_book/create.html.twig');
    }

    public function store (Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $address = new Address();
        $address->setTitle((string)$request->get('title'));
        $address->setFirstName((string)$request->get('first_name'));
        $address->setLastName((string)$request->get('last_name'));
        $address->setEmail((string)$request->get('email'));
        $address->setMobile((string)$request->get('mobile'));
        $address->setCategory((string)$request->get('category'));

        $entityManager->persist($address);

        $entityManager->flush();

        return $this->redirectToRoute('address_index');
    }


    public function show($id)
    {
        $repository = $this->getDoctrine()->getRepository(Address::class);

        $address = $repository->find($id);

        if (!$address) {
            throw $this->createNotFoundException();
        }

        return $this->render('address_book/show.html.twig', [
            'address' => $address,
        ]);
    }

    public function edit(Request $request, Address $address): Response
    {
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('address_index');
        }

        return $this->render('address_book/edit.html.twig', [
            'address' => $address,
            'form' => $form->createView(),
        ]);
    }

    public function delete(Request $request, Address $address)
    {
        if ($this->isCsrfTokenValid('delete'.$address->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($address);
            $entityManager->flush();
        }

        return $this->redirectToRoute('address_index');
    }
}
