<?php
try {
    // Conexão com o banco de dados
    $pdo = new PDO('mysql:host=localhost;dbname=geovendas', 'root', '1234567');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Query para criar a tabela
    $sql = "
    CREATE TABLE estoque (
        id INT UNSIGNED auto_increment NOT NULL,
        produto VARCHAR(100) NOT NULL,
        cor VARCHAR(100) NOT NULL,
        tamanho VARCHAR(100) NOT NULL,
        deposito VARCHAR(100) NOT NULL,
        data_disponibilidade DATE NOT NULL,
        quantidade INT UNSIGNED NOT NULL,
        CONSTRAINT estoque_pk PRIMARY KEY (id),
        CONSTRAINT estoque_un UNIQUE KEY (produto, cor, tamanho, deposito, data_disponibilidade)
    )
    ENGINE=InnoDB
    DEFAULT CHARSET=utf8mb3
    COLLATE=utf8mb3_general_ci;
    ";

    //Executar a query
    $pdo->exec($sql);
    echo "Tabela criada com sucesso!";
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

