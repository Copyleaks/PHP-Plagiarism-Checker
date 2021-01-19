<?php
/*
 The MIT License(MIT)

 Copyright(c) 2016 Copyleaks LTD (https://copyleaks.com)

 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:

 The above copyright notice and this permission notice shall be included in all
 copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 SOFTWARE.
*/

namespace Copyleaks;

class SubmissionSensitiveData
{
  /**
   * Mask driver's license numbers from the scanned document with # characters. Available for users on a plan for 2500 pages or more.
   * * Supported Types:
   *  * Australia driver's license number
   *  * Canada driver's license number
   *  * United Kingdom driver's license number
   *  * USA drivers license number
   *  * Japan driver's license number
   *  * Spain driver's license number
   *  * Germany driver's license number
   */
  public ?bool $driversLicense;
  /**
   * Mask credentials from the scanned document with # characters. Available for users on a plan for 2500 pages or more.
   * * Supported Types:
   *  * Authentication token
   *  * Amazon Web Services credentials
   *  * Azure JSON Web Token
   *  * HTTP basic authentication header
   *  * Google Cloud Platform service account credentials
   *  * Google Cloud Platform API key
   *  * JSON Web Token
   *  * Encryption key
   *  * Password
   */
  public ?bool $credentials;
  /**
   * Mask passports from the scanned document with # characters. Available for users on a plan for 2500 pages or more.
   * * Supported Types:
   *  * Canada passport number
   *  * China passport number
   *  * France passport number
   *  * Germany passport number
   *  * Ireland passport number
   *  * Japan passport number
   *  * Korea passport number
   *  * Mexico passport number
   *  * Spain passport number
   *  * United Kingdom passport number
   *  * USA passport number
   *  * Netherlands passport number
   *  * Poland passport
   *  * Sweden passport number
   *  * Australia passport number
   *  * Singapore passport number
   *  * Taiwan passport number
   */
  public ?bool $passport;
  /**
   * Mask network identifiers from the scanned document with # characters. Available for users on a plan for 2500 pages or more.
   * * Supported Types:
   *  * IP address
   *  * Local MAC address
   *  * MAC address
   */
  public ?bool $network;
  /**
   * Mask url from the scanned document with # characters. Available for users on a plan for 2500 pages or more.
   */
  public ?bool $url;
  /**
   * Mask email addresses from the scanned document with # characters. Available for users on a plan for 2500 pages or more.
   */
  public ?bool $emailAddress;
  /**
   * Mask credit card numbers and credit card track numbers from the scanned document with # characters. Available for users on a plan for 2500 pages or more.
   */
  public ?bool $creditCard;
  /**
   * Mask phone numbers from the scanned document with # characters. Available for users on a plan for 2500 pages or more.
   */
  public ?bool $phoneNumber;
  /**
   *@param bool $driversLicense - Mask driver's license numbers from the scanned document with # characters. Available for users on a plan for 2500 pages or more.
   *@param bool $credentials - Mask credentials from the scanned document with # characters. Available for users on a plan for 2500 pages or more.
   *@param bool $passport - Mask passports from the scanned document with # characters. Available for users on a plan for 2500 pages or more.
   *@param bool $network - Mask network identifiers from the scanned document with # characters. Available for users on a plan for 2500 pages or more.
   *@param bool $url - Mask url from the scanned document with # characters. Available for users on a plan for 2500 pages or more.
   *@param bool $emailAddress - Mask email addresses from the scanned document with # characters. Available for users on a plan for 2500 pages or more.
   *@param bool $creditCard - Mask credit card numbers and credit card track numbers from the scanned document with # characters. Available for users on a plan for 2500 pages or more.
   *@param bool $phoneNumber - Mask phone numbers from the scanned document with # characters. Available for users on a plan for 2500 pages or more.
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
