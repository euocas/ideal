-- =====================================================
<<<<<<< HEAD
-- DESLIGA AS TRAVAS DE SEGURANÇA PARA GARANTIR A LIMPEZA
-- =====================================================
SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================
-- LIMPEZA DO BANCO
-- =====================================================
=======
-- LIMPEZA
-- =====================================================

>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
DROP DATABASE IF EXISTS empreiteira;

-- =====================================================
-- CRIAÇÃO DO BANCO
-- =====================================================
<<<<<<< HEAD
=======

>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
CREATE DATABASE empreiteira;
USE empreiteira;

-- =====================================================
<<<<<<< HEAD
-- TABELA USUARIOS
-- =====================================================
CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
=======
-- USUÁRIO
-- =====================================================

CREATE TABLE usuario (
    idUsuario INT AUTO_INCREMENT PRIMARY KEY,
>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
    nome VARCHAR(100),
    email VARCHAR(120) UNIQUE,
    senha VARCHAR(255)
);

-- =====================================================
<<<<<<< HEAD
-- TABELA FUNCIONARIOS
-- =====================================================
=======
-- FUNCIONÁRIO
-- =====================================================

>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
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
<<<<<<< HEAD
    estado CHAR (2),
    email VARCHAR(150) NOT NULL,
    cargoFuncao VARCHAR(100),
    tipoContrato ENUM('CLT', 'CONTRATO TEMPORARIO','PESSOA JURÍDICA', 'TERCEIRIZADA') NOT NULL,
=======
    estado CHAR(2),
    email VARCHAR(150) NOT NULL,
    cargoFuncao VARCHAR(100),
    tipoContrato ENUM('CLT', 'CONTRATO TEMPORARIO', 'PESSOA JURÍDICA', 'TERCEIRIZADA') NOT NULL,
>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
    dataAdmissao DATE,
    dataDesligamento DATE,
    feriasProgramadas DATE,
    status ENUM('ativo', 'inativo') NOT NULL DEFAULT 'ativo',
    observacoes TEXT
);

-- =====================================================
<<<<<<< HEAD
-- CONTATO FUNCIONARIO
-- =====================================================
CREATE TABLE contatoFuncionario (
    idContato INT AUTO_INCREMENT PRIMARY KEY,
    idFuncionario INT NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    whatsapp VARCHAR(20),

=======
-- CONTATO FUNCIONÁRIO
-- =====================================================

CREATE TABLE contatoFuncionario (
    idContato INT AUTO_INCREMENT PRIMARY KEY,
    idFuncionario INT NOT NULL,
    telefone VARCHAR(20),
    whatsapp VARCHAR(20),
>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
    CONSTRAINT fk_contatoFuncionario
        FOREIGN KEY (idFuncionario)
        REFERENCES funcionario(idFuncionario)
        ON DELETE CASCADE
);

-- =====================================================
<<<<<<< HEAD
-- CLIENTE (CORRIGIDA - SEM A COLUNA TELEFONE AQUI)
-- =====================================================
=======
-- CLIENTE
-- =====================================================

>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
CREATE TABLE cliente (
    idCliente INT AUTO_INCREMENT PRIMARY KEY,
    nomeCliente VARCHAR(45) NOT NULL,
    cpf CHAR(11) UNIQUE,
    cnpj CHAR(14) UNIQUE,
<<<<<<< HEAD
    email VARCHAR(150) NOT NULL,
    tipoCliente ENUM('Pessoa Física', 'Pessoa Jurídica') NOT NULL,
=======
    email VARCHAR(150),
    tipoCliente ENUM('Pessoa Física', 'Pessoa Jurídica'),
>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
    tipoLogradouro VARCHAR(15),
    nomeLogradouro VARCHAR(100),
    numero VARCHAR(6),
    complemento VARCHAR(30),
<<<<<<< HEAD
    cidade VARCHAR(100) NOT NULL,
    cep CHAR(8) NOT NULL,
    estado CHAR (2) NOT NULL,
=======
    cidade VARCHAR(100),
    cep CHAR(8),
    estado CHAR(2),
>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
    observacoes TEXT,
    CONSTRAINT chk_documento CHECK (cpf IS NOT NULL OR cnpj IS NOT NULL)
);

-- =====================================================
<<<<<<< HEAD
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
=======
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
>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
        ON DELETE CASCADE
);

