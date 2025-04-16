<?php

namespace Enjoyscms\PackageSetup;

use Composer\Composer;
use Composer\EventDispatcher\Event;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;
use Enjoyscms\PackageSetup\SetupHandlers\Cmd;
use Enjoyscms\PackageSetup\SetupHandlers\ConsoleConfigurator;
use Enjoyscms\PackageSetup\SetupHandlers\Env;
use Enjoyscms\PackageSetup\SetupHandlers\SetupHandler;
use Enjoyscms\PackageSetup\SetupHandlers\Symlink;

class PackageConfigurator implements PluginInterface, EventSubscriberInterface
{

    private Composer $composer;
    private IOInterface $io;
    private string $vendorDir;
    private array $installedPackages;
    private string $rootPath;

    /**
     * @var class-string<SetupHandler>[]
     */
    private array $handlers = [
        Cmd::class,
        Env::class,
        Symlink::class,
        ConsoleConfigurator::class
    ];

    public function activate(Composer $composer, IOInterface $io): void
    {
        $this->composer = $composer;
        $this->io = $io;
        $this->vendorDir = $this->composer->getConfig()->get('vendor-dir');
        $installed = require $this->vendorDir . '/composer/installed.php';
        $this->installedPackages = $installed['versions'];
        $this->rootPath = realpath($installed['root']['install_path']);
        $this->composer->getConfig()->merge([
            'config' => [
                'root-path' => $this->rootPath
            ]
        ]);
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ScriptEvents::POST_INSTALL_CMD => 'process',
            ScriptEvents::POST_UPDATE_CMD => 'process',
        ];
    }

    public function process(Event $event): void
    {
        $handlePaths = array_merge([
            'Application' => $this->rootPath . '/install.json',
        ], $this->getModulesInfo());


        foreach ($handlePaths as $package => $path) {
            if (!file_exists($path)) {
                continue;
            }

            $packageConfig = json_decode(file_get_contents($path), true);

            $this->io->write(["", sprintf("<warning>%s is configuring...</warning>", $package)]);

            foreach ($this->handlers as $handlerClass) {
                $handler = new $handlerClass($packageConfig, $this->composer, $this->io);
                $handler->setCwd(pathinfo($path, PATHINFO_DIRNAME));
                $handler->process();
            }
        }
        $this->io->write(["", "<info>enjoyscms/package-setup:</info> Packages is configured"]);
    }

    private function getModulesInfo(): array
    {
        $result = [];

        foreach ($this->findPackages('enjoyscms') as $package) {
            if (!file_exists($package['install_path'] . '/install.json')) {
                continue;
            }
            $result[sprintf(
                '%s',
                $package['name']
            )] = $package['install_path'] . '/install.json';
        }
        return $result;
    }

    private function findPackages(?string $packageNamePattern = null, ?string $type = null): array
    {
        $result = [];
        foreach ($this->installedPackages as $packageName => $packageInfo) {
            if (!array_key_exists('install_path', $packageInfo) || $packageInfo['install_path'] === null) {
                continue;
            }
            $packageInfo['name'] = $packageName;
            $packageInfo['install_path'] = realpath($packageInfo['install_path']);

            if ($packageNamePattern !== null) {
                if (\str_contains($packageName, $packageNamePattern)) {
                    $result[] = $packageInfo;
                    continue;
                }
            }
            if ($type !== null) {
                if ($packageInfo['type'] === $type) {
                    $result[] = $packageInfo;
                }
            }
        }
        return $result;
    }
}
