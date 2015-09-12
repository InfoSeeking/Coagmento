<?php
namespace App\Utilities;

use App\Utilities\Status;
use App\Illuminate\Contracts\Validation\Validator;

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

	public static function fromValidation(Validator $validator) {
		$code = $validator->fails() ? StatusCodes::BAD_INPUT : StatusCodes::OK;
		$status = new Status();
		$status->setValidationErrors($validator);
		return $status;
	}

	public static function OK() {
		return new Status();
	}

	public function isOK() {
		return $this->code == StatusCodes::OK;
	}

	public function asArray() {
		return [
			"status" => $this->ok ? "ok" : "error",
			"errors" => [
				"input" => !is_null($this->validationErrors) ? $this->validationErrors->all() : [],
				"general" => $this->generalErrors
			]
		];
	}

	private function Status() {}

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

// StatusWithResult is a wrapper around Status to include a return object.
// It is meant to be returned from a function which may return errors (not exceptions).
//
class StatusWithResult {
	public static function fromErrors(array $messages, $code=StatusCodes::GENERIC_ERROR) {
		$statusWithResult = new StatusWithResult();
		$statusWithResult->status = Status::fromErrors($messages, $code);
		return $statusWithResult;
	}

	public static function fromError(string $message, $code=StatusCodes::GENERIC_ERROR) {
		$statusWithResult = new StatusWithResult();
		$statusWithResult->status = Status::fromError($message, $code);
		return $statusWithResult;
	}

	public static function fromValidation(Validator $validator) {
		$statusWithResult = new StatusWithResult();
		$statusWithResult->status = Status::fromValidation($validator, $code);
		return $statusWithResult;
	}

	public static function fromResult($result) {
		$statusWithResult = new StatusWithResult();
		$statusWithResult->result = $result;
		return $statusWithResult;
	}

	public function getResult() {
		return $result;
	}

	public function getStatus() {
		return $status;
	}

	private StatusWithResult() {}
	private $result = null;
	private Status $status;
}