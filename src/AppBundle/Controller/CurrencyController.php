<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Valutes\OpenUrls;
use Valutes\Parser_Xml;


class CurrencyController extends Controller
{
    /**
     * @param $currency
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getRateAction($currency)
    {
        $gu = new OpenUrls();
        $res = $gu->getUrls();
//        $response = new JsonResponse([
//            'date' => date('d.m.Y H:i:s'),
//            'value' => 70.88
//        ]);
//        $val = $res['valutes'];
//        var_dump($res['valutes']);
        if($currency){
            $currency = strval($currency);
            foreach($res['valutes'] as $val){
                if($val['currency']==$currency){
                    $response = new JsonResponse([
                    'rate' => $val['rate'],
                    'date' => $res['date'],
                    ]);
                    if(!isset($response)){
                        $this->getRateAction($currency);
                    }
                    return $response;
                }
            }
        }

        return new Response('',404);
    }
}
