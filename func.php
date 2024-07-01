<?php

//UTILIZEI POSTMAN PARA TESTAR O CODIGO
function atualizarEstoque($jsonData) { // Função para atualizar o estoque no banco de dados
    $dsn = 'mysql:host=localhost;dbname=geovendas;charset=utf8'; // DSN de conexão com o banco de dados
    $username = 'root'; // Usuário do banco de dados
    $password = '1234567'; // Senha do banco de dados

    try {
        $pdo = new PDO($dsn, $username, $password); // Conecta ao banco de dados

        $sqlCheck = "SELECT id FROM estoque WHERE produto = :produto 
                     AND cor = :cor AND tamanho = :tamanho  AND deposito = :deposito
                     AND data_disponibilidade = :data_disponibilidade";
        //aqui em cima é a query que verifica se o registro já existe no banco de dados
        $stmtCheck = $pdo->prepare($sqlCheck);// prepara a query para ser executada

        $sqlInsert = "INSERT INTO estoque (produto, cor, tamanho, deposito, data_disponibilidade, quantidade) 
                      VALUES (:produto, :cor, :tamanho, :deposito, :data_disponibilidade, :quantidade)"; 
                        //aqui em cima é a query que insere o registro no banco de dados
        $stmtInsert = $pdo->prepare($sqlInsert); // prepara a query para ser executada


        //CONSIDERAÇÕES:
        //EU ENTENDI QUE OS CAMPOS `PRODUTO`, `COR`, `TAMANHO`, `DEPOSITO` E `DATA_DISPONIBILIDADE' COMO UNICAS
        //POR ISSO EU FIZ A VERIFICAÇÃO SE O REGISTRO JÁ EXISTE NO BANCO DE DADOS
        //SE O REGISTRO JÁ EXISTIR, EU FAÇO UM UPDATE NO CAMPO 'QUANTIDADE'

        $sqlUpdate = "UPDATE estoque SET quantidade = :quantidade, data_disponibilidade = :data_disponibilidade, deposito = :deposito
        WHERE produto = :produto AND cor = :cor AND tamanho = :tamanho AND data_disponibilidade = :data_disponibilidade";
        //aqui em cima é a query que atualiza o registro no banco de dados
        $stmtUpdate = $pdo->prepare($sqlUpdate); // prepara a query para ser executada

        foreach ($jsonData as $produto) {
            // Verifica se o registro já existe
            $stmtCheck->execute([
                ':produto' => $produto['produto'],
                ':cor' => $produto['cor'],
                ':tamanho' => $produto['tamanho'],
                ':deposito' => $produto['deposito'],
                ':data_disponibilidade' => $produto['data_disponibilidade']
            ]); // comparando os campos do json com os campos do banco de dados
            // Se o registro existir, faz o update, senão, faz o insert

            // Se o registro existir, faz o update:
            if ($stmtCheck->rowCount() > 0) { 

                $stmtUpdate->execute([
                    ':produto' => $produto['produto'],
                    ':cor' => $produto['cor'],
                    ':tamanho' => $produto['tamanho'],
                    ':deposito' => $produto['deposito'],
                    ':data_disponibilidade' => $produto['data_disponibilidade'],
                    ':quantidade' => $produto['quantidade']
                ]);
            } else {

                // Seo registro não existe, faz o insert
                $stmtInsert->execute([
                    ':produto' => $produto['produto'],
                    ':cor' => $produto['cor'],
                    ':tamanho' => $produto['tamanho'],
                    ':deposito' => $produto['deposito'],
                    ':data_disponibilidade' => $produto['data_disponibilidade'],
                    ':quantidade' => $produto['quantidade']
                ]);
            }
        }

        echo "Estoque atualizado com sucesso!";
    } catch (PDOException $e) {
        echo "Erro ao atualizar o estoque: " . $e->getMessage();
    }
}

//Aqui comeca o codigo que vai chamar a função atualizarEstoque:

$jsonFilePath = 'data.json'; // Caminho do arquivo JSON

if (file_exists($jsonFilePath)) { //Aqui vai verificar se o arquivo existe
    
    $jsonContent = file_get_contents($jsonFilePath); // Lê o conteúdo do arquivo JSON

    $produtos = json_decode($jsonContent, true); // Decodifica o JSON para um array associativo

    atualizarEstoque($produtos); // Chama a função para atualizar o estoque no banco de dados
} else {
    echo "Arquivo JSON não encontrado."; // Se o arquivo não existir, exibe uma mensagem de erro
}
