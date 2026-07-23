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
    perfil VARCHAR(20) NOT NULL DEFAULT 'Usuario',
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
    valorContratado DECIMAL(10,2) NOT NULL DEFAULT 0.00,
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
isResponsavel BOOLEAN NOT NULL DEFAULT FALSE,
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
-- CATEGORIA FINANCEIRA OBRA
-- =====================================================
CREATE TABLE categoriaFinanceiroObra (
    idCategoriaFinanceiroObra INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE
);

-- =====================================================
-- FINANCEIRO OBRA
-- =====================================================
CREATE TABLE financeiroObra (
    idFinanceiroObra INT AUTO_INCREMENT PRIMARY KEY,
    idObra INT NOT NULL,
    idCategoriaFinanceiroObra INT NOT NULL,
    descricao VARCHAR(100) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    dataGasto DATE NOT NULL,
    formaPagamento ENUM('Dinheiro','PIX','Cartão','Boleto','Transferência','TED'),
    observacao VARCHAR(200),
    CONSTRAINT fk_financeiroObra_obra
        FOREIGN KEY (idObra)
        REFERENCES obra(idObra),
    CONSTRAINT fk_financeiroObra_categoria
        FOREIGN KEY (idCategoriaFinanceiroObra)
        REFERENCES categoriaFinanceiroObra(idCategoriaFinanceiroObra)
);
-- =====================================================
-- CATEGORIA FINANCEIRA VEICULO
-- =====================================================
CREATE TABLE categoriaFinanceiroVeiculo (
    idCategoriaFinanceiroVeiculo INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    tipo ENUM('ENTRADA','SAIDA') NOT NULL,
    ativo BOOLEAN NOT NULL DEFAULT TRUE,
    UNIQUE (nome, tipo) -- PERMITE OUTROS PARA SAÍDA E ENTRADA --
);

