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
    sexo ENUM('Masculino', 'Feminino', 'Outro') NOT NULL,
    naturalidade VARCHAR(100) NOT NULL,
    estadoNascimento VARCHAR(10)NOT NULL,
    cpf CHAR(11) UNIQUE NOT NULL,
    tipoLogradouro VARCHAR(15) NOT NULL,
    nomeLogradouro VARCHAR(100) NOT NULL,
    numero VARCHAR(6) NOT NULL,
    complemento VARCHAR(30),
    cidade VARCHAR(100)NOT NULL,
    cep CHAR(8)NOT NULL,
    estado CHAR(2)NOT NULL,
    email VARCHAR(150),
    cargoFuncao VARCHAR(100) NOT NULL,
    tipoContrato ENUM('CLT', 'CONTRATO TEMPORARIO', 'PESSOA JURÍDICA', 'TERCEIRIZADO') NOT NULL,
    dataAdmissao DATE NOT NULL, 
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
    tipoCliente ENUM('Pessoa Física', 'Pessoa Jurídica') NOT NULL,
    tipoLogradouro VARCHAR(15) NOT NULL,
    nomeLogradouro VARCHAR(100) NOT NULL,
    numero VARCHAR(6) NOT NULL,
    complemento VARCHAR(30),
    cidade VARCHAR(100) NOT NULL,
    cep CHAR(8) NOT NULL,
    estado CHAR(2) NOT NULL,
    observacoes TEXT,
    CONSTRAINT chk_documento
        CHECK (cpf IS NOT NULL OR cnpj IS NOT NULL)
);
 
-- =====================================================
-- CONTATO CLIENTE
-- =====================================================
CREATE TABLE contatoCliente (
    idContato INT AUTO_INCREMENT PRIMARY KEY,
    idCliente INT NOT NULL,
    telefone VARCHAR(20)NOT NULL,
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
    bairro VARCHAR(60) NOT NULL,
    cep CHAR(8) NOT NULL,
    tipoLogradouro VARCHAR(15) NOT NULL,
    nomeLogradouro VARCHAR(100) NOT NULL,
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
('Usuario','Senac', 'senacsantos@gmail.com','$2a$12$0O1dCY1Z2WIV5JxmlK.UZ.kbuWliW5pyMS7jLpZeAj3UmC9B3mCf2'),
('Aluno', 'Visitante','visitante.ideal@gmail.com','$2a$12$wEt6R7XrUwVL/QOmvIW0k.nje3A8Cl/Vxpksvo2z/TojUSgXOdjp2');
 
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
'Avenida', 'Ana Costa', '120', 'Apto 12', 'Santos', '11060001', 'SP',
'joao.silva@empresa.com', 'Analista Financeiro', 'CLT',
'2022-03-14', NULL, '2026-12-10',
'1234', '12345', 'CORRENTE', '58058711063',
'ativo', 'Funcionário experiente em desenvolvimento web.'),

('Maria Oliveira Souza', '1988-11-23', 'Feminino', 'São Vicente', 'SP', '98765432100',
'Rua', 'Frei Gaspar', '450', NULL, 'São Vicente', '11310000', 'SP',
'maria.souza@empresa.com', 'Recursos Humanos', 'CONTRATO TEMPORARIO',
'2023-07-03', NULL, '2026-09-15',
'2100', '98765', 'POUPANCA', 'maria.souza@empresa.com',
'ativo', 'Atua no recrutamento e seleção.'),

('Carlos Henrique Lima', '1995-02-17', 'Masculino', 'Praia Grande', 'SP', '61841080004',
'Avenida', 'Presidente Kennedy', '890', 'Sala 3', 'Praia Grande', '11700000', 'SP',
'carlos.lima@empresa.com', 'Auxiliar Administrativo', 'TERCEIRIZADO',
'2021-01-11', NULL, '2026-11-03',
'3050', '45678', 'SALARIO', '61999998888',
'inativo', 'Contrato encerrado em 2025.'),

('Fernanda Alves Costa', '1992-08-30', 'Feminino', 'Guarujá', 'SP', '65396386045',
'Rua', 'Mário Ribeiro', '77', NULL, 'Guarujá', '11410000', 'SP',
'fernanda.costa@empresa.com', 'Cabista', 'PESSOA JURÍDICA',
'2024-02-05', NULL, '2026-08-18',
'4102', '32145', 'CORRENTE', 'fernanda.costa@empresa.com',
'ativo', 'Responsável por cabeamento das empresas.'),

('Lucas Martins Pereira', '2000-01-10', 'Outro', 'Cubatão', 'SP', '15935745682',
'Travessa', 'Das Flores', '15', 'Casa', 'Cubatão', '11500000', 'SP',
'lucas.pereira@empresa.com', 'Instalador Elétrico', 'CLT',
'2025-01-20', NULL, '2027-01-12',
'5501', '78945', 'CORRENTE', '15935745682',
'ativo', 'Atendimento interno e suporte aos usuários.'),

