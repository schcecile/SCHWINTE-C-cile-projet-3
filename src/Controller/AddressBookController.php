<?php

namespace App\Controller;

use App\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class AddressBookController extends AbstractController
{

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

    public function edit(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(Address::class);

        $address = $repository->find($id);
        if (!$address) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        if ($title = $request->get('title')) {
            $address->setTitle((string) $title);
        }
        if ($first_name = $request->get('first_name')) {
            $address->setFirstName((string) $first_name);
        }
        if ($last_name = $request->get('last_name')) {
            $address->setLastName((string) $last_name);
        }
        if ($email = $request->get('email')) {
            $address->setEmail((string) $email);
        }
        if ($mobile = $request->get('mobile')) {
            $address->setMobile((string) $mobile);
        }
        if ($category = $request->get('category')) {
            $address->setCategory((string) $category);
        }

        $entityManager->flush();

        return $this->redirectToRoute('address_index');

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
