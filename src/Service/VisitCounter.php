<?php

namespace App\Service;

use App\Entity\Visits;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class VisitCounter
{
    private $entityManager;
    private $requestStack;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    public function increment()
    {
        $request = $this->requestStack->getCurrentRequest();
        $ipaddress = $request->getClientIp();
       
        // Vérifiez si l'adresse IP existe déjà dans la base de données
        $visit = $this->entityManager->getRepository(Visits::class)->findOneBy(['ipaddress' => $ipaddress]);
       
        if (!$visit) {
            // Si l'adresse IP n'existe pas, créez une nouvelle visite
            $visit = new Visits();
            $visit->setIpAddress($ipaddress);
           
        }

        // Incrémentation du compteur
        $count = $visit->getCount() + 1;
        $visit->setCount($count);

        // Enregistrez la visite dans la base de données
        $this->entityManager->persist($visit);
        $this->entityManager->flush();
    }

    public function getCount()
    {
        // Calculez le nombre total de visites
        return $this->entityManager->getRepository(Visits::class)->sumVisitsCount();
    }
}