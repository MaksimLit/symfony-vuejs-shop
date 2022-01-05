<?php declare(strict_types = 1);

namespace App\Form\Handler;

use App\Entity\Product;
use App\Service\File\FileSaver;
use App\Service\Manager\ProductManager;
use Symfony\Component\Form\FormInterface;

/**
 * Class ProductFormHandler
 */
class ProductFormHandler
{
    /**
     * @var FileSaver
     */
    private FileSaver $fileSaver;

    /**
     * @var ProductManager
     */
    private ProductManager $productManager;

    public function __construct(ProductManager $productManager, FileSaver $fileSaver)
    {
        $this->fileSaver      = $fileSaver;
        $this->productManager = $productManager;
    }

    /**
     * @param Product       $product
     * @param FormInterface $form
     *
     * @return Product
     */
    public function processEditForm(Product $product, FormInterface $form): Product
    {
        $this->productManager->persistAndFlush($product);

        $newImageFile = $form->get('newImage')->getData();

        $tempImageFilename = $newImageFile
            ? $this->fileSaver->saveUploadedFileIntoTemp($newImageFile)
            : null;

        $this->productManager->updateProductImages($product, $tempImageFilename);

        $this->productManager->persistAndFlush($product);

        return $product;
    }
}
