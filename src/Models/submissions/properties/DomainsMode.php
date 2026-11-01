<?php

namespace Copyleaks;

class SubmissionFilterDomainsMode
{
  /** Keep only results from the properties.filters.domains list. */
  const Include = 0;
  /** Keep only results outside the properties.filters.domains list. */
  const Exclude = 1;
}
