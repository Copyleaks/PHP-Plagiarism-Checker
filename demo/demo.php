<?php

namespace Demo;

use Copyleaks\Copyleaks;
use Copyleaks\CopyleaksAuthToken;
use Copyleaks\CopyleaksConfig;
use Copyleaks\CopyleaksDeleteRequestModel;
use Copyleaks\CopyleaksExportModel;
use Copyleaks\CopyleaksFileOcrSubmissionModel;
use Copyleaks\CopyleaksFileSubmissionModel;
use Copyleaks\CopyleaksStartErrorHandlings;
use Copyleaks\CopyleaksStartRequestModel;
use Copyleaks\CopyleaksURLSubmissionModel;
use Copyleaks\ExportCrawledVersion;
use Copyleaks\ExportResults;
use Copyleaks\IdObject;
use Copyleaks\SubmissionActions;
use Copyleaks\SubmissionAuthor;
use Copyleaks\SubmissionExclude;
use Copyleaks\SubmissionFilter;
use Copyleaks\SubmissionIndexing;
use Copyleaks\SubmissionPDF;
use Copyleaks\SubmissionProperties;
use Copyleaks\SubmissionRepository;
use Copyleaks\SubmissionScanning;
use Copyleaks\SubmissionScanningCopyleaksDB;
use Copyleaks\SubmissionScanningExclude;
use Copyleaks\SubmissionSensitiveData;
use Copyleaks\SubmissionWebhooks;
use Throwable;

class Test {
  public Copyleaks $copyleaks;
  public string $webookUrl;
  public function __construct() {
    $this->copyleaks = new Copyleaks();
  }

  public function run(string $email, string $key, string $webook) {
    $this->webookUrl = $webook;
    try {
      CopyleaksConfig::SET_IDENTITY_SERVER_URI("https://id.copyleaks.com");
      CopyleaksConfig::SET_API_SERVER_URI("https://api.copyleaks.com");

      // $this->TEST_MISC();

      $loginResult = $this->copyleaks->login($email, $key);
      $this->logInfo("-Login-", $loginResult);

      // $this->TEST_UsagesHistoryCsv($loginResult);

      // $this->TEST_CreditsBalance($loginResult);

      // $this->TEST_delete($loginResult);

      // $this->TEST_start($loginResult);

      // $this->TEST_submitUrl($loginResult);

      // $this->TEST_submitFile($loginResult);

      // $this->TEST_submitOcrFile($loginResult);

      // $this->TEST_export($loginResult);

    } catch (Throwable $th) {
      echo $th->getMessage();
    }
  }

  private function TEST_export(CopyleaksAuthToken $authToken) {
    $model = new CopyleaksExportModel(
      "$this->webookUrl/export-webhook",
      array(new ExportResults("2a1b402420", "$this->webookUrl/export-webhook/result/2a1b402420", "POST", array(array("key", "value")))),
      new ExportCrawledVersion("$this->webookUrl/export-webhook/crawled-version", "POST", array(array("key", "value")))
    );
    $exportedScanId = "1611042365";
    $this->copyleaks->export($authToken, $exportedScanId, $exportedScanId, $model);
    $this->logInfo("-export-");
  }

  private function TEST_submitOcrFile(CopyleaksAuthToken $authToken) {
    $submission = new CopyleaksFileOcrSubmissionModel(
      "en",
      "aGVsbG8gd29ybGQ=",
      "php.txt",
      new SubmissionProperties(
        new SubmissionWebhooks("$this->webookUrl/{STATUS}"),
        false,
        null,
        true,
        6,
        1,
        true,
        SubmissionActions::Scan,
        new SubmissionAuthor('php-test'),
        new SubmissionFilter(true, true, true),
        new SubmissionScanning(true, new SubmissionScanningExclude('php-test-*'), null, new SubmissionScanningCopyleaksDB(true, true)),
        new SubmissionIndexing((array)[new SubmissionRepository('repoId')]),
        new SubmissionExclude(true, true, true, true, true),
        new SubmissionPDF(true, 'title', 'https://lti.copyleaks.com/images/copyleaks50x50.png', false)
      )
    );

    $this->copyleaks->submitFileOcr($authToken, time(), $submission);
    $this->logInfo("-submitFileOcr-");
  }

