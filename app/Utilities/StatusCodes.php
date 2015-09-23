<?php
namespace App\Utilities;

abstract class StatusCodes {
	const OK = 0;
	const GENERIC_ERROR = 1;
	const BAD_INPUT = 2;
	const INSUFFICIENT_PERMISSIONS = 3;
}