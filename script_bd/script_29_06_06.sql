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
    tipoContrato ENUM('CLT', 'CONTRATO TEMPORARIO', 'PESSOA JURÍDICA', 'TERCEIRIZADO') NOT NULL,
    dataAdmissao DATE,
    dataDesligamento DATE,
    feriasProgramadas DATE,
    agencia VARCHAR(5),
    conta VARCHAR(15),
  tipoConta ENUM('CORRENTE', 'POUPANCA', 'SALARIO'),
    chavePix VARCHAR(77),
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
-- OBRA
-- =====================================================

CREATE TABLE obra (
    idObra INT AUTO_INCREMENT PRIMARY KEY,
    idCliente INT NOT NULL,
    dataInicio DATETIME NOT NULL,
    dataFim DATETIME,
    status ENUM('Em andamento', 'Concluida', 'Cancelada') NOT NULL,
    estado CHAR(2) NOT NULL,
    cidade VARCHAR(45) NOT NULL,
    cep CHAR(8) NOT NULL,
    logradouro VARCHAR(80) NOT NULL,
    endereco VARCHAR(50) NOT NULL,
    numero CHAR(4) NOT NULL,
    complemento VARCHAR(45),
    contrato VARCHAR(45),
    observacoes TEXT,
    CONSTRAINT fk_obra_cliente
        FOREIGN KEY (idCliente)
        REFERENCES cliente(idCliente)
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
-- OBRA FUNCIONARIO VEÍCULO
-- =====================================================

CREATE TABLE obraFuncionarioVeiculo (
    idObraFuncionarioVeiculo INT AUTO_INCREMENT PRIMARY KEY,
    idObraFuncionario INT NOT NULL,
    idVeiculo INT NOT NULL,
    FOREIGN KEY (idObraFuncionario)
        REFERENCES obraFuncionario(idObraFuncionario),
    FOREIGN KEY (idVeiculo)
        REFERENCES veiculo(idVeiculo)
);

-- =====================================================
-- CATEGORIA FINANCEIRA FUNCIONARIO
-- =====================================================

CREATE TABLE categoriaFinanceiroFuncionario (
    idCategoria INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(80) NOT NULL, -- Salário, Horas Extras, Férias, 13º terceiro 
    tipo ENUM('ENTRADA','SAIDA') NOT NULL,
    tipoContrato ENUM('CLT','CONTRATO TEMPORARIO','TERCEIRIZADO','PESSOA JURÍDICA', 'TODOS') NOT NULL DEFAULT 'TODOS',
    ativo BOOLEAN DEFAULT TRUE
);

-- =====================================================
-- FINANCEIRO FUNCIONARIO
-- =====================================================

CREATE TABLE financeiroFuncionario (
    idFinanceiroFuncionario INT AUTO_INCREMENT PRIMARY KEY,
    idFuncionario INT NOT NULL,
    idCategoria INT NOT NULL,
    descricao VARCHAR(200) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    dataReferencia DATE NOT NULL,   
    formaPagamento VARCHAR(40),
    contaPagamento VARCHAR(100),
    observacao TEXT,
    dataCadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idFuncionario)
        REFERENCES funcionario(idFuncionario),
    FOREIGN KEY (idCategoria)
        REFERENCES categoriaFinanceiroFuncionario(idCategoria)
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
    dataRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
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
('Juliana','emaildajuju@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Douglas','emaildodouglas@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Matheus','emaildomatheus@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Camila', 'emaildacamila@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Francielly','emaildafrancielly@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Danilo', 'emaildodanilo@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Alexandre', 'emaildoalexandre@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Senac', 'senacsantos@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2');
 
-- =====================================================
-- INSERÇAO DE DADOS DE FUNCIONÁRIOS
-- =====================================================

INSERT INTO funcionario (
    nome, dataNascimento, sexo, naturalidade, estadoNascimento, cpf,
    tipoLogradouro, nomeLogradouro, numero, complemento, cidade, cep,
    estado, email, cargoFuncao, tipoContrato, dataAdmissao, dataDesligamento,
    feriasProgramadas, agencia, conta, tipoConta, chavePix, status, observacoes
) VALUES

('João Pedro Silva', '1990-05-12', 'Masculino', 'Santos', 'SP', '58058711063',
'Rua', 'Avenida Ana Costa', '120', 'Apto 12', 'Santos', '11060001', 'SP',
'joao.silva@empresa.com', 'Analista de Sistemas', 'CLT',
'2022-03-14', NULL, '2026-12-10',
'1234', '12345', 'CORRENTE', '58058711063',
'ativo', 'Funcionário experiente em desenvolvimento web.'),

('Maria Oliveira Souza', '1988-11-23', 'Feminino', 'São Vicente', 'SP', '98765432100',
'Rua', 'Rua Frei Gaspar', '450', NULL, 'São Vicente', '11310000', 'SP',
'maria.souza@empresa.com', 'Recursos Humanos', 'CONTRATO TEMPORARIO',
'2023-07-03', NULL, '2026-09-15',
'2100', '98765', 'POUPANCA', 'maria.souza@empresa.com',
'ativo', 'Atua no recrutamento e seleção.'),

('Carlos Henrique Lima', '1995-02-17', 'Masculino', 'Praia Grande', 'SP', '61841080004',
'Avenida', 'Avenida Presidente Kennedy', '890', 'Sala 3', 'Praia Grande', '11700000', 'SP',
'carlos.lima@empresa.com', 'Assistente Administrativo', 'TERCEIRIZADO',
'2021-01-11', NULL, '2026-11-03',
'3050', '45678', 'SALARIO', '61999998888',
'inativo', 'Contrato encerrado em 2025.'),

('Fernanda Alves Costa', '1992-08-30', 'Feminino', 'Guarujá', 'SP', '65396386045',
'Rua', 'Rua Mário Ribeiro', '77', NULL, 'Guarujá', '11410000', 'SP',
'fernanda.costa@empresa.com', 'Designer Gráfico', 'PESSOA JURÍDICA',
'2024-02-05', NULL, '2026-08-18',
'4102', '32145', 'CORRENTE', 'fernanda.costa@empresa.com',
'ativo', 'Responsável pela identidade visual da empresa.'),

('Lucas Martins Pereira', '2000-01-10', 'Outro', 'Cubatão', 'SP', '15935745682',
'Travessa', 'Travessa das Flores', '15', 'Casa', 'Cubatão', '11500000', 'SP',
'lucas.pereira@empresa.com', 'Suporte Técnico', 'CLT',
'2025-01-20', NULL, '2027-01-12',
'5501', '78945', 'CORRENTE', '15935745682',
'ativo', 'Atendimento interno e suporte aos usuários.');



-- =====================================================
-- INSERÇAO DE DADOS DE CONTATO DE FUNCIONÁRIOS
-- =====================================================

INSERT INTO contatoFuncionario (idFuncionario, telefone, whatsapp) VALUES
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

('Gabriella Guimarães', '41683643054', '74537841000179', 'guimaraesltda@gmail.com',
'Pessoa Jurídica', 'Avenida', 'Lunares', '888', NULL, 'Mogi Mirim', '13800005', 'SP', 'Responsável pela empresa.'),

('Maria Luiza Moralles Gomes', '60877158002', '88465497000164', 'morallesgomes@outlook.com',
'Pessoa Jurídica', 'Avenida', 'Riviera', '108', NULL, 'Rivieira de São Lourenço', '11250000', 'SP', 'Responsável pela empresa.'),

('Giovanni Henrique Muniz Gonçalves Lemos', '48245469076',NULL, 'gigilemosmuniz@icloud.com',
'Pessoa Física', 'Rua', 'da Praia das Astúrias', '10', NULL, 'Guaruja', '11410002', 'SP', 'Não tem empresa vinculada. Serviços avulsos na casa de praia'),
 
('Julio Novares Norton', '79529502079', '81042967000138', 'novaresjulio@gmail.com',
'Pessoa Jurídica', 'Avenida', 'Solares', '108', NULL, 'Americana', '13145560', 'SP', 'Responsável pela empresa.');
 
-- =====================================================
-- INSERÇAO DE CONTATO DE CLIENTES
-- =====================================================

INSERT INTO contatoCliente (idCliente, telefone, whatsapp) VALUES
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
'Veículo utilizado para visitas externas.'),

