<?php
declare(strict_types=1);

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Filesystem\Filesystem;
use Exception;

class GrabberService
{
    /** @var ContainerInterface */
    private $container;

    /** @var HttpClient */
    private $httpClient;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->httpClient = HttpClient::create();
    }

    /**
     * @return string
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function putContent(): string
    {
        $filesystem = new Filesystem();
        $uploadsDir = $this->container->getParameter('kernel.project_dir').'\public\uploads';
        if (!$filesystem->exists($uploadsDir)) {
            $filesystem->mkdir($uploadsDir);
        }
        $fileName = $this->container->getParameter('info_file');
        $bytes = file_put_contents($uploadsDir."\\".$fileName, $this->grab());
        if ($bytes === false) {
            throw new Exception('Unable to put downloaded file into directory');
        }

        return $uploadsDir."\\".$fileName;
    }

    /**
     * @return string
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function grab()
    {
        $response = $this->httpClient->request('GET', $this->container->getParameter('info_url'));

        return $response->getContent();
    }
}