-- =====================================================
-- FINANCEIRO VEÍCULO
-- =====================================================
CREATE TABLE financeiroVeiculo (
    idFinanceiroVeiculo INT AUTO_INCREMENT PRIMARY KEY,
    idVeiculo INT NOT NULL,
    idCategoriaFinanceiroVeiculo INT NOT NULL,
    descricao VARCHAR(100) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    dataMovimentacao DATE NOT NULL,
    formaPagamento ENUM('Dinheiro','PIX','Cartão','Boleto','Transferência','TED'),
    observacao VARCHAR(200),
    CONSTRAINT fk_financeiroAutomovel_veiculo
        FOREIGN KEY (idVeiculo)
        REFERENCES veiculo(idVeiculo),
	CONSTRAINT fk_financeiroAutomovel_categoria
    FOREIGN KEY (idCategoriaFinanceiroVeiculo)
    REFERENCES categoriaFinanceiroVeiculo(idCategoriaFinanceiroVeiculo)

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
 
 
-- =====================================================
-- RECUPERAR SENHA
-- =====================================================
CREATE TABLE recuperacaoSenha (
    idRecuperacao INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(150) NOT NULL,
    codigo VARCHAR(6) NOT NULL,
    expiraEm DATETIME NOT NULL,
    usado BOOLEAN NOT NULL DEFAULT FALSE,
    criadoEm TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
);
 
-- ==================================================================================================================
-- INSERÇAO DE DADOS DE USUÁRIOS - SENHA PADRÃO: 1234 = $2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2
-- ==================================================================================================================
INSERT INTO usuario (perfil,nome, email, senha) VALUES
('Administrador','Ideal','emailteste@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Usuario','Juliana','jujusantista23@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Usuario','Douglas','euocas.co@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Usuario','Matheus','matheusguida08@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Usuario','Camila', 'camila.macedomendes@outlook.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Usuario','Francielly','franciellym.ferreira15@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Usuario','Danilo', 'daniloremonti23409@gmail.com', '$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Usuario','Alexandre', 'alexandrecardoso590@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
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
'ativo', 'Atendimento interno e suporte aos usuários.'),

('Antonio Americo Bilhões', '1984-03-18', 'Masculino', 'Santos', 'SP', '39433845005',
'Rua', 'Rua Alexandre Martins', '210', NULL, 'Santos', '11025001', 'SP',
'antonio.bilhoes@empresa.com', 'Eletricista Industrial', 'CLT',
'2022-05-16', NULL, '2027-03-10',
'1025', '45678', 'CORRENTE', '39433845005',
'ativo', 'Responsável por instalações elétricas industriais.'),

('Levi Guimarães Moralles', '1991-09-07', 'Masculino', 'São Vicente', 'SP', '62047999081',
'Avenida', 'Avenida Capitão-Mor Aguiar', '480', 'Sala 2', 'São Vicente', '11310040', 'SP',
'levi.moralles@empresa.com', 'Encarregado de Obras Elétricas', 'CONTRATO TEMPORARIO',
'2024-01-08', NULL, '2026-11-22',
'2034', '98765', 'CORRENTE', 'levi.moralles@empresa.com',
'ativo', 'Responsável pela coordenação das equipes de campo.'),

('Antonelli Nunes Mercedes', '1987-12-11', 'Feminino', 'Praia Grande', 'SP', '44444813075',
'Rua', 'Rua Xixová', '155', NULL, 'Praia Grande', '11701010', 'SP',
'antonelli.mercedes@empresa.com', 'Analista Financeiro', 'PESSOA JURÍDICA',
'2023-06-12', NULL, '2026-10-18',
'3098', '65432', 'POUPANCA', 'antonelli.mercedes@empresa.com',
'ativo', 'Responsável pelo acompanhamento financeiro da empresa.'),

('Veronica Muniz', '1994-05-28', 'Feminino', 'Cubatão', 'SP', '82395630071',
'Rua', 'Rua São Paulo', '890', 'Casa', 'Cubatão', '11510000', 'SP',
'veronica.muniz@empresa.com', 'Cabista', 'TERCEIRIZADO',
'2021-09-20', NULL, '2026-12-05',
'4156', '74125', 'SALARIO', '82395630071',
'ativo', 'Executa instalações e manutenção de cabeamento estruturado.'),

('Maria Julia Nascimento Silva', '1998-07-14', 'Feminino', 'Guarujá', 'SP', '37040495066',
'Avenida', 'Avenida Puglisi', '325', 'Apto 81', 'Guarujá', '11410100', 'SP',
'maria.nascimento@empresa.com', 'Auxiliar Administrativo', 'CLT',
'2025-02-03', NULL, '2027-02-15',
'5280', '96325', 'CORRENTE', '37040495066',
'ativo', 'Auxilia nas rotinas administrativas e atendimento interno.');


-- =====================================================
-- INSERÇAO DE DADOS DE CONTATO DE FUNCIONÁRIOS
-- =====================================================
INSERT INTO contatoFuncionario (idFuncionario, telefone, whatsapp) VALUES
(1, '13990001136', '13990001136'),
(2, '11987654321', '11987654321'),
(3, '21991234567', '21991234567'),
(4, '31999887766', '31999887766'),
(5, '41995554433', '41995554433'),
(6, '13991112222', '13991112222'),
(7, '13992223333', '13992223333'),
(8, '13993334444', '13993334444'),
(9, '13994445555', '13994445555'),
(10, '13995556666', '13995556666');
 
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
'Pessoa Jurídica', 'Avenida', 'Solares', '108', NULL, 'Americana', '13145560', 'SP', 'Responsável pela empresa.'),

('Adriano Nunes Antunes', NULL, '69076964000110', 'adriano.antunes@empresa.com',
'Pessoa Jurídica', 'Rua', 'das Palmeiras', '120', NULL, 'Santos', '11055000', 'SP','Responsável pela empresa.'),

