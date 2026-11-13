<?php
/** Values are bound; identifiers and ordering use restricted SQL grammar. */
class db_crud extends mySqlConnection {
    public $data, $filters, $id, $orderby, $proc, $table;
    private ?PDO $connection;
    private const TABLES = ['tb_usuario', 'tb_perfil', 'tb_recovery', 'tb_image', 'tb_parametro', 'tb_lead', 'v_select_user_profile'];
    public function __construct(?PDO $connection = null) { $this->connection = $connection; }
    private function identifier($value): string {
        if (!is_string($value) || !preg_match('/\A[a-z][a-z0-9_]*\z/D', $value)) throw new InvalidArgumentException('Identificador inválido.');
        return '`' . $value . '`';
    }
    private function tableName(bool $write = false): string {
        if (!in_array($this->table, self::TABLES, true) || ($write && $this->table === 'v_select_user_profile')) throw new InvalidArgumentException('Tabela inválida.');
        return $this->identifier($this->table);
    }
    private function where(array $filters, array &$values, string $join = ' AND '): string {
        $parts = [];
        foreach ($filters as $filter) {
            if (!is_array($filter)) throw new InvalidArgumentException('Filtro SQL bruto não permitido.');
            if (isset($filter['any'])) {
                if (!$filter['any']) throw new InvalidArgumentException('Filtro vazio.');
                $parts[] = '(' . $this->where($filter['any'], $values, ' OR ') . ')';
                continue;
            }
            if (count($filter) !== 3 || !in_array($filter[1], ['=', '!=', '>', '>=', '<', '<='], true) || !is_scalar($filter[2])) throw new InvalidArgumentException('Filtro inválido.');
            $parts[] = $this->identifier($filter[0]) . ' ' . $filter[1] . ' ?';
            $values[] = $filter[2];
        }
        return implode($join, $parts);
    }
    private function execute(string $sql, array $values): PDOStatement {
        foreach ($values as $value) if (!is_scalar($value) && $value !== null) throw new InvalidArgumentException('Valor inválido.');
        $pdo = $this->connection ?? $this->newConnection();
        try {
            $statement = $pdo->prepare($sql);
            $statement->execute($values);
            return $statement;
        } catch (PDOException $error) {
            error_log('database_operation_failed');
            throw new RuntimeException('Não foi possível concluir a operação.');
        }
    }
    private function validId(): int {
        $id = filter_var($this->id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($id === false) throw new InvalidArgumentException('Identificador inválido.');
        return $id;
    }
    public function default_select_data(): array {
        $sql = 'SELECT * FROM ' . $this->tableName();
        $values = [];
        if ($this->filters) $sql .= ' WHERE ' . $this->where($this->filters, $values);
        if ($this->orderby) {
            if (!preg_match('/\A([a-z][a-z0-9_]*) (asc|desc)(?: limit ([0-9]{1,6}), ([0-9]{1,3}))?\z/Di', $this->orderby, $m)) throw new InvalidArgumentException('Ordenação inválida.');
            $sql .= ' ORDER BY ' . $this->identifier($m[1]) . ' ' . strtoupper($m[2]);
            if (isset($m[3])) $sql .= ' LIMIT ' . (int) $m[3] . ', ' . min(100, (int) $m[4]);
        }
        return $this->execute($sql, $values)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function default_insert_data(): bool {
        $table = $this->tableName(true);
        if (!is_array($this->data) || !$this->data) throw new InvalidArgumentException('Dados inválidos.');
        $columns = array_map(fn($key) => $this->identifier($key), array_keys($this->data));
        $this->execute('INSERT INTO ' . $table . ' (' . implode(',', $columns) . ') VALUES (' . implode(',', array_fill(0, count($columns), '?')) . ')', array_values($this->data));
        return true;
    }
    public function default_update_data(): bool {
        $table = $this->tableName(true);
        if (!is_array($this->data) || !$this->data) throw new InvalidArgumentException('Dados inválidos.');
        $columns = array_map(fn($key) => $this->identifier($key) . ' = ?', array_keys($this->data));
        $this->execute('UPDATE ' . $table . ' SET ' . implode(',', $columns) . ' WHERE ' . $this->identifier('id_' . substr($this->table, 3)) . ' = ?', [...array_values($this->data), $this->validId()]);
        return true;
    }
    public function default_delete_data(): bool {
        $table = $this->tableName(true);
        return $this->execute('DELETE FROM ' . $table . ' WHERE ' . $this->identifier('id_' . substr($this->table, 3)) . ' = ?', [$this->validId()])->rowCount() > 0;
    }
}
