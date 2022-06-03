<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/team', name: 'team_')]
class TeamController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        /** @var TeamRepository $cr */
        $tr = $this->em->getRepository(Team::class);

        $teams = $tr->findAll();

        return $this->render('team/index.html.twig', [
            'teams' => $teams
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request,int $id): Response
    {
        /** @var TeamRepository $tr */
        $tr = $this->em->getRepository(Team::class);

        $team = $tr->find($id);

        if($team==null){
            $this->createNotFoundException("Team not found !");
        }
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($team);
            $this->em->flush();
        }

        return $this->render('team/edit.html.twig', [
            'team' => $team,
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'])]
    public function delete(Request $request,int $id): RedirectResponse
    {
        /** @var TeamRepository $tr */
        $tr = $this->em->getRepository(Team::class);

        $team = $tr->find($id);

        if($team==null){
            $this->createNotFoundException("Team not found !");
        }
        $this->em->remove($team);
        $this->em->flush();

        return $this->redirectToRoute("team_index");
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request): Response|RedirectResponse
    {
        $team = new Team();

        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($team);
            $this->em->flush();
            return $this->redirectToRoute("team_index");
        }

        return $this->render('team/create.html.twig', [
            'team' => $team,
            'form' => $form->createView()
        ]);
    }
}
