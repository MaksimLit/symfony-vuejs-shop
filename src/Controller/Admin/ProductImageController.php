<?php declare(strict_types = 1);

namespace App\Controller\Admin;

use App\Entity\ProductImage;
use App\Service\Manager\ProductImageManager;
use App\Service\Manager\ProductManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductImageController
 *
 * @Route("/admin/product-image", name="admin_product_image_")
 */
class ProductImageController extends AbstractController
{
    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(ProductImage $productImage, ProductManager $productManager, ProductImageManager $productImageManager): Response
    {
        if (!$productImage) {
            return $this->redirectToRoute('admin_product_list');
        }

        $product = $productImage->getProduct();

        $productImagesDir = $productManager->getProductImagesDir($product);

        $productImageManager->removeImageFromProduct($productImage, $productImagesDir);

        return $this->redirectToRoute('admin_product_edit', [
            'id' => $product->getId(),
        ]);
    }
}
