<?php

namespace App\Tests;

use App\Entity\City;
use PHPUnit\Framework\TestCase;

class CityEntityTest extends TestCase
{
    public function testCityProperties()
    {
        $city = new City();

        $this->assertNull($city->getId());
        $this->assertNull($city->getName());
        $this->assertNull($city->getArea());
        $this->assertNull($city->getHousing());
        $this->assertNull($city->getElectricity());
        $this->assertNull($city->getInternet());
        $this->assertNull($city->getSunshineRate());
        $this->assertNull($city->getTemperatureAverage());
        $this->assertNull($city->getCost());
        $this->assertNull($city->getLanguage());
        $this->assertNull($city->getDemography());
        $this->assertNull($city->getTimezone());
        $this->assertNull($city->getEnvironment());
        $this->assertNull($city->getRating());

        $city->setName('Test City');
        $city->setArea(1000);
        $city->setHousing('medium');
        $city->setElectricity('low');
        $city->setInternet('high');
        $city->setSunshineRate('low');
        $city->setTemperatureAverage(12.1);
        $city->setCost(600);
        $city->setLanguage('Polonais');
        $city->setEnvironment('Désert');

        $this->assertSame('Test City', $city->getName());
        $this->assertSame(1000, $city->getArea());
        $this->assertSame(12.1, $city->getTemperatureAverage());
        $this->assertSame(600, $city->getCost());
        $this->assertSame('low', $city->getElectricity());
        $this->assertSame('low', $city->getSunshineRate());
        $this->assertSame('high', $city->getInternet());
        $this->assertSame('medium', $city->getHousing());
        $this->assertSame('Polonais', $city->getLanguage());
        $this->assertSame('Désert', $city->getEnvironment());

    }
}
