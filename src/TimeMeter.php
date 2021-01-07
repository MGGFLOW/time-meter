<?php


namespace MGGFLOW\Tools;


class TimeMeter
{
    /**
     * Prefix for start event dot name
     *
     * @var string
     */
    protected $startPrefix = 'start_';
    /**
     * Prefix for end event dot name
     *
     * @var string
     */
    protected $endPrefix = 'end_';
    /**
     * Dots times
     *
     * @var array
     */
    protected $times;
    /**
     * Time of object creation
     *
     * @var string
     */
    public $creationTime;

    /**
     * Name of current event
     *
     * @var string
     */
    protected $currentEvent;

    /**
     * TimeMeter constructor. Set creation time.
     */
    public function __construct(){
        $this->creationTime = static::getTime();
    }

    /**
     * Get formatted time as a float with microseconds accuracy
     *
     * @return string
     */
    public static function getTime(){
        return number_format(microtime(true),8,'.','');
    }

    /**
     * Create new dot
     *
     * @param $name
     * @return $this
     */
    public function newDot($name){
        $this->times[$name] = static::getTime();

        return $this;
    }

    /**
     * Check dot existing
     *
     * @param $name
     * @return bool
     */
    public function dotExists($name){
        if(isset($this->times[$name])){
            return true;
        }

        return false;
    }

    /**
     * Get time between two dots.
     *
     * @param string $dot1
     * @param string $dot2
     * @return false|float|int
     */
    public function timeBetween($dot1='#',$dot2='#'){
        if($dot1!='#'){
            if(!isset($this->times[$dot1])) return false;
            $dot1_time = $this->times[$dot1];

            if($dot2!='#'){
                if(!isset($this->times[$dot2])) return false;
                $dot2_time = $this->times[$dot2];
            }else{
                $dot2_time = $this->creationTime;
            }
        }else{
            $dot1_time = $this->creationTime;
            $dot2_time = static::getTime();
        }

        return abs($dot2_time-$dot1_time);
    }

    /**
     * Set current event name
     *
     * @param string $name
     * @return $this
     */
    protected function setCurrentEvent($name){
        $this->currentEvent = $name;
        return $this;
    }

    /**
     * Get current event name
     *
     * @return false|string
     */
    protected function getCurrentEvent(){
        if(empty($this->currentEvent)) return false;

        return $this->currentEvent;
    }

    /**
     * Setting current event abstraction
     *
     * @param string $name
     * @return $this
     */
    public function event($name){
        $this->setCurrentEvent($name);

        return $this;
    }

    /**
     * Set start dot for current event
     *
     * @return $this|false
     */
    public function start(){
        $event = $this->getCurrentEvent();
        if(empty($event)) return false;

        $startName = $this->startPrefix.$event;
        $this->newDot($startName);

        return $this;
    }

    /**
     * Set end dot for current event
     *
     * @return $this|false
     */
    public function end(){
        $event = $this->getCurrentEvent();
        if(empty($event)) return false;

        $endName = $this->endPrefix.$event;
        $this->newDot($endName);

        return $this;
    }

    /**
     * Get elapsed time for current event
     *
     * @return false|float|int
     */
    public function time(){
        $event = $this->getCurrentEvent();
        if(empty($event)) return false;

        $startName = $this->startPrefix.$event;
        if(!$this->dotExists($startName)){
            $startName = '#';
        }
        $endName = $this->endPrefix.$event;
        if(!$this->dotExists($endName)){
            $endName = '#';
        }

        return $this->timeBetween($startName,$endName);
    }
}