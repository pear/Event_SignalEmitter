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

/**
* Generic signal emitting class with the same
* API as GObject.
*
* Since GObject doesn't allow classes to define
* or emit own signals, this class provides a PHP
* implementation with the same API.
*
* Let your own class extend this one, and you have
* the following methods available:
* - connect
* - connect_simple
* - disconnect
* - block
* - unblock
* - emit
* - register_signal
*
* @category Event
* @package  Event_SignalEmitter
* @author   Christian Weiske <cweiske@php.net>
* @license  http://www.gnu.org/copyleft/lesser.html  LGPL License 2.1
* @link     http://pear.php.net/package/Event_SignalEmitter
*/
class Event_SignalEmitter
{
    protected $arListener           = array();
    protected $arBlocked            = array();
    protected $nNextHandlerNumber   = 1;

    /**
    * Add a listener. Callbacks get the listener object as first parameter.
    * Also accepts user data as parameter.
    *
    * @param string   $strSignal Signal name (e.g. "go")
    * @param callback $callback  Function/Method that should be called
    *
    * @return int Signal handler id
    */
    public function connect($strSignal, $callback)
    {
        if (func_num_args() > 2) {
            //get user data from method arguments
            $arUserData = func_get_args();
            array_shift($arUserData);
            array_shift($arUserData);
        } else {
            $arUserData = array();
        }
        return $this->connectImplementation(
            $strSignal, $callback, false, $arUserData
        );
    }//public function connect($strSignal, $callback)



    /**
    * Add a simple listener. Callbacks do not get the emitter object.
    * Also accepts user data as parameter.
    *
    * @param string   $strSignal Signal name (e.g. "go")
    * @param callback $callback  Function/Method that should be called
    *
    * @return int Signal handler id
    */
    public function connect_simple($strSignal, $callback)
    {
        if (func_num_args() > 2) {
            //get user data from method arguments
            $arUserData = func_get_args();
            array_shift($arUserData);
            array_shift($arUserData);
        } else {
            $arUserData = array();
        }
        return $this->connectImplementation(
            $strSignal, $callback, true, $arUserData
        );
    }//public function connect_simple($strSignal, $callback)



    /**
    * Internal implementation to add a listener.
    *
    * @param string   $strSignal  Signal name (e.g. "go")
    * @param callback $callback   Function/Method that should be called
    * @param boolean  $bSimple    If it's a simple listener or not
    * @param array    $arUserData User defined data to pass to the callback
    *
    * @return int Signal handler id
    */
    protected function connectImplementation($strSignal, $callback, $bSimple,
        $arUserData)
    {
        if (!isset($this->arListener[$strSignal])) {
            throw new Exception('Unknown signal "' . $strSignal . '"');
        }

        $nHandlerId = $this->nNextHandlerNumber++;

        $this->arListener[$strSignal][$nHandlerId] = array(
            'callback'  => $callback,
            'userdata'  => $arUserData,
            'simple'    => $bSimple
        );

        return $nHandlerId;
    }//protected function connectImplementation($strSignal, $callback, ...)



    /**
    * Disconnects a signal handler.
    *
    * @param int $nHandlerId ID returned by connect()
    *
    * @return boolean true if the handler has been found and disconnected
    */
    public function disconnect($nHandlerId)
    {
        foreach ($this->arListener as $strSignal => &$arHandler) {
            if (isset($arHandler[$nHandlerId])) {
                unset($arHandler[$nHandlerId]);
                return true;
            }
        }

        return false;
    }//public function disconnect($nHandlerId)



    /**
    * Blocks a signal handler
    *
    * @param int $nHandlerId ID returned by connect()
    *
    * @return void
    */
    public function block($nHandlerId)
    {
        $this->arBlocked[$nHandlerId] = true;
    }//public function block($nHandlerId)



    /**
    * Unblocks a signal handler
    *
    * @param int $nHandlerId ID returned by connect()
    *
    * @return void
    */
    public function unblock($nHandlerId)
    {
        if (isset($this->arBlocked[$nHandlerId])) {
            unset($this->arBlocked[$nHandlerId]);
        }
    }//public unblock block($nHandlerId)



    /**
    * Emit a signal to all listeners
    *
    * @param string $strSignal   Signal to emit (determines listener list)
    * @param array  $arParameter Array of parameters to pass to the callback
    *                             before the user defined params
    *
    * @return void
    */
    protected function emit($strSignal, $arParameter = array())
    {
        if (!isset($this->arListener[$strSignal])) {
            throw new Exception('Unknown signal "' . $strSignal . '"');
        }
        if (!is_array($arParameter)) {
            //catch dumb programmers that didn't read the api docs
            $arParameter = array($arParameter);
        }

        foreach ($this->arListener[$strSignal] as $nHandlerId => $arListener) {
            if (!isset($this->arBlocked[$nHandlerId])) {
                $arParams = array_merge($arParameter, $arListener['userdata']);
                if (!$arListener['simple']) {
                    $arParams = array_merge(array($this), $arParams);
                }

                call_user_func_array($arListener['callback'], $arParams);
            }
        }
    }//protected function emit($strSignal, $arParameter = array())



    /**
    * Registers a signal that can be emitted.
    *
    * @param string $strSignal Signal name
    *
    * @return void
    */
    public function register_signal($strSignal)
    {
        if (!isset($this->arListener[$strSignal])) {
            $this->arListener[$strSignal] = array();
        }
    }//public function register_signal($strSignal)



    /**
    *   PEAR-style camelCase method aliases
    */



    /**
    * Alias of @link connect_simple()
    *
    * @param string   $strSignal Signal name (e.g. "go")
    * @param callback $callback  Function/Method that should be called
    *
    * @return int Signal handler id
    *
    * @see connect_simple()
    */
    public function connectSimple($strSignal, $callback)
    {
        return $this->connect_simple($strSignal, $callback);
    }//public function connectSimple($strSignal, $callback)



    /**
    * Alias of @link register_signal()
    *
    * @param string $strSignal Signal name
    *
    * @return void
    *
    * @see register_signal()
    */
    public function registerSignal($strSignal)
    {
        return $this->register_signal($strSignal);
    }//public function registerSignal($strSignal)

}//class Event_SignalEmitter
?>
