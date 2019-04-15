<?php

interface Observer
{
	/**
	 * An observer object needs to be capable of receiving
	 * notifications. Therefore the observer needs to implement the
	 * onEvent method with two parameters: the event identifier specifying the
	 * current event and a message object (in RedBeanPHP this can also be a bean).
	 *
	 * @param string $eventname event identifier
	 * @param mixed  $bean      a message sent along with the notification
	 *
	 * @return void
	 */
	public function onEvent( $eventname, $bean );
}

class Obserable
{
    /**
	 * @var array
	 */
	private $observers = array();

	/**
	 * Implementation of the Observer Pattern.
	 * Adds an event listener to the observable object.
	 * First argument should be the name of the event you wish to listen for.
	 * Second argument should be the object that wants to be notified in case
	 * the event occurs.
	 *
	 * @param {string}   $eventname event identifier
	 * @param {Observer} $observer  observer instance
	 *
	 * @return void
	 */
	public function addEventListener( $eventname, Observer $observer )
	{
		if ( !isset( $this->observers[$eventname] ) ) {
			$this->observers[$eventname] = array();
		}

		foreach ( $this->observers[$eventname] as $o ) {
			if ( $o == $observer ) {
				return;
			}
		}

		$this->observers[$eventname][] = $observer;
	}

	/**
	 * Notifies listeners.
	 * Sends the signal $eventname, the event identifier and a message object
	 * to all observers that have been registered to receive notification for
	 * this event. Part of the observer pattern implementation in RedBeanPHP.
	 *
	 * @param string $eventname event you want signal
	 * @param mixed  $info      message object to send along
	 *
	 * @return void
	 */
	public function signal( $eventname, $info )
	{
		if ( !isset( $this->observers[$eventname] ) ) {
			$this->observers[$eventname] = array();
		}

		foreach ( $this->observers[$eventname] as $observer ) {
			$observer->onEvent( $eventname, $info );
		}
	}
}