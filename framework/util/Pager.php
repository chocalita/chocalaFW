<?php
require_once('URI.php');
/**
 * Description of Paginador
 *
 * @author ypra
 */
class Pager
{

    /**
     *
     * @var int
     */
    private $registroInicial;

    /**
     *
     * @var int
     */
    private $registrosPorPagina;

    /**
     *
     * @var int
     */
    private $registrosTotales;

    /**
     *
     * @var int
     */
    private $conteoRegistros;

    /**
     *
     * @var int
     */
    private $conteoRegistrosME;

    /**
     *
     * @var array
     */
    private $resultado = null;

    /**
     *
     * @var array
     */
    private $mExportacion = null;

    /**
     *
     * @var String
     */
    private $nombre;

    /**
     *
     * @var array
     */
    private $paginadorSession = null;

    /**
     *
     * @var String
     */
    private $URI;

    /**
     * 
     * @return int
     */
    public function getConteoRegistros()
    {
        return $this->conteoRegistros;
    }

    /**
     * 
     * @return int
     */
    public function getRegistroInicial()
    {
        return $this->registroInicial;
    }

    /**
     * 
     * @param int $registroInicial
     */
    public function setRegistroInicial($registroInicial)
    {
        $this->registroInicial = $registroInicial;
    }

    /**
     * 
     * @return int
     */
    public function getRegistrosPorPagina()
    {
        return $this->registrosPorPagina;
    }

    /**
     * 
     * @param int $registrosXPagina
     */
    public function setRegistrosPorPagina($registrosXPagina)
    {
        $this->registrosPorPagina=$registrosXPagina;
    }

    /**
     * 
     * @return int
     */
    public function getResultado()
    {
        return $this->resultado;
    }

    /**
     * 
     * @param int $resultado
     */
    public function setResultado($resultado)
    {
        $this->resultado=$resultado;
    }

    /**
     * 
     * @return array
     */
    public function getMExportacion()
    {
        return $this->mExportacion;
    }

    /**
     * 
     * @param array $mExportacion
     */
    public function setMExportacion($mExportacion)
    {
        $this->mExportacion=$mExportacion;
    }

    /**
     * 
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * 
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre=$nombre;
    }

    /**
     * 
     * @return array
     */
    public function getPaginadorSession()
    {
        return $this->getPaginadorSession();
    }

    /**
     * 
     * @param array $paginadorSession
     */
    public function setPaginadorSession($paginadorSession)
    {
        $this->paginadorSession=$paginadorSession;
    }

    /**
     * 
     * @param string $nombreObjeto
     * @param array $resultado
     */
    public function __construct($nombreObjeto, $resultado)
    {
        $this->nombre = $nombreObjeto;
        $this->resultado = $resultado;
        $this->URI = URI::URIPagina();
        $this->registrosTotales = sizeof($this->resultado);
        $this->registroInicial = 1;
        $this->conteoRegistros = 1;
        $this->conteoRegistrosME = 1;
        if(isset($this->resultado)){
            if($this->resultado == null){
                $this->setTituloMExportacion(0,
                    "No existen registros Correspondientes");
            }
        }else{
            $this->setTituloMExportacion(0,
                "No existen registros Correspondientes");
        }
        $this->mExportacion = array("0"=>array());
        if(NO_PAGINAR){
            $this->registrosPorPagina = 9999;
        }else{
            $this->registrosPorPagina = REGISTROS_POR_PAGINA;
        }
        if(isset($_REQUEST['anularPaginacion'])){
            $this->disable();
        }else{
            if($_SESSION['URI']!=$this->URI || isset($_REQUEST['filtrar'])){
                $_SESSION[$this->nombre.'Session'] = null;
            }
            if(isset($_SESSION[$this->nombre.'Session'])){
                $this->paginadorSession = unserialize(
                    $_SESSION[$this->nombre.'Session']);
                if(isset($_REQUEST['aplicarA'])
                    && $_REQUEST['aplicarA']==$this->nombre){
                    $this->paginadorSession->setRegistroInicial(
                        $_REQUEST['registroInicial']);
                    $this->inicializarEn(
                        $this->paginadorSession->getRegistroInicial());
                }
            }
            $_SESSION['URI']=$this->URI;
        }
    }

    public function setTituloMExportacion($columnaPoner,$titulo)
    {
        $this->mExportacion[0][$columnaPoner] = $titulo;
    }
    public function crearFila()
    {
        $this->mExportacion[] = array();
    }
    public function setTituloME($titulo)
    {
        array_push($this->mExportacion[0],$titulo);
    }
    public function setValorME($valor)
    {
        $indice = sizeof($this->mExportacion);
        array_push($this->mExportacion[$indice-1],$valor);
    }
    public function setValorMExportacion($columna,$valor)
    {
        $this->mExportacion[$this->getConteoRegistrosME()][$columna] = $valor;
    }

