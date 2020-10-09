class MZLDR_Front_Helper {
	constructor(){}
	
	/**
	 * Emit an Event
	 * 
	 * @param  {String}		 eventName
	 * @param  {DOMElement}	 element
	 * @param  {Object}		 data
	 */
	emitEvent(eventName, element, data) {
		if (!eventName) {
			return;
		}

		var event = new CustomEvent(eventName);
		element.dispatchEvent(event, { 'detail' : data, cancelable: true });
		return event;
	}

	
}