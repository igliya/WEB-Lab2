<?php

namespace App\Entity;

use App\Helper\DbConnection;
use App\Repository\AppUserRepository;
use DateTime;
use Exception;
use ReflectionClass;

class TicketEntity extends BaseEntity
{
    private int $ticketId;
    private int $userId;
    private int $supportId;
    private $theme;
    private $content;
    private string $file;
    private $isClosed;
    private $status;
    private DateTime $ticketDate;

    public function __construct(array $data)
    {
        $array = explode('\\', self::class);
        $this->tableName = self::getCamelValue(end($array));
        if (empty($data)) {
            return;
        }
        if (isset($data['ticket_id'])) {
            $this->ticketId = $data['ticket_id'];
        }
        if (isset($data['support_id'])) {
            $this->supportId = $data['support_id'];
        }
        if (isset($data['file'])) {
            $this->file = $data['file'];
        }
        if (isset($data['is_closed'])) {
            $this->isClosed = $data['is_closed'];
        }
        if (isset($data['status'])) {
            $this->status = $data['status'];
        }
        if (isset($data['ticket_date'])) {
            $this->ticketDate = DateTime::createFromFormat('Y-m-d H:i:s.u', $data['ticket_date']);
        }
        $this->userId = $data['user_id'];
        $this->theme = $data['theme'];
        $this->content = $data['content'];
        try {
            $this->db = new DbConnection();
        } catch (Exception $e) {
            echo $e;
        }
    }

    protected function getCamelValue(string $value): string
    {
        $var = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $value));
        return str_replace("_entity", "", $var);
    }

    public function __get($property)
    {
        if ($property == 'ticketDate') {
            return $this->$property->format('Y-m-d H:i:s');
        }
        return $this->$property ?? null;
    }

    public function __set($name, $value)
    {
        if ($name == 'ticketDate') {
            $this->ticketDate = DateTime::createFromFormat('Y-m-d H:i:s.u', $value);
            return;
        }
        $this->$name = $value;
    }

    public function save(): bool
    {
        try {
            if (!isset($this->ticketId)) {
                return self::create();
            }
            $rowCountAffected = self::update();
            if ($rowCountAffected == 0) {
                return false;
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    protected function create(): bool
    {
        $excludedFields = ['support_id', 'is_closed', 'status', 'ticket_date'];
        $excludedValues = ['supportId', 'isClosed', 'status', 'ticketDate'];
        if (!isset($this->file)) {
            $excludedFields[] = 'file';
            $excludedValues[] = 'file';
        }
        $columns = self::iterateColumns(false, false, $excludedFields);
        $values = self::iterateColumns(true, false, $excludedFields);

        $sql = "INSERT INTO $this->tableName ($columns) VALUES ($values)";
        $fieldValues = self::iterateFields(false, $excludedFields, $excludedValues);
        $result = $this->db->execGetStatus($sql, $fieldValues);
        $this->ticketId = $this->db->getLastInsertId();
        return $result;
    }

    protected function iterateColumns(
        bool $toNamedColumns = false,
        bool $includeId = true,
        array $excludedFields = []
    ): string {
        $startWith = $toNamedColumns ? ":" : "";
        $tableSrcFields = self::getAllEntityFields(true);
        $tableFields = array_diff($tableSrcFields, $excludedFields);
        $columnsIterable = "";
        foreach ($tableFields as $column) {
            if ($column === 'ticket_id' && !$includeId) {
                continue;
            } else {
                if (end($tableFields) != $column) {
                    $columnsIterable .= $startWith . $column . ", ";
                } else {
                    $columnsIterable .= $startWith . $column;
                }
            }
        }
        return $columnsIterable;
    }

    protected function getAllEntityFields(bool $camelCase = false): array
    {
        $reflect = new ReflectionClass(new self([]));
        $props = $reflect->getProperties();
        $ownProps = [];
        foreach ($props as $prop) {
            if ($prop->class === self::class) {
                $ownProps[] = $camelCase ? self::getCamelValue($prop->getName()) : $prop->getName();
            }
        }
        return $ownProps;
    }

    protected function iterateFields(
        bool $includeId = true,
        array $excludedFields = [],
        array $excludedValues = []
    ): array {
        $entityFields = array_diff(self::getAllEntityFields(true), $excludedFields);
        if (!$includeId) {
            unset($entityFields[0]);
        }
        $valueFields = array_diff(self::getAllEntityFields(), $excludedValues);
        $valuesArray = array();
        foreach ($valueFields as $field) {
            if ($field == 'ticketId' && !$includeId) {
                continue;
            }
            $valuesArray[] = $this->{$field} ?? null;
        }
        return array_combine($entityFields, $valuesArray);
    }

    protected function update(): int
    {
        $data = self::iterateColumnsAndFields();
        $sql = "UPDATE " . $this->tableName . " SET " . $data['query_string'] . " WHERE ticket_id=:ticket_id";
        $newArray = array();
        $flag = true;
        foreach ($data['fields'] as $fieldKey => $field) {
            if ($flag) {
                $flag = false;
            } else {
                $newArray[$fieldKey] = $field;
            }
        }
        $newArray['ticket_id'] = $data['fields']['ticket_id'];
        return $this->db->execGetRowCount($sql, $newArray);
    }

    protected function iterateColumnsAndFields(): array
    {
        $result = array();
        $queryString = "";
        $tableFields = array_diff(
            self::getAllEntityFields(true),
            ['ticket_id', 'ticket_date', 'user_id', 'theme', 'content', 'file']
        );
        foreach ($tableFields as $column) {
            $queryString .= $column . "=:" . $column;
            if (end($tableFields) != $column) {
                $queryString .= ", ";
            }
        }
        $result['query_string'] = $queryString;
        $result['fields'] = self::iterateFields(
            true,
            ['ticket_date', 'user_id', 'theme', 'content', 'file'],
            ['ticketDate', 'userId', 'theme', 'content', 'file']
        );
        return $result;
    }

    public function getFileUrl()
    {
        return "http://" . $_SERVER['HTTP_HOST'] . '/uploads/' . $this->file;
    }

    public function getTicketUrl()
    {
        return "http://" . $_SERVER['HTTP_HOST'] . "/ticket/" . $this->ticketId;
    }

    public function getUserName()
    {
        $repos = new AppUserRepository();
        $user = $repos->findOneBy(['user_id' => $this->userId]);
        return $user->name;
    }

    public function getSupportName()
    {
        if (!isset($this->supportId)) {
            return "Отсутствует";
        }
        $repos = new AppUserRepository();
        $user = $repos->findOneBy(['user_id' => $this->supportId]);
        return $user->name;
    }
}
