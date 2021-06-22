<?php

namespace App\Entity;

use App\Helper\DbConnection;
use App\Repository\AppUserRepository;
use DateTime;
use Exception;
use ReflectionClass;

class CommentEntity extends BaseEntity
{
    private int $commentId;
    private int $userId;
    private int $ticketId;
    private string $text;
    private DateTime $commentDate;

    public function __construct(array $data)
    {
        $array = explode('\\', self::class);
        $this->tableName = self::getCamelValue(end($array));
        if (empty($data)) {
            return;
        }
        if (isset($data['comment_id'])) {
            $this->commentId = $data['comment_id'];
        }
        $this->userId = $data['user_id'];
        $this->ticketId = $data['ticket_id'];
        $this->text = $data['text'];
        if (isset($data['comment_date'])) {
            $this->commentDate = DateTime::createFromFormat('Y-m-d H:i:s.u', $data['comment_date']);
        }
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
        if ($property == 'commentDate') {
            return $this->$property->format('Y-m-d H:i:s');
        }
        return $this->$property;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function save(): bool
    {
        try {
            if (!isset($this->commentId)) {
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
        $excludedFields = ['comment_date'];
        $excludedValues = ['commentDate'];
        $columns = self::iterateColumns(false, false, $excludedFields);
        $values = self::iterateColumns(true, false, $excludedFields);

        $sql = "INSERT INTO $this->tableName ($columns) VALUES ($values)";
        $fieldValues = self::iterateFields(false, $excludedFields, $excludedValues);
        return $this->db->execGetStatus($sql, $fieldValues);
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
            if ($column === 'comment_id' && !$includeId) {
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
        array $excludeFields = [],
        array $excludedValues = []
    ): array {
        $entityFields = array_diff(self::getAllEntityFields(true), $excludeFields);
        if (!$includeId) {
            unset($entityFields[0]);
        }
        $valueFields = array_diff(self::getAllEntityFields(), $excludedValues);
        $valuesArray = array();
        foreach ($valueFields as $field) {
            if ($field == 'commentId' && !$includeId) {
                continue;
            }
            $valuesArray[] = $this->{$field} ?? null;
        }
        return array_combine($entityFields, $valuesArray);
    }

    protected function update(): int
    {
        $data = self::iterateColumnsAndFields();
        $sql = "UPDATE " . $this->tableName . " SET " . $data['query_string'] . " WHERE comment_id=:comment_id";
        return $this->db->execGetRowCount($sql, $data['fields']);
    }

    protected function iterateColumnsAndFields(): array
    {
        $result = array();
        $queryString = "";
        $tableFields = self::getAllEntityFields();
        foreach ($tableFields as $column) {
            $queryString .= $column . "=:" . $column;
            if (end($tableFields) != $column) {
                $queryString .= ", ";
            }
        }
        $result['query_string'] = $queryString;
        $result['fields'] = self::iterateFields();
        return $result;
    }

    public function getUserName()
    {
        $repos = new AppUserRepository();
        $user = $repos->findOneBy(['user_id' => $this->userId]);
        return $user->name;
    }
}
