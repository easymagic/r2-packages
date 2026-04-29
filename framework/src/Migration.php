<?php

namespace R2Packages\Framework;

/**
 * MigrationV2 is a helper class to manage and modify MySQL table fields.
 * It offers a fluent API to add/modify columns, apply default values, enum options, and create tables during migration.
 */
class Migration
{
    // Supported MySQL data types as class constants for convenience/validation.
    const TYPE_INT = 'INT';
    const TYPE_VARCHAR = 'VARCHAR';
    const TYPE_FLOAT = 'FLOAT';
    const TYPE_DOUBLE = 'DOUBLE';
    const TYPE_DATE = 'DATE';
    const TYPE_TIME = 'TIME';
    const TYPE_DATETIME = 'DATETIME';
    const TYPE_TIMESTAMP = 'TIMESTAMP';
    const TYPE_TEXT = 'TEXT';
    const TYPE_LONGTEXT = 'LONGTEXT';
    const TYPE_ENUM = 'ENUM';
    const TYPE_SET = 'SET';
    const TYPE_BOOLEAN = 'BOOLEAN';
    const TYPE_JSON = 'JSON';
    const TYPE_JSONB = 'JSONB';
    const TYPE_BINARY = 'BINARY';
    const TYPE_VARBINARY = 'VARBINARY';
    const TYPE_TINYINT = 'TINYINT';
    const TYPE_SMALLINT = 'SMALLINT';
    const TYPE_MEDIUMINT = 'MEDIUMINT';
    const TYPE_BIGINT = 'BIGINT';

    /** @var string|null */
    private $table;
    /** @var string|null */
    private $field;
    /** @var string|null */
    private $type;
    /** @var string|int|null */
    private $size;
    /** @var mixed|null */
    private $default;


    private $definition = '';

    /**
     * @var int Default minimum size for numeric types.
     */
    private $minimumSize = 11;

    /**
     * @param string|null $table (optional) The table name for the migration instance.
     */
    public function __construct($table = null)
    {
        $this->table = $table;
    }

    /**
     * Create a new MigrationV2 instance for the specified table.
     * Usage: MigrationV2::table('users')
     * @param string $table
     * @return static
     */
    public static function table($table)
    {
        return new self($table);
    }

    public function definition($definition)
    {
        $this->definition = $definition;
        return $this;
    }

    /**
     * Set the field/column name.
     * @param string $field
     * @return $this
     */
    public function field($field)
    {
        $this->field = $field;
        return $this;
    }

    /**
     * Set the column type. Supported types are as defined by class constants.
     * Adjusts its minimum recommended size for certain types.
     * @param string $type
     * @return $this
     */
    public function type($type)
    {
        switch (strtoupper($type)) {
            case self::TYPE_INT:
                $this->type = self::TYPE_INT;
                $this->minimumSize = 11;
                break;
            case self::TYPE_VARCHAR:
                $this->type = self::TYPE_VARCHAR;
                $this->minimumSize = 255;
                break;
            case self::TYPE_FLOAT:
                $this->type = self::TYPE_FLOAT;
                $this->minimumSize = 10;
                break;
            case self::TYPE_DOUBLE:
                $this->type = self::TYPE_DOUBLE;
                $this->minimumSize = 10;
                break;
            case self::TYPE_DATE:
                $this->type = self::TYPE_DATE;
                $this->minimumSize = 10;
                break;
            case self::TYPE_TIME:
            case self::TYPE_DATETIME:
            case self::TYPE_TIMESTAMP:
                $this->type = strtoupper($type);
                $this->minimumSize = 6;
                break;
            case self::TYPE_TEXT:
            case self::TYPE_LONGTEXT:
                $this->type = strtoupper($type);
                break;
            case self::TYPE_ENUM:
            case self::TYPE_SET:
            case self::TYPE_BOOLEAN:
            case self::TYPE_JSON:
            case self::TYPE_JSONB:
            case self::TYPE_BINARY:
            case self::TYPE_VARBINARY:
            case self::TYPE_TINYINT:
            case self::TYPE_SMALLINT:
            case self::TYPE_MEDIUMINT:
            case self::TYPE_BIGINT:
            case 'DECIMAL':
                $this->type = strtoupper($type);
                break;
            default:
                $this->type = self::TYPE_VARCHAR; // fallback if unknown, for safety
                break;
        }
        return $this;
    }

