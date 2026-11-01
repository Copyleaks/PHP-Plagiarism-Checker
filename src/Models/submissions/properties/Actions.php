<?php

namespace Copyleaks;

class SubmissionActions
{
  /** Start the scan immediately. */
  const Scan = 0;
  /** Only check how many credits the scan will use. */
  const CheckCredits = 1;
  /** Only index the file in the Copyleaks internal database. Uses no credits. */
  const IndexOnly = 2;
}