('Manuel Luiz Souza', NULL, '79074414000115', 'manuel.souza@empresa.com',
'Pessoa Jurídica', 'Avenida', 'Conselheiro Nébias', '450', 'Sala 4', 'Santos', '11045002', 'SP','Responsável pela empresa.'),

('Eduardo Brasil da Silva', NULL, '91056299000151', 'eduardo.silva@empresa.com',
'Pessoa Jurídica', 'Rua', 'Dom Pedro II', '98', NULL, 'São Vicente', '11320000', 'SP','Responsável pela empresa.'),

('Silvia Escola Rosa', NULL, '75476841000179', 'silvia.rosa@empresa.com',
'Pessoa Jurídica', 'Avenida', 'Presidente Wilson', '765', NULL, 'Praia Grande', '11701000', 'SP','Responsável pela empresa.'),

('Poliana Miranda Nunes dos Santos', '85191594002', NULL, 'poliana.santos@email.com',
'Pessoa Física', 'Rua', 'das Acácias', '55', NULL, 'Guarujá', '11430000', 'SP','Cliente residencial.'),

('Gabriella e Pinho Gonçalves', NULL, '26070230000110', 'gabriella.pinho@empresa.com',
'Pessoa Jurídica', 'Rua', 'Professor Toledo', '230', NULL, 'Cubatão', '11510020', 'SP','Responsável pela empresa.'),

('Ramon Gonzallez', '93531051024', NULL, 'ramon.gonzallez@email.com',
'Pessoa Física', 'Avenida', 'Marechal Deodoro', '1020', 'Casa', 'Santos', '11010000', 'SP','Serviços elétricos residenciais.'),

('Leo Fabiano Silva Santos', NULL, '36598287000140', 'leo.santos@empresa.com',
'Pessoa Jurídica', 'Rua', 'XV de Novembro', '315', NULL, 'São Vicente', '11310010', 'SP','Responsável pela empresa.'),

('Gabriel Mesquita Novaes', NULL, '16139987000160', 'gabriel.novaes@empresa.com',
'Pessoa Jurídica', 'Avenida', 'Ana Costa', '890', 'Sala 10', 'Santos', '11060002', 'SP','Responsável pela empresa.'),

('Ana Paula Honk Shin', NULL, '30893369000131', 'ana.shin@empresa.com',
'Pessoa Jurídica', 'Rua', 'Rio Branco', '500', NULL, 'Praia Grande', '11702000', 'SP','Responsável pela empresa.');
 
-- =====================================================
-- INSERÇAO DE CONTATO DE CLIENTES
-- =====================================================
INSERT INTO contatoCliente (idCliente, telefone, whatsapp) VALUES
(1, '13917403219', '13917403219'),
(2, '21992234567', '21992234567'),
(3, '13993330003', '13993330003'),
(4, '13993330004', '13993330004'),
(5, '19993330005', '19993330005'),
(6, '13993330006', '13993330006'),
(7, '13993330007', '13993330007'),
(8, '13993330008', '13993330008'),
(9, '13993330009', '13993330009'),
(10, '13993330010', '13993330010'),
(11, '13993330011', '13993330011'),
(12, '13993330012', '13993330012'),
(13, '13993330013', '13993330013'),
(14, '13993330014', '13993330014'),
(15, '13993330015', '13993330015');
 
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
'Veículo utilizado para visitas externas.'),

(6, '56248391740', 'QWE2F34', '9BGKT08GPPC302145',
'Chevrolet', 'Montana', 2023, 2024, 'Branco', 'ATIVO', 'PROPRIO',
23850, '2025-03-10', '2026-03-10', 'Empresa IDEAL Soluções Elétricas',
'Antonio Americo Bilhões', 1,
'Veículo utilizado para transporte de ferramentas e materiais elétricos.');


-- =====================================================
-- INSERÇAO DE DADOS DE OBRA
-- =====================================================
INSERT INTO obra (
    idCliente,dataInicio,dataFim,status,estado,cidade,cep,
    logradouro,endereco,numero,complemento,contrato, valorContratado,observacoes
) VALUES

