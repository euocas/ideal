-- =====================================================
-- LIMPEZA (opcional)
-- =====================================================
DROP DATABASE IF EXISTS empreiteira;
 
-- =====================================================
-- CRIAÇÃO DO BANCO e UTILIZAÇÃO
-- =====================================================
CREATE DATABASE empreiteira;
USE empreiteira;

-- =====================================================
--  CRIAÇÃO DA TABELA USUARIOS
-- =====================================================
CREATE TABLE usuario (
    idUsuario INT AUTO_INCREMENT PRIMARY KEY,
    perfil ENUM('Administrador', 'Usuario') NOT NULL DEFAULT 'Usuario',
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
);
 
-- =====================================================
--  CRIAÇÃO DA TABELA FUNCIONARIOS
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
--  CRIAÇÃO DA TABELA CONTATO FUNCIONARIO
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
--  CRIAÇÃO DA TABELA CLIENTE
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
--  CRIAÇÃO DA TABELA CONTATO CLIENTE
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
--  CRIAÇÃO DA TABELA VEICULO
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
--  CRIAÇÃO DA TABELA OBRA
-- =====================================================
CREATE TABLE obra (
    idObra INT AUTO_INCREMENT PRIMARY KEY,
    idCliente INT NOT NULL,
    idResponsavel INT NULL,
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
    valorContratado DECIMAL(12,2),
    observacoes TEXT,
    CONSTRAINT fk_obra_cliente
        FOREIGN KEY (idCliente)
        REFERENCES cliente(idCliente),
    CONSTRAINT fk_obra_responsavel
        FOREIGN KEY (idResponsavel)
        REFERENCES funcionario(idFuncionario)
);

-- =====================================================
--  CRIAÇÃO DA TABELA OBRA-FUNCIONARIO
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
--  CRIAÇÃO DA TABELA OBRA-FUNCIONARIO-VEÍCULO
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
--  CRIAÇÃO DA TABELA CATEGORIA-FINANCEIRA-FUNCIONARIO
-- =====================================================
CREATE TABLE categoriaFinanceiroFuncionario (
    idCategoria INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(80) NOT NULL, -- Salário, Horas Extras, Férias, 13º terceiro 
    tipo ENUM('ENTRADA','SAIDA') NOT NULL,
    tipoContrato ENUM('CLT','CONTRATO TEMPORARIO','TERCEIRIZADO','PESSOA JURÍDICA', 'TODOS') NOT NULL DEFAULT 'TODOS',
    ativo BOOLEAN DEFAULT TRUE
);

-- =====================================================
--  CRIAÇÃO DA TABELA FINANCEIRO-FUNCIONARIO
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
--  CRIAÇÃO DA TABELA FINANCEIRO-FUNCIONARIO-REMUNERAÇÃO
-- =====================================================
CREATE TABLE funcionarioRemuneracao (
    idRemuneracao INT AUTO_INCREMENT PRIMARY KEY,
    idFuncionario INT NOT NULL,
    salarioBase DECIMAL(10,2) NOT NULL,
    planoSaude DECIMAL(10,2) DEFAULT 0.00,
    inicioVigencia DATE NOT NULL,
    fimVigencia DATE DEFAULT NULL,
    dataCadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_remuneracao_funcionario
        FOREIGN KEY (idFuncionario)
        REFERENCES funcionario(idFuncionario)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

-- =====================================================
--  CRIAÇÃO DA TABELA FINANCEIRO-OBRA
-- =====================================================
CREATE TABLE financeiroObra (
    idFinanceiroObra INT AUTO_INCREMENT PRIMARY KEY,
    idObra INT NOT NULL,
    descricao VARCHAR(100) NOT NULL,
    categoria VARCHAR(50),
    valor DECIMAL(10,2) NOT NULL,
    dataGasto DATE NOT NULL,
    formaPagamento VARCHAR(30),
    fornecedor VARCHAR(100) NULL,
    documentoFiscal VARCHAR(100) NULL,
    observacao VARCHAR(200),
    CONSTRAINT fk_financeiroObra
        FOREIGN KEY (idObra)
        REFERENCES obra(idObra)
);

-- =====================================================
--  CRIAÇÃO DA TABELA FINANCEIRO-AUTOMOVEL
-- =====================================================
CREATE TABLE financeiroAutomovel (
    idFinanceiroAutomovel INT AUTO_INCREMENT PRIMARY KEY,
    idVeiculo INT NOT NULL,
    tipo ENUM('Entrada','Saida') NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    dataMovimentacao DATE NOT NULL,
    formaPagamento VARCHAR(30) NULL,
    fornecedor VARCHAR(100) NULL,
    documentoFiscal VARCHAR(100) NULL,
    observacao TEXT NULL,
    dataRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idVeiculo)
        REFERENCES veiculo(idVeiculo)
);

