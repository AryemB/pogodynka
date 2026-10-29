<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\ForecastRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route('/weather/{country}/{name}', name: 'app_weather_city', requirements: ['country' => '[A-Z]{2}'])]
    public function city(
        #[MapEntity(expr: 'repository.findOneByCountryAndName(country, name)')]
        Location $location,
        ForecastRepository $forecastRepository
    ): Response
    {
        $forecasts = $forecastRepository->findByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'forecasts' => $forecasts,
        ]);
    }
}