('Antonio Americo Bilhões', '1984-03-18', 'Masculino', 'Santos', 'SP', '39433845005',
'Rua', 'Alexandre Martins', '210', NULL, 'Santos', '11025001', 'SP',
'antonio.bilhoes@empresa.com', 'Eletricista Industrial', 'CLT',
'2022-05-16', NULL, '2027-03-10',
'1025', '45678', 'CORRENTE', '39433845005',
'ativo', 'Responsável por instalações elétricas industriais.'),

('Levi Guimarães Moralles', '1991-09-07', 'Masculino', 'São Vicente', 'SP', '62047999081',
'Avenida', 'Capitão-Mor Aguiar', '480', 'Sala 2', 'São Vicente', '11310040', 'SP',
'levi.moralles@empresa.com', 'Encarregado de Obras Elétricas', 'CONTRATO TEMPORARIO',
'2024-01-08', NULL, '2026-11-22',
'2034', '98765', 'CORRENTE', 'levi.moralles@empresa.com',
'ativo', 'Responsável pela coordenação das equipes de campo.'),

('Antonelli Nunes Mercedes', '1987-12-11', 'Feminino', 'Praia Grande', 'SP', '44444813075',
'Rua', 'Xixová', '155', NULL, 'Praia Grande', '11701010', 'SP',
'antonelli.mercedes@empresa.com', 'Analista Financeiro', 'PESSOA JURÍDICA',
'2023-06-12', NULL, '2026-10-18',
'3098', '65432', 'POUPANCA', 'antonelli.mercedes@empresa.com',
'ativo', 'Responsável pelo acompanhamento financeiro da empresa.'),

('Veronica Muniz', '1994-05-28', 'Feminino', 'Cubatão', 'SP', '82395630071',
'Rua', 'São Paulo', '890', 'Casa', 'Cubatão', '11510000', 'SP',
'veronica.muniz@empresa.com', 'Cabista', 'TERCEIRIZADO',
'2021-09-20', NULL, '2026-12-05',
'4156', '74125', 'SALARIO', '82395630071',
'ativo', 'Executa instalações e manutenção de cabeamento estruturado.'),

