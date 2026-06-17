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
    idUsuario INT AUTO_INCREMENT PRIMARY KEY,
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
    estado CHAR(2),
    email VARCHAR(150) NOT NULL,
    cargoFuncao VARCHAR(100),
    tipoContrato ENUM('CLT', 'CONTRATO TEMPORARIO', 'PESSOA JURÍDICA', 'TERCEIRIZADA') NOT NULL,
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
    telefone VARCHAR(20),
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
    email VARCHAR(150),
    tipoCliente ENUM('Pessoa Física', 'Pessoa Jurídica'),
    tipoLogradouro VARCHAR(15),
    nomeLogradouro VARCHAR(100),
    numero VARCHAR(6),
    complemento VARCHAR(30),
    cidade VARCHAR(100),
    cep CHAR(8),
    estado CHAR(2),
    observacoes TEXT,
    CONSTRAINT chk_documento CHECK (cpf IS NOT NULL OR cnpj IS NOT NULL)

);
 
-- =====================================================
-- CONTATO CLIENTE
-- =====================================================

CREATE TABLE contatoCliente (
    idContato INT AUTO_INCREMENT PRIMARY KEY,
    idCliente INT NOT NULL,
    telefone VARCHAR(20),
    whatsapp VARCHAR(20),
    CONSTRAINT fk_contatoCliente
        FOREIGN KEY (idCliente)
        REFERENCES cliente(idCliente)
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
    statusVeiculo ENUM('ATIVO', 'EM MANUTENCAO', 'INATIVO', 'VENDIDO') DEFAULT 'ATIVO',
    tipoPosse ENUM('PROPRIO', 'ALUGADO', 'EMPRESTADO', 'TERCEIRIZADO') DEFAULT 'PROPRIO',
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
    CONSTRAINT fk_obraFuncionario_funcionario
        FOREIGN KEY (idFuncionario)
        REFERENCES funcionario(idFuncionario),
    CONSTRAINT fk_obraFuncionario_obra
        FOREIGN KEY (idObra)
        REFERENCES obra(idObra),
    CONSTRAINT uq_obra_funcionario
        UNIQUE (idFuncionario, idObra)

);


-- =====================================================
-- OBRA CLIENTE
-- =====================================================

CREATE TABLE obraCliente (
    idObraCliente INT AUTO_INCREMENT PRIMARY KEY,
    idObra INT NOT NULL,
    idCliente INT NOT NULL,
    CONSTRAINT fk_obraCliente_obra
        FOREIGN KEY (idObra)
        REFERENCES obra(idObra),
    CONSTRAINT fk_obraCliente_cliente
        FOREIGN KEY (idCliente)
        REFERENCES cliente(idCliente),
    CONSTRAINT uq_obra_cliente
        UNIQUE (idObra, idCliente)

);

-- =====================================================
-- FINANCEIRO FUNCIONARIO
-- =====================================================

CREATE TABLE financeiroFuncionario (
    idFinanceiroFuncionario INT AUTO_INCREMENT PRIMARY KEY,
    idFuncionario INT NOT NULL,
    salario DECIMAL(10,2),
    ferias DECIMAL(10,2),
    inss DECIMAL(10,2),
    decimoTerceiro DECIMAL(10,2),
    CONSTRAINT fk_financeiroFuncionario
        FOREIGN KEY (idFuncionario)
        REFERENCES funcionario(idFuncionario)

);
-- =====================================================
-- FINANCEIRO OBRA
-- =====================================================

CREATE TABLE financeiroObra (
    idFinanceiroObra INT AUTO_INCREMENT PRIMARY KEY,
    idObra INT NOT NULL,
    descricao VARCHAR(100) NOT NULL,
    categoria VARCHAR(50),
    valor DECIMAL(10,2) NOT NULL,
    dataGasto DATE NOT NULL,
    formaPagamento VARCHAR(30),
    observacao VARCHAR(200),
    CONSTRAINT fk_financeiroObra
        FOREIGN KEY (idObra)
        REFERENCES obra(idObra)

);

-- =====================================================
-- FINANCEIRO AUTOMOVEL
-- =====================================================
CREATE TABLE financeiroAutomovel (
    idFinanceiroAutomovel INT AUTO_INCREMENT PRIMARY KEY,
    idVeiculo INT NOT NULL,
    combustivel DECIMAL(10,2),
    manutencao DECIMAL(10,2),
    ipva DECIMAL(10,2),
    CONSTRAINT fk_financeiroAutomovel
        FOREIGN KEY (idVeiculo)
        REFERENCES veiculo(idVeiculo)

);

