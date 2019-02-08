<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;

class SuggestionsContext implements Context
{
    private $em;

    const SUPPORT_SUGGESTION_TABLE = 'support_suggestion';

    /**
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
        $this->em->getConnection()->executeUpdate(sprintf('TRUNCATE TABLE %s', self::SUPPORT_SUGGESTION_TABLE));
        $this->em->getConnection()->executeUpdate('SET foreign_key_checks=1');
    }


    /**
     * @Given There are suggestions:
     */
    public function thereAreSuggestions(TableNode $table)
    {
        $default = [
            'created_at' => (new DateTime())->format('Y-m-d H:i:s'),
            'offered_by' => 1,
            'status' => 'unprocessed',
            'comment' => '',
        ];
        foreach ($table->getColumnsHash() as $row) {
            $row = array_filter($row, function ($value) { return $value !== "null"; });
            $this->em->getConnection()->insert(
                self::SUPPORT_SUGGESTION_TABLE,
                $row + $default
            );
        }
    }
}
