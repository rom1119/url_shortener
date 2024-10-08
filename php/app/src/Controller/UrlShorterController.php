<?php
namespace App\Controller;

use App\Entity\ShortUrl;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;


class UrlShorterController
{

    #[Route('/make-short-url', name: 'shorten_url', methods: ['POST'])]

    public function number(Request $request, ManagerRegistry $doctrine): Response
    {

        $params = json_decode($request->getContent());
        if (!isset($params->urlParam)) {
            return new JsonResponse(['err' => 'urlParam not sended in json'], Response::HTTP_BAD_REQUEST);
        }

        $urlParam = $params->urlParam;
        $out = [];
        preg_match_all("/^(http|https):\/\/[a-zA-Z0-9.][a-zA-Z0-9-.]{1,61}(\.[a-zA-Z]{2,}|\:[0-9]{2,4})(\/[\/\-a-zA-Z0-9]{1,})$/", $urlParam, $out);
        $matches = $out[0];

        if (!$matches) {
            return new JsonResponse(['err' => 'Invalid URL'], Response::HTTP_BAD_REQUEST);
        }

        $entityManager = $doctrine->getManager();

        $newUrl = $out[3][0];
        $existUrl = $doctrine->getRepository(ShortUrl::class)->findOneBy(['matched_url' => $newUrl]);
        if ($existUrl) {
            return new JsonResponse(['err' =>'URL is exist'], Response::HTTP_BAD_REQUEST);
        }
        $url = new ShortUrl();
        $url->setActive(true);
        $url->setMatchedUrl($newUrl);

        $generatedShortUrl = substr(md5(uniqid()), 0, 6);
        $url->setShortCodeForUrl($generatedShortUrl);

        $entityManager->persist($url);

        $entityManager->flush();
        return new JsonResponse(['short_url'=> $generatedShortUrl]);
    }

    #[Route('/short-url/{code}', name: 'get_short_url')]
    public function getOriginal($code, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $url = $entityManager->getRepository(ShortUrl::class)->findOneBy(['short_code_for_url' => $code, 'active' => true]);

        if (!$url) {
            return new JsonResponse('ok', 404);
        }

        return new JsonResponse(json_encode(['real_url'=> $url->getMatchedUrl()]), 404);
    }
}