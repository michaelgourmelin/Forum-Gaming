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


        $visits = $theme->getVisits()->first();


        if (!$visits) {

            $request = $this->requestStack->getCurrentRequest();
            $ipaddress = $request->getClientIp();
            $visits = new Visits();
            $visits->setIpAddress($ipaddress);
            $theme->addVisit($visits);
            // Increment the visit count for the theme
            $visits->setCount($visits->getCount() + 1);
            $this->entityManager->persist($visits);
        }


        $this->entityManager->persist($visits);
        $this->entityManager->flush();
    }
    public function getCount()
    {
        return $this->entityManager->getRepository(Visits::class)->sumVisitsCount();
    }
}
