<?php
/**
 * Created by Nemogroup.
 * @author: Marcelo Agüero <marcelo.aguero@nemogroup.net>
 * @since: 23/04/13 08:32
 */
namespace tasks;

use \core\TaskBase;

class TestTask extends TaskBase
{

    public function setDefination()
    {
        $definations = array(
            'group' => 'GrupoTest',
            'name' => 'test',
            'summary' => 'Algo',
            'description' => 'Mas de Algo',
            'parameters' => array(
                'module' => array('help' => 'Modulo hacer algo'),
            ),
            'options' => array(
                'env' => array('default' => 'dev', 'type'=>self::OPTIONAL, 'help' => 'ambiente'),
                'count' => array('default' => '100', 'type'=>self::MANDATORY, 'help' => 'cantidad')
            )
        );
        $this->setConfig($definations);
    }

    public function execute($parameters, $options)
    {
        var_dump($parameters, $options);die;
    }
}
