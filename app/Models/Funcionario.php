<?php

namespace App\Models;

use App\Config\Conexao;
use PDO;

class Funcionario
{
    // =====================================================
    // 1. ATRIBUTOS DA CLASSE (Representam as colunas do banco)
    // =====================================================
    private ?int $idFuncionario = null;
    private ?string $nome = null;
    private ?string $cpf = null;
    private ?string $sexo = null;
    private ?string $dataNascimento = null;
    private ?string $naturalidade = null;
    private ?string $estadoNascimento = null;
    private string $tipoLogradouro = 'Rua'; // Valor padrão
    private ?string $nomeLogradouro = null;
    private ?string $numero = null;
    private ?string $complemento = null;
    private ?string $cidade = null;
    private ?string $cep = null;
    private ?string $estado = null;
    private ?string $email = null;
    private ?string $cargoFuncao = null;
    private string $tipoContrato = 'CLT'; // Valor padrão
    private string $status = 'ativo'; // Valor padrão

    // novos campos add
    private ?string $dataAdmissao = null;
    private ?string $dataDesligamento = null;
    private ?string $feriasProgramadas = null;

    // Dados Bancários
    private ?string $agencia = null;

    private ?string $conta = null;

    private ?string $tipoConta = null;

    private ?string $chavePix = null;


    private ?string $observacoes = null;

    // Contatos (Tabela Auxiliar)
    private ?string $telefone = null;
    private ?string $whatsapp = null;


    private PDO $pdo;

    // =====================================================
    // 2. CONSTRUTOR
    // =====================================================
    public function __construct()
    {
        $banco = new Conexao();
        $this->pdo = $banco->getConnection();
    }

    // =====================================================
    // 3. GETTERS E SETTERS (Regras de negócio e sanitização)
    // =====================================================

    public function getIdFuncionario(): ?int
    {
        return $this->idFuncionario;
    }
    public function setIdFuncionario(?int $id): void
    {
        $this->idFuncionario = $id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }
    public function setNome(?string $nome): void
    {
        $this->nome = $nome;
    }

    public function getCpf(): ?string
    {
        return $this->cpf;
    }
    public function setCpf(?string $cpf): void
    {
        // Remove máscara automaticamente ao "setar" o CPF
        $this->cpf = $cpf ? preg_replace('/[^0-9]/', '', $cpf) : null;
    }

    public function getSexo(): ?string
    {
        return $this->sexo;
    }
    public function setSexo(?string $sexo): void
    {
        $this->sexo = $sexo;
    }

    public function getDataNascimento(): ?string
    {
        return $this->dataNascimento;
    }
    public function setDataNascimento(?string $data): void
    {
        $this->dataNascimento = $data;
    }

    public function getNaturalidade(): ?string
    {
        return $this->naturalidade;
    }
    public function setNaturalidade(?string $naturalidade): void
    {
        $this->naturalidade = $naturalidade;
    }

    public function getEstadoNascimento(): ?string
    {
        return $this->estadoNascimento;
    }
    public function setEstadoNascimento(?string $estado): void
    {
        $this->estadoNascimento = $estado;
    }

    public function getTipoLogradouro(): string
    {
        return $this->tipoLogradouro;
    }
    public function setTipoLogradouro(string $tipo): void
    {
        $this->tipoLogradouro = $tipo ?: 'Rua';
    }

    public function getNomeLogradouro(): ?string
    {
        return $this->nomeLogradouro;
    }
    public function setNomeLogradouro(?string $logradouro): void
    {
        $this->nomeLogradouro = $logradouro;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }
    public function setNumero(?string $numero): void
    {
        $this->numero = $numero;
    }

    public function getComplemento(): ?string
    {
        return $this->complemento;
    }
    public function setComplemento(?string $complemento): void
    {
        $this->complemento = $complemento;
    }

    public function getCidade(): ?string
    {
        return $this->cidade;
    }
    public function setCidade(?string $cidade): void
    {
        $this->cidade = $cidade;
    }

