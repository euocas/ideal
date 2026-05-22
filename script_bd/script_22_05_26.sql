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
-- CRIAÇÃO DE TABELAS
-- =====================================================

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
    tipoContrato ENUM('CLT', 'CONTRATO TEMPORARIO','PESSOA JURÍDICA', 'TERCERIZADA') NOT NULL,
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
-- CLIENTE
-- =====================================================
CREATE TABLE cliente (
    idCliente INT AUTO_INCREMENT PRIMARY KEY,
    nomeCliente VARCHAR(45) NOT NULL,
    cpf CHAR(11) UNIQUE,
    cnpj CHAR(14) UNIQUE,
    CONSTRAINT chk_documento CHECK (cpf IS NOT NULL OR cnpj IS NOT NULL)
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
-- Veiculo - COMPLETA
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

INSERT INTO usuario (nome, email, senha)
VALUES
('Administrador', 'emailteste@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Juliana', 'jujusantista23@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Douglas', 'emaildodouglas@gmail.com', '$2y$10$Cvl.oxQKkCy9N/GHUByXuOle40RaHZSpVj9g.tTImDMeBbRiNjZhm'),
('Matheus', 'emaildomatheus@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Camila', 'emaildacamila@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Francielly', 'emaildafrancielly@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Danilo', 'emaildodanilo@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Alexandre', 'emaildoalexandre@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2');

-- =====================================================
-- SENHA CRIPTOGRAFADA PARA USAR NO PROJETO. PARA O USUÁRIO SERÁ: 1234 
-- =====================================================

UPDATE usuario
SET senha = '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'
WHERE email = 'emailteste@gmail.com';

-- =====================================================
-- INSERÇAO DE DADOS DE FUNCIONÁRIOS
-- =====================================================
INSERT INTO funcionario (
    nome, dataNascimento, sexo, naturalidade, estadoNascimento, cpf,
    tipoLogradouro, nomeLogradouro, numero, complemento, cidade, cep,
    estado, email, cargoFuncao, tipoContrato, status, observacoes
) VALUES
(
    'João Pedro Silva','1990-05-12','Masculino','Santos','SP','58058711063',
    'Rua','Avenida Ana Costa','120','Apto 12','Santos','11060001','SP',
    'joao.silva@empresa.com','Analista de Sistemas','CLT',
    'ativo','Funcionário experiente em desenvolvimento web.'
),
(
    'Maria Oliveira Souza','1988-11-23','Feminino','São Vicente','SP','98765432100',
    'Rua','Rua Frei Gaspar','450',NULL,'São Vicente','11310000','SP',
    'maria.souza@empresa.com','Recursos Humanos','CONTRATO TEMPORARIO',
    'ativo','Atua no recrutamento e seleção.'
),
(
    'Carlos Henrique Lima','1995-02-17','Masculino','Praia Grande','SP','61841080004',
    'Avenida','Avenida Presidente Kennedy','890','Sala 3','Praia Grande','11700000','SP',
    'carlos.lima@empresa.com','Assistente Administrativo','TERCERIZADA',
    'inativo','Contrato encerrado em 2025.'
),
(
    'Fernanda Alves Costa','1992-08-30','Feminino','Guarujá','SP','65396386045',
    'Rua','Rua Mário Ribeiro','77',NULL,'Guarujá','11410000','SP',
    'fernanda.costa@empresa.com','Designer Gráfico','PESSOA JURÍDICA',
    'ativo','Responsável pela identidade visual da empresa.'
),
(
    'Lucas Martins Pereira','2000-01-10','Outro','Cubatão','SP','15935745682',
    'Travessa','Travessa das Flores','15','Casa','Cubatão','11500000','SP',
    'lucas.pereira@empresa.com','Suporte Técnico','CLT',
    'ativo','Atendimento interno e suporte aos usuários.'
);


-- =====================================================
-- CONSULTAS
-- =====================================================

SELECT * FROM usuario;
select * from cliente;
SELECT * FROM funcionario;
SELECT CONSTRAINT_NAME, TABLE_NAME
FROM information_schema.TABLE_CONSTRAINTS
WHERE CONSTRAINT_SCHEMA = 'empreiteira';

describe veiculo;
describe funcionario;
