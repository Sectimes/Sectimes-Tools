<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ExecuteShellCommandController extends Controller
{
    public function execute($cmd): string
    {
        $process = Process::fromShellCommandline($cmd);

        $processOutput = '';

        $captureOutput = function ($type, $line) use (&$processOutput) {
            $processOutput .= $line;
        };

        $process->setTimeout(null);

        try {
            $process->mustRun($captureOutput);
        } catch (ProcessFailedException $e) {
            report($e);

            throw $e;
        }

        return $processOutput;
    }
}