(3, '30497829222', 'ADC1K28', '9BWZZZ377VT004932',
'Volkswagen', 'Fiat Fiorino', 2024, 2023, 'Preto', 'ATIVO', 'PROPRIO',
15000, '2025-01-15', '2026-01-15', 'Empresa Meca', 'João Silva', 1,
'Veículo utilizado para visitas externas.'),

(4, '31197922190', 'AFJ1D28', '9KWZZZ377VT004252',
'Volkswagen', 'Fiat Fiorino', 2024, 2023, 'Preto', 'ATIVO', 'PROPRIO',
15000, '2025-01-15', '2026-01-15', 'Empresa WKY', 'João Silva', 1,
'Veículo utilizado para visitas externas.'),
 
(2, '20497929190', 'LFD1D28', '0BWZZZ377VT004252',
'Volkswagen', 'Fiat Fiorino', 2024, 2023, 'Preto', 'ATIVO', 'PROPRIO',
15000, '2025-01-15', '2026-01-15', 'Empresa WKY', 'João Silva', 1,
'Veículo utilizado para visitas externas.');
 

-- =====================================================
-- INSERÇAO DE DADOS DE OBRA
-- =====================================================
INSERT INTO obra (
    idCliente,dataInicio,dataFim,status,estado,cidade,cep,
    logradouro,endereco,numero,complemento,contrato,observacoes
) VALUES

