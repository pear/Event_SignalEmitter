<?php
/**
* Simple event handling package
*
* PHP Version 5
*
* @category Event
* @package  Event_SignalEmitter
* @author   Christian Weiske <cweiske@php.net>
* @license  http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
* @version  CVS: $Id$
* @link     http://pear.php.net/package/Event_SignalEmitter
*/
require_once 'Event/SignalEmitter.php';

/**
* Dispatcher class that can be used as central
* hub for all signals. For this, it includes a
* singleton method and allows signals to be emitted
* from outside.
*
* @category Event
* @package  Event_SignalEmitter
* @author   Christian Weiske <cweiske@php.net>
* @license  http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
* @link     http://pear.php.net/package/Event_SignalEmitter
*/
class Event_SignalEmitter_Dispatcher extends Event_SignalEmitter
{
    protected static $arInstances = array();



    /**
    * Please use Event_SignalEmitter_Dispatcher::singleton()
    * to get an instance of this class.
    */
    protected function __construct()
    {
    }//protected function __construct()



    /**
    * Returns the dispatcher instance.
    * This method makes sure only one instance exists.
    *
    * @param string $strName Name of dispatcher instance
    *
    * @return Event_SignalEmitter_Dispatcher Dispatcher object
    */
    public static function singleton($strName = 'default')
    {
        $strName = (string)$strName;
        if (!array_key_exists($strName, self::$arInstances)) {
            self::$arInstances[$strName] = new Event_SignalEmitter_Dispatcher();
        }
        return self::$arInstances[$strName];
    }//public static function singleton()



    /**
    * Emit a signal to all listeners.
    * Takes any number of params.
    *
    * @param string $strSignal   Signal to emit (determines listener list)
    * @param mixed  $param1      Parameter to pass to the receiving functions
    * @param ...
    *
    * @return void
    */
    public function emit($strSignal)
    {
        $params = func_get_args();
        return call_user_func_array(
            array('parent', 'emit'), $params
        );
    }//public function emit($strSignal)

}//class Event_SignalEmitter_Dispatcher extends Event_SignalEmitter
?>