    public function getCep(): ?string
    {
        return $this->cep;
    }
    public function setCep(?string $cep): void
    {
        // Remove máscara automaticamente
        $this->cep = $cep ? preg_replace('/[^0-9]/', '', $cep) : null;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }
    public function setEstado(?string $estado): void
    {
        $this->estado = $estado;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getCargoFuncao(): ?string
    {
        return $this->cargoFuncao;
    }
    public function setCargoFuncao(?string $cargo): void
    {
        $this->cargoFuncao = $cargo;
    }

    public function getTipoContrato(): string
    {
        return $this->tipoContrato;
    }
    public function setTipoContrato(?string $tipo): void
    {
        $this->tipoContrato = $tipo ?: 'CLT';
    }

    public function getStatus(): string
    {
        return $this->status;
    }
    public function setStatus(?string $status): void
    {
        $this->status = $status ?: 'ativo';
    }

    public function getObservacoes(): ?string
    {
        return $this->observacoes;
    }
    public function setObservacoes(?string $obs): void
    {
        $this->observacoes = $obs;
    }

    //novos campos add 
    public function getDataAdmissao(): ?string
    {
        return $this->dataAdmissao;
    }

    public function setDataAdmissao(?string $data): void
    {
        $this->dataAdmissao = $data ?: null;
    }

    public function getDataDesligamento(): ?string
    {
        return $this->dataDesligamento;
    }

    public function setDataDesligamento(?string $data): void
    {
        $this->dataDesligamento = $data ?: null;
    }

    public function getFeriasProgramadas(): ?string
    {
        return $this->feriasProgramadas;
    }

    public function setFeriasProgramadas(?string $data): void
    {
        $this->feriasProgramadas = $data ?: null;
    }
    // novos dados add acima (data de adm, data de deslig e férias)


    // novos campos

    public function getAgencia(): ?string
    {
        return $this->agencia;
    }

    public function setAgencia(?string $agencia): void
    {
        $this->agencia = $agencia;
    }

    public function getConta(): ?string
    {
        return $this->conta;
    }

    public function setConta(?string $conta): void
    {
        $this->conta = $conta;
    }

    public function getTipoConta(): ?string
    {
        return $this->tipoConta;
    }

    public function setTipoConta(?string $tipoConta): void
    {
        $this->tipoConta = $tipoConta;
    }

    public function getChavePix(): ?string
    {
        return $this->chavePix;
    }

    public function setChavePix(?string $chavePix): void
    {
        $this->chavePix = $chavePix;
    }



    // public function getTelefone(): ?string
    // {
    //     return $this->telefone;
    // }
    // public function setTelefone(?string $telefone): void
    // {
    //     $this->telefone = $telefone;
    // }

    // public function getWhatsapp(): ?string
    // {
    //     return $this->whatsapp;
    // }
    // public function setWhatsapp(?string $whatsapp): void
    // {
    //     $this->whatsapp = $whatsapp;
    // }


    public function getTelefone(): ?string
    {
        return $this->telefone;
    }

    public function setTelefone(?string $telefone): void
    {
        $this->telefone = $telefone
            ? preg_replace('/[^0-9]/', '', $telefone)
            : null;
    }

    public function getWhatsapp(): ?string
    {
        return $this->whatsapp;
    }

    public function setWhatsapp(?string $whatsapp): void
    {
        $this->whatsapp = $whatsapp
            ? preg_replace('/[^0-9]/', '', $whatsapp)
            : null;
    }


    // =====================================================
    // 4. MÉTODOS DE BANCO DE DADOS (CRUD)
    // =====================================================

    /**
     * Helper privado para transformar o array do banco em um Objeto Funcionario
     */
    private function hydrate(array $dados): self
    {
        $funcionario = new self();
        $funcionario->setIdFuncionario($dados['idFuncionario'] ?? null);
        $funcionario->setNome($dados['nome'] ?? null);
        $funcionario->setCpf($dados['cpf'] ?? null);
        $funcionario->setSexo($dados['sexo'] ?? null);
        $funcionario->setDataNascimento($dados['dataNascimento'] ?? null);
        $funcionario->setNaturalidade($dados['naturalidade'] ?? null);
        $funcionario->setEstadoNascimento($dados['estadoNascimento'] ?? null);
        $funcionario->setTipoLogradouro($dados['tipoLogradouro'] ?? 'Rua');
        $funcionario->setNomeLogradouro($dados['nomeLogradouro'] ?? null);
        $funcionario->setNumero($dados['numero'] ?? null);
        $funcionario->setComplemento($dados['complemento'] ?? null);
        $funcionario->setCidade($dados['cidade'] ?? null);
        $funcionario->setCep($dados['cep'] ?? null);
        $funcionario->setEstado($dados['estado'] ?? null);
        $funcionario->setEmail($dados['email'] ?? null);
        $funcionario->setCargoFuncao($dados['cargoFuncao'] ?? null);
        $funcionario->setTipoContrato($dados['tipoContrato'] ?? 'CLT');
        $funcionario->setStatus($dados['status'] ?? 'ativo');


        // novos campos add
        $funcionario->setObservacoes($dados['observacoes'] ?? null);
        $funcionario->setDataAdmissao($dados['dataAdmissao'] ?? null);
        $funcionario->setDataDesligamento($dados['dataDesligamento'] ?? null);
        $funcionario->setFeriasProgramadas($dados['feriasProgramadas'] ?? null);
        // acima campos novos add

        $funcionario->setAgencia($dados['agencia'] ?? null);
        $funcionario->setConta($dados['conta'] ?? null);
        $funcionario->setTipoConta($dados['tipoConta'] ?? null);
        $funcionario->setChavePix($dados['chavePix'] ?? null);

        $funcionario->setTelefone($dados['telefone'] ?? null);
        $funcionario->setWhatsapp($dados['whatsapp'] ?? null);

        return $funcionario;
    }

    public function findByCpf(string $cpf): ?self
    {
        $sql = "SELECT f.*, c.telefone, c.whatsapp 
                FROM funcionario f 
                LEFT JOIN contatoFuncionario c ON f.idFuncionario = c.idFuncionario 
                WHERE f.cpf = :cpf";

        $stmt = $this->pdo->prepare($sql);
        // Remove pontuação caso tenham passado com máscara na busca
        $cpfLimpo = preg_replace('/[^0-9]/', '', $cpf);
        $stmt->bindValue(':cpf', $cpfLimpo, PDO::PARAM_STR);
        $stmt->execute();

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ? $this->hydrate($dados) : null;
    }

    public function findById(int $id): ?self
    {
        $sql = "SELECT f.*, c.telefone, c.whatsapp 
                FROM funcionario f 
                LEFT JOIN contatoFuncionario c ON f.idFuncionario = c.idFuncionario 
                WHERE f.idFuncionario = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ? $this->hydrate($dados) : null;
    }

    public function save(): bool
    {
        try {
            $this->pdo->beginTransaction();

            $sql = "INSERT INTO funcionario (nome, cpf, sexo, dataNascimento, naturalidade, estadoNascimento, tipoLogradouro, nomeLogradouro, numero, complemento, cidade, cep, estado, email, cargoFuncao, tipoContrato, status, dataAdmissao, dataDesligamento, feriasProgramadas,agencia, conta, tipoConta, chavePix, observacoes) 
                VALUES (:nome, :cpf, :sexo, :dataNascimento, :naturalidade, :estadoNascimento, :tipoLogradouro, :nomeLogradouro, :numero, :complemento, :cidade, :cep, :estado, :email, :cargoFuncao, :tipoContrato, :status, :dataAdmissao, :dataDesligamento, :feriasProgramadas,:agencia, :conta, :tipoConta, :chavePix, :observacoes)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nome', $this->getNome(), PDO::PARAM_STR);
            $stmt->bindValue(':cpf', $this->getCpf(), PDO::PARAM_STR);
            $stmt->bindValue(':sexo', $this->getSexo(), PDO::PARAM_STR);
            $stmt->bindValue(':dataNascimento', $this->getDataNascimento(), PDO::PARAM_STR);
            $stmt->bindValue(':naturalidade', $this->getNaturalidade(), PDO::PARAM_STR);
            $stmt->bindValue(':estadoNascimento', $this->getEstadoNascimento(), PDO::PARAM_STR);
            $stmt->bindValue(':tipoLogradouro', $this->getTipoLogradouro(), PDO::PARAM_STR);
            $stmt->bindValue(':nomeLogradouro', $this->getNomeLogradouro(), PDO::PARAM_STR);
            $stmt->bindValue(':numero', $this->getNumero(), PDO::PARAM_STR);
            $stmt->bindValue(':complemento', $this->getComplemento(), PDO::PARAM_STR);
            $stmt->bindValue(':cidade', $this->getCidade(), PDO::PARAM_STR);
            $stmt->bindValue(':cep', $this->getCep(), PDO::PARAM_STR);
            $stmt->bindValue(':estado', $this->getEstado(), PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
            $stmt->bindValue(':cargoFuncao', $this->getCargoFuncao(), PDO::PARAM_STR);
            $stmt->bindValue(':tipoContrato', $this->getTipoContrato(), PDO::PARAM_STR);
            $stmt->bindValue(':status', $this->getStatus(), PDO::PARAM_STR);
            $stmt->bindValue(':dataAdmissao', $this->getDataAdmissao());
            $stmt->bindValue(':dataDesligamento', $this->getDataDesligamento());
            $stmt->bindValue(':feriasProgramadas', $this->getFeriasProgramadas());
            $stmt->bindValue(':agencia', $this->getAgencia(), PDO::PARAM_STR);
            $stmt->bindValue(':conta', $this->getConta(), PDO::PARAM_STR);
            $stmt->bindValue(':tipoConta', $this->getTipoConta(), PDO::PARAM_STR);
            $stmt->bindValue(':chavePix', $this->getChavePix(), PDO::PARAM_STR);
            $stmt->bindValue(':observacoes', $this->getObservacoes(), PDO::PARAM_STR);

            $stmt->execute();

            $this->idFuncionario = (int) $this->pdo->lastInsertId();

            if ($this->getTelefone() || $this->getWhatsapp()) {
                $sqlContato = "INSERT INTO contatoFuncionario (idFuncionario, telefone, whatsapp) 
                           VALUES (:idFuncionario, :telefone, :whatsapp)";
                $stmtContato = $this->pdo->prepare($sqlContato);
                $stmtContato->bindValue(':idFuncionario', $this->getIdFuncionario(), PDO::PARAM_INT);
                $stmtContato->bindValue(':telefone', $this->getTelefone(), PDO::PARAM_STR);
                $stmtContato->bindValue(':whatsapp', $this->getWhatsapp(), PDO::PARAM_STR);
                $stmtContato->execute();
            }

            $this->pdo->commit();
            return true;

        } catch (\Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log($e->getMessage());
            return false;
        }
    }

    public function update(): bool
    {
        // Impede update se não tiver ID
        if (!$this->getIdFuncionario()) {
            return false;
        }

        try {
            $this->pdo->beginTransaction();

            // atualizado o UPDATE com os novos dados da tabela (data adm, data de desliga e férias)
            $sql = "UPDATE funcionario SET 
                    nome = :nome, sexo = :sexo, dataNascimento = :dataNascimento, naturalidade = :naturalidade, estadoNascimento = :estadoNascimento, 
                    nomeLogradouro = :nomeLogradouro, numero = :numero, complemento = :complemento, cidade = :cidade, cep = :cep, estado = :estado, 
                    email = :email, cargoFuncao = :cargoFuncao, tipoContrato = :tipoContrato, status = :status, dataAdmissao = :dataAdmissao,
                    dataDesligamento = :dataDesligamento, feriasProgramadas = :feriasProgramadas,agencia = :agencia,conta = :conta,tipoConta = :tipoConta,chavePix = :chavePix, 
                    observacoes = :observacoes 
                    WHERE idFuncionario = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':nome', $this->getNome(), PDO::PARAM_STR);
            $stmt->bindValue(':sexo', $this->getSexo(), PDO::PARAM_STR);
            $stmt->bindValue(':dataNascimento', $this->getDataNascimento(), PDO::PARAM_STR);
            $stmt->bindValue(':naturalidade', $this->getNaturalidade(), PDO::PARAM_STR);
            $stmt->bindValue(':estadoNascimento', $this->getEstadoNascimento(), PDO::PARAM_STR);
            $stmt->bindValue(':nomeLogradouro', $this->getNomeLogradouro(), PDO::PARAM_STR);
            $stmt->bindValue(':numero', $this->getNumero(), PDO::PARAM_STR);
            $stmt->bindValue(':complemento', $this->getComplemento(), PDO::PARAM_STR);
            $stmt->bindValue(':cidade', $this->getCidade(), PDO::PARAM_STR);
            $stmt->bindValue(':cep', $this->getCep(), PDO::PARAM_STR);
            $stmt->bindValue(':estado', $this->getEstado(), PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
            $stmt->bindValue(':cargoFuncao', $this->getCargoFuncao(), PDO::PARAM_STR);
            $stmt->bindValue(':tipoContrato', $this->getTipoContrato(), PDO::PARAM_STR);
            $stmt->bindValue(':status', $this->getStatus(), PDO::PARAM_STR);
            //novos campos
            $stmt->bindValue(':dataAdmissao', $this->getDataAdmissao());
            $stmt->bindValue(':dataDesligamento', $this->getDataDesligamento());
            $stmt->bindValue(':feriasProgramadas', $this->getFeriasProgramadas());

            $stmt->bindValue(':agencia', $this->getAgencia(), PDO::PARAM_STR);
            $stmt->bindValue(':conta', $this->getConta(), PDO::PARAM_STR);
            $stmt->bindValue(':tipoConta', $this->getTipoConta(), PDO::PARAM_STR);
            $stmt->bindValue(':chavePix', $this->getChavePix(), PDO::PARAM_STR);

            $stmt->bindValue(':observacoes', $this->getObservacoes(), PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->getIdFuncionario(), PDO::PARAM_INT);
            $stmt->execute();

            // Lógica de update/insert na tabela auxiliar de contatos
            $stmtCheck = $this->pdo->prepare("SELECT idContato FROM contatoFuncionario WHERE idFuncionario = :id");
            $stmtCheck->bindValue(':id', $this->getIdFuncionario(), PDO::PARAM_INT);
            $stmtCheck->execute();

            if ($stmtCheck->rowCount() > 0) {
                $sqlContato = "UPDATE contatoFuncionario SET telefone = :telefone, whatsapp = :whatsapp WHERE idFuncionario = :id";
            } else {
                $sqlContato = "INSERT INTO contatoFuncionario (idFuncionario, telefone, whatsapp) VALUES (:id, :telefone, :whatsapp)";
            }

            $stmtContato = $this->pdo->prepare($sqlContato);
            $stmtContato->bindValue(':id', $this->getIdFuncionario(), PDO::PARAM_INT);
            $stmtContato->bindValue(':telefone', $this->getTelefone(), PDO::PARAM_STR);
            $stmtContato->bindValue(':whatsapp', $this->getWhatsapp(), PDO::PARAM_STR);

            $stmtContato->execute();
            $this->pdo->commit();
            return true;

        } catch (\Exception $e) {
            //    o erro fica registrado no log do PHP/Apache;
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log($e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM funcionario WHERE idFuncionario = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Retorna todos os funcionários como array associativo
     */
    public function listar(): array
    {
        $sql = "SELECT * FROM funcionario";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca funcionários com filtros
     */
    public function buscarComFiltros(string $nome = '', string $cargoFuncao = '', string $status = '', string $cpf = ''): array
    {
        $sql = "SELECT * FROM funcionario WHERE 1=1";

        if (!empty($nome)) {
            $sql .= " AND nome LIKE :nome";
        }

        if (!empty($cargoFuncao)) {
            $sql .= " AND cargoFuncao LIKE :cargoFuncao";
        }

        if (!empty($status)) {
            $sql .= " AND status = :status";
        }

        if (!empty($cpf)) {
            $sql .= " AND cpf LIKE :cpf";
        }

        $stmt = $this->pdo->prepare($sql);

        if (!empty($nome)) {
            $stmt->bindValue(':nome', '%' . $nome . '%', PDO::PARAM_STR);
        }

        if (!empty($cargoFuncao)) {
            $stmt->bindValue(':cargoFuncao', '%' . $cargoFuncao . '%', PDO::PARAM_STR);
        }

        if (!empty($status)) {
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        }

        if (!empty($cpf)) {
            $cpfLimpo = preg_replace('/[^0-9]/', '', $cpf);
            $stmt->bindValue(':cpf', '%' . $cpfLimpo . '%', PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
