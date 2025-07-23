<?php

declare(strict_types=1);

namespace App\Command;

use App\Utils\IpLocationUtils;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Psr\Container\ContainerInterface;

#[Command]
class Test extends HyperfCommand
{

    public function __construct(protected ContainerInterface $container,private IpLocationUtils $ipLocationUtils)
    {
        parent::__construct('test');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Test Command');
    }

    public function handle()
    {
        $ip = '144.48.80.169';
        $location = $this->ipLocationUtils->getSimpleLocation($ip);
        dump($location);
    }
}
