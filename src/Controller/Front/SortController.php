<?php

namespace App\Controller\Front;

use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Services\FilterMenuService;
use App\Services\PaginationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortController extends AbstractController
{
    /**
     * @Route("/cities/asc", name="sort_asc")
     */
    public function sortAscAction(
        CityRepository $cityRepository,
        CountryRepository $countryRepository, 
        PaginationService $paginationService,
        FilterMenuService $filterMenuService): Response
    {
        $order = 'ASC';
        // country list for filter menu
        $countries = $countryRepository->findAll();
        $cities = $cityRepository->findCountryAndImageByCity('', $order);

        // sidebar filter menu
        $formFilter = $filterMenuService->createFormFilterMenu($filterMenuService->getCriteria());

        $filteredData = $filterMenuService->getFilteredCities($cities);
        $citiesFiltered = $filteredData['cities'];

        if ($formFilter !== null && $citiesFiltered !== null) {
            return $this->render('front/cities/list.html.twig', [
                "cities" => $citiesFiltered,
                'countries' => $countries,
                'formFilter' => $formFilter->createView(),
            ]);
        }

        $cities = $paginationService->paginate($cities);

        return $this->render('front/cities/list.html.twig', [
            'cities' => $cities,
            'countries' => $countries,
            'sortOption' => $order,
            'formFilter' => $formFilter->createView()
        ]);
    }

    /**
     * @Route("/cities/desc", name="sort_desc")
     */
    public function sortDescAction(
        CityRepository $cityRepository, 
        CountryRepository $countryRepository,
        PaginationService $paginationService,
        FilterMenuService $filterMenuService): Response
    {
        $order = 'DESC';
        // country list for filter menu
        $countries = $countryRepository->findAll();
        $cities = $cityRepository->findCountryAndImageByCity('', $order);

        // sidebar filter menu
        $formFilter = $filterMenuService->createFormFilterMenu($filterMenuService->getCriteria());

        $filteredData = $filterMenuService->getFilteredCities($cities);
        $citiesFiltered = $filteredData['cities'];

        if ($formFilter !== null && $citiesFiltered !== null) {
            return $this->render('front/cities/list.html.twig', [
                "cities" => $citiesFiltered,
                'countries' => $countries,
                'formFilter' => $formFilter->createView(),
            ]);
        }

        $cities = $paginationService->paginate($cities);
    
        return $this->render('front/cities/list.html.twig', [
            'cities' => $cities,
            'countries' => $countries,
            'formFilter' => $formFilter->createView()
        ]);
    }
}