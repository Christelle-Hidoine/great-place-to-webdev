<?php

namespace App\Controller\Back;

use App\Entity\Review;
use App\Form\Front\ReviewType;
use App\Repository\ReviewRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/back/review", name="app_back_review_")
 * 
 * @IsGranted("ROLE_ADMIN")
 */
class ReviewController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(ReviewRepository $reviewRepository, PaginatorInterface $paginatorInterface, Request $request): Response
    {
        $reviews = $reviewRepository->findAll();
        $reviews = $paginatorInterface->paginate($reviews, $request->query->getInt('page', 1),5);
        
        return $this->render('back/review/index.html.twig', [
            'reviews' => $reviews,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, ReviewRepository $reviewRepository): Response
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reviewRepository->add($review, true);
            $this->addFlash("success", "Votre Review a bien été ajoutée.");

            return $this->redirectToRoute('app_back_review_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/review/new.html.twig', [
            'review' => $review,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"}, requirements={"id":"\d+"})
     */
    public function show(Review $review): Response
    {
        if ($review === null){throw $this->createNotFoundException("Cette review n'existe pas");}

        return $this->render('back/review/show.html.twig', [
            'review' => $review,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function edit(Request $request, Review $review, ReviewRepository $reviewRepository): Response
    {
        if ($review === null){throw $this->createNotFoundException("Cette review n'existe pas");}

        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reviewRepository->add($review, true);

            return $this->redirectToRoute('app_back_review_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/review/edit.html.twig', [
            'review' => $review,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"}, requirements={"id":"\d+"})
     */
    public function delete(Request $request, Review $review, ReviewRepository $reviewRepository): Response
    {
        if ($review === null){throw $this->createNotFoundException("Cette review n'existe pas");}

        if ($this->isCsrfTokenValid('delete'.$review->getId(), $request->request->get('_token'))) {
            $reviewRepository->remove($review, true);
        }

        return $this->redirectToRoute('app_back_review_index', [], Response::HTTP_SEE_OTHER);
    }
}