-- Cliente 1
(1, '2026-01-15 08:00:00', NULL, 'Em andamento', 'SP', 'Suzano', '08512000',
'Rua Americana', 'Galpão Industrial Moralles', '88', NULL, 'Obra 1',85000.00,'Ampliação da rede elétrica da área fabril.'),

-- Cliente 2
(2, '2025-09-10 07:30:00', '2026-03-20 17:00:00', 'Concluída', 'SP', 'Mogi Mirim', '13800005','Avenida Lunares', 'Centro Administrativo Guimarães', '888', NULL, 'Obra 2',
125000.00,'Modernização elétrica com troca de quadros e cabeamento.'),

-- Cliente 3
(3, '2026-05-01 09:00:00', NULL, 'Em andamento', 'SP', 'Bertioga', '11250000','Avenida Riviera', 'Condomínio Riviera Business', '108', 'Bloco B', 'Obra 3',98000.00,'Expansão da infraestrutura elétrica de novo bloco comercial.'),

-- Cliente 4
(4, '2026-04-10 08:00:00', NULL, 'Em andamento', 'SP', 'Guarujá', '11410002','Rua da Praia das Astúrias', 'Residência Particular', '10', NULL, 'Obra 4',42000.00,'Reforma elétrica completa da residência de praia.'),

-- Cliente 5
(5, '2026-02-03 08:30:00', NULL, 'Em andamento', 'SP', 'Americana', '13145560',
'Avenida Solares', 'Parque Empresarial Norton', '108', NULL, 'Obra 5',310000.00,'Construção de subestação elétrica para expansão industrial.'),

-- Cliente 6
(6, '2026-03-12 08:00:00', NULL, 'Em andamento', 'SP', 'Santos', '11055000','Rua das Palmeiras', 'Centro Comercial Antunes', '120', NULL, 'Obra 6',165000.00,'Instalação elétrica completa de prédio comercial.'),

-- Cliente 7
(7, '2025-11-18 07:30:00', '2026-04-28 17:30:00', 'Concluída', 'SP', 'Santos', '11045002',
'Avenida Conselheiro Nébias', 'Edifício Souza Empresarial', '450', 'Sala 4', 'Obra 7',93000.00,'Substituição dos quadros elétricos e adequação à NR-10.'),

-- Cliente 8
(8, '2026-06-02 08:30:00', NULL, 'Em andamento', 'SP', 'São Vicente', '11320000','Rua Dom Pedro II', 'Galpão Brasil Logística', '98', NULL, 'Obra 8',
118000.00,'Instalação de iluminação industrial em centro logístico.'),

-- Cliente 9
(9, '2026-02-20 09:00:00', NULL, 'Em andamento', 'SP', 'Praia Grande', '11701000',
'Avenida Presidente Wilson', 'Escola Rosa', '765', NULL, 'Obra 9',76000.00,'Reforma das instalações elétricas e iluminação da escola.'),

-- Cliente 10
(10, '2026-05-15 08:00:00', '2026-06-05 16:30:00', 'Concluída', 'SP', 'Guarujá', '11430000',
'Rua das Acácias', 'Residência Poliana', '55', NULL, 'Obra 10',38500.00,'Reforma elétrica residencial com instalação de novos circuitos.'),

-- Cliente 11
(11, '2026-07-01 08:00:00', NULL, 'Em andamento', 'SP', 'Cubatão', '11510020',
'Rua Professor Toledo', 'Centro Empresarial Gonçalves', '230', NULL, 'Obra 11',247000.00,'Execução da infraestrutura elétrica de prédio corporativo.'),

-- Cliente 12
(12, '2026-03-25 08:00:00', '2026-04-15 17:00:00', 'Concluída', 'SP', 'Santos', '11010000',
'Avenida Marechal Deodoro', 'Residência Ramon Gonzallez', '1020', 'Casa', 'Obra 12',29500.00,'Modernização da instalação elétrica residencial.'),

