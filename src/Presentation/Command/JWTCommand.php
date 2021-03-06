<?php

namespace App\Presentation\Command;

use Firebase\JWT\JWT;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class JWTCommand extends Command
{

    public function __construct(?string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:jwt')
            ->addArgument('uid')
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
        $key = "mysecret";
        $token = array(
            "uid" => $input->getArgument('uid'),
            "iat" => time(),
            "exp" => time()+3600
        );

        /**
         * IMPORTANT:
         * You must specify supported algorithms for your application. See
         * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
         * for a list of spec-compliant algorithms.
         */
        $jwt = JWT::encode($token, $key);
        $output->writeln([
            'Token',
            '============',
            $jwt,
        ]);
        $decoded = JWT::decode($jwt, $key, array('HS256'));

        print_r($decoded);

        dump($decoded);
        dump(time());
    }

}