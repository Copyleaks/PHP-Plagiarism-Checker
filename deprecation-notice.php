<?php
// ANSI color codes for terminal output
$yellow = "\033[33m";
$red = "\033[31m";
$reset = "\033[0m";

// Get package info from composer.json
$composerJson = file_get_contents(__DIR__ . '/composer.json');
$packageInfo = json_decode($composerJson, true);
$packageName = $packageInfo['name'] ?? 'your-package';

echo <<<EOT

{$yellow}================================================================{$reset}
{$red}⚠️  DEPRECATION NOTICE: {$packageName} v{$packageVersion}{$reset}
{$yellow}================================================================{$reset}
{$yellow}AI Code Detection will be discontinued on August 29, 2025.{$reset}
{$yellow}Please remove AI code detection integrations before the sunset date.{$reset}
{$yellow}================================================================{$reset}

EOT;