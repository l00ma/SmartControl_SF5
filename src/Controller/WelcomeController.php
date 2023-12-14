<?php

namespace App\Controller;

use App\Repository\SecurityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WelcomeController extends AbstractController
{
    #[Route('/welcome', name: 'welcome')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }

    #[Route('/welcome/load', name: 'welcome_load')]
    public function w_load(SecurityRepository $securityRepository): JsonResponse
    {
        $user = $this->getUser();
        $etat = $user->getLedsStrip()->getEtat();
        $temp_int = $user->getMeteo()->getTempInt();
        $temp_ext = $user->getMeteo()->getTempExt();
        $temp_bas = $user->getMeteo()->getTempBas();

        $enreg = $user->getMouvementPir()->getEnreg();
        $enreg_detect = $user->getMouvementPir()->getEnregDetect();
        $alert = $user->getMouvementPir()->getAlert();

        $pression = $user->getMeteo()->getPression();
        $vitesse_vent = $user->getMeteo()->getVitesseVent();
        $direction_vent = $user->getMeteo()->getDirectionVent();
        $location = $user->getMeteo()->getLocation();
        $humidite = $user->getMeteo()->getHumidite();
        $weather = $user->getMeteo()->getWeather();
        $icon_id = $user->getMeteo()->getIconId();
        $leve_soleil = $user->getMeteo()->getLeveSoleil();
        $couche_soleil = $user->getMeteo()->getCoucheSoleil();
        $temp_f1 = $user->getMeteo()->getTempF1();
        $temp_f2 = $user->getMeteo()->getTempF2();
        $temp_f3 = $user->getMeteo()->getTempF3();
        $time_f1 = $user->getMeteo()->getTimeF1();
        $time_f2 = $user->getMeteo()->getTimeF2();
        $time_f3 = $user->getMeteo()->getTimeF3();
        $weather_f1 = $user->getMeteo()->getWeatherF1();
        $weather_f2 = $user->getMeteo()->getWeatherF2();
        $weather_f3 = $user->getMeteo()->getWeatherF3();
        $icon_f1 = $user->getMeteo()->getIconF1();
        $icon_f2 = $user->getMeteo()->getIconF2();
        $icon_f3 = $user->getMeteo()->getIconF3();

        $temp_int_min = $user->getMeteoMemory()->getTempIntMin();
        $temp_int_min_date = $user->getMeteoMemory()->getTempIntMinDate()->format('\l\e d/m/Y à H:i');
        $temp_int_max = $user->getMeteoMemory()->getTempIntMax();
        $temp_int_max_date = $user->getMeteoMemory()->getTempIntMaxDate()->format('\l\e d/m/Y à H:i');

        $temp_ext_min = $user->getMeteoMemory()->getTempExtMin();
        $temp_ext_min_date = $user->getMeteoMemory()->getTempExtMinDate()->format('\l\e d/m/Y à H:i');
        $temp_ext_max = $user->getMeteoMemory()->getTempExtMax();
        $temp_ext_max_date = $user->getMeteoMemory()->getTempExtMaxDate()->format('\l\e d/m/Y à H:i');

        $temp_bas_min = $user->getMeteoMemory()->getTempBasMin();
        $temp_bas_min_date = $user->getMeteoMemory()->getTempBasMinDate()->format('\l\e d/m/Y à H:i');
        $temp_bas_max = $user->getMeteoMemory()->getTempBasMax();
        $temp_bas_max_date = $user->getMeteoMemory()->getTempBasMaxDate()->format('\l\e d/m/Y à H:i');

        $films = $emails = array_fill(0, 5, '0');
        $i = $j = 0;

        foreach ($securityRepository->findByMedia(8) as $key => $val) {
            $timeStamp = $val["time_stamp"]->format('\l\e d/m/Y à H:i');
            $films[$i] = $timeStamp;
            $i++;
        }
        foreach ($securityRepository->findByMedia(2) as $key => $val) {
            $timeStamp = $val["time_stamp"]->format('\l\e d/m/Y à H:i');
            $emails[$j] = $timeStamp;
            $j++;
        }

        return $this->json(['etat' => $etat, 'temp_int' => $temp_int, 'temp_ext' => $temp_ext, 'temp_bas' => $temp_bas, 'enreg' => $enreg, 'enreg_detect' => $enreg_detect, 'alert' => $alert, 'pression' => $pression, 'vitesse_vent' => $vitesse_vent, 'direction_vent' => $direction_vent, 'location' => $location, 'humidite' => $humidite, 'weather' => $weather, 'icon_id' => $icon_id, 'leve_soleil' => $leve_soleil, 'couche_soleil' => $couche_soleil, 'temp_f1' => $temp_f1, 'temp_f2' => $temp_f2, 'temp_f3' => $temp_f3, 'time_f1' => $time_f1, 'time_f2' => $time_f2, 'time_f3' => $time_f3, 'weather_f1' => $weather_f1, 'weather_f2' => $weather_f2, 'weather_f3' => $weather_f3, 'icon_f1' => $icon_f1, 'icon_f2' => $icon_f2, 'icon_f3' => $icon_f3, '0' => $films[0], '1' => $films[1], '2' => $films[2], '3' => $films[3], '4' => $films[4], '5' => $emails[0], '6' => $emails[1], '7' => $emails[2], '8' => $emails[3], '9' => $emails[4], 'temp_int_min' => $temp_int_min, 'temp_int_min_date' => $temp_int_min_date, 'temp_int_max' => $temp_int_max, 'temp_int_max_date' => $temp_int_max_date, 'temp_ext_min' => $temp_ext_min, 'temp_ext_min_date' => $temp_ext_min_date, 'temp_ext_max' => $temp_ext_max, 'temp_ext_max_date' => $temp_ext_max_date, 'temp_bas_min' => $temp_bas_min, 'temp_bas_min_date' => $temp_bas_min_date, 'temp_bas_max' => $temp_bas_max, 'temp_bas_max_date' => $temp_bas_max_date,]);
    }
}
