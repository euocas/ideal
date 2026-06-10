-- =====================================================
-- DESLIGA AS TRAVAS DE SEGURANÇA PARA GARANTIR A LIMPEZA
-- =====================================================
SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================
-- LIMPEZA DO BANCO
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
CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(120) UNIQUE,
    senha VARCHAR(255)
);

-- =====================================================
-- TABELA FUNCIONARIOS
-- =====================================================
CREATE TABLE funcionario (
    idFuncionario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    dataNascimento DATE NOT NULL,
    sexo ENUM('Masculino', 'Feminino', 'Outro'),
    naturalidade VARCHAR(100),
    estadoNascimento VARCHAR(10),
    cpf CHAR(11) UNIQUE NOT NULL,
    tipoLogradouro VARCHAR(15) NOT NULL,
    nomeLogradouro VARCHAR(100) NOT NULL,
    numero VARCHAR(6) NOT NULL,
    complemento VARCHAR(30),
    cidade VARCHAR(100),
    cep CHAR(8),
    estado CHAR (2),
    email VARCHAR(150) NOT NULL,
    cargoFuncao VARCHAR(100),
    tipoContrato ENUM('CLT', 'CONTRATO TEMPORARIO','PESSOA JURÍDICA', 'TERCEIRIZADA') NOT NULL,
    dataAdmissao DATE,
    dataDesligamento DATE,
    feriasProgramadas DATE,
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
    whatsapp VARCHAR(20),

    CONSTRAINT fk_contatoFuncionario
        FOREIGN KEY (idFuncionario)
        REFERENCES funcionario(idFuncionario)
        ON DELETE CASCADE
);

-- =====================================================
-- CLIENTE (CORRIGIDA - SEM A COLUNA TELEFONE AQUI)
-- =====================================================
CREATE TABLE cliente (
    idCliente INT AUTO_INCREMENT PRIMARY KEY,
    nomeCliente VARCHAR(45) NOT NULL,
    cpf CHAR(11) UNIQUE,
    cnpj CHAR(14) UNIQUE,
    email VARCHAR(150) NOT NULL,
    tipoCliente ENUM('Pessoa Física', 'Pessoa Jurídica') NOT NULL,
    tipoLogradouro VARCHAR(15),
    nomeLogradouro VARCHAR(100),
    numero VARCHAR(6),
    complemento VARCHAR(30),
    cidade VARCHAR(100) NOT NULL,
    cep CHAR(8) NOT NULL,
    estado CHAR (2) NOT NULL,
    observacoes TEXT,
    CONSTRAINT chk_documento CHECK (cpf IS NOT NULL OR cnpj IS NOT NULL)
);

-- =====================================================
-- CONTATO CLIENTE (ONDE O TELEFONE REALMENTE DEVE FICAR)
-- =====================================================
CREATE TABLE contatoCliente (    
    idContato INT AUTO_INCREMENT PRIMARY KEY,
    idCliente INT NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    whatsapp VARCHAR(20),

    CONSTRAINT fk_contatoCliente
        FOREIGN KEY (idCliente)
        REFERENCES cliente (idCliente)
        ON DELETE CASCADE
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
-- VEICULO
-- =====================================================
CREATE TABLE veiculo (
    idVeiculo INT AUTO_INCREMENT PRIMARY KEY,
    idFuncionario INT,
    renavam VARCHAR(11) NOT NULL UNIQUE,
    placa VARCHAR(10) NOT NULL UNIQUE,
    chassi VARCHAR(30) NOT NULL UNIQUE,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(80) NOT NULL,
    anoFabricacao YEAR NOT NULL,
    anoModelo YEAR NOT NULL,
    cor VARCHAR(30) NOT NULL,
    statusVeiculo ENUM('ATIVO','EM MANUTENCAO', 'INATIVO','VENDIDO') DEFAULT 'ATIVO',
    tipoPosse ENUM('PROPRIO','ALUGADO','EMPRESTADO','TERCEIRIZADO') DEFAULT 'PROPRIO',
    quilometragem INT DEFAULT 0,
    dataUltimaRevisao DATE,
    proximaRevisao DATE,
    propriedadeVeiculo VARCHAR(100),
    responsavelVeiculo VARCHAR(100),
    quantidade INT DEFAULT 1,
    observacoes TEXT,
    dataCadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_automovel_funcionario
        FOREIGN KEY (idFuncionario)
        REFERENCES funcionario(idFuncionario)
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
        REFERENCES funcionario(idFuncionario),
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
        REFERENCES funcionario(idFuncionario)
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
-- FINANCEIRO AUTOMOVEL
-- =====================================================
CREATE TABLE financeiroAutomovel (
    idFinanceiroAutomovel INT PRIMARY KEY AUTO_INCREMENT,
    idVeiculo INT NOT NULL,
    combustivel DECIMAL(10,2),
    manutencao DECIMAL(10,2),
    ipva DECIMAL(10,2),
    CONSTRAINT fk_FinanceiroAutomovel
        FOREIGN KEY (idVeiculo)
        REFERENCES veiculo(idVeiculo)
);

-- =====================================================
-- AUTOMOVEL FUNCIONARIO
-- =====================================================
CREATE TABLE automovelFuncionario (
    idAutomovelFuncionario INT PRIMARY KEY AUTO_INCREMENT,
    idVeiculo INT NOT NULL,
    idFuncionario INT NOT NULL,
    dataRetirada DATETIME NOT NULL,
    dataDevolucao DATETIME,
    CONSTRAINT fk_automovel_vinculo 
        FOREIGN KEY (idVeiculo) 
        REFERENCES veiculo(idVeiculo),
    CONSTRAINT fk_funcionario_vinculo 
        FOREIGN KEY (idFuncionario) 
        REFERENCES funcionario(idFuncionario)
);

-- =====================================================
-- INSERÇAO DE DADOS DE USUÁRIOS
-- =====================================================
INSERT INTO usuario (nome, email, senha) VALUES
('Administrador', 'emailteste@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Juliana', 'jujusantista23@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Douglas', 'emaildodouglas@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Matheus', 'emaildomatheus@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Camila', 'emaildacamila@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Francielly', 'emaildafrancielly@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Danilo', 'emaildodanilo@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Alexandre', 'emaildoalexandre@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2');

-- =====================================================
-- INSERÇAO DE DADOS DE CLIENTES (CORRIGIDA)
-- =====================================================
INSERT INTO cliente (
    nomeCliente, cpf, cnpj, 
    email, tipoCliente, tipoLogradouro, 
    nomeLogradouro, numero, complemento, cidade, 
    cep, estado, observacoes
) VALUES
(
    'Américo Magalhães Moralles','09836535004','63051508000139','americomoralles@hotmail.com',
    'Pessoa Jurídica','Rua','Americana','88',NULL,'Suzano','08512000','SP','Responsável pela empresa.'
),
(
    'Julio Novares Norton','79529502079','81042967000138','novaresjulio@gmail.com',
    'Pessoa Jurídica','Avenida','Solares','108',NULL,'Americana','13145560','SP','Responsável pela empresa.'
);

-- =====================================================
-- INSERÇAO DE DADOS DE CONTATOS CLIENTES
-- =====================================================
INSERT INTO contatoCliente (idCliente, telefone, whatsapp) VALUES
(1, '13917403219', '13917403219'),
(2, '21992234567', '21992234567');

-- =====================================================
-- RELIGA AS VALIDAÇÕES DE CHAVE ESTRANGEIRA
-- =====================================================
SET FOREIGN_KEY_CHECKS = 1;

SELECT*FROM cliente;
SELECT*FROM contatoCliente