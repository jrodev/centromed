<?php

/**
 * @viewHelper
 * pintando seccion activa e imagen de portada para la seccion
 * @author Jaime Rodriguez <jrodev@yahoo.es>
 */
namespace Portal\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Mvc\MvcEvent;

class Portal extends AbstractHelper
{

    private $mca       = array('modu'=>'application','ctrl'=>'index','acti'=>'index');
    private $img       = '';
    private $baseUrl   = '';
    private $baseJs    = '';
    private $baseCss   = '';
    private $urlIfrmPE = '';
    private $admlte    = '';

    public function __construct($config, MvcEvent $evt)
    {

        $routeMatch = $evt->getRouteMatch();
        $request    = $evt->getRequest();

        if ($routeMatch) {
            flog('$routeMatch:', $routeMatch);
            $aCtrll = explode('\\', $routeMatch->getParam('controller')); 
            $this->mca['modu'] = strtolower( count($aCtrll)?$aCtrll[0]:explode('\\', $routeMatch->getParam('__NAMESPACE__'))[0] );
            $this->mca['ctrl'] = strtolower($aCtrll[2]);
            $this->mca['acti'] = strtolower($routeMatch->getParam('action'));
            $this->uriPath     = $request->getUri()->getPath();
        }
        
        $ctrl = $this->mca['ctrl'];
        $acti = $this->mca['acti'];
        $this->baseUrl = $config['view_manager']['base_path'];
        $this->baseJs  = $config['view_manager']['base_path'].'/js';
        $this->baseCss = $config['view_manager']['base_path'].'/css';
        $this->admlte  = $config['view_manager']['base_path'].'/admlte';
        
        if(!($ctrl=='index' && $acti=='index')){
            $acti = ($acti=='solicite-cita2')?'solicite-cita':$acti;
            $this->img = $this->baseUrl."/images/portadas/$ctrl/$acti.jpg";
        }
        
        $this->urlIfrmPE = $config['pagos']['pago-efectivo']['extra']['urlIfrmPago'];
    }

    /**
     * Para invocar instacia de clase como un metodo.
     * @param String $index Indice del valor que deseamos (imagen|active).
     * @param Array $ca Array con el ctrl y acti segun eso enviar si es activo o no.
     */
    public function __invoke($index='', $ca=[])
    {
        $modu = $this->mca['modu'];
        $ctrl = $this->mca['ctrl'];
        $acti = $this->mca['acti']; //flog('$this->mca',$this->mca);
        
        if(trim($index)=='mca')     return $this->mca;
        if(trim($index)=='imagen')  return $this->img;
        if(trim($index)=='baseurl') return $this->baseUrl;
        if(trim($index)=='basejs')  return $this->baseJs;
        if(trim($index)=='basecss') return $this->baseCss;
        if(trim($index)=='admlte')  return $this->admlte;
        
        if(trim($index)=='ishome')  return ($modu=='portal' && $ctrl=='index' && $acti=='index');
        if(trim($index)=='iscontactenos') return ($modu=='portal' && $ctrl=='index' && $acti=='contactenos');
        if(trim($index)=='iscuenta') return ($modu=='portal' && $ctrl=='usuario' && $acti=='cuenta');
        if(trim($index)=='iscita')  return ($modu=='portal' && $ctrl=='index' && ($acti=='solicite-cita' || $acti=='solicite-cita2'));
        if(trim($index)=='islocal') return ( 'local'==(getenv('APPLICATION_ENV')?:'production') );
        if(trim($index)=='urlIfrmPE') return $this->urlIfrmPE;
        $cant = count($ca);
        if(trim($index)=='active' && $cant){
            if( ($cant==1 && $ca[0]==$ctrl) || 
                ($cant==2 && $ca[0]==$ctrl && $ca[1]==$acti) )
                return ' class="activo" ';
            return "";
        }
            
        throw new \Exception('Helper->Portal->__invoke[' . __LINE__ . ']:Index desconocido');
    }

    /**
     * 
     */
    public function getFullUrl()
    {
        $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"]=="on")?"s":"";
        $sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
        $protocol = substr($sp, 0, strpos($sp, "/")) . $s;
        $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);

        $mca = $this->mca;
        $isHome = ($mca['modu']=='application' && $mca['ctrl']=='index' && $mca['acti']=='index');
        $uriPid = ($isHome&&FALSE)?'?pid':'';  // Cambiado a cookie

        return "$protocol://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'].$uriPid;
    }

}
