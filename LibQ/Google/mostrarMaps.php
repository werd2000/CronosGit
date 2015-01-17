<?php

require_once 'class/EasyGoogleMap.php';
require_once 'class/Inmueble.php';
require_once 'class/ipdetalles.php';

class GMapsPresentacion {

    public $CountryCode;
    public $Code3;
    public $Country;
    public $Region;
    public $City;
    public $PostalCode;
    public $Latitude;
    public $Longitude;
    public $DMAcode;
    public $Areacode;

    public function mostrarMapa() {
//        $gm = & new EasyGoogleMap("ABQIAAAAe7Lwkp2FHX1_YeBLnV-jzRQH1aG4zwy6bmvII87dU1z6P4PQbhS49Rh6M3m-GlwpnMFKx6hz7CtgTQ");
        $gm->SetMarkerIconStyle('HOUSE');
        $gm->SetMapZoom(2);
        $gm->SetMarkerIconColor("GRANITE_PINE");
        $zona = self::_buscarZona($inmueble);
        self::mostrarZona($gm, $zona);
        $gm->SetMapZoom(14);
        //		$gm->mMapType = TRUE; //Para poder elegir el tipo de mapa
        //		$gm->mInset = TRUE;  //Para colocar el mapita dentro del grande
        //		$retorno='<title>EasyGoogleMap</title>';
        echo $gm->GmapsKey();
        echo $gm->MapHolder();
        echo $gm->InitJs();
        //		echo $gm->GetSideClick();
        echo $gm->UnloadMap();
    }

    private function mostrarZona($gm, $zona) {
        $gm->SetAddress($zona[direccion]);
        $gm->SetInfoWindowText("<b>$zona[titulo]</b>");
    }

    //	private function _mostrarPais($gm, $pais) {
    //		self::_listarProvincias($gm,$pais);
    //	}
    private function _buscarZona($id) {
        $ficha = Inmueble::buscarInmueble($id);
        if ($ficha->getUbicacion() != null) {
            $direccion = $ficha->getUbicacion() . ", " . $ficha->getCiudad() . ", " . $ficha->getProvincia() . ", " . $ficha->getPais();
        } else {
            $direccion = $ficha->getCiudad() . ", " . $ficha->getProvincia() . ", " . $ficha->getPais();
        }
        return array('direccion' => $direccion, 'titulo' => $ficha->getTitulo());
    }

}
