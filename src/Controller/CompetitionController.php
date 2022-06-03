<?php

namespace App\Controller;

use App\Entity\Competition;
use App\Form\CompetitionType;
use App\Repository\CompetitionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/competition', name: 'competition_')]
class CompetitionController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        /** @var CompetitionRepository $cr */
        $cr = $this->em->getRepository(Competition::class);

        $competitions = $cr->findAll();

        return $this->render('competition/index.html.twig', [
            'competitions' => $competitions
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request,int $id): Response
    {
        /** @var CompetitionRepository $cr */
        $cr = $this->em->getRepository(Competition::class);

        $competition = $cr->find($id);

        if($competition==null){
            $this->createNotFoundException("Competition not found !");
        }
        $form = $this->createForm(CompetitionType::class, $competition);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($competition);
            $this->em->flush();
        }

        return $this->render('competition/edit.html.twig', [
            'competition' => $competition,
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'])]
    public function delete(Request $request,int $id): RedirectResponse
    {
        /** @var CompetitionRepository $cr */
        $cr = $this->em->getRepository(Competition::class);

        $competition = $cr->find($id);

        if($competition==null){
            $this->createNotFoundException("Competition not found !");
        }
        $this->em->remove($competition);
        $this->em->flush();

        return $this->redirectToRoute("competition_index");
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request): Response|RedirectResponse
    {
        $competition = new Competition();

        $form = $this->createForm(CompetitionType::class, $competition);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($competition);
            $this->em->flush();
            return $this->redirectToRoute("competition_index");
        }

        return $this->render('competition/create.html.twig', [
            'competition' => $competition,
            'form' => $form->createView()
        ]);
    }
}
