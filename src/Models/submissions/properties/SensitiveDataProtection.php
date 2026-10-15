<?php

namespace Copyleaks;

/**
 * Mask sensitive data in the scanned document with # characters. Needs a plan of 2500 pages or more.
 */
class SubmissionSensitiveData
{
  /** Driver's license numbers of Australia, Canada, the United Kingdom, the USA, Japan, Spain and Germany. */
  public ?bool $driversLicense;
  /**
   * Authentication tokens, AWS credentials, Azure JSON Web Tokens, HTTP basic authentication headers, GCP service
   * account credentials and API keys, JSON Web Tokens, encryption keys and passwords.
   */
  public ?bool $credentials;
  /**
   * Passport numbers of Canada, China, France, Germany, Ireland, Japan, Korea, Mexico, Spain, the United Kingdom,
   * the USA, the Netherlands, Poland, Sweden, Australia, Singapore and Taiwan.
   */
  public ?bool $passport;
  /** IP addresses, local MAC addresses and MAC addresses. */
  public ?bool $network;
  public ?bool $url;
  public ?bool $emailAddress;
  /** Credit card numbers and credit card track numbers. */
  public ?bool $creditCard;
  public ?bool $phoneNumber;
  /**
   * @param bool $driversLicense
   * @param bool $credentials
   * @param bool $passport
   * @param bool $network
   * @param bool $url
   * @param bool $emailAddress
   * @param bool $creditCard
   * @param bool $phoneNumber
   */
  public function __construct(
    bool $driversLicense = false,
    bool $credentials = false,
    bool $passport = false,
    bool $network = false,
    bool $url = false,
    bool $emailAddress = false,
    bool $creditCard = false,
    bool $phoneNumber = false
  ) {
    $this->driversLicense = $driversLicense;
    $this->credentials = $credentials;
    $this->passport = $passport;
    $this->network = $network;
    $this->url = $url;
    $this->emailAddress = $emailAddress;
    $this->creditCard = $creditCard;
    $this->phoneNumber = $phoneNumber;
  }
}