-- =====================================================
-- AUTOMOVEL FUNCIONARIO
-- =====================================================

CREATE TABLE automovelFuncionario (
    idAutomovelFuncionario INT AUTO_INCREMENT PRIMARY KEY,
    idVeiculo INT NOT NULL,
    idFuncionario INT NOT NULL,
    dataRetirada DATETIME NOT NULL,
    dataDevolucao DATETIME,
    CONSTRAINT fk_automovelFuncionario_veiculo
        FOREIGN KEY (idVeiculo)
        REFERENCES veiculo(idVeiculo),
    CONSTRAINT fk_automovelFuncionario_funcionario
        FOREIGN KEY (idFuncionario)
        REFERENCES funcionario(idFuncionario)

);
 
-- ==================================================================================================================
-- INSERÇAO DE DADOS DE USUÁRIOS - SENHA PADRÃO: 1234 = $2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2
-- ==================================================================================================================

INSERT INTO usuario (nome, email, senha) VALUES

('Administrador','emailteste@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Juliana','jujusantista23@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Douglas','emaildodouglas@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Matheus','emaildomatheus@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Camila', 'emaildacamila@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Francielly','emaildafrancielly@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Danilo', 'emaildodanilo@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Alexandre', 'emaildoalexandre@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2');
 
-- =====================================================
-- INSERÇAO DE DADOS DE FUNCIONÁRIOS
-- =====================================================

INSERT INTO funcionario (
    nome, dataNascimento, sexo, naturalidade, estadoNascimento, cpf,
    tipoLogradouro, nomeLogradouro, numero, complemento, cidade, cep,
    estado, email, cargoFuncao, tipoContrato, dataAdmissao, dataDesligamento,
    feriasProgramadas, status, observacoes

) VALUES

('João Pedro Silva', '1990-05-12', 'Masculino', 'Santos', 'SP', '58058711063',
'Rua', 'Avenida Ana Costa', '120', 'Apto 12', 'Santos', '11060001', 'SP',
'joao.silva@empresa.com', 'Analista de Sistemas', 'CLT',
'2022-03-14', NULL, '2026-12-10', 'ativo', 'Funcionário experiente em desenvolvimento web.'),
 
('Maria Oliveira Souza', '1988-11-23', 'Feminino', 'São Vicente', 'SP', '98765432100',
'Rua', 'Rua Frei Gaspar', '450', NULL, 'São Vicente', '11310000', 'SP',
'maria.souza@empresa.com', 'Recursos Humanos', 'CONTRATO TEMPORARIO',
'2023-07-03', NULL, '2026-09-15', 'ativo', 'Atua no recrutamento e seleção.'),
 
('Carlos Henrique Lima', '1995-02-17', 'Masculino', 'Praia Grande', 'SP', '61841080004',
'Avenida', 'Avenida Presidente Kennedy', '890', 'Sala 3', 'Praia Grande', '11700000', 'SP',
'carlos.lima@empresa.com', 'Assistente Administrativo', 'TERCEIRIZADA',
'2021-01-11', NULL, '2026-11-03', 'inativo', 'Contrato encerrado em 2025.'),
 
('Fernanda Alves Costa', '1992-08-30', 'Feminino', 'Guarujá', 'SP', '65396386045',
'Rua', 'Rua Mário Ribeiro', '77', NULL, 'Guarujá', '11410000', 'SP',
'fernanda.costa@empresa.com', 'Designer Gráfico', 'PESSOA JURÍDICA',
'2024-02-05', NULL, '2026-08-18', 'ativo', 'Responsável pela identidade visual da empresa.'),

('Lucas Martins Pereira', '2000-01-10', 'Outro', 'Cubatão', 'SP', '15935745682',
'Travessa', 'Travessa das Flores', '15', 'Casa', 'Cubatão', '11500000', 'SP',
'lucas.pereira@empresa.com', 'Suporte Técnico', 'CLT',
'2025-01-20', NULL, '2027-01-12', 'ativo', 'Atendimento interno e suporte aos usuários.');
 
-- =====================================================
-- INSERÇAO DE DADOS DE FUNCIONÁRIOS
-- =====================================================