-- Cliente 13
(13, '2026-08-10 07:30:00', NULL, 'Em andamento', 'SP', 'São Vicente', '11310010',
'Rua XV de Novembro', 'Complexo Empresarial Leo Santos', '315', NULL, 'Obra 13',189000.00,'Instalação elétrica e iluminação de novo centro comercial.'),

-- Cliente 14
(14, '2026-09-05 08:30:00', NULL, 'Em andamento', 'SP', 'Santos', '11060002',
'Avenida Ana Costa', 'Edifício Novaes', '890', 'Sala 10', 'Obra 14',97000.00,'Montagem da infraestrutura elétrica e sistema de emergência.'),

-- Cliente 15
(15, '2026-10-01 08:00:00', NULL, 'Em andamento', 'SP', 'Praia Grande', '11702000',
'Rua Rio Branco', 'Centro Comercial Shin', '500', NULL, 'Obra 15',156000.00,'Instalação elétrica completa para centro comercial de médio porte.');

-- =====================================================
-- INSERÇAO DE DADOS DE OBRA FUNCIONÁRIO
-- =====================================================
INSERT INTO obraFuncionario (idFuncionario, idObra, isResponsavel) VALUES
(1, 1, TRUE),
(2, 2, TRUE),
(3, 3, TRUE),
(4, 4, TRUE),
(5, 5, TRUE),
(6, 6, TRUE),
(7, 7, TRUE),
(8, 8, TRUE),
(9, 9, TRUE),
(10, 10, TRUE);

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
-- ENTRADAS
('Salario', 'ENTRADA', 'CLT'),
('Ferias', 'ENTRADA', 'CLT'),
('Horas Extras', 'ENTRADA', 'CLT'),
('Periculosidade', 'ENTRADA', 'CLT'),
('13º Salario', 'ENTRADA', 'CLT'),
('Insalubridade', 'ENTRADA', 'CLT'),
('Comissao', 'ENTRADA', 'CLT'),
('Participacao nos Lucros', 'ENTRADA', 'CLT'),
('Bônus', 'ENTRADA', 'TODOS'),
('Ajuda de Custo', 'ENTRADA', 'TODOS'),
('Adicional Noturno', 'ENTRADA', 'CLT'),
('Outros', 'ENTRADA', 'TODOS'),
('Pagamento NF', 'ENTRADA', 'PESSOA JURÍDICA'),
('Pagamento Servico', 'ENTRADA', 'TERCEIRIZADO'),

-- SAÍDAS
('INSS', 'SAIDA', 'CLT'),
('IRRF', 'SAIDA', 'CLT'),
('Faltas', 'SAIDA', 'CLT'),
('Atrasos', 'SAIDA', 'CLT'),
('Vale Transporte', 'SAIDA', 'CLT'),
('Plano de Saúde', 'SAIDA', 'CLT'),
('Plano Odontológico', 'SAIDA', 'CLT'),
('Adiantamento Salarial', 'SAIDA', 'CLT'),
('Pensão Alimentícia', 'SAIDA', 'CLT'),
('Outros', 'SAIDA', 'TODOS'),
('Empréstimo', 'SAIDA', 'TODOS');

-- =====================================================
-- INSERÇAO DE DADOS DA FINANCEIRO FUNCIONARIO
-- =====================================================
INSERT INTO financeiroFuncionario
(idFuncionario, idCategoria, descricao, valor, dataReferencia, formaPagamento, contaPagamento, observacao)
VALUES
-- João Pedro Silva (CLT)
(1, 1, 'Salário Julho/2026', 5800.00, '2026-07-01', 'Transferência', 'Banco do Brasil', ''),
(1, 2, 'Horas Extras', 450.00, '2026-07-01', 'Transferência', 'Banco do Brasil', ''),
(1, 9, 'Desconto INSS', 640.00, '2026-07-01', 'Folha', 'Banco do Brasil', ''),
(1,10, 'Desconto IRRF', 285.00, '2026-07-01', 'Folha', 'Banco do Brasil', ''),
(1,11, 'Vale Transporte', 220.00, '2026-07-01', 'Folha', 'Banco do Brasil', ''),

