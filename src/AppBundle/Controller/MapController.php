<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Base\Coordinate;
use Ivory\GoogleMap\Control\ControlPosition;
use Ivory\GoogleMap\Control\MapTypeControl;
use Ivory\GoogleMap\Control\MapTypeControlStyle;
use Ivory\GoogleMap\MapTypeId;
use Ivory\GoogleMap\Control\ZoomControl;
use Ivory\GoogleMap\Control\StreetViewControl;
use Ivory\GoogleMap\Helper\Builder\ApiHelperBuilder;
use Ivory\GoogleMap\Helper\Builder\MapHelperBuilder;

class MapController extends Controller {
    
    public function viewAction(Request $request) {
          
//    $mapTypeControl = new MapTypeControl(
//        [MapTypeId::ROADMAP, MapTypeId::SATELLITE],
//        ControlPosition::TOP_LEFT,
//        MapTypeControlStyle::DEFAULT_
//    );
//    $zoomControl = new ZoomControl();
//    $streetViewControl = new StreetViewControl();  
        
    $mapHelperBuilder = MapHelperBuilder::create(); 
    $mapHelper = $mapHelperBuilder->build();
    
    $apiHelperBuilder = ApiHelperBuilder::create();
    $apiHelperBuilder->setLanguage('fr');
    $apiHelperBuilder->setKey($this->getParameter('google_api_key'));
    $apiHelper = $apiHelperBuilder->build();
    
    $map = new Map();
    $map->setHtmlId('map_canvas');
    $map->setCenter(new Coordinate(50.6, 5.5));
    $map->setMapOption('zoom', 10);
//    $map->setMapOption('language', 'fr');
    $map->setStylesheetOptions(array(
        'width'  => '100%',
        'height' => '500px',
        'padding' => 0
    ));
//    $map->setLanguage('fr');
//    $map->getControlManager()->setMapTypeControl($mapTypeControl);
//    $map->getControlManager()->setZoomControl($zoomControl);
//    $map->getControlManager()->setStreetViewControl($streetViewControl);
    
//    dump($mapHelper->renderHtml($map));die();
//        dump($apiHelper->render([$apiHelperBuilder]));die();
//        dump($apiHelper->render([$apiHelperBuilder]),$apiHelper->render([$map]), $apiHelperBuilder, $map);die();
    return $this->render('AppBundle::map.html.twig', array(
        'map' => $map,
        'map_api' => $apiHelper->render([$map])
    ));

    }

}
