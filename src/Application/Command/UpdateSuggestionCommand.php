<?php

namespace App\Application\Command;

class UpdateSuggestionCommand
{
    private $id;
    private $userId;
    private $status;
    private $comment;

    public function __construct($userId, $status, $comment = '')
    {
        $this->userId = $userId;
        $this->status = $status;
        $this->comment = $comment;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }
}