-- Maria Oliveira Souza (Contrato Temporário)
(2, 1, 'Salário Julho/2026', 4700.00, '2026-07-01', 'PIX', 'Caixa Econômica', ''),
(2, 5, 'Bônus por desempenho', 300.00, '2026-07-01', 'PIX', 'Caixa Econômica', ''),
(2, 9, 'Desconto INSS', 515.00, '2026-07-01', 'Folha', 'Caixa Econômica', ''),
(2,11, 'Vale Transporte', 180.00, '2026-07-01', 'Folha', 'Caixa Econômica', ''),

-- Carlos Henrique Lima (Terceirizado)
(3, 8, 'Pagamento de Serviço Julho/2026', 5200.00, '2026-07-01', 'TED', 'Banco Itaú', ''),

-- Fernanda Alves Costa (Pessoa Jurídica)
(4, 7, 'Pagamento NF Julho/2026', 6000.00, '2026-07-01', 'PIX', 'Banco Inter', ''),
(4, 6, 'Ajuda de Custo', 350.00, '2026-07-01', 'PIX', 'Banco Inter', ''),

-- Lucas Martins Pereira (CLT)
(5, 1, 'Salário Julho/2026', 4500.00, '2026-07-01', 'Transferência', 'Santander', ''),
(5, 5, 'Bônus', 250.00, '2026-07-01', 'Transferência', 'Santander', ''),
(5, 9, 'Desconto INSS', 495.00, '2026-07-01', 'Folha', 'Santander', ''),
(5,11, 'Vale Transporte', 180.00, '2026-07-01', 'Folha', 'Santander', ''),

-- Antonio Americo Bilhões (CLT)
(6, 1, 'Salário Julho/2026', 5600.00, '2026-07-01', 'Transferência', 'Banco do Brasil', ''),
(6, 2, 'Horas Extras', 380.00, '2026-07-01', 'Transferência', 'Banco do Brasil', ''),
(6, 9, 'Desconto INSS', 615.00, '2026-07-01', 'Folha', 'Banco do Brasil', ''),
(6,10, 'Desconto IRRF', 240.00, '2026-07-01', 'Folha', 'Banco do Brasil', ''),
(6,11, 'Vale Transporte', 220.00, '2026-07-01', 'Folha', 'Banco do Brasil', ''),

-- Levi Guimarães Moralles (Contrato Temporário)
(7, 1, 'Salário Julho/2026', 4900.00, '2026-07-01', 'PIX', 'Caixa Econômica', ''),
(7, 5, 'Bônus por desempenho', 450.00, '2026-07-01', 'PIX', 'Caixa Econômica', ''),
(7, 9, 'Desconto INSS', 540.00, '2026-07-01', 'Folha', 'Caixa Econômica', ''),
(7,11, 'Vale Transporte', 180.00, '2026-07-01', 'Folha', 'Caixa Econômica', ''),

-- Antonelli Nunes Mercedes (Pessoa Jurídica)
(8, 7, 'Pagamento NF Julho/2026', 7200.00, '2026-07-01', 'PIX', 'Banco Inter', ''),
(8, 6, 'Ajuda de Custo', 450.00, '2026-07-01', 'PIX', 'Banco Inter', ''),

-- Veronica Muniz (Terceirizado)
(9, 8, 'Pagamento de Serviço Julho/2026', 4800.00, '2026-07-01', 'TED', 'Banco Itaú', ''),

