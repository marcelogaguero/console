<?php
/**
 * @author: Marcelo Agüero <marcelogaguero@hotmail.com>
 * @since: 23/04/13 07:58
 */
namespace core;

abstract class TaskBase
{
    const OPTIONAL = 'optional';
    const MANDATORY = 'mandatory';

    private $definations = array(
        'group' => null,
        'name' => null,
        'summary' => null,
        'description' => null,
        'parameters' => null,
        'options' => null
    );

    private $context;

    function __construct(){
        $this->setDefination();
    }

    public abstract function setDefination();

    public abstract function execute($parameters, $options);


    public function getSummary(){

        if(!isset($this->definations['name']) || empty($this->definations['name']) || is_null($this->definations['name']))
            throw new \Exception("No esta definido el nombre");

        if(!isset($this->definations['summary']) || empty($this->definations['summary']) || is_null($this->definations['summary']))
            throw new \Exception("No esta definido la descripcion corta");

        return $this->definations['name'] ." ".$this->getHelpOptions() . $this->getHelpParameters() . " :: "  . $this->definations['summary'];
    }

    protected function getHelpParameters(){
        if(!isset($this->definations['parameters']) || empty($this->definations['parameters']) || is_null($this->definations['parameters']))
            return "";

        $help = '';
        foreach($this->definations['parameters'] as $key => $param){
            $help .= $key . " " ;
        }

        return $help;
    }

    protected function getHelpOptions(){

        if(!isset($this->definations['options']) || empty($this->definations['options']) || is_null($this->definations['options']))
            return "";

        $help = '';
                
        foreach($this->definations['options'] as $key => $option){

            if($option['type'] == self::MANDATORY){
                $help .= "-".$key."='' ";
            } else {
                if(isset($option['default'])){
                    $help .= "[-".$key."='' (default=".$option['default'].")]";
                } else {
                    $help .= "[-".$key."='']";
                }
            }

            $help .= " ";
        }

        return $help;
    }

    public function getGroup(){
        if(!isset($this->definations['group']) || empty($this->definations['group']) || is_null($this->definations['group']))
            throw new \Exception("No esta definido el grupo");
        return $this->definations['group'];
    }

    public function getName(){
        if(!isset($this->definations['name']) || empty($this->definations['name']) || is_null($this->definations['name']))
            throw new \Exception("No esta definido el nombre de la tarea");
        return $this->definations['name'];
    }

    protected function setConfig($definations){
        $this->definations = $definations;
    }

    public function getHelpTask(){
        $help = "\n " . $this->definations['name'] . $this->getHelpOptions() . $this->getHelpParameters()  ." \n\t ";

        if(is_array($this->definations['options'])) {
            foreach($this->definations['options'] as $key => $option){
                $description = (isset($option['help'])) ? $option['help'] : '';
                if($option['type'] == self::OPTIONAL){
                    $optional = " --opcional --default=(".$option['default'].")";
                } else {
                    $optional = " --obligatorio";
                }

                $help .= $key.": ".$optional." :: ".$description."\n\t";
            }
        }

        if(is_array($this->definations['parameters'])) {
            foreach($this->definations['parameters'] as $key => $params){
                $help .= $key." :: ".$params['help']."\n\t";
            }
        }

        $help .= "Decription: " . $this->definations['description'];

        return $help."\n";
    }



    private function validate($parameters, $options){
        if(is_array($this->definations['parameters'])){
            if(count($parameters) != count($this->definations['parameters'])){
                echo "\n - Faltan parametros. \n";
                echo $this->getHelpTask(). " \n";
                die();
            }
        }

        if(is_array($this->definations['options'])){
            foreach($this->definations['options'] as $key => $option){

                if($option['type'] == self::OPTIONAL){
                    if(!isset($options[$key]) && isset($option['default'])){
                        $options[$key] = $option['default'];
                    }
                } else {
                    if(!isset($options[$key])){
                        echo "\n - Faltan opciones. \n";
                        echo $this->getHelpTask(). " \n";
                        die;
                    }
                }

            }
        }

        return true;
    }

    public function run($options, $parameters){
        $this->validate($parameters, $options);
        $this->execute($parameters, $options);
    }

    public function setContext($context){
        $this->context = $context;
    }

    protected function getContext(){
        return $this->context;
    }
}
