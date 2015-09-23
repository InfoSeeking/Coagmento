<?php
namespace App\Utilities;

use Illuminate\Validation\Validator;

use App\Utilities\StatusCodes;

// A Status can have either general errors or input errors (not both)
// as well as an internal error code.
//
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
		$status->setValidationErrors($validator);
		$status->code = $validator->fails() ? StatusCodes::BAD_INPUT : StatusCodes::OK;
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
		if (!is_null($this->validationErrors)) {
			$inputErrors = $this->validationErrors->getMessages();
		}
		return [
			"status" => $this->code == StatusCodes::OK ? "ok" : "error",
			"errors" => [
				"input" => $inputErrors,
				"general" => $this->generalErrors
			]
		];
	}

	private function __construct() {}

	private function setValidationErrors(Validator $validator) {
		if ($validator->fails()) {
			$this->validationErrors = $validator->getMessageBag();
		}
	}

	private function setGeneralErrors($messages) {
		$this->generalErrors = $messages;
	}

	private $code = StatusCodes::OK;
	private $generalErrors = [];
	private $validationErrors = null;
}