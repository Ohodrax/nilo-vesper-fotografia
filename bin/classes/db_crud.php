<?php

    class db_crud extends mySqlConnection {
        public $data, $filters, $id, $orderby, $proc, $table;
        
        function __get($atributo) {
            return $this->$atributo;
        }
        function __set($atributo, $valor) {
            return $this->$atributo = $valor;
        }

        public function default_insert_data() {
            // prepara a conexão com o db
            $mysql      = new mySqlConnection();
            $pdoConnect = $mysql->newConnection();

            try {

                // verifica se os parametros foram declarados corretamente
                if (!isset($this->table) || !isset($this->data)) {
                    return false;
                }

                // coleta os valores passados para a classe
                $table      = $this->table;
                $array_data = $this->data;
            
                // define os arrays para separar as colunas e valores para inserir no banco de dados
                $array_cols           = array();
                $array_rows_prevalues = array();
                $array_rows_values    = array();
                $insert_number        = count($array_data);
            
                // executa a separação da chave e valor da variável data
                foreach ($array_data as $key => $value) {
                    array_push($array_cols, $key);
                    array_push($array_rows_prevalues, ":".$key);
                    array_push($array_rows_values, $value);
                }
            
                // executa o processo do PDO
                $sqlQuery = "insert into ".$table." (".implode(", ", $array_cols).") values (".implode(", ", $array_rows_prevalues).");";
                $execQuery = $pdoConnect->prepare($sqlQuery);

                for ($i=0; $i < $insert_number; $i++) {
                    // define o tipo de parametro que o mysql vai inserir de acordo com o valor da string
                    $param_type = $array_rows_values[$i] == "" ? PDO::PARAM_NULL : PDO::PARAM_STR;

                    // faz o bind de cada valor a ser inserido no db
                    $execQuery->bindParam($array_rows_prevalues[$i], $array_rows_values[$i], $param_type);
                }

                $execQuery->execute();
                $ret_execQuery=boolval($execQuery->rowCount());

                return $ret_execQuery;

            } catch (Exception $e) {
                return "Error when tried to execute function: ". $e->getMessage();
            }            
        }

        public function default_update_data(){
            // prepara a conexão com o db
            $mysql      = new mySqlConnection();
            $pdoConnect = $mysql->newConnection();

            try {
                
                // verifica se os parametros foram declarados corretamente
                if (!isset($this->id) || !isset($this->table) || !isset($this->data)) {
                    return false;
                }

                // coleta os valores passados para a classe
                $id         = $this->id;
                $table      = $this->table;
                $array_data = $this->data;
            
                // define os arrays para separar as colunas e valores para inserir no banco de dados
                $array_cols_value     = array();
                $array_rows_prevalues = array();
                $array_rows_values    = array();
                $insert_number        = count($array_data);

                // identifica o nome da coluna id de acordo com o nome da tabela que foi referida
                $id_col = str_replace("tb", "id", $table);
            
                // executa a separação da chave e valor da variável data
                foreach ($array_data as $key => $value) {
                    // array para referenciar o bind dos parametros do pdo
                    array_push($array_rows_prevalues, ":".$key);
                    // array para construir a query do update "... set coluna = valor..."
                    array_push($array_cols_value, $key." = :".$key);
                    // array para construção dos valores, que será inserido pelo bind do pdo
                    array_push($array_rows_values, $value);
                }

                // executa o processo do PDO
                $sqlQuery  = "update ".$table." set ".implode(", ", $array_cols_value)." where ".$id_col." = ".$id.";";
                $execQuery = $pdoConnect->prepare($sqlQuery);

                for ($i=0; $i < $insert_number; $i++) {
                    // define o tipo de parametro que o mysql vai inserir de acordo com o valor da string
                    $param_type = $array_rows_values[$i] == "" ? PDO::PARAM_NULL : PDO::PARAM_STR;

                    // faz o bind de cada valor a ser inserido no db
                    $execQuery->bindParam($array_rows_prevalues[$i], $array_rows_values[$i], $param_type);
                }

                $execQuery->execute();
                $ret_execQuery=boolval($execQuery->rowCount());

                return $ret_execQuery;

            } catch (Exception $e) {
                return "Error when tried to execute function: ". $e->getMessage();
            }
        }

        public function default_select_data() {
            // prepara a conexão com o db
            $mysql      = new mySqlConnection();
            $pdoConnect = $mysql->newConnection();

            try {
                
                // verifica se os parametros foram declarados corretamente
                if (!isset($this->table)) {
                    return false;
                }

                $table   = $this->table;
                $filters = "";
                $orderby = "";

                // filtros para serem aplicados na query do select
                if (isset($this->filters)) {
                    $array_filters = $this->filters;

                    if (count($array_filters) == 1) {
                        $filters = " where ".$array_filters[0];
                    }

                    if (count($array_filters) > 1) {
                        $filters = " where ".implode(" and ", $array_filters);
                    }
                }

                // ordernar os valores retornados do select
                if (isset($this->orderby)) {
                    $orderby = " order by ".$this->orderby;
                }

                $sqlQuery  = "select * from ".$table.$filters.$orderby.";";
                $execQuery = $pdoConnect->prepare($sqlQuery);
                $execQuery->execute();
                $ret_execQuery=$execQuery->fetchALL(PDO::FETCH_ASSOC);

                return $ret_execQuery;

            } catch (Exception $e) {
                return "Error when tried to execute function: ". $e->getMessage();
            }
        }

        public function default_delete_data() {
            // prepara a conexão com o db
            $mysql      = new mySqlConnection();
            $pdoConnect = $mysql->newConnection();

            try {
                
                // verifica se os parametros foram declarados corretamente
                if (!isset($this->table) || !isset($this->id)) {
                    return false;
                }

                $table  = $this->table;
                $id     = $this->id;

                $id_col = str_replace("tb", "id", $table);

                $sqlQuery  = "delete from ".$table." where ".$id_col." = ".$id.";";
                $execQuery = $pdoConnect->prepare($sqlQuery);
                $execQuery->execute();
                $ret_execQuery=boolval($execQuery->rowCount());

                return $ret_execQuery;

            } catch (Exception $e) {
                return "Error when tried to execute function: ". $e->getMessage();
            }
        }

        public function default_call_proc() {
            // prepara a conexão com o db
            $mysql      = new mySqlConnection();
            $pdoConnect = $mysql->newConnection();

            try {

                // verifica se os parametros foram declarados corretamente
                if (!isset($this->proc) || !isset($this->data)) {
                    return false;
                }

                // coleta os valores passados para a classe
                $proc       = $this->proc;
                $array_data = $this->data;
            
                // define os arrays para separar as colunas e valores para inserir no banco de dados
                $array_cols           = array();
                $array_rows_prevalues = array();
                $array_rows_values    = array();
                $insert_number        = count($array_data);
            
                // executa a separação da chave e valor da variável data
                foreach ($array_data as $key => $value) {
                    array_push($array_cols, $key);
                    array_push($array_rows_prevalues, ":".$key);
                    array_push($array_rows_values, $value);
                }
            
                // executa o processo do PDO
                $sqlQuery = "call ".$proc." (".implode(", ", $array_rows_prevalues).");";
                $execQuery = $pdoConnect->prepare($sqlQuery);

                for ($i=0; $i < $insert_number; $i++) {
                    // define o tipo de parametro que o mysql vai inserir de acordo com o valor da string
                    $param_type = $array_rows_values[$i] == "" ? PDO::PARAM_NULL : PDO::PARAM_STR;

                    // faz o bind de cada valor a ser inserido no db
                    $execQuery->bindParam($array_rows_prevalues[$i], $array_rows_values[$i], $param_type);
                }

                $execQuery->execute();
                $ret_execQuery=boolval($execQuery->rowCount());

                return $ret_execQuery;

            } catch (Exception $e) {
                return "Error when tried to execute function: ". $e->getMessage();
            }            
        }
    }

?>
