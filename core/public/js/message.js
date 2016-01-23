// For displaying messages from the back-end and API.

var MessageDisplay = (function() {
	var messageTemplate,
		messageArea;

	function init() {
		messageTemplate = _.template($("#messageTemplate").html());
		messageArea = $('#messageArea');
		$("#messageArea").on("click", ".close-btn", function(e){
			e.preventDefault();
			$(this).parent().slideUp(150);
		});
	}

	// Given an API response, display the status message if there is an error.
	function displayIfError(json) {
		if (!json) return;
		var errors = [];
		if (json.status == 'error') {
			errors = errors.concat(json.errors.general);
			errors = errors.concat(_.values(json.errors.input));
			if (errors.length > 0) {
				display(errors, 'danger');
			}
		}		
	}

	// className \in {danger, success, info}
	function display(messages, className) {
		messageArea.append(messageTemplate({
			items: messages,
			className: className
		}));
	}

	return {
		init: init,
		displayIfError: displayIfError,
		display: display
	};
}());

//{"status":"error","errors":{"input":[],"general":["User not found"]},"result":null}