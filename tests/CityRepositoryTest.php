<?php

namespace App\Tests;

use App\Entity\City;
use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CityRepositoryTest extends KernelTestCase
{
    /**
     * @var CityRepository
     */
    private $cityRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->cityRepository = static::getContainer()->get(CityRepository::class);
    }

    public function testFindCityById(): void
    {
        $city = $this->cityRepository->find(514);
        $this->assertInstanceOf(City::class, $city);
    }

    public function testFindByCountry(): void
    {
        $cities = $this->cityRepository->findByCountry(101);
        $this->assertNotEmpty($cities);
        $this->assertInstanceOf(City::class, $cities[0]);
    }

    public function testFindByCityName(): void
    {
        $cityName = 'Paris';
        $cityName2 = 'Paris-sur-Chauvin';
        $cities = $this->cityRepository->findByCityName($cityName);

        $this->assertNotEmpty($cities);
        
        $this->assertEquals($cityName, $cities[0]['cityName']);
        $this->assertEquals($cityName2, $cities[1]['cityName']);

    }

    public function testFindCountryAndImageByCity(): void
    {
        $order = 'ASC'; // You can set the order if required
        $result = $this->cityRepository->findCountryAndImageByCity($order);
        
        $this->assertNotEmpty($result);

        $expectedCityName = 'Adam';

        $actualCityName = $result[0]['cityName'];

        $this->assertEquals($expectedCityName, $actualCityName);
    }
}
