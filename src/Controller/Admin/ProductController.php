<?php declare(strict_types = 1);

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\DTO\EditProductModel;
use App\Form\Handler\ProductFormHandler;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use App\Service\Manager\ProductManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/edit/{id}", name="edit")
     * @Route("/add", name="add")
     *
     * @param Request            $request
     * @param ProductFormHandler $productFormHandler
     * @param Product|null       $product
     *
     * @return RedirectResponse|Response
     */
    public function addOrEdit(Request $request, ProductFormHandler $productFormHandler, Product $product = null): Response
    {
        $editProductModel = EditProductModel::makeFromProduct($product);

        $form = $this->createForm(ProductFormType::class, $editProductModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $productFormHandler->processEditForm($editProductModel, $form);

            $this->addFlash('success', 'Your changes were save!');

            return $this->redirectToRoute('admin_product_edit', ['id' => $product->getId()]);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('warning', 'Something went wrong. Please check your form!');
        }

        $images = $product
            ? $product->getProductImages()->getValues()
            : [];

        return $this->render('admin/product/edit.html.twig', [
            'images'  => $images,
            'product' => $product,
            'form'    => $form->createView()
        ]);
    }


    /**
     * @Route("/delete/{id}", name="delete")
     *
     * @param Product        $product
     * @param ProductManager $productManager
     */
    public function delete(Product $product, ProductManager $productManager)
    {
        $productManager->remove($product);

        $this->addFlash('warning', 'The product was successfully deleted!');

        $this->redirectToRoute('admin_product_list');
    }
}
