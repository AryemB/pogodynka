<?php
namespace App\Command;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
#[AsCommand(
    name: 'weather:city',
    description: 'Show the weather forecast for a city by country code and city name',
)]
class WeatherCityCommand extends Command
{
    public function __construct(
        private WeatherUtil $weatherUtil
    ) {
        parent::__construct();
    }
    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Country code')] string $country,
        #[Argument('City name')] string $city,
    ): int {
        $forecasts = $this->weatherUtil->getWeatherForCountryAndCity($country, $city);
        $io->writeln(sprintf('Location: %s, %s', $city, $country));
        if ($forecasts === []) {
            $io->writeln('No forecast available.');
            return Command::SUCCESS;
        }
        $rows = [];
        foreach ($forecasts as $forecast) {
            $rows[] = [
                $forecast->getDate()->format('Y-m-d'),
                $forecast->getTemperatura() . ' °C',
                $forecast->getHumidity() . ' %',
                $forecast->getPressure() . ' hPa',
                $forecast->getWind(),
            ];
        }
        $io->table(
            ['Date', 'Temperature', 'Humidity', 'Pressure', 'Wind'],
            $rows
        );
        return Command::SUCCESS;
    }
}
