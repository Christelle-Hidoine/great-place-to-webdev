<?php

namespace App\Services;

use App\Data\FilterData;
use App\Form\Front\FilterDataType;
use App\Repository\CityRepository;
use App\Services\PaginationService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class FilterMenuService
{
    private $formFactory;
    private $request;
    private $cityRepository;
    private $paginationService;

    public function __construct(
        FormFactoryInterface $formFactory, 
        RequestStack $requestStack, 
        CityRepository $cityRepository,
        PaginationService $paginationService)
    {
        $this->formFactory = $formFactory;
        $this->request = $requestStack->getCurrentRequest();
        $this->cityRepository = $cityRepository;
        $this->paginationService = $paginationService;
    }

    public function getCriteria() {
        return new FilterData();
    }

    public function createFormFilterMenu($criteria) {
        $formFilter = $this->formFactory->create(FilterDataType::class, $criteria);
        return $formFilter->handleRequest($this->request);
    }

    public function getFilteredCities(array $cities)
    {   $criteria = $this->getCriteria();
        $formFilter = $this->createFormFilterMenu($criteria);

        $filteredData = ['cities' => null];

        if ($formFilter->isSubmitted() && $formFilter->isValid()) {
            $cities = $this->cityRepository->findByFilter($criteria);
            $cities = $this->paginationService->paginate($cities);
            $filteredData['cities'] = $cities;
        }

        return $filteredData;
    }
}
