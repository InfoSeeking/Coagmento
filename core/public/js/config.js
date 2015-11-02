var Config = (function() {
	var storage = {};

	function get(key) {
		if (!storage.hasOwnProperty(key)) {
			throw "Config missing key " + key;
		} else {
			return storage[key];
		}
	}

	function getOrDefault(key, defaultValue) {
		if (!storage.hasOwnProperty(key)) {
			return defaultValue;
		} else {
			return storage[key];
		}
	}

	function set(key, value) {
		storage[key] = value;
	}

	function setAll(obj) {
		_.extend(storage, obj);
	}

	return {
		get: get,
		getOrDefault: getOrDefault,
		set: set,
		setAll: setAll
	};
}());