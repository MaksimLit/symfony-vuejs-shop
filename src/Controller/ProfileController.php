<?php declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
