<?php
namespace App\Utilities;

use Redirect;
use Illuminate\Validation\Validator;
use App\Utilities\StatusCodes;

/** A Status can have general errors and/or input errors
 *  as well as an internal error code.
 */
class Status {
	public static function fromErrors($messages, $code=StatusCodes::GENERIC_ERROR) {
		$status = new Status();
		$status->setGeneralErrors($messages);
		$status->code = $code;
		return $status;
	}

	public static function fromError($message, $code=StatusCodes::GENERIC_ERROR) {
		$status = new Status();
		$status->setGeneralErrors([$message]);
		$status->code = $code;
		return $status;
	}

	public static function fromValidator(Validator $validator) {
		$status = new Status();
		$status->setValidator($validator);
		$status->code = $validator->fails() ? StatusCodes::BAD_INPUT : StatusCodes::OK;
		return $status;
	}

	public static function fromResult($result) {
		$status = new Status();
		$status->result = $result;
		return $status;
	}

	public static function OK() {
		return new Status();
	}

	public function isOK() {
		return $this->code == StatusCodes::OK;
	}

	public function getCode() {
		return $this->code;
	}

	public function asArray() {
		$inputErrors = [];
		if (!is_null($this->validator)) {
			$inputErrors = $this->validator->getMessageBag()->getMessages();
		}
		return [
			'status' => $this->code == StatusCodes::OK ? 'ok' : 'error',
			'errors' => [
				'input' => $inputErrors,
				'general' => $this->generalErrors,
				'result' => $this->result
			]
		];
	}

	public function getValidator() {
		return $this->validator;
	}

	public function getGeneralErrors() {
		return $this->generalErrors;
	}

	public function asRedirect($path) {
		$redirect = Redirect::to($path);
		$validator = $this->status->getValidator();
		if (!is_null($validator)) {
			$redirect->withErrors($validator);
		}
		$generalErrors = $this->status->getGeneralErrors();
		if (count($generalErrors) > 0) {
			$redirect->with('generalErrors', $generalErrors);
		}
		return $redirect;
	}

	private function __construct() {}

	private function setValidator(Validator $validator) {
		if ($validator->fails()) {
			$this->validator = $validator;
		}
	}

	private function setGeneralErrors($messages) {
		$this->generalErrors = $messages;
	}

	private $code = StatusCodes::OK;
	private $generalErrors = [];
	// Input errors have both a key and value (key being input field), this can be used
	// to show inline errors in forms.
	private $validator = null;
	private $result = null;
}