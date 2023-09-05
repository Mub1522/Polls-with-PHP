<?php

namespace Mub\PollsWithPhp\model;

use Mub\PollsWithPhp\model\Database;
use PDO;

class Poll extends Database
{
    private string $title;
    private string $ref;
    private int $id;
    private array $options;

    public function __construct($title, $createREF = false)
    {
        parent::__construct();
        $this->title = $title;
        $this->options = [];
        if ($createREF) {
            $this->ref = uniqid();
        }
    }

    public function save()
    {
        $query = $this->connect()->prepare("INSERT INTO polls (ref, title) VALUES (:ref, :title)");
        $query->execute(["ref" => $this->ref, "title" => $this->title]);

        $query = $this->connect()->prepare("SELECT id FROM polls WHERE ref = :ref");
        $query->execute(["ref" => $this->ref]);

        $this->id = $query->fetchColumn();
    }

    public function insertOptions(array $options)
    {
        foreach ($options as $option) {
            $query = $this->connect()->prepare("INSERT INTO options (poll_id, title, votes) VALUES (:poll_id, :title, 0)");
            $query->execute(["poll_id" => $this->id, "title" => $option]);
        }
    }

    public static function getPolls()
    {
        $polls = [];
        $db = new Database();
        $query = $db->connect()->query("SELECT * FROM polls");
        
        while ($r = $query->fetch(PDO::FETCH_ASSOC)) {
            $poll = Poll::createFromArray($r);
            array_push($polls, $poll);
        }

        return $polls;
    }

    public static function getPoll($ref)
    {
        $db = new Database();
        $query = $db->connect()->prepare("SELECT * FROM polls WHERE ref = :ref");
        $query->execute(['ref' => $ref]);

        $r = $query->fetch(PDO::FETCH_ASSOC);

        $poll = Poll::createFromArray($r);

        $query = $db->connect()->prepare("SELECT * FROM polls INNER JOIN options ON polls.id = options.poll_id WHERE polls.ref = :ref");
        $query->execute(["ref" => $ref]);

        while($r = $query->fetch(PDO::FETCH_ASSOC)){
            $poll->includeOptions($r);
        }

        return $poll;
    }

    public function vote($optionId)
    {
        $query = $this->connect()->prepare("UPDATE options SET votes = votes + 1 WHERE id = :id");
        $query->execute(['id' => $optionId]);

        $poll = Poll::getPoll($this->ref);
        return $poll;
    }

    public function getTotalVotes()
    {
        $total = 0;
        foreach ($this->options as $option) {
            $total = $total + $option['votes'];
        }

        return $total;
    }

    public function includeOptions($r)
    {
        array_push($this->options, $r);
    }

    public static function createFromArray(array $a)
    {
        $poll = new Poll($a['title'], false);
        $poll->setRef($a['ref']);
        $poll->setId($a['id']);

        return $poll;
    }

    public function setRef($value)
    {
        $this->ref = $value;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function getTitle()
    {
        return $this->title;
    }


}
