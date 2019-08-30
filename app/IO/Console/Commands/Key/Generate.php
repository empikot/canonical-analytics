<?php

namespace App\IO\Console\Commands\Key;

use App\IO\Exceptions\DotEnvFileWasNotFoundException;
use Illuminate\Console\Command;

class Generate extends Command
{
    /**
     * @var string
     */
    protected $signature = 'key:generate';

    /**
     * @var string
     */
    protected $description = 'Generates APP_KEY in .env file';

    /**
     * @throws DotEnvFileWasNotFoundException
     */
    public function handle()
    {
        $env = $this->getDotEnvFileContents();
        $env = $this->insertMissingAppKey($env);
        $this->setDotEnvFileContents($env);
        return;
    }

    /**
     * @return string
     * @throws DotEnvFileWasNotFoundException
     */
    private function getDotEnvFileContents(): string
    {
        echo $this->getDotEnvFilePath();
        if (file_exists($this->getDotEnvFilePath())) {
            return file_get_contents($this->getDotEnvFilePath());
        }
        throw new DotEnvFileWasNotFoundException();
    }

    /**
     * @param string $env
     * @return string
     */
    private function insertMissingAppKey(string $env): string
    {
        if (strpos($env, "APP_KEY=\n") !== false) {
            return str_replace('APP_KEY=', 'APP_KEY=' . $this->generateAppKey(), $env);
        }
        return $env;
    }

    /**
     * @return string
     */
    private function generateAppKey(): string
    {
        return str_random(32);
    }

    /**
     * @param string $env
     */
    private function setDotEnvFileContents(string $env)
    {
        file_put_contents($this->getDotEnvFilePath(), $env);
    }

    /**
     * @return string
     */
    private function getDotEnvFilePath(): string
    {
        return __ROOT__ . DIRECTORY_SEPARATOR . '.env';
    }
}
