<?php
declare(strict_types=1);
namespace App\Controller;
use App\Entity\Forecast;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_api_v1_weather', methods: ['GET'])]
    public function index(
        #[MapQueryParameter] string $city,
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false,
        WeatherUtil $weatherUtil = null,
    ): Response {
        $forecasts = $weatherUtil->getWeatherForCountryAndCity($country, $city);
        if ($twig) {
            if ($format === 'csv') {
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'forecasts' => $forecasts,
                ], new Response('', 200, ['Content-Type' => 'text/plain']));
            }
            return $this->render('weather_api/index.json.twig', [
                'city' => $city,
                'country' => $country,
                'forecasts' => $forecasts,
            ], new Response('', 200, ['Content-Type' => 'application/json']));
        }
        if ($format === 'csv') {
            $rows = [
                'city,country,date,temperatura,fahrenheit,wind,humidity,pressure'
            ];
            foreach ($forecasts as $f) {
                $rows[] = sprintf(
                    '%s,%s,%s,%s,%s,%s,%s,%s',
                    $city,
                    $country,
                    $f->getDate()->format('Y-m-d'),
                    $f->getTemperatura(),
                    $f->getFahrenheit(),
                    $f->getWind(),
                    $f->getHumidity(),
                    $f->getPressure()
                );
            }
            return new Response(implode("\n", $rows), 200, [
                'Content-Type' => 'text/csv',
            ]);
        }
        return $this->json([
            'city' => $city,
            'country' => $country,
            'forecasts' => array_map(fn(Forecast $f) => [
                'date' => $f->getDate()->format('Y-m-d'),
                'fahrenheit' => $f->getFahrenheit(),
                'temperatura' => $f->getTemperatura(),
                'wind' => $f->getWind(),
                'humidity' => $f->getHumidity(),
                'pressure' => $f->getPressure(),
            ], $forecasts),
        ]);
    }
}