-- Cliente 1: Américo Magalhães Moralles
(1, '2026-01-15 08:00:00', NULL, 'Em andamento', 'SP', 'Suzano', '08512000',
'Rua Americana', 'Galpão Industrial Moralles', '88', NULL, 'Obra 1',
'Ampliação de rede elétrica: Instalação elétrica de área fabril'),

-- Cliente 2: Gabriella Guimarães
(2, '2025-09-10 07:30:00', '2026-03-20 17:00:00', 'Concluída', 'SP', 'Mogi Mirim', '13800005',
'Avenida Lunares', 'Centro Administrativo Guimarães', '888', NULL, 'Obra 2',
'Modernização elétrica: Troca completa de quadros e cabeamento'),

-- Cliente 3: Maria Luiza Moralles Gomes
(3, '2026-05-01 09:00:00', NULL, 'Em andamento', 'SP', 'Bertioga', '11250000',
'Avenida Riviera', 'Condomínio Riviera Business', '108', 'Bloco B', 'Obra 3',
'Expansão da Infraestrutura: Instalação elétrica de novo bloco comercial'),

-- Cliente 4: Giovanni Henrique Muniz Gonçalves Lemos
(4, '2026-04-10 08:00:00', NULL, 'Em andamento', 'SP', 'Guarujá', '11410002',
'Rua da Praia das Astúrias', 'Residência Particular', '10', NULL, 'Obra 4',
'Serviço residencial: Reforma elétrica da casa de praia'),

-- Cliente 5: Julio Novares Norton
(5, '2026-02-03 08:30:00', NULL, 'Em andamento', 'SP', 'Americana', '13145560',
'Avenida Solares', 'Parque Empresarial Norton', '108', NULL, 'Obra 5',
'Construção de subestação: Infraestrutura elétrica para expansão industrial');


-- =====================================================
-- INSERÇAO DE DADOS DE OBRA FUNCIONÁRIO
-- =====================================================

INSERT INTO obraFuncionario (idObra, idFuncionario) VALUES
(1, 1), -- João Pedro Silva na Obra 1
(1, 3), -- Carlos Henrique Lima na Obra 1
(2, 2), -- Maria Oliveira Souza na Obra 2
(2, 4), -- Fernanda Alves Costa na Obra 2
(3, 5); -- Lucas Martins Pereira na Obra 3

-- =====================================================
-- INSERÇAO DE DADOS DE OBRA FUNCIONÁRIO VEÍCULO
-- =====================================================

INSERT INTO obraFuncionarioVeiculo (idObraFuncionario, idVeiculo) VALUES
(1, 1), 
(2, 2), 
(3, 3), 
(4, 4), 
(5, 5); 

-- =====================================================
-- INSERÇAO DE DADOS DA CATEGORIA FINANCEIRO FUNCIONARIO
-- =====================================================
INSERT INTO categoriaFinanceiroFuncionario (nome, tipo, tipoContrato) VALUES
('Salário', 'ENTRADA', 'CLT'),
('Horas Extras', 'ENTRADA', 'CLT'),
('13º Salário', 'ENTRADA', 'CLT'),
('Férias', 'ENTRADA', 'CLT'),
('Bônus', 'ENTRADA', 'TODOS'),
('Ajuda de Custo', 'ENTRADA', 'TODOS'),
('Pagamento NF', 'ENTRADA', 'PESSOA JURÍDICA'),
('Pagamento Serviço', 'ENTRADA', 'TERCEIRIZADO'),

('INSS', 'SAIDA', 'CLT'),
('IRRF', 'SAIDA', 'CLT'),
('Vale Transporte', 'SAIDA', 'CLT'),
('Vale Alimentação', 'SAIDA', 'CLT'),
('Empréstimo', 'SAIDA', 'TODOS');

-- =====================================================
-- INSERÇAO DE DADOS DA FINANCEIRO FUNCIONARIO
-- =====================================================

