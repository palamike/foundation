<?php
/**
 * Project : servicize
 * User: palagornp
 * Date: 2/11/2016 AD
 * Time: 1:38 PM
 */

namespace Palamike\Foundation\Services\UI;


use Illuminate\Support\Facades\Config;

class AssetService
{
    private $scripts = [];
    private $styles = [];
    private $storeScripts = [];
    private $storeStyles = [];
    private $outScripts = [];
    private $outStyles = [];

    public function __construct()
    {
        $assets = Config::get('assets');
        $this->scripts = $assets['scripts'];
        $this->styles = $assets['styles'];
    }


    /**
     * @param $scripts string|array the assets scripts key
     */
    public function addScripts($scripts){
        if(is_string($scripts)){
            if(array_key_exists($scripts,$this->scripts)){
                $this->storeScripts[$scripts] = $scripts;
            }//if

            return ;
        }//if

        if(is_array($scripts)){
            foreach($scripts as $script){
                if(array_key_exists($script,$this->scripts)){
                    $this->storeScripts[$script] = $script;
                }//if
            }//foreach

            return ;
        }//if
    }//public function addScripts

    /**
     * @param $styles string|array the assets styles key
     */
    public function addStyles($styles){
        if(is_string($styles)){
            if(array_key_exists($styles,$this->styles)){
                $this->storeStyles[$styles] = $styles;
            }//if

            return ;
        }//if

        if(is_array($styles)){
            foreach($styles as $style){
                if(array_key_exists($style,$this->styles)){
                    $this->storeStyles[$style] = $style;
                }//if
            }//foreach

            return ;
        }//if
    }//public function addStyles

    /**
     * @param $scripts
     * @return string
     */
    public function scripts($scripts){
        if(isset($scripts)){
            $this->addScripts($scripts);
        }//if

        $outputs = '';
        $path = Config::get('assets.path');

        foreach($this->storeScripts as $script){
            if(!array_key_exists($script,$this->outScripts)){
                $src = url($path.$this->scripts[$script]);
                $outputs .= "<script src=\"$src\"></script>\n";
                $this->outScripts[$script] = $script;
            }
        }//foreach
        
        $this->storeScripts = [];

        return $outputs;

    }//public function scripts

    /**
     * @param $styles
     * @return string
     */
    public function styles($styles){
        if(isset($styles)){
            $this->addStyles($styles);
        }//if

        $outputs = '';
        $path = Config::get('assets.path');

        foreach($this->storeStyles as $style){
            if(!array_key_exists($style,$this->outStyles)){
                $src = url($path.$this->styles[$style]);
                $outputs .= "<link href=\"$src\" rel='stylesheet' type='text/css'>\n";
                $this->outStyles[$style] = $style;
            }
        }//foreach

        $this->storeStyles = [];

        return $outputs;

    }//public function styles
}