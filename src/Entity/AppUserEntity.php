<?php

namespace App\Entity;

use App\Helper\DbConnection;
use Exception;
use ReflectionClass;

class AppUserEntity extends BaseEntity
{
    private int $userId;
    private string $email;
    private string $password;
    private string $role;
    private string $name;

    public function __construct(array $data)
    {
        $array = explode('\\', self::class);
        $this->tableName = self::getCamelValue(end($array));
        if (empty($data)) {
            return;
        }
        if (isset($data['user_id'])) {
            $this->userId = $data['user_id'];
        }
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->role = $data['role'];
        $this->name = $data['name'];
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

    public function setPassword(string $password)
    {
        $this->password = (string)password_hash($password, PASSWORD_DEFAULT);
    }

    public function __get($property)
    {
        return $this->$property;
    }

    public function __set($name, $value)
    {
        if ($name == 'password') {
            $this->$name = (string)password_hash($value, PASSWORD_DEFAULT);
        }
        $this->$name = $value;
    }

    public function save(): bool
    {
        try {
            if (!isset($this->userId)) {
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
        $columns = self::iterateColumns(false, false);
        $values = self::iterateColumns(true, false);

        $sql = "INSERT INTO $this->tableName ($columns) VALUES ($values)";
        $fieldValues = self::iterateFields(false);
        return $this->db->execGetStatus($sql, $fieldValues);
    }

    protected function iterateColumns(bool $toNamedColumns = false, bool $includeId = true): string
    {
        $startWith = $toNamedColumns ? ":" : "";
        $tableFields = self::getAllEntityFields(true);
        $columnsIterable = "";
        foreach ($tableFields as $column) {
            if ($column === 'user_id' && !$includeId) {
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

    protected function iterateFields(bool $includeId = true): array
    {
        $entityFields = self::getAllEntityFields(true);
        if (!$includeId) {
            unset($entityFields[0]);
        }
        $valueFields = self::getAllEntityFields();
        $valuesArray = array();
        foreach ($valueFields as $field) {
            if ($field == 'userId' && !$includeId) {
                continue;
            }
            $valuesArray[] = $this->{$field} ?? null;
        }
        return array_combine($entityFields, $valuesArray);
    }

    protected function update(): int
    {
        $data = self::iterateColumnsAndFields();
        $sql = "UPDATE " . $this->tableName . " SET " . $data['query_string'] . " WHERE user_id=:id";
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
}
