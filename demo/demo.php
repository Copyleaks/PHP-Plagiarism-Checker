<?php

namespace Demo;

use Copyleaks\Copyleaks;
use Copyleaks\CopyleaksAuthToken;
use Copyleaks\CopyleaksConfig;
use Throwable;

// Include utilities and all example files
require_once 'examples/Utils.php';
require_once 'examples/ExportExample.php';
require_once 'examples/SubmitOcrFileExample.php';
require_once 'examples/SubmitFileExample.php';
require_once 'examples/SubmitUrlExample.php';
require_once 'examples/AiDetectionNaturalLanguageExample.php';
require_once 'examples/WritingAssistantExample.php';
require_once 'examples/StartExample.php';
require_once 'examples/DeleteExample.php';
require_once 'examples/CreditsBalanceExample.php';
require_once 'examples/UsageHistoryCsvExample.php';
require_once 'examples/MiscExample.php';
require_once 'examples/TextModerationExample.php';
require_once 'examples/ImageDetectionExample.php';

use function Demo\Examples\logInfo;
use function Demo\Examples\runExportExample;
use function Demo\Examples\runSubmitOcrFileExample;
use function Demo\Examples\runSubmitFileExample;
use function Demo\Examples\runSubmitUrlExample;
use function Demo\Examples\runAiDetectionNaturalLanguageExample;
use function Demo\Examples\runWritingAssistantExample;
use function Demo\Examples\runStartExample;
use function Demo\Examples\runDeleteExample;
use function Demo\Examples\runCreditsBalanceExample;
use function Demo\Examples\runUsageHistoryCsvExample;
use function Demo\Examples\runMiscExample;
use function Demo\Examples\runTextModerationExample;
use function Demo\Examples\runImageDetectionExample;

class Test {
    public Copyleaks $copyleaks;
    public string $webhookUrl;

    public function __construct() {
        $this->copyleaks = new Copyleaks();
    }

    public function run(string $email, string $key, string $webhook): void {
        $this->webhookUrl = $webhook;
        
        try {
            CopyleaksConfig::SET_IDENTITY_SERVER_URI("https://id.copyleaks.com");
            CopyleaksConfig::SET_API_SERVER_URI("https://api.copyleaks.com");

            $loginResult = $this->copyleaks->login($email, $key);
            logInfo("-Login-", $loginResult);

            // Uncomment the examples you want to run:
            
            // runMiscExample($this->copyleaks);
            
            // runUsageHistoryCsvExample($this->copyleaks, $loginResult);
            
            // runCreditsBalanceExample($this->copyleaks, $loginResult);
            
            // runDeleteExample($this->copyleaks, $loginResult);
            
            // runStartExample($this->copyleaks, $loginResult);
            
            // runSubmitUrlExample($this->copyleaks, $loginResult, $this->webhookUrl);
            
            // runSubmitFileExample($this->copyleaks, $loginResult, $this->webhookUrl);
            
            // runSubmitOcrFileExample($this->copyleaks, $loginResult, $this->webhookUrl);
            
            // runExportExample($this->copyleaks, $loginResult, $this->webhookUrl);
            
            // runAiDetectionNaturalLanguageExample($this->copyleaks, $loginResult);
            
            // runWritingAssistantExample($this->copyleaks, $loginResult);
            
            // // runTextModerationExample($this->copyleaks, $loginResult);
            
            runImageDetectionExample($this->copyleaks, $loginResult);

        } catch (Throwable $th) {
            echo $th->getMessage();
        }
    }
}
