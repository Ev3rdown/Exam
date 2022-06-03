<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\PlayerType;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/player', name: 'player_')]
class PlayerController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        /** @var PlayerRepository $pr */
        $pr = $this->em->getRepository(Player::class);

        $players = $pr->findAll();

        return $this->render('player/index.html.twig', [
            'players' => $players
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request,int $id): Response
    {
        /** @var PlayerRepository $tr */
        $pr = $this->em->getRepository(Player::class);

        $player = $pr->find($id);

        if($player==null){
            $this->createNotFoundException("Player not found !");
        }
        $form = $this->createForm(PlayerType::class, $player);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($player);
            $this->em->flush();
        }

        return $this->render('player/edit.html.twig', [
            'player' => $player,
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'])]
    public function delete(Request $request,int $id): RedirectResponse
    {
        /** @var PlayerRepository $pr */
        $pr = $this->em->getRepository(Player::class);

        $player = $pr->find($id);

        if($player==null){
            $this->createNotFoundException("Player not found !");
        }
        $this->em->remove($player);
        $this->em->flush();

        return $this->redirectToRoute("player_index");
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request): Response|RedirectResponse
    {
        $player = new Player();

        $form = $this->createForm(PlayerType::class, $player);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($player);
            $this->em->flush();
            return $this->redirectToRoute("player_index");
        }

        return $this->render('player/create.html.twig', [
            'player' => $player,
            'form' => $form->createView()
        ]);
    }
}