-- FUNCIONÁRIO JOÃO - CLT
INSERT INTO financeiroFuncionario
(idFuncionario, idCategoria, descricao, valor, dataReferencia, formaPagamento, contaPagamento, observacao)
VALUES
(1, 1, 'Salário Julho/2026', 5800.00, '2026-07-01', 'Transferência', 'Banco do Brasil', ''),
(1, 2, 'Horas Extras', 450.00, '2026-07-01', 'Transferência', 'Banco do Brasil', ''),
(1, 9, 'Desconto INSS', 640.00, '2026-07-01', 'Folha', 'Banco do Brasil', ''),
(1,10, 'Desconto IRRF', 285.00, '2026-07-01', 'Folha', 'Banco do Brasil', ''),
(1,11, 'Vale Transporte', 220.00, '2026-07-01', 'Folha', 'Banco do Brasil', '');

-- FUNCIONÁRIA MARIA - CONTRATO TEMPORÁRIO
INSERT INTO financeiroFuncionario
(idFuncionario, idCategoria, descricao, valor, dataReferencia, formaPagamento, contaPagamento, observacao)
VALUES
(2, 1, 'Salário Julho/2026', 4700.00, '2026-07-01', 'PIX', 'Caixa Econômica', ''),
(2, 5, 'Bônus por desempenho', 300.00, '2026-07-01', 'PIX', 'Caixa Econômica', ''),
(2, 9, 'Desconto INSS', 515.00, '2026-07-01', 'Folha', 'Caixa Econômica', ''),
(2,11, 'Vale Transporte', 180.00, '2026-07-01', 'Folha', 'Caixa Econômica', '');

-- FUNCIONÁRIO CARLOS - TERCEIRIZADA
INSERT INTO financeiroFuncionario
(idFuncionario, idCategoria, descricao, valor, dataReferencia, formaPagamento, contaPagamento, observacao)
VALUES
(3, 8, 'Pagamento de Serviço Julho/2026', 5200.00, '2026-07-01', 'TED', 'Banco Itaú', '');

-- FUNCIONÁRIA FERNANDA - PESSOA JURÍDICA
INSERT INTO financeiroFuncionario
(idFuncionario, idCategoria, descricao, valor, dataReferencia, formaPagamento, contaPagamento, observacao)
VALUES
(4, 7, 'Pagamento NF Julho/2026', 6000.00, '2026-07-01', 'PIX', 'Banco Inter', ''),
(4, 6, 'Ajuda de Custo', 350.00, '2026-07-01', 'PIX', 'Banco Inter', '');

-- FUNCIONÁRIO LUCAS - CLT
INSERT INTO financeiroFuncionario
(idFuncionario, idCategoria, descricao, valor, dataReferencia, formaPagamento, contaPagamento, observacao)
VALUES
(5, 1, 'Salário Julho/2026', 4500.00, '2026-07-01', 'Transferência', 'Santander', ''),
(5, 5, 'Bônus', 250.00, '2026-07-01', 'Transferência', 'Santander', ''),
(5, 9, 'Desconto INSS', 495.00, '2026-07-01', 'Folha', 'Santander', ''),
(5,11, 'Vale Transporte', 180.00, '2026-07-01', 'Folha', 'Santander', '');


-- =====================================================
-- CONSULTAS DE TESTE (Rode após a criação)
-- =====================================================

SELECT * FROM usuario;
SELECT * FROM cliente;
SELECT * FROM obra;
SELECT * FROM veiculo;
SELECT * FROM funcionario;
SELECT * FROM contatoFuncionario;
SELECT * FROM contatoCliente;
SELECT * FROM obraFuncionario;
SELECT * FROM funcionario WHERE idFuncionario = 1;
SELECT * FROM categoriaFinanceiroFuncionario;
 
SELECT CONSTRAINT_NAME, TABLE_NAME
FROM information_schema.TABLE_CONSTRAINTS
WHERE CONSTRAINT_SCHEMA = 'empreiteira';

SELECT
    o.idObra,
    o.contrato,
    o.idCliente,
    c.nomeCliente,
    c.cnpj
FROM obra o
INNER JOIN cliente c
    ON o.idCliente = c.idCliente;

SELECT * FROM funcionario WHERE cpf='58058711063'; 

-- =====================================================
-- DESCRIÇÃO DAS TABELAS
-- =====================================================
 
DESCRIBE veiculo;
DESCRIBE obra;
DESCRIBE funcionario;
DESCRIBE cliente;
DESCRIBE contatoFuncionario;
DESCRIBE contatoCliente;
DESCRIBE financeiroFuncionario;
DESCRIBE financeiroFuncionario;

SHOW COLUMNS FROM funcionario;
SHOW COLUMNS FROM financeiroObra;

 
SELECT idFinanceiroObra, idObra, descricao, valor, dataGasto 
FROM financeiroObra 
ORDER BY idFinanceiroObra DESC LIMIT 1;