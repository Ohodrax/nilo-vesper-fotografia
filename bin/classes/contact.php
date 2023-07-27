<?php

    class contact extends mySqlConnection {
        
        function __get($atributo) {
            return $this->$atributo;
        }
        function __set($atributo, $valor) {
            return $this->$atributo = $valor;
        }

        public function getContact() {
            $mysql = new mySqlConnection();
            $pdoConnect = $mysql->newConnection();

            $sqlQuery = "select * from tb_contato where cpf = :cpf or whatsapp = :whatsapp or email = :email;";
            $execQuery=$pdoConnect->prepare($sqlQuery);
            $execQuery->bindParam(":cpf", $this->cpf);
            $execQuery->bindParam(":whatsapp", $this->whatsapp);
            $execQuery->bindParam(":email", $this->email);
            $execQuery->execute();
            $ret_execQuery = $execQuery->fetcHALL(PDO::FETCH_ASSOC);

            return $ret_execQuery;
        }

        public function saveNewContact() {
            $mysql = new mySqlConnection();
            $pdoConnect = $mysql->newConnection();

            $sqlQuery = "insert into tb_contato (nome_completo, cpf, email, whatsapp, mensagem, valor_desejado, parcelas, carencia, checkbox_consulta, checkbox_infos) values (:nome_completo, :cpf, :email, :whatsapp, :mensagem, :valor_desejado, :parcelas, :carencia, :checkbox_consulta, :checkbox_infos);";
            $execQuery = $pdoConnect->prepare($sqlQuery);
            $execQuery->bindParam("nome_completo", $this->nome_completo, PDO::PARAM_STR);
            $execQuery->bindParam("cpf", $this->cpf, PDO::PARAM_STR);
            $execQuery->bindParam("email", $this->email, PDO::PARAM_STR);
            $execQuery->bindParam("whatsapp", $this->whatsapp, PDO::PARAM_STR);
            $execQuery->bindParam("mensagem", $this->mensagem, PDO::PARAM_STR);
            $execQuery->bindParam("valor_desejado", $this->valor_desejado, PDO::PARAM_STR);
            $execQuery->bindParam("parcelas", $this->parcelas, PDO::PARAM_STR);
            $execQuery->bindParam("carencia", $this->carencia, PDO::PARAM_STR);
            $execQuery->bindParam("checkbox_consulta", $this->checkbox_consulta, PDO::PARAM_STR);
            $execQuery->bindParam("checkbox_infos", $this->checkbox_infos, PDO::PARAM_STR);
            $execQuery->execute();
            $ret_execQuery=boolval($execQuery->rowCount());

            return $ret_execQuery;
        }
    }

?>