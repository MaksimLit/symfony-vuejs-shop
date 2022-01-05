<?php declare(strict_types = 1);

namespace App\Service\Manager;

use App\Entity\ProductImage;
use App\Service\File\ImageResizer;
use App\Service\Filesystem\FilesystemWorker;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ProductImageManager
 */
class ProductImageManager
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var FilesystemWorker
     */
    private FilesystemWorker $filesystemWorker;

    /**
     * @var string
     */
    private string $uploadsTempDir;

    /**
     * @var ImageResizer
     */
    private ImageResizer $imageResizer;

    /**
     * ProductImageManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param FilesystemWorker       $filesystemWorker
     * @param ImageResizer           $imageResizer
     * @param string                 $uploadsTempDir
     */
    public function __construct(EntityManagerInterface $entityManager, FilesystemWorker $filesystemWorker, ImageResizer $imageResizer, string $uploadsTempDir)
    {
        $this->entityManager    = $entityManager;
        $this->filesystemWorker = $filesystemWorker;
        $this->uploadsTempDir   = $uploadsTempDir;
        $this->imageResizer     = $imageResizer;
    }

    /**
     * @param string      $productDir
     * @param string|null $tempImageFilename
     *
     * @return ProductImage|null
     */
    public function saveImageForProduct(string $productDir, string $tempImageFilename = null): ?ProductImage
    {
        if (!$tempImageFilename) {
            return null;
        }

        $this->filesystemWorker->createFolderIfItNotExist($productDir);

        $filenameId = uniqid();
        $imageSmallParams = [
            'width'       => 60,
            'height'      => null,
            'newFolder'   => $productDir,
            'newFilename' => sprintf('%s_%s.jpg', $filenameId, 'small'),
        ];
        $imageSmall = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $tempImageFilename, $imageSmallParams);

        $imageMiddleParams = [
            'width'       => 430,
            'height'      => null,
            'newFolder'   => $productDir,
            'newFilename' => sprintf('%s_%s.jpg', $filenameId, 'middle')
        ];
        $imageMiddle = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $tempImageFilename, $imageMiddleParams);

        $imageBigParams = [
            'width'       => 800,
            'height'      => null,
            'newFolder'   => $productDir,
            'newFilename' => sprintf('%s_%s.jpg', $filenameId, 'big')
        ];
        $imageBig = $this->imageResizer->resizeImageAndSave($this->uploadsTempDir, $tempImageFilename, $imageBigParams);

        $productImage = new ProductImage();
        $productImage->setFilenameSmall($imageSmall);
        $productImage->setFilenameMiddle($imageMiddle);
        $productImage->setFilenameBig($imageBig);

        return $productImage;
    }

    /**
     * @param ProductImage $productImage
     * @param string       $productDir
     */
    public function removeImageFromProduct(ProductImage $productImage, string $productDir)
    {
        $smallFilePath = $productDir.'/'.$productImage->getFilenameSmall();
        $this->filesystemWorker->remove($smallFilePath);

        $middleFilePath = $productDir.'/'.$productImage->getFilenameMiddle();
        $this->filesystemWorker->remove($middleFilePath);

        $bigFilePath = $productDir.'/'.$productImage->getFilenameBig();
        $this->filesystemWorker->remove($bigFilePath);

        $product = $productImage->getProduct();
        $product->removeProductImage($productImage);

        $this->entityManager->flush();
    }
}
