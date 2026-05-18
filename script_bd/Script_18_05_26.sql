-- =====================================================
-- LIMPEZA (opcional)
-- =====================================================

DROP DATABASE IF EXISTS empreiteira;

-- =====================================================
-- CRIAÇÃO DO BANCO
-- =====================================================

CREATE DATABASE empreiteira;
USE empreiteira;

-- =====================================================
-- TABELA USUARIOS
-- =====================================================

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(120) UNIQUE,
    senha VARCHAR(255)
);

INSERT INTO usuarios (nome, email, senha)
VALUES ('adm', 'emailteste@gmail.com', '123');

insert into usuarios (nome, email, senha)
values ('Juliana', 'jujusantista23@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2');

insert into usuarios (nome, email, senha)
values ('Douglas', 'emaildodouglas@gmail.com', '$2y$10$Cvl.oxQKkCy9N/GHUByXuOle40RaHZSpVj9g.tTImDMeBbRiNjZhm');

insert into usuarios (nome, email, senha)
values ('Matheus', 'emaildomatheus@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2');

insert into usuarios (nome, email, senha)
values ('Camila', 'emaildacamila@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2');

insert into usuarios (nome, email, senha)
values ('Francielly', 'emaildafrancielly@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2');

insert into usuarios (nome, email, senha)
values ('Danilo', 'emaildodanilo@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2');

insert into usuarios (nome, email, senha)
values ('Alexandre', 'emaildoalexandre@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2');




-- =====================================================
-- SENHA CRIPTOGRAFADA PARA USAR NO PROJETO. PARA O USUÁRIO SERÁ: 1234
-- =====================================================
UPDATE usuarios 
set nome = 'Administrador'
where nome ='adm';


UPDATE usuarios
SET senha = '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'
WHERE email = 'emailteste@gmail.com';

UPDATE usuarios
SET senha = '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'
WHERE email = 'jujusantista23@gmail.com';

UPDATE usuarios
SET senha = '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'
WHERE email = 'emaildodouglas@gmail.com';

UPDATE usuarios
SET email = 'emaildodanilo@gmail.com'
WHERE email = 'emaildadanilo@gmail.com';

-- =====================================================
-- TABELA FUNCIONARIOS
-- =====================================================

CREATE TABLE funcionarios (
    idFuncionario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    dataNascimento DATE NOT NULL,
    sexo ENUM('Masculino', 'Feminino', 'Outro'),
    naturalidade VARCHAR(100),
    estadoNascimento VARCHAR(2),
    tipoLogradouro VARCHAR(15) NOT NULL,
    nomeLogradouro VARCHAR(100) NOT NULL,
    numero VARCHAR(6) NOT NULL,
    complemento VARCHAR(30),
    cidade VARCHAR(100),
    cep VARCHAR(10),
 	estado varchar (2),
    email VARCHAR(150) NOT NULL,
    cargoFuncao VARCHAR(100),
    tipoContrato ENUM('CLT', 'CONTRATO TEMPORARIO','PESSOA JURÍRIDICA', 'TERCERIZADA') NOT NULL,
    status ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
    observacoes TEXT
);

-- =====================================================
-- CONTATO FUNCIONARIO
-- =====================================================

CREATE TABLE contatoFuncionario (
    idContato INT AUTO_INCREMENT PRIMARY KEY,
    idFuncionario INT NOT NULL,

    telefone VARCHAR(20) NOT NULL,

    tipo ENUM('Celular', 'Residencial', 'Comercial', 'WhatsApp'),

    CONSTRAINT fk_contatoFuncionario
        FOREIGN KEY (idFuncionario)
        REFERENCES funcionarios(idFuncionario)
        ON DELETE CASCADE
);

-- =====================================================
-- CLIENTE
-- =====================================================

CREATE TABLE cliente (
    idCliente INT AUTO_INCREMENT PRIMARY KEY,

    nomeCliente VARCHAR(45) NOT NULL,

    cpf CHAR(11) UNIQUE,
    cnpj CHAR(14) UNIQUE
);

-- =====================================================
-- OBRA
-- =====================================================

CREATE TABLE obra (
    idObra INT AUTO_INCREMENT PRIMARY KEY,

    dataInicio DATETIME NOT NULL,
    dataFim DATETIME,

    status ENUM('Em andamento', 'Concluída', 'Cancelada') NOT NULL,

    estado CHAR(2) NOT NULL,
    cidade VARCHAR(45) NOT NULL,
    cep CHAR(8) NOT NULL,

    logradouro VARCHAR(80) NOT NULL,
    endereco VARCHAR(50) NOT NULL,
    numero CHAR(4) NOT NULL,

    complemento VARCHAR(45),

    contrato VARCHAR(45)
);

-- =====================================================
-- AUTOMOVEL
-- =====================================================

