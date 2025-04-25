<?php

namespace Enjoyscms\PackageSetup;

use Composer\Composer;
use Composer\EventDispatcher\Event;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;

class PackageConfigurator implements PluginInterface, EventSubscriberInterface
{

    private Composer $composer;
    private IOInterface $io;
    private string $rootPath;
    private string $vendorDir;
    private PackageCollection $packageCollection;
    private \Composer\Repository\InstalledRepositoryInterface $localRepository;
    private \Composer\Installer\InstallationManager $instalationManager;

    public function activate(Composer $composer, IOInterface $io): void
    {
        $this->composer = $composer;
        $this->io = $io;
        $this->vendorDir = $this->composer->getConfig()->get('vendor-dir');
        $this->rootPath = realpath($this->vendorDir . '/../');
        $this->localRepository = $this->composer->getRepositoryManager()->getLocalRepository();
        $this->instalationManager = $this->composer->getInstallationManager();
        $this->composer->getConfig()->merge([
            'config' => [
                'root-path' => $this->rootPath
            ]
        ]);
        $this->packageCollection = new PackageCollection();

        if (!getenv('ROOT_PATH')) {
            putenv(sprintf('ROOT_PATH=%s', $this->rootPath));
            $_ENV['ROOT_PATH'] = getenv('ROOT_PATH');
        }
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
        foreach ($this->getModulesPackages() as $modulesPackage) {
            $this->packageCollection->add($modulesPackage);
        }
        $this->packageCollection->add(new Package($this->composer->getPackage(), $this->rootPath));

        foreach ($this->packageCollection->getCollection() as $package) {
            $installJsonPath = $package->installationPath . '/install.json';

            if (!file_exists($installJsonPath)) {
                continue;
            }

            $packageConfig = json_decode(file_get_contents($installJsonPath), true);

            $this->io->write(["", sprintf("<warning>%s is configuring...</warning>", $package->getName())]);

            foreach ($packageConfig as $configurator => $options) {
                $handlerClass = Configurator::tryFrom($configurator)?->handler();
                if ($handlerClass === null) {
                    continue;
                }
                $handler = new $handlerClass($options, $this->composer, $this->io, $package);
                $handler->setCwd($package->installationPath);
                try {
                    $handler->process();
                } catch (\Exception $e) {
                    $this->io->write(
                        sprintf(
                            '<fg=red;bg=default>[%s] %s</>',
                            $e::class,
                            $e->getMessage()
                        )
                    );
                }
            }
        }
        $this->io->write(["", "<info>enjoyscms/package-setup:</info> Packages is configured"]);
    }

    private function getModulesPackages(): array
    {
        $packages = [];
        foreach (
            array_merge(
                $this->localRepository->search('', type: 'enjoyscms-module'),
                /** @todo remove, when all modules will be have type enjoyscms-module */
                $this->localRepository->search('enjoyscms')
            ) as $data
        ) {
            $package = $this->localRepository->findPackage($data['name'], '*');

            if ($package === null) {
                continue;
            }

            $installPath = $this->instalationManager->getInstallPath($package);

            if ($installPath === null) {
                continue;
            }
            $packages[] = new Package($package, $installPath);
        }
        return $packages;
    }

}
