<?php

namespace App\Controller\Front;

use App\Data\FilterData;
use App\Form\Front\FilterDataType;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Repository\ReviewRepository;
use App\Services\GoogleApi;
use App\Services\PaginationService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CityController extends AbstractController
{
    /**
     * Cities List
     *
     * @Route("/cities", name="cities_list")
     */
    public function list(
        CityRepository $cityRepository,
        CountryRepository $countryRepository,
        PaginationService $paginationService, 
        Request $request)
    {
        // country list in filter menu
        $countries = $countryRepository->findAll();
        $cities = $cityRepository->findCountryAndImageByCity();

        // sidebar filter form
        $criteria = new FilterData();
        $formFilter = $this->createForm(FilterDataType::class, $criteria);
        $formFilter->handleRequest($request);

        if ($formFilter->isSubmitted() && $formFilter->isValid()) {
            
            $cities = $cityRepository->findByFilter($criteria);
            $cities = $paginationService->paginate($cities);

            return $this->render('front/cities/list.html.twig', [
                "cities" => $cities, 
                "countries" => $countries,
                "formFilter" => $formFilter->createView(),
            ]);
        }

        $cities = $paginationService->paginate($cities);

        return $this->render('front/cities/list.html.twig', [
            'cities' => $cities,
            'countries' => $countries,
            'formFilter' => $formFilter->createView(),
        ]);
    }

    /**
     * City Page
     * 
     * @Route("/cities/{id}", name="cities_detail", requirements={"id":"\d+"})
     */
    public function show(
        $id, 
        CityRepository $cityRepository, 
        ReviewRepository $reviewRepository,
        GoogleApi $googleApi
        ): Response
    {
        $city = $cityRepository->find($id);
        $cityName = $city->getName();
        $countryName = $city->getCountry()->getName();
        $googleMap = $googleApi->fetch($cityName, $countryName);
                
        if ($city === null) {
            throw new Exception("Nous n'avons pas encore de données sur cette ville", 404);
        }

        $allReviews = $reviewRepository->findBy(
            [
                "city" => $city
            ]
        );

        return $this->render('front/cities/show.html.twig', [
            'cityId' => $id,
            'city' => $city,
            "allReviewFromBDD" => $allReviews,
            "googleMap" => $googleMap
        ]);
    }
    
}


