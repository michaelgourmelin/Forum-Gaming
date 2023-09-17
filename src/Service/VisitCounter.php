<?php

namespace App\Service;

use App\Entity\Theme;
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



    public function increment(Theme $theme)
    {
        $request = $this->requestStack->getCurrentRequest();
        $ipaddress = $request->getClientIp();

        // Recherchez une visite existante avec l'adresse IP donnée
        $existingVisit = $this->entityManager->getRepository(Visits::class)->findOneBy([
            'ipaddress' => $ipaddress,
        ]);

        if (!$existingVisit) {
            // Si aucune visite n'existe pour cette adresse IP, créez une nouvelle visite
            $visits = new Visits();
            $visits->setIpAddress($ipaddress);
            $theme->addVisit($visits);
          
            $visits->setCount(1); // Nouvelle visite, donc le compteur est à 1
            $this->entityManager->persist($visits);

        } else {

            $theme->addVisit($existingVisit);
        }

        $this->entityManager->flush();
    }
    public function getCount()
    {
        return $this->entityManager->getRepository(Visits::class)->sumVisitsCount();
    }
}