CREATE TABLE automovel (
    idAutomovel INT AUTO_INCREMENT PRIMARY KEY,

    idFuncionario INT NOT NULL,

    nomeAutomovel VARCHAR(45) NOT NULL,
    marca VARCHAR(20) NOT NULL,

    ano CHAR(4) NOT NULL,

    placa CHAR(7) NOT NULL UNIQUE,
    renavam CHAR(11) NOT NULL UNIQUE,

    CONSTRAINT fk_automovelfuncionario
        FOREIGN KEY (idFuncionario)
        REFERENCES funcionarios(idFuncionario)
);

-- =====================================================
-- OBRA FUNCIONARIO
-- =====================================================

CREATE TABLE obraFuncionario (
    idObraFuncionario INT AUTO_INCREMENT PRIMARY KEY,

    idFuncionario INT NOT NULL,
    idObra INT NOT NULL,

    CONSTRAINT fk_funcionario_funcionario
        FOREIGN KEY (idFuncionario)
        REFERENCES funcionarios(idFuncionario),

    CONSTRAINT fk_funcionario_obra
        FOREIGN KEY (idObra)
        REFERENCES obra(idObra),

    CONSTRAINT uq_obra_funcionario
        UNIQUE (idFuncionario, idObra)
);

-- =====================================================
-- FINANCEIRO FUNCIONARIO
-- =====================================================

CREATE TABLE financeiroFuncionario (
    idFinanceiroFuncionario INT PRIMARY KEY AUTO_INCREMENT,

    idFuncionario INT NOT NULL,

    salario DECIMAL(10,2) NOT NULL,
    ferias DECIMAL(10,2) NOT NULL,
    inss DECIMAL(10,2) NOT NULL,
    decimoTerceiro DECIMAL(10,2),

    CONSTRAINT fk_financeirofuncionario
        FOREIGN KEY (idFuncionario)
        REFERENCES funcionarios(idFuncionario)
);

-- =====================================================
-- FINANCEIRO OBRA
-- =====================================================

CREATE TABLE financeiroObra (
    idFinanceiroObra INT PRIMARY KEY AUTO_INCREMENT,

    idObra INT NOT NULL,

    descricao VARCHAR(100) NOT NULL,
    categoria VARCHAR(50),

    valor DECIMAL(10,2) NOT NULL,

    dataGasto DATE NOT NULL,

    formaPagamento VARCHAR(30),

    observacao VARCHAR(200),

    CONSTRAINT fk_FinanceiroObra
        FOREIGN KEY (idObra)
        REFERENCES obra(idObra)
);

-- =====================================================
-- OBRA CLIENTE
-- =====================================================

CREATE TABLE obraCliente (
    idObraCliente INT PRIMARY KEY AUTO_INCREMENT,

    idObra INT NOT NULL,
    idCliente INT NOT NULL,

    CONSTRAINT fk_Obra
        FOREIGN KEY (idObra)
        REFERENCES obra(idObra),

    CONSTRAINT fk_Cliente
        FOREIGN KEY (idCliente)
        REFERENCES cliente(idCliente)
);

-- =====================================================
-- CONTATO CLIENTE
-- =====================================================

CREATE TABLE contatoCliente (
    idContatoCliente INT PRIMARY KEY AUTO_INCREMENT,

    idCliente INT NOT NULL,

    celular CHAR(11),

    CONSTRAINT fk_ClienteContato
        FOREIGN KEY (idCliente)
        REFERENCES cliente(idCliente)
);

-- =====================================================
-- FINANCEIRO AUTOMOVEL
-- =====================================================

CREATE TABLE financeiroAutomovel (
    idFinanceiroAutomovel INT PRIMARY KEY AUTO_INCREMENT,

    idAutomovel INT NOT NULL,

    combustivel DECIMAL(10,2),
    manutencao DECIMAL(10,2),
    ipva DECIMAL(10,2),

    CONSTRAINT fk_FinanceiroAutomovel
        FOREIGN KEY (idAutomovel)
        REFERENCES automovel(idAutomovel)
);

-- =====================================================
-- AUTOMOVEL FUNCIONARIO
-- =====================================================

CREATE TABLE automovelFuncionario (
    idAutomovelFuncionario INT PRIMARY KEY AUTO_INCREMENT,

    idAutomovel INT NOT NULL,
    idFuncionario INT NOT NULL,

    dataRetirada DATETIME NOT NULL,
    dataDevolucao DATETIME,

    CONSTRAINT fk_automovel_vinculo 
    FOREIGN KEY (idAutomovel) 
    REFERENCES automovel(idAutomovel),

	 CONSTRAINT fk_funcionario_vinculo 
    FOREIGN KEY (idFuncionario) 
    REFERENCES funcionarios(idFuncionario)
);

-- =====================================================
-- USUARIO EXTRA
-- =====================================================

CREATE TABLE usuario (
    idUsuario INT PRIMARY KEY AUTO_INCREMENT,

    login VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(250) NOT NULL
);

-- =====================================================
-- TESTE
-- =====================================================

SELECT * FROM usuarios;
select * from cliente;
select * from usuarios;