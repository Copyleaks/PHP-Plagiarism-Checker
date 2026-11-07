<?php

namespace Copyleaks;

/**
 * Values of the error type of an API error, see getErrorType() on CommandException, RateLimitException and
 * UnderMaintenanceException, and https://docs.copyleaks.com/using-the-apis/api-errors/ .
 * A type that is not listed here is still returned as is.
 */
class CopyleaksErrorTypes
{
    public const INVALID_REQUEST_ERROR = "invalid_request_error";

    public const AUTHENTICATION_ERROR = "authentication_error";

    public const PAYMENT_ERROR = "payment_error";

    public const API_ERROR = "api_error";
}
