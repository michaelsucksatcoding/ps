<?php
class Model
{
    /** @var PDO */
    protected $db;
    /** @var string|null */
    protected $error;
    /** @var string[] */
    protected $fields = [];
    /** @var string */
    protected $table;

    function __construct($db_connect)
    {
        $this->db = $db_connect;
    }

    public function getAll()
    {
        try {
            $sql = 'SELECT ' . $this->buildFieldList($this->fields) . ' FROM ' . $this->escapeIdentifier($this->table);
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function getById($fieldname, $record)
    {
        try {
            $sql = 'SELECT ' . $this->buildFieldList($this->fields)
                . ' FROM ' . $this->escapeIdentifier($this->table)
                . ' WHERE ' . $this->escapeIdentifier($fieldname) . ' = :record LIMIT 1';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':record' => $record]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function getByCondition(array $fieldname, array $record)
    {
        try {
            [$whereSql, $bindings] = $this->buildWhereClause($fieldname, $record, 'w');
            $sql = 'SELECT ' . $this->buildFieldList($this->fields)
                . ' FROM ' . $this->escapeIdentifier($this->table)
                . ' WHERE ' . $whereSql;
            $stmt = $this->db->prepare($sql);
            $stmt->execute($bindings);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function inputData(array $data)
    {
        try {
            $parameter = $this->serializeData($data);
            $columns = array_map([$this, 'escapeIdentifier'], array_keys($data));
            $placeholders = implode(',', array_keys($parameter));
            $sql = 'INSERT INTO ' . $this->escapeIdentifier($this->table)
                . '(' . implode(',', $columns) . ') VALUES (' . $placeholders . ')';
            $inputData = $this->db->prepare($sql);
            $inputData->execute($parameter);
            return true;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function updateData(array $newValue, array $fields, array $record)
    {
        try {
            $newValueMapped = $this->mapFieldsToValues($this->fields, $newValue);
            [$setSql, $setBindings] = $this->buildSetClause(array_keys($newValueMapped), array_values($newValueMapped), 's');
            [$whereSql, $whereBindings] = $this->buildWhereClause($fields, $record, 'w');
            $sql = 'UPDATE ' . $this->escapeIdentifier($this->table)
                . ' SET ' . $setSql
                . ' WHERE ' . $whereSql;
            $updateData = $this->db->prepare($sql);
            $updateData->execute(array_merge($setBindings, $whereBindings));
            return true;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function updateByCondition(array $fieldName, array $newValue, array $fieldCondition, array $recordCondition)
    {
        try {
            [$setSql, $setBindings] = $this->buildSetClause($fieldName, $newValue, 's');
            [$whereSql, $whereBindings] = $this->buildWhereClause($fieldCondition, $recordCondition, 'w');
            $sql = 'UPDATE ' . $this->escapeIdentifier($this->table)
                . ' SET ' . $setSql
                . ' WHERE ' . $whereSql;
            $updateByCondition = $this->db->prepare($sql);
            $updateByCondition->execute(array_merge($setBindings, $whereBindings));
            return true;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function deleteData(array $fields, array $record)
    {
        try {
            [$whereSql, $bindings] = $this->buildWhereClause($fields, $record, 'w');
            $sql = 'DELETE FROM ' . $this->escapeIdentifier($this->table) . ' WHERE ' . $whereSql;
            $deleteData = $this->db->prepare($sql);
            $deleteData->execute($bindings);
            return true;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function sumData($fieldname, array $fields, array $record)
    {
        try {
            [$whereSql, $bindings] = $this->buildWhereClause($fields, $record, 'w');
            $safeField = $this->escapeIdentifier($fieldname);
            $sql = 'SELECT SUM(' . $safeField . ') AS ' . $safeField
                . ' FROM ' . $this->escapeIdentifier($this->table)
                . ' WHERE ' . $whereSql;
            $sumData = $this->db->prepare($sql);
            $sumData->execute($bindings);
            return $sumData->fetch();
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function countData($fieldname, array $fields, array $record)
    {
        try {
            [$whereSql, $bindings] = $this->buildWhereClause($fields, $record, 'w');
            $safeField = $this->escapeIdentifier($fieldname);
            $sql = 'SELECT COUNT(' . $safeField . ') AS ' . $safeField
                . ' FROM ' . $this->escapeIdentifier($this->table)
                . ' WHERE ' . $whereSql;
            $countData = $this->db->prepare($sql);
            $countData->execute($bindings);
            return $countData->fetch();
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function serializeData(array $data)
    {
        $param = [];

        foreach ($data as $key => $value) {
            $param[':' . $key] = $value;
        }

        return $param;
    }

    public function param(array $fields, array $record)
    {
        $param = [];

        foreach ($fields as $key => $value) {
            $safeField = $this->escapeIdentifier($value);
            $valueAtKey = array_key_exists($key, $record) ? $record[$key] : null;
            $param[$key] = $safeField . " = " . $this->db->quote((string) $valueAtKey);
        }

        return $param;
    }

    public function getLastError()
    {
        $lastError = $this->error;

        return $lastError;
    }

    private function escapeIdentifier(string $identifier): string
    {
        if (!preg_match('/^[A-Za-z0-9_]+$/', $identifier)) {
            throw new InvalidArgumentException('Invalid SQL identifier: ' . $identifier);
        }

        return '`' . $identifier . '`';
    }

    private function buildFieldList(array $fields): string
    {
        return implode(',', array_map([$this, 'escapeIdentifier'], $fields));
    }

    private function mapFieldsToValues(array $fields, array $values): array
    {
        $mapped = [];
        foreach ($fields as $index => $field) {
            if (array_key_exists($field, $values)) {
                $mapped[$field] = $values[$field];
            } elseif (array_key_exists($index, $values)) {
                $mapped[$field] = $values[$index];
            }
        }

        return $mapped;
    }

    private function buildWhereClause(array $fields, array $values, string $prefix): array
    {
        $pairs = $this->mapFieldsToValues($fields, $values);
        $clauses = [];
        $bindings = [];
        $i = 0;

        foreach ($pairs as $field => $value) {
            $placeholder = ':' . $prefix . $i++;
            $clauses[] = $this->escapeIdentifier($field) . ' = ' . $placeholder;
            $bindings[$placeholder] = $value;
        }

        return [implode(' AND ', $clauses), $bindings];
    }

    private function buildSetClause(array $fields, array $values, string $prefix): array
    {
        $pairs = $this->mapFieldsToValues($fields, $values);
        $clauses = [];
        $bindings = [];
        $i = 0;

        foreach ($pairs as $field => $value) {
            $placeholder = ':' . $prefix . $i++;
            $clauses[] = $this->escapeIdentifier($field) . ' = ' . $placeholder;
            $bindings[$placeholder] = $value;
        }

        return [implode(', ', $clauses), $bindings];
    }
}