-- =====================================================
-- OBRA
-- =====================================================
<<<<<<< HEAD
=======

>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
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
<<<<<<< HEAD
-- VEICULO
-- =====================================================
=======
-- VEÍCULO
-- =====================================================

>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
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
<<<<<<< HEAD
    statusVeiculo ENUM('ATIVO','EM MANUTENCAO', 'INATIVO','VENDIDO') DEFAULT 'ATIVO',
    tipoPosse ENUM('PROPRIO','ALUGADO','EMPRESTADO','TERCEIRIZADO') DEFAULT 'PROPRIO',
=======
    statusVeiculo ENUM('ATIVO', 'EM MANUTENCAO', 'INATIVO', 'VENDIDO') DEFAULT 'ATIVO',
    tipoPosse ENUM('PROPRIO', 'ALUGADO', 'EMPRESTADO', 'TERCEIRIZADO') DEFAULT 'PROPRIO',
>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
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
<<<<<<< HEAD
-- OBRA FUNCIONARIO
-- =====================================================
=======
-- OBRA FUNCIONÁRIO
-- =====================================================

>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
CREATE TABLE obraFuncionario (
    idObraFuncionario INT AUTO_INCREMENT PRIMARY KEY,
    idFuncionario INT NOT NULL,
    idObra INT NOT NULL,
<<<<<<< HEAD
    CONSTRAINT fk_funcionario_funcionario
        FOREIGN KEY (idFuncionario)
        REFERENCES funcionario(idFuncionario),
    CONSTRAINT fk_funcionario_obra
=======
    CONSTRAINT fk_obraFuncionario_funcionario
        FOREIGN KEY (idFuncionario)
        REFERENCES funcionario(idFuncionario),
    CONSTRAINT fk_obraFuncionario_obra
>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
        FOREIGN KEY (idObra)
        REFERENCES obra(idObra),
    CONSTRAINT uq_obra_funcionario
        UNIQUE (idFuncionario, idObra)
);

-- =====================================================
<<<<<<< HEAD
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
=======
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
-- FINANCEIRO FUNCIONÁRIO
-- =====================================================

CREATE TABLE financeiroFuncionario (
    idFinanceiroFuncionario INT AUTO_INCREMENT PRIMARY KEY,
    idFuncionario INT NOT NULL,
    salario DECIMAL(10,2),
    ferias DECIMAL(10,2),
    inss DECIMAL(10,2),
    decimoTerceiro DECIMAL(10,2),
    CONSTRAINT fk_financeiroFuncionario
>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
        FOREIGN KEY (idFuncionario)
        REFERENCES funcionario(idFuncionario)
);

