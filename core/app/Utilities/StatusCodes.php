<?php
namespace App\Utilities;

abstract class StatusCodes {
	const OK = 0;
	const GENERIC_ERROR = 1;
	const BAD_INPUT = 2;
	const INSUFFICIENT_PERMISSIONS = 3;
	const NOT_FOUND = 4;
	const UNAUTHENTICATED = 5;
	const SERVER_DOWN = 6;
}