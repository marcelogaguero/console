<?php
/**
 * @author: Marcelo Agüero <marcelogaguero@hotmail.com>
 * @since: 22/04/13 13:58
 */
namespace core;

class Console
{
    protected $argv;
    protected $tasks = array();
    private $directory;
    private $namespace;
    private $context;


    function __construct($argv, $dir = false, $namespace = false){

        require_once "TaskBase.php";

        $dir = ($dir == false) ? __DIR__.'/../tasks' : $dir;
        $namespace = ($namespace == false) ? '\\tasks\\' : $namespace;

        $this->setDirectoryTask($dir, $namespace);
        $this->argv = $argv;
        $this->init($argv);
    }

    public function setContext($context){
        $this->context = $context;
    }


    public function setDirectoryTask($dir, $namespace){
        $this->namespace = $namespace;
        if(!is_dir($dir)) throw new \Exception("Directorio de tareas invalido");
        $this->directory = $dir;
        $this->tasks = array();
        $this->loadTask();
    }

    private function execute(){

        if(count($this->argv) == 1){
            $this->showAllHelps();
        } else {
            if($this->argv[1] == '-h'){
                $this->getHelpTask($this->argv[2]);
            } else {
                $this->executeTask($this->argv);
            }
        }
    }

    protected function executeTask($argv){
        $task = $this->getTaskByName($argv[1]);

        if($task != false){
            $task->run($this->getOptions($argv), $this->getParametes($argv));
        } else {
            echo "No existe la tarea.\n";
        }

    }

    private function getOptions($argv){
        $argv = array_slice($argv,2,count($argv));
        $options = array();
        foreach($argv as $arg){
            if(substr($arg, 0, 1) == '-'){
                list($name, $value) = explode('=',substr($arg, 1));
                $options[$name] = $value;
            }
        }
        return $options;
    }

    private function getParametes($argv){
        $argv = array_slice($argv,2,count($argv));
        $options = array();

        foreach($argv as $arg){
            if(substr($arg, 0, 1) != '-'){
                $options[] = $arg;
            }
        }
        return $options;
    }

    protected function getHelpTask($taskname){
        $task = $this->getTaskByName($taskname);

        if($task != false){
            echo $task->getHelpTask();
        } else {
            echo "No existe la tarea.";
        }
    }

    protected function showAllHelps(){

        foreach($this->tasks as $key => $group){
            echo "\n " . $key .": \n";
            foreach($group as $task){
                echo "\t".$task->getSummary() ."\n";
            }
        }
        
        echo "\n\n";
    }

    private function loadTask(){

        if ($handle = opendir($this->directory)) {

            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    include_once($this->directory."/".$file);
                    $this->addTask($file);
                }
            }

            closedir($handle);
        }
    }

    private function addTask($filename){

        $classname = substr($filename, 0, -4);

        $task = null;
        eval('$task = new '.$this->namespace.$classname.'();');

        if(isset($this->tasks[$task->getGroup()])){
            $this->tasks[$task->getGroup()][] = $task;
        } else {
            $this->tasks[$task->getGroup()] = array($task);
        }

    }

    private function getTaskByName($taskname){

        list($groupname, $name) = explode(':',$taskname);

        if(isset($this->tasks[$groupname])){
            foreach($this->tasks[$groupname] as $task){
                if(strtoupper($task->getName()) == strtoupper($name)){
                    return $task;
                }
            }
        }

        return false;
    }

    protected function init() {
        $this->execute();
    }
}
