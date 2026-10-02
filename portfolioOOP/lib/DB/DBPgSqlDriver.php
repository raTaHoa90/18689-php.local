<?php

namespace lib\DB;

final class DBPgSqlDriver extends DataBase {

    const VALUE_NO_STRING = [
        'NULL', 'NOT NULL', 'TRUE', 'NOT TRUE', 'FALSE', 'NOT FALSE',
        'CURRENT_DATE', 'CURRENT_TIME', 'CURRENT_TIMESTAMP',
        'UNKNOWN', 'NOT UNKNOWN', 'LOCALTIME', 'LOCALTIMESTAMP',
        'DEFAULT'
    ];

    private $_connect;
    private $_result = null;

    public $returnFieldName = 'id';
    private $_idResults = [];

    function getReturnIDS(): array {
        return $this->_idResults;
    }

    function connect(string $hostname, string $database, string $username, ?string $password = null, int $port = 0) {
        if($port == 0) $port = 5432;

        $this->_connect = @pg_connect("host=$hostname port=$port dbname=$database user=$username".($password !== null ? " password=$password" : ''));
        
        if(!$this->_connect){
            $this->debugAddError();
            throw new \Exception('<b>Приносим наши извинения!</b> <br/>В настоящее время на сайте ведутся технические работы!<br/>');
        }
    }

    function disconnect() {
        @pg_close($this->_connect);
    }

    function query(string $sql, ?array $params): bool {
        if(static::$debug){
            $this->debugLog($sql, 'QUERY');
            if($params !== null)
                $this->debugLog('params = ['.implode(',', $params).']', 'QUERY');
            $timer = microtime(true);
        }

        if($params === null)
            $this->_result = @pg_query($this->_connect, $sql);
        else {
            $i = 1;
            $sql = preg_replace_callback("/(\\$\?)/", function($matches)use(&$i){
                return '$'.($i++);
            }, $sql);
            $this->_result = @pg_query_params($this->_connect, $sql, $params);
        }

        if(static::$debug)
            $this->debugLog('worked time: '.sprintf('%0.8f', microtime(true) - $timer), 'QUERY');
        return !!$this->_result;
    } 

    function queryClose(string $sql, ?array $params): int {
        $result = $this->query($sql, $params);
        return $result ? $this->affectRows() : 0;
    }

    function affectRows(): int {
        if(!$this->_result) return -1;
        return pg_affected_rows($this->_result);
    }

    function rowsCount(): int {
        if(!$this->_result) return -1;
        return pg_num_rows($this->_result);
    }

    function countFields(): int {
        if(!$this->_result) return 0;
        return pg_num_fields($this->_result);
    }

    function getFields(): array { 
        if(!$this->_result) return [];

        $result = [];
        $count = pg_num_fields($this->_result);
        if($count > 0)
            for($i = 0; $i < $count; $i++)
                $result[] = [
                    'name' => pg_field_name($this->_result, $i),
                    'num' => $i,
                    'size' => pg_field_size($this->_result, $i),
                    'type' => pg_field_type($this->_result, $i),
                    'type_oid' => pg_field_type_oid($this->_result, $i)
                ];
        return $result;
    }

    function getNameFields(): array {
        if(!$this->_result) return [];

        $result = [];
        $count = pg_num_fields($this->_result);
        if($count > 0)
            for($i = 0; $i < $count; $i++)
                $result[] = pg_field_name($this->_result, $i);
        return $result;
    }

    function resultAll(bool $typeResult = self::TYPE_OBJECT, ?string $className = null, array $args = []): array {
        if(!$this->_result) return [];

        if($typeResult == static::TYPE_ASSOC){
            $rows = pg_fetch_all($this->_result, PGSQL_ASSOC);
            pg_free_result($this->_result);
            return $rows;
        }

        $rows = [];
        while($row = pg_fetch_object($this->_result, null, $className ?? 'stdClass', $args))
            $rows[] = $row;
        pg_free_result($this->_result);
        return $rows;
    }

    function esc_db(mixed $value, string $temp): string {
        if(!in_array($temp, static::VALUE_NO_STRING))
            $value = "'" . pg_escape_string($this->_connect, $value) . "'";
        return $value;
    }

    function limit(int $offset, int $limit): string {
        return " LIMIT ".$limit.($offset > 0 ? " OFFSET $offset" : '');
    }

    function insertGetId(string $sql, ?array $params): int {
        $this->query($sql.' RETURNING '.$this->returnFieldName, $params);
        $result = @pg_fetch_all($this->_result);

        $this->_idResults = $result;
        if(count($result) == 1)
            return $result[0][$this->returnFieldName];
        elseif(count($result) > 1)
            return -1;
        return 0;
    }

    function debugAddError(){
        $this->debugLog( $this->_result
            ? pg_result_error($this->_result)
            : pg_last_error($this->_connect)
        , 'ERROR');
    }

}