INSERT INTO contatoFuncionario (idFuncionario, telefone, whatsapp
) VALUES

(1, '13990001136', '13990001136'),
(2, '11987654321', '11987654321'),
(3, '21991234567', '21991234567'),
(4, '31999887766', '31999887766'),
(5, '41995554433', '41995554433');
 
-- =====================================================
-- INSERÇAO DE DADOS DE CLIENTES
-- =====================================================

INSERT INTO cliente (
    nomeCliente, cpf, cnpj, email, tipoCliente,
    tipoLogradouro, nomeLogradouro, numero, complemento,
    cidade, cep, estado, observacoes

) VALUES

('Américo Magalhães Moralles', '09836535004', '63051508000139', 'americomoralles@hotmail.com',
'Pessoa Jurídica', 'Rua', 'Americana', '88', NULL, 'Suzano', '08512000', 'SP', 'Responsável pela empresa.'),
 
('Julio Novares Norton', '79529502079', '81042967000138', 'novaresjulio@gmail.com',
'Pessoa Jurídica', 'Avenida', 'Solares', '108', NULL, 'Americana', '13145560', 'SP', 'Responsável pela empresa.');
 
-- =====================================================
-- INSERÇAO DE CONTATO DE CLIENTES
-- =====================================================

INSERT INTO contatoCliente (idCliente, telefone, whatsapp
) VALUES

(1, '13917403219', '13917403219'),
(2, '21992234567', '21992234567');
 
-- =====================================================
-- INSERÇAO DE DADOS DE VEÍCULOS
-- =====================================================

INSERT INTO veiculo (
    idFuncionario, renavam, placa, chassi, marca, modelo,
    anoFabricacao, anoModelo, cor, statusVeiculo, tipoPosse,
    quilometragem, dataUltimaRevisao, proximaRevisao,
    propriedadeVeiculo, responsavelVeiculo, quantidade, observacoes

) VALUES

(1, '87996693683', 'ABC1D23', '9BWZZZ377VT004251',
'Volkswagen', 'Gol', 2022, 2023, 'Prata', 'ATIVO', 'PROPRIO',
15000, '2025-01-15', '2026-01-15', 'Empresa XYZ', 'João Silva', 1,
'Veículo utilizado para visitas externas.'),
 
(5, '30497929190', 'AFC1D28', '9BWZZZ377VT004252',
'Volkswagen', 'Fiat Fiorino', 2024, 2023, 'Preto', 'ATIVO', 'PROPRIO',
15000, '2025-01-15', '2026-01-15', 'Empresa WKY', 'João Silva', 1,
'Veículo utilizado para visitas externas.');
 

-- =====================================================
-- INSERÇAO DE DADOS DE OBRA
-- =====================================================
INSERT INTO obra (
    dataInicio,dataFim,status,
    estado,cidade,cep,logradouro,
    endereco,numero,complemento, contrato
) VALUES
(
    '2026-01-15 08:00:00', NULL,
    'Em andamento', 'SP','Santos','11045000','Avenida Ana Costa',
    'Edifício Comercial Atlântico','120','Sala 05','Obra 1'
 ),
 (
    '2025-09-10 07:30:00','2026-03-20 17:00:00','Concluída',
    'RJ','Niterói','24020000','Rua da Conceição',
    'Condomínio Empresarial Centro','450',NULL,'Obra 2'

),(

    '2026-05-01 09:00:00',NULL,'Em andamento',
    'MG','Belo Horizonte','30130010','Avenida Afonso Pena',
    'Torre Corporativa Horizonte','850','Bloco B','Obra 3'

);


-- =====================================================
-- CONSULTAS
-- =====================================================

select * FROM usuario;

select * from cliente;

select * from obra;

select * from veiculo;

select * from contatoCliente;

select * FROM funcionario;

Select * from contatoFuncionario;

select * from contatoCliente;
 
select CONSTRAINT_NAME, TABLE_NAME

FROM information_schema.TABLE_CONSTRAINTS

WHERE CONSTRAINT_SCHEMA = 'empreiteira';

-- =====================================================
-- DESCRIÇÃO DAS TABELAS
-- =====================================================
 
describe veiculo;
describe funcionario;
describe cliente;
describe contatoFuncionario;
describe contatoCliente;

SHOW COLUMNS FROM funcionario;
 