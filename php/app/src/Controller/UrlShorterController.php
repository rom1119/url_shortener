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

    #[Route('/from-short-url', name: 'from_short_url')]
    public function getOriginal(Request $request, ManagerRegistry $doctrine): Response
    {
        $code = $request->query->get('code');

        $entityManager = $doctrine->getManager();

        $url = $entityManager->getRepository(ShortUrl::class)->findOneBy(['short_code_for_url' => $code, 'active' => true]);

        if (!$url) {
            return new JsonResponse('ok', 404);
        }

        return new JsonResponse(['real_url'=> $url->getMatchedUrl()]);
    }
    
    #[Route('/from-real-url', name: 'from_real_url')]
    public function getRealUrl(Request $request, ManagerRegistry $doctrine): Response
    {
        $real_url = $request->query->get('real_url');
        $entityManager = $doctrine->getManager();
        $real_url = '/' . $real_url;
        $url = $entityManager->getRepository(ShortUrl::class)->findOneBy(['matched_url' => $real_url, 'active' => true]);

        if (!$url) {
            return new JsonResponse('ok', 404);
        }

        return new JsonResponse(['real_url'=> $url->getMatchedUrl()]);
    }

    #[Route('/list-url', name: 'get_list_url')]
    public function getListUrl(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $urls = $entityManager->getRepository(ShortUrl::class)->findBy(['active' => true]);

        $res = [];
        /** @var  ShortUrl $item  */
        foreach($urls as $item) {
            $res[] = [
                'oryginal_path' => $item->getMatchedUrl(),
                'short_code' => $item->getShortCodeForUrl(),
            ];
        }

        return new JsonResponse($res);
    }
}