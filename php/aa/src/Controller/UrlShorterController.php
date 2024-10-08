<?php
namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Response;


class UrlShorterController
{
    /**
     * @Route("/short_url")
     */
    // public function number(EntityManagerInterface $em): Response
    public function number(): Response
    {
        return new Response('asdas');
    }
}