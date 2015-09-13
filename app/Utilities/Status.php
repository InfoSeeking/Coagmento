<?php
namespace App\Utilities;

use Illuminate\Validation\Validator;

abstract class StatusCodes {
	const OK = 0;
	const GENERIC_ERROR = 1;
	const BAD_INPUT = 2;
	const INSUFFICIENT_PERMISSIONS = 3;
}

// A Status can have either generic errors or input errors (not both)
// as well as an internal error code.
//
class Status {
	public static function fromErrors(array $messages, $code=StatusCodes::GENERIC_ERROR) {
		$status = new Status();
		$status->setGeneralErrors($messages);
		$status->code = $code;
		return $status;
	}

	public static function fromError(string $message, $code=StatusCodes::GENERIC_ERROR) {
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

	private function setGenericErrors(array $messages) {
		$generalErrors = $messages;
	}

	private $code = StatusCodes::OK;
	private $generalErrors = [];
	private $validationErrors = null;
}