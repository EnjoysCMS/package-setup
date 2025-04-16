<?php

namespace Enjoyscms\PackageSetup;

use Composer\Composer;
use Composer\EventDispatcher\Event;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;
use Enjoyscms\PackageSetup\SetupHandlers\Cmd;
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

    public function process(Event $event)
    {
        $handlePaths = [
            'Application' => $this->rootPath . '/install.json',
        ];

        foreach ($handlePaths as $package => $path) {
            if (!file_exists($path)) {
                continue;
            }

            $packageConfig = json_decode(file_get_contents($path), true);

            $this->io->write(sprintf('<warning>Configuring is %s...</warning>', $package));

            foreach ($this->handlers as $handlerClass) {
                $handler = new $handlerClass($packageConfig, $this->composer, $this->io);
                $handler->setCwd(pathinfo($path, PATHINFO_DIRNAME));
                $handler->process();
            }
        }
        $this->io->write('<info>enjoyscms/package-setup:</info> Packages is configured');
    }
}