('Maria Julia Nascimento Silva', '1998-07-14', 'Feminino', 'Guarujá', 'SP', '37040495066',
'Avenida', 'Puglisi', '325', 'Apto 81', 'Guarujá', '11410100', 'SP',
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
-- INSERÇÃO DE DADOS DE OBRA
-- =====================================================

INSERT INTO obra (
    idCliente,dataInicio,dataFim, status, estado,cidade,bairro,cep,tipoLogradouro,
    nomeLogradouro,numero,complemento,contrato,valorContratado,observacoes
    ) VALUES

-- Cliente 1
(1, '2026-01-15 08:00:00', NULL, 'Em andamento',
'SP', 'Suzano', 'Centro', '08512000','Rua', 'Americana', '88', NULL, 
'Obra 1',85000.00,'Ampliação da rede elétrica da área fabril.'),

-- Cliente 2
(2, '2025-09-10 07:30:00', '2026-03-20 17:00:00', 'Concluida',
'SP', 'Mogi Mirim', 'Centro', '13800005','Avenida', 'Lunares', '888', NULL, 
'Obra 2',125000.00,'Modernização elétrica com troca de quadros e cabeamento.'),

-- Cliente 3
(3, '2026-05-01 09:00:00', NULL, 'Em andamento',
'SP', 'Bertioga', 'Riviera', '11250000','Avenida', 'Riviera', '108', 'Bloco B', 
'Obra 3',98000.00,'Expansão da infraestrutura elétrica de novo bloco comercial.'),

-- Cliente 4
(4, '2026-04-10 08:00:00', NULL, 'Em andamento',
'SP', 'Guarujá', 'Astúrias', '11410002','Rua', 'da Praia das Astúrias', '10', NULL, 
'Obra 4',42000.00,'Reforma elétrica completa da residência de praia.'),

-- Cliente 5
(5, '2026-02-03 08:30:00', NULL, 'Em andamento',
'SP', 'Americana', 'Centro', '13145560','Avenida', 'Solares', '108', NULL, 
'Obra 5',310000.00,'Construção de subestação elétrica para expansão industrial.'),

-- Cliente 6
(6, '2026-03-12 08:00:00', NULL, 'Em andamento',
'SP', 'Santos', 'Gonzaga', '11055000','Rua', 'das Palmeiras', '120', NULL,
'Obra 6',165000.00,'Instalação elétrica completa de prédio comercial.'),

-- Cliente 7
(7, '2025-11-18 07:30:00', '2026-04-28 17:30:00', 'Concluida',
'SP', 'Santos', 'Vila Mathias', '11045002','Avenida', 'Conselheiro Nébias', '450','Edifício Souza Empresarial - Sala 4',
'Obra 7',93000.00,'Substituição dos quadros elétricos e adequação à NR-10.'),

-- Cliente 8
(8, '2026-06-02 08:30:00', NULL, 'Em andamento',
'SP', 'São Vicente', 'Centro', '11320000','Rua', 'Dom Pedro II', '98','Galpão Brasil Logística',
'Obra 8',118000.00,'Instalação de iluminação industrial em centro logístico.'),

-- Cliente 9
(9, '2026-02-20 09:00:00', NULL, 'Em andamento',
'SP', 'Praia Grande', 'Boqueirão', '11701000','Avenida', 'Presidente Wilson', '765','Escola Rosa',
'Obra 9',76000.00,'Reforma das instalações elétricas e iluminação da escola.'),

-- Cliente 10
(10, '2026-05-15 08:00:00', '2026-06-05 16:30:00', 'Concluida',
'SP', 'Guarujá', 'Enseada', '11430000','Rua', 'das Acácias', '55','Residência Poliana',
'Obra 10',38500.00,'Reforma elétrica residencial com instalação de novos circuitos.'),

-- Cliente 11
(11, '2026-07-01 08:00:00', NULL, 'Em andamento',
'SP', 'Cubatão', 'Centro', '11510020','Rua', 'Professor Toledo', '230','Centro Empresarial Gonçalves',
'Obra 11',247000.00,'Execução da infraestrutura elétrica de prédio corporativo.'),

-- Cliente 12
(12, '2026-03-25 08:00:00', '2026-04-15 17:00:00', 'Concluida',
'SP', 'Santos', 'Centro', '11010000','Avenida', 'Marechal Deodoro', '1020','Residência Ramon Gonzallez - Casa',
'Obra 12',29500.00,'Modernização da instalação elétrica residencial.'),

-- Cliente 13
(13, '2026-08-10 07:30:00', NULL, 'Em andamento',
'SP', 'São Vicente', 'Centro', '11310010','Rua', 'XV de Novembro', '315','Complexo Empresarial Leo Santos',
'Obra 13',189000.00,'Instalação elétrica e iluminação de novo centro comercial.'),

-- Cliente 14
(14, '2026-09-05 08:30:00', NULL, 'Em andamento',
'SP', 'Santos', 'Gonzaga', '11060002','Avenida', 'Ana Costa', '890','Edifício Novaes - Sala 10',
'Obra 14',97000.00,'Montagem da infraestrutura elétrica e sistema de emergência.'),

-- Cliente 15
(15, '2026-10-01 08:00:00', NULL, 'Em andamento',
'SP', 'Praia Grande', 'Boqueirão', '11702000','Rua', 'Rio Branco', '500','Centro Comercial Shin',
'Obra 15',156000.00,'Instalação elétrica completa para centro comercial de médio porte.');

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

-- =========================================================
-- INSERÇAO DE DADOS DA FINANCEIRO FUNCIONÁRIOS - MAIO/2026
-- =========================================================

INSERT INTO financeiroFuncionario
(idFuncionario, idCategoria, descricao, valor, dataReferencia, formaPagamento, contaPagamento, observacao)
VALUES

-- João Pedro Silva (CLT)
(1,1,'Salário Maio/2026',5800.00,'2026-05-01','Transferência','Banco do Brasil',''),
(1,3,'Horas Extras',320.00,'2026-05-01','Transferência','Banco do Brasil',''),
(1,15,'INSS',640.00,'2026-05-01','Folha','Banco do Brasil',''),
(1,16,'IRRF',285.00,'2026-05-01','Folha','Banco do Brasil',''),
(1,19,'Vale Transporte',220.00,'2026-05-01','Folha','Banco do Brasil',''),

-- Maria Oliveira Souza (Contrato Temporário)
(2,1,'Salário Maio/2026',4700.00,'2026-05-01','PIX','Caixa Econômica',''),
(2,9,'Bônus por desempenho',250.00,'2026-05-01','PIX','Caixa Econômica',''),
(2,15,'INSS',515.00,'2026-05-01','Folha','Caixa Econômica',''),
(2,19,'Vale Transporte',180.00,'2026-05-01','Folha','Caixa Econômica',''),

-- Carlos Henrique Lima (Terceirizado)
(3,14,'Pagamento de Serviço Maio/2026',5100.00,'2026-05-01','TED','Banco Itaú',''),

-- Fernanda Alves Costa (Pessoa Jurídica)
(4,13,'Pagamento NF Maio/2026',5900.00,'2026-05-01','PIX','Banco Inter',''),
(4,10,'Ajuda de Custo',250.00,'2026-05-01','PIX','Banco Inter',''),

-- Lucas Martins Pereira (CLT)
(5,1,'Salário Maio/2026',4500.00,'2026-05-01','Transferência','Santander',''),
(5,9,'Bônus',180.00,'2026-05-01','Transferência','Santander',''),
(5,15,'INSS',495.00,'2026-05-01','Folha','Santander',''),
(5,19,'Vale Transporte',180.00,'2026-05-01','Folha','Santander',''),

-- Antonio Americo Bilhões (CLT)
(6,1,'Salário Maio/2026',5600.00,'2026-05-01','Transferência','Banco do Brasil',''),
(6,3,'Horas Extras',260.00,'2026-05-01','Transferência','Banco do Brasil',''),
(6,15,'INSS',615.00,'2026-05-01','Folha','Banco do Brasil',''),
(6,16,'IRRF',240.00,'2026-05-01','Folha','Banco do Brasil',''),
(6,19,'Vale Transporte',220.00,'2026-05-01','Folha','Banco do Brasil',''),

-- Levi Guimarães Moralles (Contrato Temporário)
(7,1,'Salário Maio/2026',4900.00,'2026-05-01','PIX','Caixa Econômica',''),
(7,9,'Bônus por desempenho',300.00,'2026-05-01','PIX','Caixa Econômica',''),
(7,15,'INSS',540.00,'2026-05-01','Folha','Caixa Econômica',''),
(7,19,'Vale Transporte',180.00,'2026-05-01','Folha','Caixa Econômica',''),

-- Antonelli Nunes Mercedes (Pessoa Jurídica)
(8,13,'Pagamento NF Maio/2026',7000.00,'2026-05-01','PIX','Banco Inter',''),
(8,10,'Ajuda de Custo',350.00,'2026-05-01','PIX','Banco Inter',''),

-- Veronica Muniz (Terceirizado)
(9,14,'Pagamento de Serviço Maio/2026',4700.00,'2026-05-01','TED','Banco Itaú',''),

-- Maria Julia Nascimento Silva (CLT)
(10,1,'Salário Maio/2026',3900.00,'2026-05-01','Transferência','Santander',''),
(10,3,'Horas Extras',180.00,'2026-05-01','Transferência','Santander',''),
(10,15,'INSS',430.00,'2026-05-01','Folha','Santander',''),
(10,19,'Vale Transporte',180.00,'2026-05-01','Folha','Santander','');

-- =====================================================
-- INSERÇAO DE DADOS DA FINANCEIRO FUNCIONÁRIOS - JUNHO/2026
-- =====================================================

INSERT INTO financeiroFuncionario
(idFuncionario, idCategoria, descricao, valor, dataReferencia, formaPagamento, contaPagamento, observacao)
VALUES

-- João Pedro Silva
(1,1,'Salário Junho/2026',5800.00,'2026-06-01','Transferência','Banco do Brasil',''),
(1,3,'Horas Extras',450.00,'2026-06-01','Transferência','Banco do Brasil',''),
(1,9,'Bônus por produtividade',350.00,'2026-06-01','Transferência','Banco do Brasil',''),
(1,15,'INSS',640.00,'2026-06-01','Folha','Banco do Brasil',''),
(1,16,'IRRF',295.00,'2026-06-01','Folha','Banco do Brasil',''),
(1,19,'Vale Transporte',220.00,'2026-06-01','Folha','Banco do Brasil',''),

-- Maria Oliveira Souza
(2,1,'Salário Junho/2026',4700.00,'2026-06-01','PIX','Caixa Econômica',''),
(2,3,'Horas Extras',180.00,'2026-06-01','PIX','Caixa Econômica',''),
(2,15,'INSS',515.00,'2026-06-01','Folha','Caixa Econômica',''),
(2,19,'Vale Transporte',180.00,'2026-06-01','Folha','Caixa Econômica',''),

-- Carlos Henrique Lima
(3,14,'Pagamento de Serviço Junho/2026',5400.00,'2026-06-01','TED','Banco Itaú',''),

-- Fernanda Alves Costa
(4,13,'Pagamento NF Junho/2026',6100.00,'2026-06-01','PIX','Banco Inter',''),
(4,10,'Ajuda de Custo',300.00,'2026-06-01','PIX','Banco Inter',''),

-- Lucas Martins Pereira
(5,1,'Salário Junho/2026',4500.00,'2026-06-01','Transferência','Santander',''),
(5,3,'Horas Extras',280.00,'2026-06-01','Transferência','Santander',''),
(5,15,'INSS',495.00,'2026-06-01','Folha','Santander',''),
(5,19,'Vale Transporte',180.00,'2026-06-01','Folha','Santander',''),

-- Antonio Americo Bilhões
(6,1,'Salário Junho/2026',5600.00,'2026-06-01','Transferência','Banco do Brasil',''),
(6,3,'Horas Extras',390.00,'2026-06-01','Transferência','Banco do Brasil',''),
(6,9,'Bônus',250.00,'2026-06-01','Transferência','Banco do Brasil',''),
(6,15,'INSS',615.00,'2026-06-01','Folha','Banco do Brasil',''),
(6,16,'IRRF',250.00,'2026-06-01','Folha','Banco do Brasil',''),
(6,19,'Vale Transporte',220.00,'2026-06-01','Folha','Banco do Brasil',''),

-- Levi Guimarães Moralles
(7,1,'Salário Junho/2026',4900.00,'2026-06-01','PIX','Caixa Econômica',''),
(7,10,'Ajuda de Custo',180.00,'2026-06-01','PIX','Caixa Econômica',''),
(7,15,'INSS',540.00,'2026-06-01','Folha','Caixa Econômica',''),
(7,19,'Vale Transporte',180.00,'2026-06-01','Folha','Caixa Econômica',''),

-- Antonelli Nunes Mercedes
(8,13,'Pagamento NF Junho/2026',7200.00,'2026-06-01','PIX','Banco Inter',''),
(8,10,'Ajuda de Custo',420.00,'2026-06-01','PIX','Banco Inter',''),

-- Veronica Muniz
(9,14,'Pagamento de Serviço Junho/2026',4950.00,'2026-06-01','TED','Banco Itaú',''),

-- Maria Julia Nascimento Silva
(10,1,'Salário Junho/2026',3900.00,'2026-06-01','Transferência','Santander',''),
(10,3,'Horas Extras',260.00,'2026-06-01','Transferência','Santander',''),
(10,9,'Bônus por produtividade',180.00,'2026-06-01','Transferência','Santander',''),
(10,15,'INSS',430.00,'2026-06-01','Folha','Santander',''),
(10,19,'Vale Transporte',180.00,'2026-06-01','Folha','Santander','');


-- ========================================================
-- INSERÇAO DE DADOS DA FINANCEIRO FUNCIONÁRIOS - JULHO/2026
-- ========================================================

INSERT INTO financeiroFuncionario
(idFuncionario, idCategoria, descricao, valor, dataReferencia, formaPagamento, contaPagamento, observacao)
VALUES

-- João Pedro Silva (CLT)
(1,1,'Salário Julho/2026',5800.00,'2026-07-01','Transferência','Banco do Brasil',''),
(1,2,'Férias',5800.00,'2026-07-10','Transferência','Banco do Brasil','Férias de 30 dias'),
(1,3,'Horas Extras',380.00,'2026-07-01','Transferência','Banco do Brasil',''),
(1,11,'Adicional Noturno',220.00,'2026-07-01','Transferência','Banco do Brasil','Plantão noturno'),
(1,15,'INSS',640.00,'2026-07-01','Folha','Banco do Brasil',''),
(1,16,'IRRF',285.00,'2026-07-01','Folha','Banco do Brasil',''),
(1,19,'Vale Transporte',220.00,'2026-07-01','Folha','Banco do Brasil',''),
(1,20,'Plano de Saúde',185.00,'2026-07-01','Folha','Banco do Brasil','Plano empresarial'),
(1,18,'Atrasos do período',58.00,'2026-05-30','Folha','Banco do Brasil',''),
(1,4,'Periculosidade',480.00,'2026-07-01','Transferência','Banco do Brasil',''),

-- Maria Oliveira Souza (Contrato Temporário)
(2,1,'Salário Julho/2026',4700.00,'2026-07-01','PIX','Caixa Econômica',''),
(2,5,'Bônus por desempenho',300.00,'2026-07-01','PIX','Caixa Econômica',''),
(2,9,'Desconto INSS',515.00,'2026-07-01','Folha','Caixa Econômica',''),
(2,10,'Desconto IRRF',140.00,'2026-07-01','Folha','Caixa Econômica',''),
(2,11,'Vale Transporte',180.00,'2026-07-01','Folha','Caixa Econômica',''),
(10,21,'Plano Odontológico',35.00,'2026-07-01','Folha','Santander',''),

-- Carlos Henrique Lima (Terceirizado)
(3,8,'Pagamento de Serviço Julho/2026',5200.00,'2026-07-01','TED','Banco Itaú',''),

-- Fernanda Alves Costa (Pessoa Jurídica)
(4,7,'Pagamento NF Julho/2026',6000.00,'2026-07-01','PIX','Banco Inter',''),
(4,6,'Ajuda de Custo',350.00,'2026-07-01','PIX','Banco Inter',''),

-- Lucas Martins Pereira (CLT)
(5,1,'Salário Julho/2026',4500.00,'2026-07-01','Transferência','Santander',''),
(5,5,'Bônus',250.00,'2026-07-01','Transferência','Santander',''),
(5,9,'Desconto INSS',495.00,'2026-07-01','Folha','Santander',''),
(5,10,'Desconto IRRF',185.00,'2026-07-01','Folha','Santander',''),
(5,11,'Vale Transporte',180.00,'2026-07-01','Folha','Santander',''),
(5,21,'Plano Odontológico',39.90,'2026-07-01','Folha','Santander',''),
(5,17,'Falta injustificada',120.00,'2026-06-18','Folha','Santander',''),
(5,6,'Insalubridade',320.00,'2026-06-01','Transferência','Santander',''),

-- Antonio Americo Bilhões (CLT)
(6,1,'Salário Julho/2026',5600.00,'2026-07-01','Transferência','Banco do Brasil',''),
(6,4,'Periculosidade',480.00,'2026-07-01','Transferência','Banco do Brasil','Trabalho em rede energizada'),
(6,8,'Participação nos Lucros',900.00,'2026-07-20','Transferência','Banco do Brasil','PLR 2026'),
(6,3,'Horas Extras',350.00,'2026-07-01','Transferência','Banco do Brasil',''),
(6,15,'INSS',615.00,'2026-07-01','Folha','Banco do Brasil',''),
(6,16,'IRRF',240.00,'2026-07-01','Folha','Banco do Brasil',''),
(6,19,'Vale Transporte',220.00,'2026-07-01','Folha','Banco do Brasil',''),
(6,20,'Plano de Saúde',210.00,'2026-07-01','Folha','Banco do Brasil',''),
(6,4,'Periculosidade',480.00,'2026-07-01','Transferência','Banco do Brasil',''),

-- Levi Guimarães Moralles (Contrato Temporário)
(7,1,'Salário Julho/2026',4900.00,'2026-07-01','PIX','Caixa Econômica',''),
(7,5,'Bônus por desempenho',450.00,'2026-07-01','PIX','Caixa Econômica',''),
(7,9,'Desconto INSS',540.00,'2026-07-01','Folha','Caixa Econômica',''),
(7,10,'Desconto IRRF',165.00,'2026-07-01','Folha','Caixa Econômica',''),
(7,11,'Vale Transporte',180.00,'2026-07-01','Folha','Caixa Econômica',''),

-- Antonelli Nunes Mercedes (Pessoa Jurídica)
(8,7,'Pagamento NF Julho/2026',7200.00,'2026-07-01','PIX','Banco Inter',''),
(8,6,'Ajuda de Custo',450.00,'2026-07-01','PIX','Banco Inter',''),

-- Veronica Muniz (Terceirizado)
(9,8,'Pagamento de Serviço Julho/2026',4800.00,'2026-07-01','TED','Banco Itaú',''),

-- Maria Julia Nascimento Silva (CLT)
(10,1,'Salário Julho/2026',3900.00,'2026-07-01','Transferência','Santander',''),
(10,5,'13º Salário (1ª Parcela)',1950.00,'2026-07-15','Transferência','Santander',''),
(10,3,'Horas Extras',290.00,'2026-07-01','Transferência','Santander',''),
(10,10,'Ajuda de Custo',180.00,'2026-07-01','Transferência','Santander',''),
(10,20,'Plano de Saúde',165.00,'2026-07-01','Folha','Santander',''),
(10,15,'INSS',430.00,'2026-07-01','Folha','Santander',''),
(10,16,'IRRF',110.00,'2026-07-01','Folha','Santander',''),
(10,19,'Vale Transporte',180.00,'2026-07-01','Folha','Santander',''),
(10,8,'Participação nos Lucros',650.00,'2026-07-20','Transferência','Santander','');

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

-- ============================================================
-- INSERÇÃO DE DADOS DA FINANCEIRO OBRA - OBRA #2 - MARÇO/2026
-- ============================================================
INSERT INTO financeiroObra
(idObra, idCategoriaFinanceiroObra, descricao, valor, dataGasto, formaPagamento, observacao)
VALUES
(2,1,'Compra complementar de cabos elétricos',2850.00,'2026-03-02','PIX','Fornecedor Elétrica Santos'),
(2,1,'Compra de disjuntores trifásicos',1950.00,'2026-03-03','PIX','Schneider Electric'),
(2,3,'Reposição de ferramentas',620.00,'2026-03-04','Cartão','Substituição de furadeira'),
(2,6,'Pagamento final da equipe',8400.00,'2026-03-10','Transferência','Conclusão dos serviços'),
(2,7,'Empresa terceirizada para testes',1850.00,'2026-03-12','PIX','Laudos e testes elétricos'),
(2,8,'Frete de materiais',430.00,'2026-03-13','Dinheiro','Entrega final'),
(2,9,'Combustível dos veículos',590.00,'2026-03-14','Cartão','Deslocamento da equipe'),
(2,10,'Alimentação da equipe',760.00,'2026-03-15','PIX','Equipe em campo'),
(2,12,'Reposição de EPIs',340.00,'2026-03-16','Cartão','Capacetes e luvas'),
(2,13,'Taxa de vistoria elétrica',780.00,'2026-03-18','PIX','Emissão de documentação'),
(2,15,'Limpeza técnica da obra',520.00,'2026-03-19','PIX','Preparação para entrega');

-- ============================================================
-- INSERÇÃO DE DADOS DA FINANCEIRO OBRA  - OBRA #1 - MAIO/2026
-- ============================================================
INSERT INTO financeiroObra
(idObra, idCategoriaFinanceiroObra, descricao, valor, dataGasto, formaPagamento, observacao)
VALUES
(1,1,'Compra de cabos elétricos',1850.00,'2026-05-03','PIX','Fornecedor Elétrica Santos'),
(1,2,'Compra de cimento e areia',1450.00,'2026-05-04','Boleto','Entrega da fundação'),
(1,3,'Compra de ferramentas',980.00,'2026-05-05','Cartão','Furadeiras e alicates'),
(1,4,'Locação de betoneira',650.00,'2026-05-06','PIX','Locação semanal'),
(1,6,'Pagamento da equipe',5200.00,'2026-05-15','Transferência','Primeira quinzena'),
(1,12,'Compra de EPIs',780.00,'2026-05-18','Cartão','Capacetes e luvas');

-- ============================================================
-- INSERÇÃO DE DADOS DA FINANCEIRO OBRA  - OBRA #1 - JUNHO/2026
-- ============================================================
INSERT INTO financeiroObra
(idObra, idCategoriaFinanceiroObra, descricao, valor, dataGasto, formaPagamento, observacao)
VALUES

(1,1,'Compra de disjuntores',1380.00,'2026-06-02','PIX','Schneider'),
(1,7,'Serviço terceirizado',2400.00,'2026-06-08','Transferência','Instalação especializada'),
(1,8,'Frete dos materiais',420.00,'2026-06-10','Dinheiro',''),
(1,9,'Combustível dos veículos',610.00,'2026-06-12','Cartão',''),
(1,10,'Alimentação da equipe',890.00,'2026-06-13','PIX',''),
(1,6,'Pagamento da equipe',6200.00,'2026-06-15','Transferência','Segunda quinzena');

-- ============================================================
-- INSERÇÃO DE DADOS DA FINANCEIRO OBRA  - OBRA #1 - JULHO/2026
-- ============================================================
INSERT INTO financeiroObra
(idObra, idCategoriaFinanceiroObra, descricao, valor, dataGasto, formaPagamento, observacao)
VALUES

(1,5,'Locação de plataforma elevatória',1800.00,'2026-07-05','PIX',''),
(1,13,'Licença municipal',650.00,'2026-07-08','PIX',''),
(1,14,'Impostos da obra',1250.00,'2026-07-10','Boleto',''),
(1,12,'Reposição de EPIs',320.00,'2026-07-12','Cartão',''),
(1,15,'Limpeza e acabamento',980.00,'2026-07-18','PIX','Serviços finais'),
(1,6,'Pagamento final da equipe',6300.00,'2026-07-20','Transferência','Fechamento da obra');

-- ============================================================
-- INSERÇÃO DE DADOS DA FINANCEIRO OBRA - OBRA #4 - JUNHO/2026
-- ============================================================
INSERT INTO financeiroObra
(idObra, idCategoriaFinanceiroObra, descricao, valor, dataGasto, formaPagamento, observacao)
VALUES

(4,1,'Compra de cabos e fios elétricos',1650.00,'2026-06-03','PIX','Fornecedor Elétrica Santos'),
(4,3,'Compra de ferramentas manuais',420.00,'2026-06-05','Cartão','Alicate, chave teste e multímetro'),
(4,6,'Pagamento da equipe',3800.00,'2026-06-15','Transferência','Execução da reforma elétrica'),
(4,9,'Combustível da equipe',240.00,'2026-06-17','Cartão','Deslocamento até a residência'),
(4,10,'Alimentação da equipe',310.00,'2026-06-18','PIX','Equipe em campo'),
(4,12,'Compra de EPIs',280.00,'2026-06-20','Cartão','Luvas isolantes e óculos de proteção');

-- ============================================================
-- INSERÇÃO DE DADOS DA FINANCEIRO OBRA - OBRA #4 - JULHO/2026
-- ============================================================
INSERT INTO financeiroObra
(idObra, idCategoriaFinanceiroObra, descricao, valor, dataGasto, formaPagamento, observacao)
VALUES

(4,1,'Compra de tomadas e interruptores',890.00,'2026-07-02','PIX','Material complementar'),
(4,1,'Compra de disjuntores',620.00,'2026-07-04','PIX','Quadro de distribuição'),
(4,6,'Pagamento final da equipe',3950.00,'2026-07-15','Transferência','Conclusão da instalação'),
(4,8,'Frete de materiais',180.00,'2026-07-16','Dinheiro','Entrega de materiais'),
(4,13,'Taxa de vistoria elétrica',280.00,'2026-07-18','PIX','Vistoria técnica'),
(4,15,'Limpeza e acabamento',350.00,'2026-07-20','PIX','Limpeza final da obra');

-- ============================================================
-- INSERÇÃO DE DADOS DA FINANCEIRO OBRA - OBRA #5 - MAIO/2026
-- ============================================================

INSERT INTO financeiroObra
(idObra,idCategoriaFinanceiroObra,descricao,valor,dataGasto,formaPagamento,observacao)
VALUES

(5,1,'Cabos de média tensão',12850.00,'2026-05-02','PIX','Fornecedor Prysmian'),
(5,2,'Concreto para base da subestação',9650.00,'2026-05-04','Boleto',''),
(5,3,'Ferramentas industriais',3850.00,'2026-05-06','Cartão',''),
(5,6,'Pagamento equipe elétrica',22800.00,'2026-05-15','Transferência','Primeira quinzena'),
(5,5,'Locação de guindaste',7200.00,'2026-05-18','PIX',''),
(5,8,'Frete de transformadores',2650.00,'2026-05-22','TED',''),
(5,12,'Aquisição de EPIs',1950.00,'2026-05-25','Cartão',''),
(5,9,'Combustível frota',1350.00,'2026-05-28','Cartão','');

-- ============================================================
-- INSERÇÃO DE DADOS DA FINANCEIRO OBRA - OBRA #5 - JUNHO/2026
-- ============================================================
INSERT INTO financeiroObra
(idObra,idCategoriaFinanceiroObra,descricao,valor,dataGasto,formaPagamento,observacao)
VALUES

(5,1,'Disjuntores industriais',9350.00,'2026-06-03','PIX',''),
(5,7,'Montagem especializada da subestação',16800.00,'2026-06-06','Transferência','Empresa terceirizada'),
(5,6,'Pagamento equipe elétrica',23150.00,'2026-06-15','Transferência','Segunda quinzena'),
(5,10,'Alimentação da equipe',1860.00,'2026-06-18','PIX',''),
(5,9,'Combustível dos veículos',1420.00,'2026-06-20','Cartão',''),
(5,5,'Locação de plataforma',2850.00,'2026-06-22','PIX',''),
(5,13,'Licença ambiental',1680.00,'2026-06-25','PIX',''),
(5,12,'Reposição de EPIs',820.00,'2026-06-27','Cartão','');

-- ============================================================
-- INSERÇÃO DE DADOS DA FINANCEIRO OBRA - OBRA #5 - JULHO/2026
-- ============================================================
INSERT INTO financeiroObra
(idObra,idCategoriaFinanceiroObra,descricao,valor,dataGasto,formaPagamento,observacao)
VALUES

(5,1,'Painéis elétricos',18400.00,'2026-07-02','Transferência',''),
(5,2,'Materiais complementares',6420.00,'2026-07-05','Boleto',''),
(5,6,'Pagamento equipe elétrica',23600.00,'2026-07-15','Transferência',''),
(5,7,'Instalação do transformador',15200.00,'2026-07-18','TED','Empresa terceirizada'),
(5,14,'Impostos da obra',4850.00,'2026-07-20','Boleto',''),
(5,9,'Combustível equipamentos',1650.00,'2026-07-23','Cartão',''),
(5,12,'EPIs adicionais',980.00,'2026-07-25','Cartão',''),
(5,15,'Despesas diversas da obra',740.00,'2026-07-28','PIX','');

-- =====================================================
-- INSERÇÃO DE DADOS DA FINANCEIRO VEÍCULO - JUNHO/2026
-- =====================================================
INSERT INTO financeiroVeiculo
(idVeiculo, idCategoriaFinanceiroVeiculo, descricao, valor, dataMovimentacao, formaPagamento, observacao)
VALUES

-- =====================================================
-- VEÍCULO 1 - ABC1D23
-- =====================================================
(1,9,'Abastecimento',295.00,'2026-06-04','Cartão',''),
(1,15,'Troca de óleo e filtros',460.00,'2026-06-09','PIX','Manutenção preventiva'),
(1,19,'Pedágio',38.50,'2026-06-12','Dinheiro',''),
(1,1,'Cashback combustível',95.00,'2026-06-21','PIX',''),

-- =====================================================
-- VEÍCULO 2 - AFC1D28
-- =====================================================
(2,9,'Abastecimento',385.00,'2026-06-05','Cartão',''),
(2,21,'Seguro Mensal',285.00,'2026-06-10','PIX',''),
(2,13,'Lavagem Completa',60.00,'2026-06-15','Dinheiro',''),
(2,2,'Reembolso de Viagem',280.00,'2026-06-24','Transferência',''),

-- =====================================================
-- VEÍCULO 3 - ADC1K28
-- =====================================================
(3,9,'Abastecimento',405.00,'2026-06-03','Cartão',''),
(3,15,'Revisão preventiva',790.00,'2026-06-11','PIX',''),
(3,16,'Multa de trânsito',130.00,'2026-06-18','Boleto','Estacionamento irregular'),
(3,1,'Cashback combustível',135.00,'2026-06-27','PIX',''),

-- =====================================================
-- VEÍCULO 4 - AFJ1D28
-- =====================================================
(4,18,'Troca de pastilhas',340.00,'2026-06-02','PIX',''),
(4,9,'Abastecimento',360.00,'2026-06-08','Cartão',''),
(4,20,'Alinhamento e balanceamento',280.00,'2026-06-16','PIX',''),
(4,2,'Reembolso do seguro',620.00,'2026-06-25','TED',''),

-- =====================================================
-- VEÍCULO 5 - LFD1D28
-- =====================================================
(5,9,'Abastecimento',290.00,'2026-06-06','Cartão',''),
(5,13,'Lavagem',55.00,'2026-06-11','Dinheiro',''),
(5,11,'Estacionamento',35.00,'2026-06-19','Dinheiro',''),
(5,6,'Indenização do seguro',980.00,'2026-06-28','TED',''),

-- =====================================================
-- VEÍCULO 6 - QWE2F34
-- =====================================================
(6,9,'Abastecimento',495.00,'2026-06-01','Cartão',''),
(6,15,'Revisão Completa',1280.00,'2026-06-09','PIX','Troca de filtros e óleo'),
(6,21,'Seguro',420.00,'2026-06-18','PIX',''),
(6,5,'Venda de sucata',950.00,'2026-06-29','Transferência','Venda de peças antigas');

-- =====================================================
-- INSERÇÃO DE DADOS DA FINANCEIRO VEICULO - JULHO/2026
-- =====================================================
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
SELECT * FROM contatoCliente;
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

SELECT *
FROM financeirofuncionario
WHERE idFuncionario = 1;


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

 
SELECT idFinanceiroObra, idObra, descricao, valor, dataGasto 
FROM financeiroObra 
ORDER BY idFinanceiroObra DESC LIMIT 1;

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
DESCRIBE usuario;
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

ALTER TABLE obraFuncionario DROP FOREIGN KEY fk_obraFuncionario_obra;

ALTER TABLE obraFuncionario 
  ADD CONSTRAINT fk_obraFuncionario_obra 
  FOREIGN KEY (idObra) 
  REFERENCES obra(idObra) 
  ON DELETE CASCADE;

-- 2. Atualizar a tabela financeiroObra (para evitar o mesmo erro no futuro)
ALTER TABLE financeiroObra DROP FOREIGN KEY fk_financeiroObra_obra;

ALTER TABLE financeiroObra 
  ADD CONSTRAINT fk_financeiroObra_obra 
  FOREIGN KEY (idObra) 
  REFERENCES obra(idObra) 
  ON DELETE CASCADE;