  private function TEST_submitFile(CopyleaksAuthToken $authToken) {
    $submission = new CopyleaksFileSubmissionModel(
      "aGVsbG8gd29ybGQ=",
      "php.txt",
      new SubmissionProperties(
        new SubmissionWebhooks("$this->webookUrl/{STATUS}"),
        false,
        null,
        true,
        6,
        1,
        true,
        SubmissionActions::Scan,
        new SubmissionAuthor('php-test'),
        new SubmissionFilter(true, true, true),
        new SubmissionScanning(true, new SubmissionScanningExclude('php-test-*'), null, new SubmissionScanningCopyleaksDB(true, true)),
        new SubmissionIndexing((array)[new SubmissionRepository('repoId')]),
        new SubmissionExclude(true, true, true, true, true),
        new SubmissionPDF(true, 'title', 'https://lti.copyleaks.com/images/copyleaks50x50.png', false)
      )
    );

    $this->copyleaks->submitFile($authToken, time(), $submission);
    $this->logInfo("-submitFile-");
  }

  private function TEST_submitUrl(CopyleaksAuthToken $authToken) {
    $submission = new CopyleaksURLSubmissionModel(
      "https://copyleaks.com",
      new SubmissionProperties(
        new SubmissionWebhooks("$this->webookUrl/{STATUS}"),
        false,
        null,
        true,
        6,
        1,
        true,
        SubmissionActions::Scan,
        new SubmissionAuthor('php-test'),
        new SubmissionFilter(true, true, true),
        new SubmissionScanning(true, new SubmissionScanningExclude('php-test-*'), null, new SubmissionScanningCopyleaksDB(true, true)),
        new SubmissionIndexing((array)[new SubmissionRepository('repoId')]),
        new SubmissionExclude(true, true, true, true, true),
        new SubmissionPDF(true, 'title', 'https://lti.copyleaks.com/images/copyleaks50x50.png', false)
      )
    );

    $this->copyleaks->submitUrl($authToken, time(), $submission);
    $this->logInfo("-submitUrl-");
  }

  private function TEST_start(CopyleaksAuthToken $authToken) {
    $model = new CopyleaksStartRequestModel(array("cqcps25xxh5cloxe"), CopyleaksStartErrorHandlings::IGNORE);
    $start = $this->copyleaks->start($authToken, $model);
    $this->logInfo("-start-", $start);
  }

  private function TEST_delete(CopyleaksAuthToken $authToken) {
    $idsToDelete = array(new IdObject("cqcps25xxh5cloxe"));

    $model = new CopyleaksDeleteRequestModel($idsToDelete, true, "https://glacial-refuge-96501.herokuapp.com/18ml1by1/delete-hook");
    $delete = $this->copyleaks->delete($authToken, $model);

    $this->logInfo("-delete-", $delete);
  }

  private function TEST_CreditsBalance(CopyleaksAuthToken $authToken) {
    $CreditsBalance = $this->copyleaks->getCreditsBalance($authToken);
    $this->logInfo("-getCreditsBalance:", $CreditsBalance);

  }

  private function TEST_UsagesHistoryCsv(CopyleaksAuthToken $authToken) {
    $UsagesHistoryCsv = $this->copyleaks->getUsagesHistoryCsv($authToken, '01-01-2021', '02-02-2021');
    $this->logInfo("-getUsagesHistoryCsv:", $UsagesHistoryCsv);
  }

  private function TEST_MISC() {
    $OCRSupportedLanguages = $this->copyleaks->getOCRSupportedLanguages();
    $this->logInfo("-getOCRSupportedLanguages-", $OCRSupportedLanguages);

    $SupportedFileTypes = $this->copyleaks->getSupportedFileTypes();
    $this->logInfo("-getSupportedFileTypes-", $SupportedFileTypes);

    $ReleaseNotes = $this->copyleaks->getReleaseNotes();
    $this->logInfo("-getReleaseNotes-", $ReleaseNotes);
  }

  private function logInfo($title, $info = null) {
    echo "\n";
    echo "----------------" . $title . "----------------" . "\n\n";
    if ($info) {
      echo json_encode($info);
      echo "\n\n";
    }
  }
}
