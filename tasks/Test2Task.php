<?php
/**
 * @author: Marcelo Agüero <marcelogaguero@hotmail.com>
 * @since: 23/04/13 08:59
 */
namespace tasks;

use \core\TaskBase;

class Test2Task extends TaskBase
{
    public function setDefination()
    {
        $definations = array(
            'group' => 'GrupoTest',
            'name' => 'test2',
            'summary' => 'Algo',
            'description' => 'Mas de Algo',
            'parameters' => null,
            'options' => null
        );
        $this->setConfig($definations);
    }

    public function execute($parameters, $options)
    {
        // TODO: Implement execute() method.
    }
}
