<?php

namespace App\Tests\Entity;

use App\Entity\Forecast;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ForecastTest extends TestCase
{
    #[DataProvider('dataGetFahrenheit')]
    public function testGetFahrenheit(string $celsius, string $expectedFahrenheit): void
    {
        $forecast = new Forecast();
        $forecast->setTemperatura((float) $celsius);

        $this->assertEqualsWithDelta((float) $expectedFahrenheit, $forecast->getFahrenheit(), 0.01);
    }

    public static function dataGetFahrenheit(): array
    {
        return [
            ['0', '32'],
            ['-100', '-148'],
            ['100', '212'],
            ['0.5', '32.9'],
            ['25', '77'],
            ['-20', '-4'],
            ['10', '50'],
            ['-40', '-40'],
            ['36.6', '97.88'],
            ['50', '122'],
        ];
    }
}
