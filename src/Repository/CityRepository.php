<?php

namespace App\Repository;

use App\Data\FilterData;
use App\Entity\City;
use App\Entity\Country;
use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

/**
 * @extends ServiceEntityRepository<City>
 *
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    public function add(City $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(City $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByCountry($id, $order = null, $group = null)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('c.id AS cityId, i.id AS imageId, i.url AS imageUrl, c.name AS cityName, c.rating AS cityRating, c.area AS cityArea, c.electricity AS cityElectricity, c.internet AS cityInternet, c.sunshineRate AS citySunshineRate, c.temperatureAverage AS cityTemperatureAverage, c.cost AS cityCost, c.language AS cityLanguage, c.demography AS cityDemography, c.housing AS cityHousing, c.timezone AS cityTimezone, c.environment AS cityEnvironment, co.name AS countryName, co.id AS countryId')
            ->innerJoin('c.country', 'co', 'WITH', 'co.id = :country_id')
            ->innerJoin('c.images', 'i')
            ->setParameter('country_id', $id)
            ->andWhere($qb->expr()->eq(
                '(SELECT COUNT(img.id) 
                    FROM App\Entity\Image img 
                    WHERE img.city = c.id 
                    AND img.id <= i.id)',
                1
            ));
            

        if ($group !== null) {
            $qb->addGroupBy($group == 'country' ? 'co.id' : 'c.id');
        }

        if ($order !== null) {
            $qb->orderBy('c.name', $order === 'DESC' ? 'DESC' : 'ASC');
        }

        return $qb->getQuery()->getResult();
    }

    public function findCountryAndImageByCity($group = null, $order = null)
    {
        $entityManager = $this->getEntityManager();

        $dql = "
        SELECT c.id AS cityId, i.id AS imageId, i.url AS imageUrl, c.name AS cityName, c.rating AS cityRating, c.area AS cityArea, c.createdAt AS cityCreatedAt, c.updatedAt AS cityUpdatedAt, c.electricity AS cityElectricity, c.internet AS cityInternet, c.sunshineRate AS citySunshineRate, c.temperatureAverage AS cityTemperatureAverage, c.cost AS cityCost, c.language AS cityLanguage, c.demography AS cityDemography, c.housing AS cityHousing, c.timezone AS cityTimezone, c.environment AS cityEnvironment, co.name AS countryName, co.id AS countryId
        FROM App\Entity\City c
        JOIN App\Entity\Image i WITH i.city = c
        JOIN App\Entity\Country co WITH c.country = co
        WHERE (
            SELECT COUNT(img.id) 
            FROM App\Entity\Image img 
            WHERE img.city = c.id 
            AND img.id <= i.id) 
            = 1
        ";

        if ($group !== null) {
            $dql .= " GROUP BY " . ($group == 'country' ? 'co.id' : 'c.id');
        }
        if ($order !== null) {
            $dql .= " ORDER BY c.name " . ($order === 'DESC' ? 'DESC' : 'ASC');
        }

        $query = $entityManager->createQuery($dql);
        $sortedCities = $query->getResult();

        return $sortedCities;
    }

    public function findByCityName($search)
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->select('c.id AS cityId, c.name AS cityName, c.rating AS cityRating, co.id AS countryId, co.name AS countryName, i.id AS imageId, i.url AS imageUrl')
                    ->where($queryBuilder->expr()->like('c.name', ':name'))
                    ->innerJoin('c.country', 'co')
                    ->innerJoin('c.images', 'i')
                    ->andWhere($queryBuilder->expr()->eq(
                        '(SELECT COUNT(img.id) 
                            FROM App\Entity\Image img 
                            WHERE img.city = c.id 
                            AND img.id <= i.id)',
                        1
                    ))
                    ->orderBy('c.name', 'ASC')
                    ->setParameter('name', "$search%");

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Retrieve data from database with criteria passed in filter form
     *
     * @param FilterData $filerData
     * @return array
     */
    public function findByFilter(FilterData $filterData, $order = null)
    {
        $query = $this->createQueryBuilder('c');
        $query->select('c.id AS cityId, i.id AS imageId, i.url AS imageUrl, c.name AS cityName, c.rating AS cityRating, c.area AS cityArea, c.electricity AS cityElectricity, c.internet AS cityInternet, c.sunshineRate AS citySunshineRate, c.temperatureAverage AS cityTemperatureAverage, c.cost AS cityCost, c.language AS cityLanguage, c.demography AS cityDemography, c.housing AS cityHousing, c.timezone AS cityTimezone, c.environment AS cityEnvironment, co.name AS countryName, co.id AS countryId')
            ->join('c.country', 'co')
            ->innerJoin('c.images', 'i')
            ->andWhere($query->expr()->eq(
                '(SELECT COUNT(img.id) 
                    FROM App\Entity\Image img 
                    WHERE img.city = c.id 
                    AND img.id <= i.id)',
                1
            ));

            // electricity
            if (!empty($filterData->electricityLevel)) {
                $query = $query
                    ->andWhere('c.electricity LIKE :filterData')
                    ->setParameter("filterData", "%$filterData->electricityLevel%");
            }
            // internet
            if (!empty($filterData->internetLevel)) {
                $query = $query
                    ->andWhere('c.internet LIKE :filterData')
                    ->setParameter("filterData", "%$filterData->internetLevel%");
            }
            // sunshine
            if (!empty($filterData->sunshineLevel)) {
                $query = $query
                    ->andWhere('c.sunshineRate LIKE :filterData')
                    ->setParameter("filterData", "%$filterData->sunshineLevel%");
            }
            // housing
            if (!empty($filterData->housingLevel)) {
                $query = $query
                    ->andWhere('c.housing LIKE :filterData')
                    ->setParameter("filterData", "%$filterData->housingLevel%");
            }
            // temperature
            if (!empty($filterData->temperatureMin)) {
                $query = $query
                    ->andWhere('c.temperatureAverage >= :temperatureMin')
                    ->setParameter('temperatureMin', $filterData->temperatureMin);
            }
            if (!empty($filterData->temperatureMax)) {
                $query = $query
                    ->andWhere('c.temperatureAverage <= :temperatureMax')
                    ->setParameter('temperatureMax', $filterData->temperatureMax);
            }
            // demography
            if (!empty($filterData->demographyMin)) {
                $query = $query
                    ->andWhere('c.demography >= :demographyMin')
                    ->setParameter('demographyMin', $filterData->demographyMin);
            }
            if (!empty($filterData->demographyMax)) {
                $query = $query
                    ->andWhere('c.demography <= :demographyMax')
                    ->setParameter('demographyMax', $filterData->demographyMax);
            }
            // cost
            if (!empty($filterData->costMin)) {
                $query = $query
                    ->andWhere('c.cost >= :costMin')
                    ->setParameter('costMin', $filterData->costMin);
            }
            if (!empty($filterData->costMax)) {
                $query = $query
                    ->andWhere('c.cost <= :costMax')
                    ->setParameter('costMax', $filterData->costMax);
            }
            // area
            if (!empty($filterData->areaMin)) {
                $query = $query
                    ->andWhere('c.area >= :areaMin')
                    ->setParameter('areaMin', $filterData->areaMin);
            }
            if (!empty($filterData->areaMax)) {
                $query = $query
                    ->andWhere('c.area >= :areaMax')
                    ->setParameter('areaMax', $filterData->areaMax);
            }
            // timezone
            if (!empty($filterData->timezone)) {
                $query = $query
                    ->andWhere('c.timezone = :timezone')
                    ->setParameter('timezone', $filterData->timezone);
            }
            // currency
            if (!empty($filterData->currencyType)) {
                $query = $query
                    ->andWhere('co.currency = :currencyType')
                    ->setParameter('currencyType', $filterData->currencyType);
            }
            // visa
            if (!empty($filterData->visaRequired)) {
                $query = $query
                    ->andWhere('co.visaIsRequired = 1');
            }
            if (!empty($filterData->visaType)) {
                $query = $query
                    ->andWhere('co.visa = :visaType')
                    ->setParameter('visaType', $filterData->visaType);
            }
            // language
            if (!empty($filterData->language)) {
                $query = $query
                    ->andWhere('c.language = :language')
                    ->setParameter('language', $filterData->language);  
            }
            // environment
            if (!empty($filterData->environment)) {
                $query = $query
                    ->andWhere('c.environment = :environment')
                    ->setParameter('environment', $filterData->environment);  
            }

            if ($order !== null) {
                $query = $query->orderBy("c.name", $order);
            }

        return $query->getQuery()->getResult();  
    }
}

