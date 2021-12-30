<?php declare(strict_types = 1);

namespace App\Controller;

use App\Form\ProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProfileController
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="main_profile")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('main/profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    /**
     * @Route("/profile/edit", name="main_profile_edit")
     *
     * @param Request                $request
     * @param EntityManagerInterface $em
     *
     * @return RedirectResponse|Response
     */
    public function edit(Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('main_profile');
        }

        return $this->render('main/profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