-- Maria Julia Nascimento Silva (CLT)
(10, 1, 'Salário Julho/2026', 3900.00, '2026-07-01', 'Transferência', 'Santander', ''),
(10, 2, 'Horas Extras', 290.00, '2026-07-01', 'Transferência', 'Santander', ''),
(10, 9, 'Desconto INSS', 430.00, '2026-07-01', 'Folha', 'Santander', ''),
(10,11, 'Vale Transporte', 180.00, '2026-07-01', 'Folha', 'Santander', '');


-- =====================================================
-- INSERÇÃO DE DADOS DA CATEGORIA FINANCEIRO OBRA
-- ======================================================
INSERT INTO categoriaFinanceiroObra (nome) VALUES
('Material Eletrico'),
('Material de Construcao'),
('Ferramentas'),
('Equipamentos'),
('Locação'),
('Mao de Obra'),
('Terceirizados'),
('Transporte'),
('Combustível'),
('Alimentação'),
('Hospedagem'),
('EPIs'),
('Licenças e Taxas'),
('Impostos'),
('Outros');

-- =====================================================
-- INSERÇÃO DE DADOS DA CATEGORIA FINANCEIRO VEICULO
-- ======================================================

INSERT INTO categoriaFinanceiroVeiculo (nome, tipo) VALUES
-- ==========================
-- ENTRADAS (RECEBIMENTOS)
-- ==========================
('Bonificacao / Cashback', 'ENTRADA'),
('Reembolso', 'ENTRADA'),
('Venda de Peças', 'ENTRADA'),
('Venda de Pneus', 'ENTRADA'),
('Venda do Veículo', 'ENTRADA'),
('Indenização de Seguro', 'ENTRADA'),
('Outros', 'ENTRADA'),

-- ==========================
-- SAÍDAS (GASTOS)
-- ==========================
('Acessorios', 'SAIDA'),
('Combustivel', 'SAIDA'),
('Documentacao', 'SAIDA'),
('Estacionamento', 'SAIDA'),
('IPVA', 'SAIDA'),
('Lavagem', 'SAIDA'),
('Licenciamento', 'SAIDA'),
('Manutencao', 'SAIDA'),
('Multa', 'SAIDA'),
('Outros', 'SAIDA'),
('Peças', 'SAIDA'),
('Pedagio', 'SAIDA'),
('Pneus', 'SAIDA'),
('Seguro', 'SAIDA'),
('Troca de Oleo', 'SAIDA');

-- =====================================================
-- DADOS INICIAIS - FINANCEIRO OBRA
-- =====================================================
INSERT INTO financeiroObra (
    idObra, idCategoriaFinanceiroObra, descricao, valor, dataGasto,formaPagamento, observacao
) VALUES

(1, 1, 'Compra de cabos elétricos', 2850.00, '2026-07-01', 'PIX', 'Fornecedor Elétrica Santos'),
(1, 2, 'Compra de cimento e areia', 1450.00, '2026-07-03', 'Boleto', NULL),
(1, 6, 'Pagamento da equipe', 6200.00, '2026-07-05', 'Transferência', 'Primeira quinzena'),
(1, 4, 'Locação de plataforma elevatória', 1800.00, '2026-07-07', 'PIX', NULL),
(1, 12, 'Compra de capacetes e luvas', 890.00, '2026-07-08', 'Cartão', NULL),
(1, 8, 'Frete de materiais', 380.00, '2026-07-09', 'Dinheiro', NULL),
(1, 9, 'Abastecimento do caminhão', 420.00, '2026-07-10', 'Cartão', NULL),
(1, 13, 'Taxa de licença municipal', 650.00, '2026-07-11', 'PIX', NULL);

INSERT INTO financeiroVeiculo
(idVeiculo, idCategoriaFinanceiroVeiculo, descricao, valor, dataMovimentacao, formaPagamento, observacao)
VALUES
-- =====================================================
-- VEÍCULO 1 - ABC1D23
-- =====================================================
(1, 9,'Abastecimento',320.00,'2026-07-03','Cartão',''),
(1, 15,'Troca de óleo',480.00,'2026-07-08','PIX',''),
(1, 19,'Pedágio',42.80,'2026-07-10','Dinheiro',''),
(1, 1, 'Bonificação Cashback',120.00,'2026-07-15','PIX',''),

