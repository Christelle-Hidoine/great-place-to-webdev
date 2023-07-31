<?php

namespace App\Controller\Front;

use App\Data\FilterData;
use App\Form\Front\FilterDataType;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
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
        Request $request, 
        PaginationService $paginationService): Response
    {
        // country list in filter menu
        $countries = $countryRepository->findAll();
        $cities = $cityRepository->findCountryAndImageByCity('ASC', 'country');

        // sidebar filter form
        $criteria = new FilterData();
        $formFilter = $this->createForm(FilterDataType::class, $criteria);
        $formFilter->handleRequest($request);
        

        if ($formFilter->isSubmitted() && $formFilter->isValid()) {

            $cities = $cityRepository->findByFilter($criteria);
            $cities = $paginationService->paginate($cities);

            return $this->render('front/cities/list.html.twig', [
                "countries" => $countries, 
                "cities" => $cities, 
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
        PaginationService $paginationService): Response
    {   
        // country list in filter menu
        $countries = $countryRepository->findAll();
        $search = $request->query->get('search', '');

        $cities = $cityRepository->findByCityName($search);
        
        if ($cities === []) {
            throw $this->createNotFoundException("Cette ville n'est pas répertoriée/n'existe pas");
        }

        $cities = $paginationService->paginate($cities);
        
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
                'formFilter' => $formFilter->createView()
            ]);
        }

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

    /**
     * create cookie 
     * 
     * @Route("/cookie", name="accept_cookie", methods={"GET"})
     *
     * @return Response
     */
    public function cookies(): Response
    {
        $response = new Response();
        $response->headers->setCookie(new Cookie('accept-cookie', 'chocolate', strtotime('+1 year')));
        $response->sendHeaders();

        return $this->redirectToRoute('default');
    }

}

