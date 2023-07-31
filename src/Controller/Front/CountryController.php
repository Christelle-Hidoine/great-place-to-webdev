<?php

namespace App\Controller\Front;

use App\Data\FilterData;
use App\Form\Front\FilterDataType;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Services\PaginationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CountryController extends AbstractController
{

    /**
     * Cities list by country
     * 
     * @Route("/cities/country/{id}", name="cities_country", requirements={"id"="\d+"})
     * 
     * @return Response
     */
    public function countryShow(
        $id, 
        CountryRepository $countryRepository,
        CityRepository $cityRepository,
        PaginationService $paginationService,
        Request $request): Response
    {
        // country list in filter menu
        $countries = $countryRepository->findAll();

        $countryId = $countryRepository->find($id);
        $cities = $cityRepository->findByCountry($countryId);

        if ($cities === null) {
            throw $this->createNotFoundException("Ce pays n'a pas de villes enregistrées pour le moment");
        }
        if ($countryId === null) {
            throw $this->createNotFoundException("Ce pays n'est pas répertorié");
        }

        // sidebar filter form
        $criteria = new FilterData();
        $formFilter = $this->createForm(FilterDataType::class, $criteria);
        $formFilter->handleRequest($request);

        if ($formFilter->isSubmitted() && $formFilter->isValid()) {
            
            $cities = $cityRepository->findByFilter($criteria);
            $cities = $paginationService->paginate($cities);

            return $this->render('front/cities/list.html.twig', [
                "cities" => $cities, 
                'countries' => $countries, 
                'formFilter' => $formFilter->createView(),
            ]);
        }

        $cities = $paginationService->paginate($cities);
        
        return $this->render("front/cities/list.html.twig", [
               'cities' => $cities,
               'countries' => $countries,
               'formFilter' => $formFilter->createView(),
            ]
        );
    }
}