    /**
     * Set field/column size (e.g., VARCHAR(60)). For DECIMAL uses the "precision,scale" string.
     * @param int|string $size
     * @return $this
     */
    public function size($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Set the DEFAULT value for the column.
     * @param mixed $default
     * @return $this
     */
    public function useDefault($default)
    {
        $this->default = $default;
        return $this;
    }

    /**
     * Return a clone configured to modify the current field.
     * (Not actively used, but enables a way to chain modifications.)
     * @return static
     */
    public function modify()
    {
        $obj = new self($this->table);
        $obj->table($this->table)->field($this->field)->size($this->size);
        return $obj;
    }

    /**
     * For ENUM/SET: specify allowed options, auto-fills default value as first item.
     * @param string[] $options
     * @return $this
     */
    public function options($options)
    {
        $this->size = "'" . implode("','", $options) . "'";
        $this->default = $options[0];
        return $this;
    }

    /**
     * Create a dummy table if it doesn't exist, only with auto-incrementing "id".
     * @param string $table
     */
    public function createTable($table)
    {
        $sql = "CREATE TABLE $table (
            id BIGINT AUTO_INCREMENT PRIMARY KEY
        )";
        dbExecute($sql);
    }

    /**
     * Actually run the migration for the field.
     * Handles type casting, default values, table/field existence, SQL generation and execution.
     * @return static New instance for further chaining.
     */
    public function run()
    {
        // Set default size if not provided (applies to datatypes with sizing)
        if (empty($this->size)) {
            $this->size = $this->minimumSize;
        }

        // Handle the default value according to type
        if (empty($this->default) && !is_numeric($this->default)) {
            $this->default = 'NULL';
        } elseif (in_array($this->type, [
            self::TYPE_INT,
            self::TYPE_FLOAT,
            self::TYPE_DOUBLE,
            self::TYPE_TINYINT,
            self::TYPE_SMALLINT,
            self::TYPE_MEDIUMINT,
            self::TYPE_BIGINT
        ])) {
            $this->default = intval($this->default);
        } elseif (in_array($this->type, [self::TYPE_FLOAT, self::TYPE_DOUBLE])) {
            $this->default = floatval($this->default);
        } elseif ($this->type === self::TYPE_BOOLEAN) {
            $this->default = boolval($this->default);
        } elseif (in_array($this->type, [
            self::TYPE_DATE,
            self::TYPE_TIME,
            self::TYPE_DATETIME,
            self::TYPE_TIMESTAMP
        ])) {
            // Do not quote likely keyword-style default (e.g. CURRENT_TIMESTAMP)
        } else {
            $this->default = "'" . $this->default . "'";
        }

        // Create table if it doesn't exist
        if (!$this->tableExists($this->table)) {
            $this->createTable($this->table);
        }

        // If field exists: MODIFY, else: ADD
        if ($this->fieldExists($this->table, $this->field)) {
            $sql = "ALTER TABLE {$this->table} MODIFY COLUMN `{$this->field}` {$this->definition}";
        } else {
            $sql = "ALTER TABLE {$this->table} ADD COLUMN `{$this->field}` {$this->definition}";
        }
        echo $sql . '<br>';
        dbExecute($sql);
        return new self($this->table);
    }

    /**
     * Check if the requested table exists in the database.
     * @param string $table
     * @return bool
     */
    public function tableExists($table)
    {
        $sql = "SHOW TABLES LIKE '$table'";
        $result = dbFetchOne($sql);
        return $result ? true : false;
    }

    /**
     * Check if the field/column exists in the specified table.
     * @param string $table
     * @param string $field
     * @return bool
     */
    public function fieldExists($table, $field)
    {
        $sql = "SHOW COLUMNS FROM $table LIKE '$field'";
        $result = dbFetchOne($sql);
        return $result ? true : false;
    }
}