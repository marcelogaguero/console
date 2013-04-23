<?php
/**
 * Created by Nemogroup.
 * @author: Marcelo Agüero <marcelo.aguero@nemogroup.net>
 * @since: 23/04/13 11:27
 */
namespace tasks;

use \core\TaskBase;

class Test3Task extends TaskBase
{
    public function setDefination()
    {
        $definations = array(
            'group' => 'GrupoTest2',
            'name' => 'test',
            'summary' => 'Otra mas',
            'description' => 'Deberia hacer algo',
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