    public function paginateTo($numReg)
    {
        $this->registrosPorPagina = $numReg;
    }

    public function shortPager()
    {
        $this->registrosPorPagina = 5;
    }

    public function middlePager()
    {
        $this->registrosPorPagina = 10;
    }

    public function disable()
    {
        $this->registrosPorPagina = 99999;
    }

    public function initTo($regIni)
    {
        $this->setRegistroInicial($regIni);
        if($this->conteoRegistros < $this->registroInicial){
            $this->resultado = array_slice($this->resultado,
                ($this->registroInicial-1));
        }
    }

    public function nextRecordCount()
    {
        $this->conteoRegistrosME++;
    }

    public function stopPage()
    {
        $this->conteoRegistros++;
        if(($this->conteoRegistros-1) >= $this->registrosPorPagina){
            return true;
        }else{
            return false;
        }
    }

    public function canGoBack()
    {
        if($this->registroInicial>1){
            return true;
        }else{
            return false;
        }
    }

    public function canGoNext(){
        if(($this->registroInicial + $this->registrosPorPagina - 1)
            < $this->registrosTotales){
            return true;
        }else{
            return false;
        }
    }

    public function prevRecords()
    {
        return $this->registroInicial - $this->registrosPorPagina;
    }

    public function nextRecords()
    {
        return $this->conteoRegistros + $this->registroInicial - 1;
    }

    public function lastRecords()
    {
        $final=(floor($this->registrosTotales/$this->registrosPorPagina)
            * $this->registrosPorPagina) + 1;
        if($final <= $this->registrosTotales){
            return $final;
        }else{
            return ($final - $this->registrosPorPagina);
        }
    }

    public function initialRowNumber()
    {
        return $this->registroInicio() + $this->conteoRegistros - 2;
    }

    public function endRowNumber()
    {
        return $this->registroInicial + $this->conteoRegistros - 2;
    }

    public function updateInitialRow($valor)
    {
        $this->registroInicial = $valor;
    }

    public function displayIcons()
    {
        $iconoRetro = "";
        $iconoAvanza = "";
        if($this->canGoBack()){
            $iconoRetro .= "<a href=\"".$this->URI."?paginar=si&registroInicial=1&aplicarA=".$this->nombre."\">";
            $iconoRetro .= "<img src=\"".ICO__16."first.png\" border=\"0\" align=\"middle\" id=\"firstimg\">";
            $iconoRetro .= "</a>";
            $iconoRetro .= "<a href=\"".$this->URI."?paginar=si&registroInicial=".$this->prevRecords()."&aplicarA=".$this->nombre."\">";
            $iconoRetro .= "<img src=\"".ICO_16."previous.png\" border=\"0\" align=\"middle\" id=\"previousimg\">";
            $iconoRetro .= "</a>";
        }
        if($this->canGoNext()){
            $iconoAvanza .= "<a href=\"".$this->URI."?paginar=si&registroInicial=".$this->nextRecords()."&aplicarA=".$this->nombre."\">";
            $iconoAvanza .= "<img src=\"".ICO_16."play.png\" border=\"0\" align=\"middle\" id=\"nextimg\">";
            $iconoAvanza .= "</a>";
            $iconoAvanza .= "<a href=\"".$this->URI."?paginar=si&registroInicial=".$this->lastRecords()."&aplicarA=".$this->nombre."\">";
            $iconoAvanza.= "<img src=\"".ICO_16."last.png\" border=\"0\" align=\"middle\" id=\"lastimg\">";
            $iconoAvanza .= "</a>";
        }
        $_SESSION[$this->nombre.'Session']=serialize($this);
        return $iconoRetro . $iconoAvanza;
    }

    public function pageNumber()
    {
        $numeracion = "";
        if($this->resultado!=null){
            $numeracion .= '(Registros &nbsp;'.$this->registroInicial.'&nbsp; al &nbsp;';
            if(isset($_GET['anularPaginacion']))
                $numeracion .= $this->registrosTotales;
            else
                $numeracion .= $this->endRowNumber();
            $numeracion .= '&nbsp; de &nbsp;'.$this->registrosTotales.')';
        }else{
            $numeracion .= 'No hay Registros';
        }
        return $numeracion;
    }

    public function paginationBar()
    {
        $barra = '<div class="pagerButtons">';
        $barra .= $this->pageNumber();
        $barra .= '&nbsp;&nbsp;'.$this->displayIcons();
        $barra .= '</div>';
        echo $barra;
        $this->sessioning();
    }

    public function regsCount()
    {
        return $this->conteoRegistros + $this->registroInicial - 1;
    }

    public function sessioning()
    {
        $_SESSION['ME_'.$this->URI] = $this->mExportacion;
    }

    public function sessioningAs($name)
    {
        $_SESSION[$name] = $this->mExportacion;
    }

}
?>