-- =====================================================
-- FINANCEIRO OBRA
-- =====================================================
<<<<<<< HEAD
CREATE TABLE financeiroObra (
    idFinanceiroObra INT PRIMARY KEY AUTO_INCREMENT,
=======

CREATE TABLE financeiroObra (
    idFinanceiroObra INT AUTO_INCREMENT PRIMARY KEY,
>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
    idObra INT NOT NULL,
    descricao VARCHAR(100) NOT NULL,
    categoria VARCHAR(50),
    valor DECIMAL(10,2) NOT NULL,
    dataGasto DATE NOT NULL,
    formaPagamento VARCHAR(30),
    observacao VARCHAR(200),
<<<<<<< HEAD
    CONSTRAINT fk_FinanceiroObra
=======
    CONSTRAINT fk_financeiroObra
>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
        FOREIGN KEY (idObra)
        REFERENCES obra(idObra)
);

-- =====================================================
<<<<<<< HEAD
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
=======
-- FINANCEIRO AUTOMÓVEL
-- =====================================================

CREATE TABLE financeiroAutomovel (
    idFinanceiroAutomovel INT AUTO_INCREMENT PRIMARY KEY,
>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
    idVeiculo INT NOT NULL,
    combustivel DECIMAL(10,2),
    manutencao DECIMAL(10,2),
    ipva DECIMAL(10,2),
<<<<<<< HEAD
    CONSTRAINT fk_FinanceiroAutomovel
=======
    CONSTRAINT fk_financeiroAutomovel
>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
        FOREIGN KEY (idVeiculo)
        REFERENCES veiculo(idVeiculo)
);

-- =====================================================
<<<<<<< HEAD
-- AUTOMOVEL FUNCIONARIO
-- =====================================================
CREATE TABLE automovelFuncionario (
    idAutomovelFuncionario INT PRIMARY KEY AUTO_INCREMENT,
=======
-- AUTOMÓVEL FUNCIONÁRIO
-- =====================================================

CREATE TABLE automovelFuncionario (
    idAutomovelFuncionario INT AUTO_INCREMENT PRIMARY KEY,
>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
    idVeiculo INT NOT NULL,
    idFuncionario INT NOT NULL,
    dataRetirada DATETIME NOT NULL,
    dataDevolucao DATETIME,
<<<<<<< HEAD
    CONSTRAINT fk_automovel_vinculo 
        FOREIGN KEY (idVeiculo) 
        REFERENCES veiculo(idVeiculo),
    CONSTRAINT fk_funcionario_vinculo 
        FOREIGN KEY (idFuncionario) 
=======
    CONSTRAINT fk_automovelFuncionario_veiculo
        FOREIGN KEY (idVeiculo)
        REFERENCES veiculo(idVeiculo),
    CONSTRAINT fk_automovelFuncionario_funcionario
        FOREIGN KEY (idFuncionario)
>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
        REFERENCES funcionario(idFuncionario)
);

-- =====================================================
<<<<<<< HEAD
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
=======
-- INSERÇÃO DE USUÁRIOS
-- SENHA PADRÃO: 1234
-- =====================================================

INSERT INTO usuario (nome, email, senha) VALUES
('Administrador',  'emailteste@gmail.com',        '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Juliana',        'jujusantista23@gmail.com',     '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Douglas',        'emaildodouglas@gmail.com',     '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Matheus',        'emaildomatheus@gmail.com',     '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Camila',         'emaildacamila@gmail.com',      '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Francielly',     'emaildafrancielly@gmail.com',  '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Danilo',         'emaildodanilo@gmail.com',      '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Alexandre',      'emaildoalexandre@gmail.com',   '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2');

-- =====================================================
-- INSERÇÃO DE FUNCIONÁRIOS
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
-- INSERÇÃO DE CONTATOS DE FUNCIONÁRIOS
-- =====================================================

INSERT INTO contatoFuncionario (idFuncionario, telefone, whatsapp) VALUES
(1, '13990001136', '13990001136'),
(2, '11987654321', '11987654321'),
(3, '21991234567', '21991234567'),
(4, '31999887766', '31999887766'),
(5, '41995554433', '41995554433');

-- =====================================================
-- INSERÇÃO DE CLIENTES
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
-- INSERÇÃO DE CONTATOS DE CLIENTES
-- =====================================================

>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
INSERT INTO contatoCliente (idCliente, telefone, whatsapp) VALUES
(1, '13917403219', '13917403219'),
(2, '21992234567', '21992234567');

-- =====================================================
<<<<<<< HEAD
-- RELIGA AS VALIDAÇÕES DE CHAVE ESTRANGEIRA
-- =====================================================
SET FOREIGN_KEY_CHECKS = 1;

SELECT*FROM cliente;
SELECT*FROM contatoCliente
=======
-- INSERÇÃO DE VEÍCULOS
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
>>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
