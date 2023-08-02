<?php

namespace App\Controller\Front;

use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Services\FilterMenuService;
use App\Services\PaginationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class MainController extends AbstractController
{
    /**
     * Homepage
     * 
     * @Route("/", name="default", methods={"GET", "POST"})
     * 
     * @return Response
     */
    public function home(
        CityRepository $cityRepository, 
        CountryRepository $countryRepository,
        FilterMenuService $filterMenuService): Response
    {
        // country list in filter menu
        $countries = $countryRepository->findAll();
        $cities = $cityRepository->findCountryAndImageByCity('ASC', 'country');

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

        return $this->render('front/main/index.html.twig', [
            'formFilter' => $formFilter->createView(),
            'cities' => $cities,
            'countries' => $countries
        ]);
    }

    /** 
     * Search method by cities name
     *
     * @Route("/search", name="app_front_city_search", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function search(
        CityRepository $cityRepository, 
        CountryRepository $countryRepository,
        Request $request,
        FilterMenuService $filterMenuService,
        PaginationService $paginationService): Response
    {   
        // country list in filter menu
        $countries = $countryRepository->findAll();
        $search = $request->query->get('search', '');

        $cities = $cityRepository->findByCityName($search);
        
        if ($cities === []) {
            throw $this->createNotFoundException("Cette ville n'est pas répertoriée/n'existe pas");
        }
        
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
            'formFilter' => $formFilter->createView(),
            'cities' => $cities,
            'countries' => $countries,
        ]);
    }

    /**
     * About-us page
     * 
     * @Route("/about-us", name="aboutUs", methods={"GET"})
     * 
     * @return Reponse
     */

    public function aboutUs()
    {
        return $this->render('front/footer/about_us.html.twig', [
            
        ]);
    }

    /**
     * Legal Notices page
     * 
     * @Route("/legal-notices", name="legal_notices", methods={"GET"})
     * 
     * @return Reponse
     */

    public function legalNotices()
    {
        return $this->render('front/footer/legal_notices.html.twig', [
            
        ]);
    }

}

