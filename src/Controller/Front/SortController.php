<?php

namespace App\Controller\Front;

use App\Data\FilterData;
use App\Form\Front\FilterDataType;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Services\PaginationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SortController extends AbstractController
{
    /**
     * @Route("/cities/asc", name="sort_asc")
     */
    public function sortAscAction(
        CityRepository $cityRepository,
        CountryRepository $countryRepository, 
        PaginationService $paginationService, 
        Request $request): Response
    {
        $order = 'ASC';
        // country list for filter menu
        $countries = $countryRepository->findAll();
        $cities = $cityRepository->findCountryAndImageByCity('', $order);

        // sidebar filter form
        $criteria = new FilterData();
        $formFilter = $this->createForm(FilterDataType::class, $criteria);
        $formFilter->handleRequest($request);

        if ($formFilter->isSubmitted() && $formFilter->isValid()) {
            
            $cities = $cityRepository->findByFilter($criteria, $order);
            $cities = $paginationService->paginate($cities);

            return $this->render('front/cities/list.html.twig', [
                'sortOption' => $order, 
                "cities" => $cities, 
                'countries' => $countries,
                'formFilter' => $formFilter->createView()
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
        Request $request): Response
    {
        $order = 'DESC';
        // country list for filter menu
        $countries = $countryRepository->findAll();
        $cities = $cityRepository->findCountryAndImageByCity('', $order);

        // sidebar filter form
        $criteria = new FilterData();
        $formFilter = $this->createForm(FilterDataType::class, $criteria);
        $formFilter->handleRequest($request);

        if ($formFilter->isSubmitted() && $formFilter->isValid()) {
            
            $cities = $cityRepository->findByFilter($criteria, $order);
            $cities = $paginationService->paginate($cities);

            return $this->render('front/cities/list.html.twig', [
                'sortOption' => $order,
                "cities" => $cities, 
                'countries' => $countries,
                'formFilter' => $formFilter->createView()
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
}