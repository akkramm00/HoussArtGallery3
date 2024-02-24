<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    /**
     * This controller allow us to send messages 
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/contact', name: 'contact.index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        EntityManagerInterface $manager,
        MailerInterface $mailer
    ): Response {
        $contact = new Contact();

        if ($this->getUser()) {
            $contact->setFullName($this->getUser()->getFullName())
                ->setEmail($this->getUser()->getEmail());
        }

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            $manager->persist($contact);
            $manager->flush();


            //Email
            $email = (new Email())
                ->from($contact->getEmail())
                ->to('admin@houssartgallery.com')
                ->subject($contact->getSubject())
                ->html($contact->getMessage());

            $mailer->send($email);

            $this->addFlash(
                'success',
                'Votre message a bien été enregistré avec succès'
            );

            return $this->redirectToRoute('contact.index');
        }

        return $this->render('pages/contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /******************************************************************* */
    #[Route('/contact/show', name: 'show.index', methods: ['GET'])]
    /**
     * This controller allow us to show all messagesContact
     *
     * @param ContactRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function show(
        ContactRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $contact = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/contact/show.html.twig', [
            'contact' => $contact
        ]);
    }


    /************************************************************************* */
    #[Route('/contact/suppression/{id}', 'show.delete', methods: ['GET'])]
    public function delete(
        EntityManagerInterface $manager,
        ContactRepository $repository,
        Contact $contact,
        $id
    ): Response {
        $contact = $repository->findOneBy(["id" => $id]);
        if (!$contact) {
            $this->addFlash(
                'warning',
                'Le message en question n\'a pas été trouvé'
            );

            return $this->redirectToRoute('show.index');
        }
        $manager->remove($contact);
        $manager->flush();

        $this->addflash(
            'success',
            'Le message a été supprimé avec succès!'
        );

        return $this->redirectToRoute('show.index');
    }

    /*************************************************************** */
    #[Route('/contact/showById/{id}', 'showById', methods: ['GET'])]
    public function showById(
        ContactRepository $repository,
        $id
    ): Response {
        $contact = $repository->findOneBy(["id" => $id]);

        if (!$contact) {
            // Le produit n'existe pas , renvoyer une reponse d'erreur
            $this->addflash(
                'warning',
                'Le contact en question n\'a pas été trouvé!'
            );
            return $this->redirectToRoute('show.index');
        }

        return $this->render('pages/contact/showById.html.twig', [
            'contact' => $contact
        ]);
    }
}
