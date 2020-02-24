<?php
declare(strict_types=1);

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Entity\Course;
use App\Repository\CourseRepository;

class RateService
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var GrabberService
     */
    private $grabberService;

    /**
     * @var ZipExtractorService
     */
    private $zipExtractorService;

    /**
     * @var ParserService
     */
    private $parserService;

    /**
     * @var CalculatorService
     */
    private $calculatorService;

    /**
     * @var CourseRepository
     */
    private $courseRepository;

    /**
     * RateService constructor.
     * @param ContainerInterface $container
     * @param GrabberService $grabberService
     * @param ZipExtractorService $zipExtractorService
     * @param ParserService $parserService
     * @param CalculatorService $calculatorService
     * @param CourseRepository $courseRepository
     */
    public function __construct(
        ContainerInterface $container,
        GrabberService $grabberService,
        ZipExtractorService $zipExtractorService,
        ParserService $parserService,
        CalculatorService $calculatorService,
        CourseRepository $courseRepository
    ) {
        $this->container = $container;
        $this->grabberService = $grabberService;
        $this->zipExtractorService = $zipExtractorService;
        $this->parserService = $parserService;
        $this->calculatorService = $calculatorService;
        $this->courseRepository = $courseRepository;
    }

    /**
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function store(): array
    {
        // get data from downloaded and parsed file
        $data = $this->downloadAndParse();
        // calculate data
        $profitableRateRecord = $this->calculatorService->calculateProfitableRate($data);

        // store data
        $course = new Course();
        $course->setSendingCurrencyId((int) $profitableRateRecord['sending_currency_id']);
        $course->setSendingRate((float) $profitableRateRecord['sending_rate']);
        $course->setReceivedCurrencyId((int) $profitableRateRecord['receiving_currency_id']);
        $course->setReceivedRate((float) $profitableRateRecord['receiving_rate']);
        $this->courseRepository->store($course);

        return $profitableRateRecord;
    }

    /**
     * @param int $sendingCurrencyId
     * @param int $receivingCurrencyId
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getByCurrencies(int $sendingCurrencyId, int $receivingCurrencyId): array
    {
        $data = $this->downloadAndParse();
        return $this->calculatorService->getByCurrencies($data, $sendingCurrencyId, $receivingCurrencyId);
    }

    /**
     * @param array $filters
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function get(array $filters = []): array
    {
        $data = $this->downloadAndParse();
        return $this->calculatorService->filter($data, $filters);
    }

    /**
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function downloadAndParse(): array
    {
        $fileFromArchive = $this->container->getParameter('file_to_extract');
        $localZipArchiveName = $this->grabberService->putContent();
        $unzipFileName = $this->zipExtractorService->unzip($localZipArchiveName, $fileFromArchive);

        return $this->parserService->parse($unzipFileName);
    }
}