-- =====================================================
-- VEÍCULO 2 - AFC1D28
-- =====================================================
(2, 9, 'Abastecimento',410.00, '2026-07-04', 'Cartão', ''),
(2, 21,'Seguro Mensal',285.00, '2026-07-09', 'PIX', ''),
(2, 13,'Lavagem Completa', 65.00, '2026-07-13', 'Dinheiro', ''),
(2, 2,'Reembolso de Viagem',350.00, '2026-07-18', 'Transferência', ''),

-- =====================================================
-- VEÍCULO 3 - ADC1K28
-- =====================================================
(3, 9,'Abastecimento',430.00, '2026-07-05', 'Cartão', ''),
(3, 15,'Manutenção Preventiva',820.00, '2026-07-11', 'PIX', ''),
(3, 16,'Multa de trânsito',195.00, '2026-07-16', 'Boleto', ''),
(3, 1,'Cashback Combustível',150.00, '2026-07-22', 'PIX', ''),

-- =====================================================
-- VEÍCULO 4 - AFJ1D28
-- =====================================================
(4, 18, 'Troca de Pastilhas',360.00, '2026-07-02', 'PIX', ''),
(4, 9,  'Abastecimento',390.00, '2026-07-07', 'Cartão', ''),
(4, 20, 'Compra de Pneus',1650.00, '2026-07-14', 'Boleto', ''),
(4, 2,  'Reembolso Seguro',780.00, '2026-07-25', 'TED', ''),

-- =====================================================
-- VEÍCULO 5 - LFD1D28
-- =====================================================
(5, 9,  'Abastecimento',305.00, '2026-07-06', 'Cartão', ''),
(5, 13, 'Lavagem', 55.00, '2026-07-09', 'Dinheiro', ''),
(5, 11, 'Estacionamento', 40.00, '2026-07-17', 'Dinheiro', ''),
(5, 6,  'Indenização Seguro',1200.00, '2026-07-28', 'TED', ''),

-- =====================================================
-- VEÍCULO 6 - QWE2F34
-- =====================================================
(6, 9,  'Abastecimento',520.00, '2026-07-01', 'Cartão', ''),
(6, 15, 'Revisão Completa',1350.00, '2026-07-08', 'PIX', ''),
(6, 21, 'Seguro',420.00, '2026-07-18', 'PIX', ''),
(6, 5,  'Venda do Veículo Antigo',5800.00, '2026-07-30', 'Transferência', '');

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
SELECT * FROM categoriaFinanceiroObra;
SELECT * FROM categoriaFinanceiroVeiculo;
SELECT * FROM financeiroObra;
SELECT * FROM usuario WHERE idUsuario = 2;
SELECT * FROM financeiroVeiculo;


SELECT idObra, contrato, valorContratado
FROM obra;
 
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
-- CONTADOR 
-- =====================================================
SELECT COUNT(*) AS total_funcionarios
FROM funcionario  where  status = 'ativo';

SELECT COUNT(*) AS total_obras
FROM obra;

SELECT COUNT(*) AS total_clientes
FROM cliente;

SELECT COUNT(*) AS total_usuarios
FROM usuario;


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
DESCRIBE obraFuncionario;
DESCRIBE financeiroVeiculo;
DESCRIBE categoriaFinanceiroVeiculo;
SHOW COLUMNS FROM funcionario;
SHOW COLUMNS FROM financeiroObra;

SHOW TABLES;

 
SELECT idFinanceiroObra, idObra, descricao, valor, dataGasto 
FROM financeiroObra 
ORDER BY idFinanceiroObra DESC LIMIT 1;