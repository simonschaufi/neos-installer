<?php

declare(strict_types=1);

namespace Neos\Installer\Console\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Neos\Installer\Console\NewCommand;

class NewCommandTest extends TestCase
{
    /**
     * @test
     */
    public function itCanScaffoldANewNeosProject(): void
    {
        $scaffoldDirectoryName = 'tests-output/my-app';
        $scaffoldDirectory = __DIR__ . '/../' . $scaffoldDirectoryName;

        if (file_exists($scaffoldDirectory)) {
            if (PHP_OS_FAMILY === 'Windows') {
                exec("rd /s /q \"$scaffoldDirectory\"");
            } else {
                exec("rm -rf \"$scaffoldDirectory\"");
            }
        }

        $app = new Application('Neos Installer');
        $app->add(new NewCommand);

        $tester = new CommandTester($app->find('new'));

        $statusCode = $tester->execute(['name' => $scaffoldDirectoryName]);

        self::assertSame(0, $statusCode);
        self::assertDirectoryExists($scaffoldDirectory . '/Packages/Libraries');
        self::assertFileExists($scaffoldDirectory . '/flow');
    }
}
