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
    protected static $instance = null;



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
    * @return Event_SignalEmitter_Dispatcher Dispatcher object
    */
    public static function singleton()
    {
        if (self::$instance === null) {
            self::$instance = new Event_SignalEmitter_Dispatcher();
        }
        return self::$instance;
    }//public static function singleton()



    /**
    * Emit a signal to all listeners
    *
    * @param string $strSignal   Signal to emit (determines listener list)
    * @param array  $arParameter Array of parameters to pass to the callback
    *                             before the user defined params
    *
    * @return void
    */
    public function emit($strSignal, $arParameter = array())
    {
        return parent::emit($strSignal, $arParameter);
    }//public function emit($strSignal, $arParameter = array())

}//class Event_SignalEmitter_Dispatcher extends Event_SignalEmitter
?>