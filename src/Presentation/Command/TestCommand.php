<?php

namespace App\Presentation\Command;

use App\Application\RegisterViolationDTO;
use App\Application\ViolationRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
    /**
     * @var ViolationRegistry
     */
    private $registrator;

    public function __construct(?string $name = null, ViolationRegistry $registrator)
    {
        $this->registrator = $registrator;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:test')
            ->addArgument('qm')
            ->addArgument('vr')
            ->addArgument('vi')
            ->addArgument('vt')
            ->addArgument('r')
            ->addArgument('c')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $violation = $this->registrator->register(
            new RegisterViolationDTO(
                $input->getArgument('qm'),
                $input->getArgument('vr'),
                $input->getArgument('vi'),
                $input->getArgument('vt'),
                $input->getArgument('r'),
                $input->getArgument('c')
            )
        );
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'Violation',
            '============',
            '',
        ]);
        dump($violation);
    }

}