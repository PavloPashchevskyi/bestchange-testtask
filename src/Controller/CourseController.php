<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\RateService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Exception;

class CourseController extends AbstractController
{
    /** @var RateService */
    private $rateService;

    /**
     * CourseController constructor.
     * @param RateService $rateService
     */
    public function __construct(RateService $rateService)
    {
        $this->rateService = $rateService;
    }

    /**
     * @Route("/store", name="course_save", methods={"GET"})
     *
     * @return JsonResponse
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function store(): JsonResponse
    {
        try {
            $data = $this->rateService->store();
            return $this->json($data);
        } catch (Exception $ex) {
            return $this->json([
                'errors' => [
                    'server' => [
                        'code' => $ex->getCode(),
                        'message' => $ex->getMessage(),
                        'trace' => $ex->getTrace(),
                    ],
                ]
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/course/{sendingCurrencyId}/{receivingCurrencyId}", name="course", methods={"GET"})
     *
     * @param int $sendingCurrencyId
     * @param int $receivingCurrencyId
     * @return JsonResponse
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function course(int $sendingCurrencyId, int $receivingCurrencyId): JsonResponse
    {
        try {
            $data = $this->rateService->getByCurrencies($sendingCurrencyId, $receivingCurrencyId);
            return $this->json($data);
        } catch (Exception $ex) {
            return $this->json([
                'errors' => [
                    'server' => [
                        'code' => $ex->getCode(),
                        'message' => $ex->getMessage(),
                        'trace' => $ex->getTrace(),
                    ],
                ]
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/courses", name="courses_list", methods={"GET"})
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function list(Request $request): JsonResponse
    {
        try {
            $data = $this->rateService->get($request->query->all());
            return $this->json($data);
        } catch (Exception $ex) {
            return $this->json([
                'errors' => [
                    'server' => [
                        'code' => $ex->getCode(),
                        'message' => $ex->getMessage(),
                        'trace' => $ex->getTrace(),
                    ],
                ],
            ]);
        }
    }
}
