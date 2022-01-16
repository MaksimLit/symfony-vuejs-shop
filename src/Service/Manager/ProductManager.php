<?php declare(strict_types = 1);

namespace App\Service\Manager;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

/**
 * Class ProductManager
 */
class ProductManager extends AbstractBaseManager
{
    /**
     * @var string
     */
    private $productImagesDir;

    /**
     * @var ProductImageManager
     */
    private $productImageManager;

    /**
     * ProductManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ProductImageManager    $productImageManager
     * @param string                 $productImagesDir
     */
    public function __construct(EntityManagerInterface $entityManager, ProductImageManager $productImageManager, string $productImagesDir)
    {
        parent::__construct($entityManager);

        $this->productImagesDir    = $productImagesDir;
        $this->productImageManager = $productImageManager;
    }

    /**
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Product::class);
    }

    /**
     * @param object $product
     */
    public function persistAndFlush(object $product)
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    /**
     * @param object $product
     */
    public function remove(object $product)
    {
        $product->setIsDeleted(true);
        $this->persistAndFlush($product);
    }

    /**
     * @param Product $product
     *
     * @return string
     */
    public function getProductImagesDir(Product $product): string
    {
        return sprintf('%s/%s', $this->productImagesDir, $product->getId());
    }

    /**
     * @param Product     $product
     * @param string|null $tempImageFilename
     *
     * @return Product
     */
    public function updateProductImages(Product $product, string $tempImageFilename = null): Product
    {
        if (!$tempImageFilename) {
            return $product;
        }

        $productDir = $this->getProductImagesDir($product);

        $productImage = $this->productImageManager->saveImageForProduct($productDir, $tempImageFilename);
        $productImage->setProduct($product);
        $product->addProductImage($productImage);

        return $product;
    }
}
