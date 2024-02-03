<?php

namespace Commands\Programs;

use Commands\AbstractCommand;
use Commands\Argument;

class JobManagement extends AbstractCommand
{
    protected static ?string $alias = 'job-mng';
    protected int $STATUS_CODE_SUCCESS = 0;
    protected int $STATUS_CODE_ERROR = 255;

    public static function getArguments(): array
    {
        return [
            (new Argument('register'))->description("")->required(false)->allowAsShort(false),
            (new Argument('delete'))->description("")->required(false)->allowAsShort(false),
            (new Argument('list'))->description("")->required(false)->allowAsShort(false),
        ];
    }

    public function execute(): int
    {
        $register = $this->getArgumentValue('register');
        $delete = $this->getArgumentValue('delete');
        $list = $this->getArgumentValue('list');

        if (!$register && !$delete && !$list) {
            echo "Specify one of the following options." . PHP_EOL . "--register, --delete, --list";
            return $this->STATUS_CODE_ERROR;
        }




        return $this->STATUS_CODE_SUCCESS;
    }

    private function listJobs(): void
    {
    }

    private function registerJob(string $jobScriptFileName): void
    {
    }

    private function deleteJob(string $jobScriptFileName): array
    {
        $directory = sprintf("%s/../../Database/Migrations", __DIR__);
        $this->log($directory);
        $allFiles = glob($directory . "/*.php");
        usort($allFiles, function ($a, $b) use ($order) {
            $compareResult = strcmp($a, $b);
            return ($order === 'desc') ? -$compareResult : $compareResult;
        });
        return $allFiles;
    }
}