-- =====================================================
--  CRIAÇÃO DA TABELA AUTOMOVEL-FUNCIONARIO
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
INSERT INTO usuario (perfil, nome, email, senha) VALUES
('Administrador','Ideal','ideal@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Usuario','Juliana','emaildajuju@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Usuario','Douglas','emaildodouglas@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Usuario','Matheus','emaildomatheus@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Usuario','Camila', 'emaildacamila@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Usuario','Francielly','emaildafrancielly@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Usuario','Danilo', 'emaildodanilo@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Usuario','Alexandre', 'emaildoalexandre@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Usuario','Senac', 'senacsantos@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2');
 
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
'joao.silva@empresa.com', 'Analista Financeiro', 'CLT',
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
'carlos.lima@empresa.com', 'Auxiliar Administrativo', 'TERCEIRIZADO',
'2021-01-11', NULL, '2026-11-03',
'3050', '45678', 'SALARIO', '61999998888',
'inativo', 'Contrato encerrado em 2025.'),
('Fernanda Alves Costa', '1992-08-30', 'Feminino', 'Guarujá', 'SP', '65396386045',
'Rua', 'Rua Mário Ribeiro', '77', NULL, 'Guarujá', '11410000', 'SP',
'fernanda.costa@empresa.com', 'Cabista', 'PESSOA JURÍDICA',
'2024-02-05', NULL, '2026-08-18',
'4102', '32145', 'CORRENTE', 'fernanda.costa@empresa.com',
'ativo', 'Responsável por cabeamento das empresas.'),
('Lucas Martins Pereira', '2000-01-10', 'Outro', 'Cubatão', 'SP', '15935745682',
'Travessa', 'Travessa das Flores', '15', 'Casa', 'Cubatão', '11500000', 'SP',
'lucas.pereira@empresa.com', 'Instalador Elétrico', 'CLT',
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
-- INSERÇAO DE DADOS DO FINANCEIRO-VEÍCULOS
-- =====================================================
INSERT INTO financeiroAutomovel (idVeiculo,tipo,categoria,descricao, valor,dataMovimentacao,formaPagamento,fornecedor,documentoFiscal,observacao)
VALUES

-- VEÍCULO 1
(1, 'Saida', 'Combustível', 'Abastecimento Diesel S10', 420.50, '2026-07-02', 'PIX', 'Posto BR', 'NF0001', 'Viagem para atendimento em obra.'),
(1, 'Saida', 'Manutenção', 'Troca de óleo e filtros', 680.00, '2026-07-08', 'Cartão', 'Auto Center Santos', 'OS1025', 'Revisão preventiva.'),
(1, 'Saida', 'IPVA', 'Pagamento da parcela do IPVA', 950.00, '2026-07-10', 'Boleto', 'Detran-SP', 'IPVA2026-01', NULL),
(1, 'Entrada', 'Reembolso', 'Reembolso de despesas da obra', 2050.00, '2026-07-12', 'Transferência', 'Cliente Alpha', 'REC001', 'Reembolso referente ao abastecimento.'),

-- VEÍCULO 2
(2, 'Saida', 'Combustível', 'Abastecimento Gasolina', 310.80, '2026-07-03', 'PIX', 'Posto Shell', 'NF0002', NULL),
(2, 'Saida', 'Licenciamento', 'Pagamento do licenciamento anual', 185.30, '2026-07-05', 'PIX', 'Detran-SP', 'LIC2026', NULL),
(2, 'Saida', 'Seguro', 'Parcela do seguro', 420.00, '2026-07-15', 'Débito', 'Porto Seguro', 'SEG001', NULL),

-- VEÍCULO 3
(3, 'Saida', 'Combustível', 'Abastecimento Diesel', 385.40, '2026-07-04', 'Cartão', 'Posto Ipiranga', 'NF0003', NULL),
(3, 'Saida', 'Pedágio', 'Pedágios durante viagem', 58.90, '2026-07-04', 'Dinheiro', 'CCR', 'PED001', NULL),
(3, 'Saida', 'Manutenção', 'Troca de pneus', 1980.00, '2026-07-18', 'Boleto', 'Pneus Brasil', 'OS2048', 'Troca dos quatro pneus.'),
(3, 'Entrada', 'Venda', 'Venda de pneus usados', 450.00, '2026-07-20', 'PIX', 'Borracharia Central', 'REC010', NULL),

-- VEÍCULO 4
(4, 'Saida', 'Combustível', 'Abastecimento Gasolina', 295.70, '2026-07-06', 'PIX', 'Posto Petrobras', 'NF0004', NULL),
(4, 'Saida', 'Multa', 'Infração de trânsito', 195.23, '2026-07-14', 'Boleto', 'Detran-SP', 'MULTA001', 'Excesso de velocidade.'),
(4, 'Saida', 'Seguro', 'Parcela do seguro', 430.00, '2026-07-21', 'Débito', 'Tokio Marine', 'SEG004', NULL),

-- VEÍCULO 5
(5, 'Saida', 'Combustível', 'Abastecimento Diesel', 402.60, '2026-07-07', 'PIX', 'Posto Ale', 'NF0005', NULL),
(5, 'Saida', 'Manutenção', 'Alinhamento e balanceamento', 240.00, '2026-07-11', 'Cartão', 'Auto Center Santos', 'OS3098', NULL),
(5, 'Saida', 'Licenciamento', 'Licenciamento anual', 185.30, '2026-07-16', 'PIX', 'Detran-SP', 'LIC2026-05', NULL),
(5, 'Entrada', 'Reembolso', 'Reembolso de despesas de viagem', 980.00, '2026-07-22', 'Transferência', 'Cliente Beta', 'REC020', 'Reembolso aprovado.');

-- =====================================================
-- INSERÇAO DE DADOS DE OBRA
-- =====================================================
INSERT INTO obra (
    idCliente,idResponsavel,dataInicio,dataFim,status,estado,cidade,cep,
    logradouro,endereco,numero,complemento,contrato,valorContratado,observacoes
) VALUES
-- Cliente 1: Américo Magalhães Moralles
(1,5, '2026-01-15 08:00:00', NULL, 'Em andamento', 'SP', 'Suzano', '08512000',
'Rua Americana', 'Galpão Industrial Moralles', '88', NULL, 'Obra 1', 158000.00,
'Ampliação de rede elétrica: Instalação elétrica de área fabril'),
-- Cliente 2: Gabriella Guimarães
(2,2, '2025-09-10 07:30:00', '2026-03-20 17:00:00', 'Concluída', 'SP', 'Mogi Mirim', '13800005',
'Avenida Lunares', 'Centro Administrativo Guimarães', '888', NULL, 'Obra 2',351000.00,
'Modernização elétrica: Troca completa de quadros e cabeamento'),
-- Cliente 3: Maria Luiza Moralles Gomes
(3,5, '2026-05-01 09:00:00', NULL, 'Em andamento', 'SP', 'Bertioga', '11250000',
'Avenida Riviera', 'Condomínio Riviera Business', '108', 'Bloco B', 'Obra 3',1781000.00,
'Expansão da Infraestrutura: Instalação elétrica de novo bloco comercial'),
-- Cliente 4: Giovanni Henrique Muniz Gonçalves Lemos
(4,2, '2026-04-10 08:00:00', NULL, 'Em andamento', 'SP', 'Guarujá', '11410002',
'Rua da Praia das Astúrias', 'Residência Particular', '10', NULL, 'Obra 4',415000.00,
'Serviço residencial: Reforma elétrica da casa de praia'),
-- Cliente 5: Julio Novares Norton
(5,5, '2026-02-03 08:30:00', NULL, 'Em andamento', 'SP', 'Americana', '13145560',
'Avenida Solares', 'Parque Empresarial Norton', '108', NULL, 'Obra 5',15000.00,
'Construção de subestação: Infraestrutura elétrica para expansão industrial');

-- =====================================================
-- INSERÇÃO DE DADOS DO FINANCEIRO-OBRA
-- =====================================================
INSERT INTO financeiroObra (idObra,descricao,categoria,valor,dataGasto,formaPagamento,fornecedor,documentoFiscal,observacao)
VALUES
-- =====================================================
-- OBRA 1 - Galpão Industrial Moralles
-- =====================================================
(1,'Compra de cabos elétricos','Material',12500.00,'2026-01-20', 'PIX', 'Eletro Comercial Suzano','NF-e 10251', 'Aquisição de cabos para instalação da área fabril'),
(1,'Quadros de distribuição','Material', 8750.00,'2026-02-05','Boleto','Distribuidora Elétrica Paulista','NF-e 18547','Compra de quadros de distribuição para ampliação da rede'),
(1,'Almoço da equipe','Alimentação', 685.50,'2026-02-18','Cartão','Restaurante Sabor de Suzano','NFC-e 45872','Alimentação da equipe durante execução dos serviços'),
(1,'Locação de plataforma elevatória','Equipamento',3200.00,'2026-03-02','Transferência','Suzano Locações','NF-e 7895','Locação de plataforma para instalação elétrica em altura'),

-- =====================================================
-- OBRA 2 - Centro Administrativo Guimarães
-- =====================================================
(2,'Compra de painéis elétricos','Material',28500.00,'2025-09-18','Boleto','Painéis Elétricos Brasil','NF-e 22541','Painéis destinados à modernização dos quadros elétricos'),
(2,'Cabos e conectores','Material',18900.00,'2025-10-08','PIX','Mogi Materiais Elétricos','NF-e 14587','Aquisição de cabos e conectores para substituição da rede'),
(2,'Hospedagem da equipe','Hospedagem',4850.00,'2025-11-15','Cartão','Hotel Central Mogi Mirim','NF-e 8754','Hospedagem da equipe técnica durante execução da obra'),
(2,'Serviço de descarte de materiais','Serviço',2150.00,'2026-01-12','Transferência','Eco Descarte Ambiental','NF-e 6521','Descarte de cabeamento e componentes elétricos substituídos'),
(2,'Alimentação da equipe','Alimentação',1250.00,'2026-03-10','Cartão','Restaurante Avenida','NFC-e 98451','Alimentação da equipe na etapa final da obra'),

-- =====================================================
-- OBRA 3 - Condomínio Riviera Business
-- =====================================================
(3,'Compra de eletrodutos','Material',22500.00,'2026-05-05','Boleto','Bertioga Materiais Elétricos','NF-e 32541','Eletrodutos para infraestrutura elétrica do novo bloco'),
(3,'Compra de cabos de potência','Material',45750.00,'2026-05-12','Transferência','Cabos Brasil Distribuidora','NF-e 78452','Cabos de potência para alimentação do novo bloco comercial'),
(3,'Locação de gerador','Equipamento',7800.00,'2026-05-20','PIX','Litoral Geradores','NF-e 11258','Gerador utilizado durante intervenção na rede principal'),
(3,'Hospedagem da equipe técnica','Hospedagem',6250.00,'2026-06-02','Cartão','Hotel Praia Bertioga','NF-e 88547','Hospedagem da equipe responsável pela instalação elétrica'),
(3,'Refeições da equipe','Alimentação',1850.00,'2026-06-10','Cartão','Restaurante Litoral','NFC-e 65874','Refeições da equipe durante execução da obra'),

-- =====================================================
-- OBRA 4 - Residência Particular
-- =====================================================
(4,'Compra de cabos residenciais','Material',9850.00,'2026-04-15','PIX','Astúrias Materiais Elétricos','NF-e 21458','Cabos destinados à reforma elétrica da residência'),
(4,'Tomadas e interruptores','Material',4250.00,'2026-04-22','Cartão','Casa Elétrica Guarujá','NF-e 33587','Compra de tomadas e interruptores para substituição'),
(4,'Quadro elétrico residencial','Material',6850.00,'2026-05-04','Boleto','Painéis Litoral','NF-e 78541','Novo quadro de distribuição da residência'),
(4,'Alimentação da equipe','Alimentação',780.00,'2026-05-15','Cartão','Restaurante Astúrias','NFC-e 11254','Alimentação da equipe durante os serviços'),

-- =====================================================
-- OBRA 5 - Parque Empresarial Norton
-- =====================================================
(5,'Projeto elétrico da subestação','Serviço',3500.00,'2026-02-10','Transferência','Engenharia Elétrica Paulista','NF-e 55874','Desenvolvimento do projeto elétrico da subestação'),
(5,'Materiais para infraestrutura','Material',4850.00,'2026-02-18','Boleto','Americana Materiais Industriais','NF-e 66541','Materiais utilizados na preparação da infraestrutura'),
(5,'Locação de equipamento','Equipamento',1250.00,'2026-03-02','PIX','Americana Locações','NF-e 77854','Locação de equipamento para execução da infraestrutura'),
(5,'Alimentação da equipe','Alimentação',450.00,'2026-03-08','Cartão','Restaurante Empresarial Norton','NFC-e 99854','Alimentação da equipe durante os serviços');

-- =====================================================
-- INSERÇAO DE DADOS DE OBRA-FUNCIONÁRIO
-- =====================================================
INSERT INTO obraFuncionario (idObra, idFuncionario) VALUES
(1, 1), -- João Pedro Silva na Obra 1
(1, 3), -- Carlos Henrique Lima na Obra 1
(2, 2), -- Maria Oliveira Souza na Obra 2
(2, 4), -- Fernanda Alves Costa na Obra 2
(3, 5); -- Lucas Martins Pereira na Obra 3

-- =====================================================
-- INSERÇAO DE DADOS DE OBRA-FUNCIONÁRIO-VEÍCULO
-- =====================================================
INSERT INTO obraFuncionarioVeiculo (idObraFuncionario, idVeiculo) VALUES
(1, 1), 
(2, 2), 
(3, 3), 
(4, 4), 
(5, 5); 

-- =====================================================
-- INSERÇAO DE DADOS DA CATEGORIA-FINANCEIRO-FUNCIONARIO
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
-- INSERÇAO DE DADOS DA FINANCEIRO-FUNCIONARIO
-- =====================================================
INSERT INTO financeiroFuncionario
(idFuncionario,idCategoria,descricao,valor,dataReferencia,formaPagamento,contaPagamento,observacao)
VALUES

-- FUNCIONÁRIO JOÃO - CLT
(1, 1, 'Salário Julho/2026', 5800.00, '2026-07-01', 'Transferência', 'Banco do Brasil', ''),
(1, 2, 'Horas Extras', 450.00, '2026-07-01', 'Transferência', 'Banco do Brasil', ''),
(1, 9, 'Desconto INSS', 621.60, '2026-07-01', 'Folha', 'Banco do Brasil', ''),
(1,10, 'Desconto IRRF', 515.33, '2026-07-01', 'Folha', 'Banco do Brasil', ''),
(1,11, 'Vale Transporte', 348.00, '2026-07-01', 'Folha', 'Banco do Brasil', ''),

-- FUNCIONÁRIA MARIA - CONTRATO TEMPORÁRIO
(2, 1, 'Salário Julho/2026', 3000.00, '2026-07-01', 'PIX', 'Caixa Econômica', ''),
(2, 5, 'Bônus por desempenho', 300.00, '2026-07-01', 'PIX', 'Caixa Econômica', ''),
(2, 9, 'Desconto INSS', 248.60, '2026-07-01', 'Folha', 'Caixa Econômica', ''),
(2,11, 'Vale Transporte',180.00, '2026-07-01', 'Folha', 'Caixa Econômica', ''),

-- FUNCIONÁRIO CARLOS - TERCEIRIZADA
(3, 8, 'Pagamento de Serviço Julho/2026', 5200.00, '2026-07-01', 'TED', 'Banco Itaú', ''),

-- FUNCIONÁRIA FERNANDA - PESSOA JURÍDICA
(4, 7, 'Pagamento NF Julho/2026', 6000.00, '2026-07-01', 'PIX', 'Banco Inter', ''),
(4, 6, 'Ajuda de Custo', 350.00, '2026-07-01', 'PIX', 'Banco Inter', ''),

-- FUNCIONÁRIO LUCAS - CLT
(5, 1, 'Salário Julho/2026', 4700.00, '2026-07-01', 'Transferência', 'Santander', ''),
(5, 5, 'Bônus', 300.00, '2026-07-01', 'Transferência', 'Santander', ''),
(5, 9, 'Desconto INSS', 467.60, '2026-07-01', 'Folha', 'Santander', ''),
(5,10, 'Desconto IRRF', 276.80, '2026-07-01', 'Folha', 'Santander', ''),
(5,11, 'Vale Transporte', 282.00, '2026-07-01', 'Folha', 'Santander', '');

-- ============================================================
-- INSERÇAO DE DADOS FUNCIONARIO-REMUNERAÇÃO (Uso no financeiro)
-- =============================================================
INSERT INTO funcionarioRemuneracao (
idFuncionario,salarioBase,planoSaude,inicioVigencia,fimVigencia)
VALUES
-- João Pedro Silva
(1, 5800.00, 550.00, '2022-03-14', NULL),
-- Maria Oliveira Souza
(2, 3900.00, 500.00, '2023-07-03', NULL),
-- Carlos Henrique Lima (Inativo)
(3, 2500.00, 400.00, '2021-01-11', '2025-12-31'),
-- Fernanda Alves Costa (PJ)
(4, 5200.00, 0.00, '2024-02-05', NULL),
-- Lucas Martins Pereira
(5, 2800.00, 450.00, '2025-01-20', NULL);


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
SELECT * FROM funcionarioRemuneracao;
SELECT * FROM financeiroFuncionario;
SELECT * FROM financeiroObra;
SELECT * FROM financeiroAutomovel;

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

SELECT idUsuario, nome, perfil
FROM usuario;

SELECT idFinanceiroObra, idObra, descricao, valor, dataGasto 
FROM financeiroObra 
ORDER BY idFinanceiroObra DESC LIMIT 1;

SELECT *
FROM financeiroAutomovel
WHERE idVeiculo = 1
AND MONTH(dataMovimentacao) = 7
AND YEAR(dataMovimentacao) = 2026;

SELECT
    idFinanceiroAutomovel,
    tipo,
    descricao,
    valor,
    dataMovimentacao
FROM financeiroAutomovel
ORDER BY idFinanceiroAutomovel DESC;

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
DESCRIBE financeiroObra;
DESCRIBE financeiroAutomovel;
DESCRIBE categoriaFinanceiroFuncionario;


SHOW COLUMNS FROM funcionario;
SHOW COLUMNS FROM financeiroObra;

 
