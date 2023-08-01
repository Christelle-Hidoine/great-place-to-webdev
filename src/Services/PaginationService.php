<?php

namespace App\Services;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginationService
{
    private $paginator;
    private $request;

    public function __construct(PaginatorInterface $paginator, RequestStack $request)
    {
        $this->paginator = $paginator;
        $this->request = $request;
    }

    public function paginate($data)
    {
        return $this->paginator->paginate(
            $data,
            $this->request->getCurrentRequest()->query->getInt('page', 1),
            6
        );
    }
}
