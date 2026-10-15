<?php

namespace Copyleaks;

function isSuccessStatusCode($statusCode)
{
  return $statusCode >= 200 && $statusCode <= 299;
}

function isUnderMaintenanceResponse($statusCode)
{
  return $statusCode === 503;
}

function isRateLimitResponse($statusCode)
{
  return $statusCode === 429;
}
