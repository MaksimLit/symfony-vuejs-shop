<?php declare(strict_types = 1);

namespace App\Controller\Admin;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 *
 * @Route("/admin/product", name="admin_product_")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     *
     * @param ProductRepository $productRepository
     *
     * @return Response
     */
    public function show(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy(
            ['isDeleted' => false],
            ['id' => 'DESC'],
        );

        return $this->render('admin/product/index.html.twig', [
            'products' => $products,
        ]);
    }

//    /**
//     * @Route("/add", name="add")
//     */
//    public function add()
//    {
//
//    }
//
//    /**
//     * @Route("/edit/{id}", name="edit")
//     */
//    public function edit()
//    {
//
//    }
//
//    /**
//     * @Route("/remove/{id}", name="remove")
//     */
//    public function remove()
//    {
//
//    }
}
