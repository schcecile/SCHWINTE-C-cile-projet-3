<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CalendarRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class DashboardController extends AbstractController
{
    /**
     * @IsGranted("ROLE_USER")
     */
    public function dashboard(CalendarRepository $calendarRepository)
    {
        $user = $this->getUser();
        if($user){
            $calendars = $calendarRepository->displayLastEvents($user);

        }
        return $this->render('dashboard/index.html.twig', [
        'calendars' => $calendars,
        ]);
    }

}
