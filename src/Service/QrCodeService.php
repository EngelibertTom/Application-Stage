<?php

namespace App\Service;

use App\Entity\QrCode;
use Endroid\QrCode\Exception\ValidationException;
use Endroid\QrCode\Factory\QrCodeFactory;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class QrCodeService
{
    private const PATH_QRCODE = __DIR__. '/../../public';

    private $filesystem;
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator, Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->urlGenerator = $urlGenerator;
    }

    abstract protected function getNameRouteRedirectQrCode(): string;
    abstract protected function getNameFileQrCode(QrCode $entity): string;

    /**
     * Génère le QrCode d'un élèment avec un route de redirection.
     *
     * @param QrCode $entity
     * @return string
     */
    public function generateQrCode(QrCode $entity): string
    {
        $fileQrCode = null;
        $qrCodeFactory = new QrCodeFactory();

        try
        {
            $qrCode = $qrCodeFactory->create($this->getRouteRedirectQrCode($entity));

            $fileQrCode = sprintf('%s%s', self::PATH_QRCODE, $this->getNameFileQrCode($entity));
            $qrCode->writeFile($fileQrCode);

        } catch (ValidationException $exception) {}

        return $this->getNameFileQrCode($entity);
    }

    /**
     * Génère la route de redirection.
     *
     * @param QrCode $entity
     * @return string
     */
    public function getRouteRedirectQrCode(QrCode $entity): string
    {
        return $this->urlGenerator->generate(
            $this->getNameRouteRedirectQrCode(),
            ['id' => $entity->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    /**
     * Supprime l'image du QrCode d'un élèment.
     * @param QrCode $entity
     */
    public function removeQrCode(QrCode $entity): void
    {
        $fileQrCode = sprintf('%s%s', self::PATH_QRCODE, $this->getNameFileQrCode($entity));
        $this->filesystem->remove($fileQrCode);
    }
}
