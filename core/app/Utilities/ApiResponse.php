<?php

namespace App\Utilities;

use Response;
use App\Utilities\Status;

// Utilities to make responses in API from Status objects.
class ApiResponse {
	public static function fromStatus($status) {
		$httpCode = self::getHttpCode($status->getCode());
		return Response::json($status->asArray(), $httpCode);
	}

	public static function fromResult($result) {
		$json = [
			"status" => "ok",
			"result" => $result
		];
		return response()->json($json, 200);
	}

	public static function OK() {
		return self::fromStatus(Status::OK());
	}

	private function __construct() {}
	private static function getHttpCode($code) {
		switch($code) {
			case StatusCodes::OK:
			return 200;
			case StatusCodes::UNAUTHENTICATED:
			return 401;
			case StatusCodes::BAD_INPUT:
			return 400;
			case StatusCodes::INSUFFICIENT_PERMISSIONS:
			return 403;
			case StatusCodes::NOT_FOUND:
			return 404;
			default:
			return 400;
		}
	}
}