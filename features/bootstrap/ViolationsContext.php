<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;

class ViolationsContext implements Context
{
    private $em;

    const SUPPORT_VIOLATION_TABLE = 'support_violation';

    /**
     * ViolationsContext constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     *
     * @BeforeScenario
     */
    public function clearDb()
    {
        $this->em->getConnection()->executeUpdate('SET foreign_key_checks=0');
        $this->em->getConnection()->executeUpdate(sprintf('TRUNCATE TABLE %s', self::SUPPORT_VIOLATION_TABLE));
        $this->em->getConnection()->executeUpdate('SET foreign_key_checks=1');
    }


    /**
     * @Given There are violations:
     */
    public function thereAreViolations(TableNode $table)
    {
        $default = [
            'created_at' => (new DateTime())->format('Y-m-d H:i:s'),
            'quality_manager_id' => 1,
            'resolved' => 0,
            'comment' => '',
        ];
        foreach ($table->getColumnsHash() as $row) {
            $row = array_filter($row, function ($value) { return $value !== "null"; });
            $this->em->getConnection()->insert(
                self::SUPPORT_VIOLATION_TABLE,
                $row + $default
            );
        }
    }
}
