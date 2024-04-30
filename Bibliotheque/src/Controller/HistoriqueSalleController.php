<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class HistoriqueSalleController extends AbstractController
{

    private $reservationRepository;

    public function __construct(ReservationRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }


    #[Route('/historique/salle', name: 'app_historique_salle')]
    #[IsGranted('ROLE_USER')]
    public function index(Security $security): Response
    {
        if (!$security->getUser()) {
            throw new AccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $user = $this->getUser();

        $reservations = $this->reservationRepository->findBy(['user' => $user]);

        if (empty($reservations)) {
            return $this->render('historique_salle/index.html.twig', [
                'controller_name' => 'HistoriqueSalleController',
                'message' => "Vous n'avez pas encore réservé de salle pour le moment."
            ]);
        }

        $reservationsDetails = [];

        foreach ($reservations as $reservation) {
            $room = $reservation->getRoom();
            $dateDebut = $reservation->getDateDebut();
            $dateFin = $reservation->getDateFin();

            $equipements = $room->getEquipement()->toArray();

            $reservationsDetails[] = [
                'nom_salle' => $room->getName(),
                'capacite' => $room->getCapability(),
                'equipements' => $equipements,
                'date_debut' => $dateDebut->format('Y-m-d H:i'),
                'date_fin' => $dateFin->format('Y-m-d H:i')
            ];
        }

        return $this->render('historique_salle/index.html.twig', [
            'controller_name' => 'HistoriqueSalleController',
            'reservationsDetails' => $reservationsDetails,
            'prenomUser' => $user->getFirstname()
        ]);
    }
}
