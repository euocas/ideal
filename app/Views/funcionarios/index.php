<?php

/** @var \App\Models\Funcionario|null $funcionario */ //

// Valores padrão para evitar notices de variáveis indefinidas
$mensagem = $mensagem ?? '';
$cpfBusca = $cpfBusca ?? '';

// Lógica para definir se estamos no modo de Edição (agora validando se é Objeto)
$isEdit = isset($funcionario) && is_object($funcionario);

$actionUrl = $isEdit ? "/ideal/public/index.php?url=funcionarios/update&id={$funcionario->getIdFuncionario()}" : "/ideal/public/index.php?url=funcionarios/store";
$cpfValue = $isEdit ? $funcionario->getCpf() : ($cpfBusca ?? '');

// TÍTULO DA PÁGINA
$titulo = 'Funcionários';
$favicon = '/ideal/public/assets/icon/funcionario2.png';


//  ARRAY PARA OS CARGOS 
$cargos = [
    'Almoxarife',
    'Analista Financeiro',
    'Assistente Administrativo',
    'Assistente de RH',
    'Auxiliar Administrativo',
    'Auxiliar de Eletricista',
    'Cabista',
    'Comprador',
    'Designer Gráfico',
    'Eletricista de Manutenção',
    'Eletricista Industrial',
    'Eletricista Montador',
    'Eletricista Predial',
    'Encarregado de Obras Elétricas',
    'Instalador Elétrico',
    'Mestre de Obras',
    'Montador de Painéis Elétricos',
    'Oficial Eletricista',
    'Social Midia'
];
sort($cargos);

//  ARRAY PARA OS ESTADOS
$estados = [
    'AC' => 'Acre',
    'AL' => 'Alagoas',
    'AP' => 'Amapá',
    'AM' => 'Amazonas',
    'BA' => 'Bahia',
    'CE' => 'Ceará',
    'DF' => 'Distrito Federal',
    'ES' => 'Espírito Santo',
    'GO' => 'Goiás',
    'MA' => 'Maranhão',
    'MT' => 'Mato Grosso',
    'MS' => 'Mato Grosso do Sul',
    'MG' => 'Minas Gerais',
    'PA' => 'Pará',
    'PB' => 'Paraíba',
    'PR' => 'Paraná',
    'PE' => 'Pernambuco',
    'PI' => 'Piauí',
    'RJ' => 'Rio de Janeiro',
    'RN' => 'Rio Grande do Norte',
    'RS' => 'Rio Grande do Sul',
    'RO' => 'Rondônia',
    'RR' => 'Roraima',
    'SC' => 'Santa Catarina',
    'SP' => 'São Paulo',
    'SE' => 'Sergipe',
    'TO' => 'Tocantins'
];



//HEADER
require_once __DIR__ . '/../includes/header.php';
?>

<link rel="stylesheet" href="/ideal/public/assets/css/variables.css">
<link rel="stylesheet" href="/ideal/public/assets/css/base.css">
<link rel="stylesheet" href="/ideal/public/assets/css/component.css">
<link rel="stylesheet" href="/ideal/public/assets/css/forms.css">
<link rel="stylesheet" href="/ideal/public/assets/css/alerts.css">
<link rel="stylesheet" href="/ideal/public/assets/css/tables.css">

<link rel="stylesheet" href="/ideal/public/assets/css/dashboard.css">

<link rel="stylesheet" href="/ideal/public/assets/css/funcionarios.css?v=<?= time() ?>">
</head>

<body>
    <div class="dashboard-container">

        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

        <main class="main-content">

            <section class="card">
                <div class="grid-busca">
                    <div class="busca-container">

    <div class="busca-box">

        <h2>
            <i class="fa-solid fa-users"></i>
            BUSCAR FUNCIONÁRIO
        </h2>

        <?php if (!empty($mensagem)): ?>
            <div class="alert alert-warning">
                <?= $mensagem ?>
            </div>
        <?php endif; ?>

        <form class="form-busca"
            action="/ideal/public/index.php?url=funcionarios"
            method="POST">

            <div class="input-group">
                <label>CPF</label>

                <input
                    type="text"
                    name="cpf"
                    placeholder="000.000.000-00"
                    maxlength="14"
                    oninput="mascaraCPF(this)"
                    required>
            </div>

            <button type="submit" class="btn-buscar">
                <i class="bi bi-search"></i>
                BUSCAR
            </button>

        </form>

    </div>

    <div class="dica-box">

        <h3>
            <i class="fa-solid fa-circle-info"></i>
            DICA
        </h3>

        <p>
            Digite o CPF do funcionário e clique em
            <strong>BUSCAR</strong>.
            Se não existir, você poderá cadastrar um novo funcionário.
        </p>

    </div>

</div>
                </div>
            </section>
            <!-- SEGUNDO FORM -->
            <section class="card2">
                <?php if (isset($_SESSION['mensagem_sucesso'])): ?>
                    <div class="alert alert-success">
                        ✅ <?= $_SESSION['mensagem_sucesso']; ?>
                    </div>
                    <?php unset($_SESSION['mensagem_sucesso']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['mensagem_erro'])): ?>
                    <div class="alert alert-error">
                        ❌ <?= $_SESSION['mensagem_erro']; ?>
                    </div>
                    <?php unset($_SESSION['mensagem_erro']); ?>
                <?php endif; ?>


                <h2> <i class="fa-regular fa-clipboard icone-titulo"></i> Dados do Funcionário</h2>

                <form id="form-dados" action="<?= $actionUrl ?>" method="POST">
                    <div class="grid-form">

                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" name="nome"
                                value="<?= htmlspecialchars($isEdit ? $funcionario->getNome() : '') ?>" minlength="3"
                                pattern="[A-Za-zÀ-ÿ\s]+" title="Digite pelo menos 3 letras"
                                placeholder="Digite o Nome Completo" required>
                        </div>

                        <div class="form-group">
                            <label>Sexo</label>
                            <select name="sexo">
                                <option value="">Selecione</option>
                                <option value="Masculino" <?= ($isEdit ? $funcionario->getSexo() : '') === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                                <option value="Feminino" <?= ($isEdit ? $funcionario->getSexo() : '') === 'Feminino' ? 'selected' : '' ?>>Feminino</option>
                                <option value="Outro" <?= ($isEdit ? $funcionario->getSexo() : '') === 'Outro' ? 'selected' : '' ?>>Outro</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Data Nascimento</label>
                            <input type="date" name="dataNascimento"
                                value="<?= htmlspecialchars($isEdit ? $funcionario->getDataNascimento() : '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="naturalidade">Naturalidade</label>
                            <input type="text" name="naturalidade"
                                value="<?= htmlspecialchars($isEdit ? $funcionario->getNaturalidade() : '') ?>"
                                minlength="3" title="Digite apenas letras" placeholder="Digite apenas o nome da cidade">
                        </div>

                        <div class="form-group">
                            <label>Estado Nasc.</label>
                            <select name="estadoNascimento">
                                <option value="">Selecione</option>
                                <?php foreach ($estados as $sigla => $nome): ?>
                                    <option value="<?= $sigla ?>" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === $sigla ? 'selected' : '' ?>>
                                        <?= $nome ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>CPF</label>
                            <input type="text" name="cpf" value="<?= htmlspecialchars($cpfValue) ?>" maxlength="14"
                                inputmode="numeric" placeholder="000.000.000-00" oninput="mascaraCPF(this)" <?= $isEdit ? 'readonly style="background-color: #eee;"' : 'required' ?>>
                        </div>

                        <div class="form-group">
                            <label>Cargo / Função</label>

                            <select name="cargoFuncao">
                                <option value="">Selecione</option>
                                <?php foreach ($cargos as $cargo): ?>
                                    <option value="<?= $cargo ?>" <?= ($isEdit ? $funcionario->getCargoFuncao() : '') === $cargo ? 'selected' : '' ?>>
                                        <?= $cargo ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                        </div>

                        <div class="form-group">
                            <label for="endereco">Endereço</label>
                            <input type="text" name="nomeLogradouro"
                                value="<?= htmlspecialchars($isEdit ? $funcionario->getNomeLogradouro() : '') ?>"
                                minlength="3" title="Digite apenas letras"
                                placeholder="Digite apenas o nome da Rua/Avenida/Alameda/Viela">
                        </div>

                        <div class="form-group">
                            <label>Número</label>
                            <input type="text" name="numero"
                                value="<?= htmlspecialchars($isEdit ? $funcionario->getNumero() : '') ?>"
                                pattern="[0-9]+" placeholder="Somente números">
                        </div>

                        <div class="form-group">
                            <label>Complemento</label>
                            <input type="text" name="complemento"
                                value="<?= htmlspecialchars($isEdit ? ($funcionario->getComplemento() ?? '') : '') ?>" placeholder="Números e letras">
                        </div>

                        <div class="form-group">
                            <label>Cidade</label>
                            <input type="text" name="cidade"
                                value="<?= htmlspecialchars($isEdit ? $funcionario->getCidade() : '') ?>" minlength="3"
                                pattern="[A-Za-zÀ-ÿ\s]+" title="Digite pelo menos 3 letras"
                                placeholder="Digite o nome da cidade" required>
                        </div>

                        <div class="form-group">
                            <label>CEP</label>
                            <input type="text" name="cep"
                                value="<?= htmlspecialchars($isEdit ? $funcionario->getCep() : '') ?>" maxlength="9"
                                inputmode="numeric" oninput="mascaraCEP(this)" placeholder="00000-000">
                        </div>

                        <div class="form-group">
                            <label>Estado</label>
                            <select name="estado">
                                <option value="">Selecione</option>
                                <?php foreach ($estados as $sigla => $nome): ?>
                                    <option value="<?= $sigla ?>" <?= ($isEdit ? $funcionario->getEstado() : '') === $sigla ? 'selected' : '' ?>>
                                        <?= $nome ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email"
                                value="<?= htmlspecialchars($isEdit ? $funcionario->getEmail() : '') ?>" minlength="5"
                                maxlength="100" placeholder="seuemail@dominio.com" title="Digite um e-mail válido"
                                pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                        </div>

                        <div class="form-group">
                            <label>Tipo Contrato</label>
                            <select name="tipoContrato">
                                <option value="">Selecione</option>
                                <option value="CLT" <?= ($isEdit ? $funcionario->getTipoContrato() : '') === 'CLT' ? 'selected' : '' ?>>CLT</option>
                                <option value="CONTRATO TEMPORARIO" <?= ($isEdit ? $funcionario->getTipoContrato() : '') === 'CONTRATO TEMPORARIO' ? 'selected' : '' ?>>Contrato Temporário</option>
                                <option value="PESSOA JURÍRIDICA" <?= ($isEdit ? $funcionario->getTipoContrato() : '') === 'PESSOA JURÍRIDICA' ? 'selected' : '' ?>>Pessoa Jurídica</option>
                                <option value="TERCERIZADA" <?= ($isEdit ? $funcionario->getTipoContrato() : '') === 'TERCERIZADA' ? 'selected' : '' ?>>Tercerizada</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select name="status">
                                <option value="">Selecione</option>
                                <option value="ativo" <?= ($isEdit ? $funcionario->getStatus() : '') === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                                <option value="inativo" <?= ($isEdit ? $funcionario->getStatus() : '') === 'inativo' ? 'selected' : '' ?>>Inativo</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Telefone</label>
                            <input type="text" name="telefone" placeholder="(11) 0000-0000"
                                oninput="mascaraTelefone(this)"
                                value="<?= htmlspecialchars($isEdit ? $funcionario->getTelefone() : '') ?>">
                        </div>

                        <div class="form-group">
                            <label>WhatsApp</label>
                            <input type="text" name="whatsapp" placeholder="(11) 00000-0000"
                                oninput="mascaraTelefone(this)"
                                value="<?= htmlspecialchars($isEdit ? $funcionario->getWhatsapp() : '') ?>">
                        </div>




                        <div class="card-contratacao">

                            <h2><i class="fa-solid fa-file-signature icone-titulo"></i> Dados de Contratação</h2>
                            <div class="grupo-datas">
                                <div class="form-group">
                                    <label>Admissão</label>

                                    <input type="date" name="dataAdmissao"
                                        value="<?= htmlspecialchars($isEdit ? $funcionario->getDataAdmissao() : '') ?>">

                                </div>

                                <div class="form-group">
                                    <label>Desligamento</label>
                                    <input type="date" name="dataDesligamento"
                                        value="<?= htmlspecialchars($isEdit ? $funcionario->getDataDesligamento() : '') ?>">
                                </div>

                                <div class="form-group">
                                    <label>Férias Programadas</label>
                                    <input type="date" name="feriasProgramadas"
                                        value="<?= htmlspecialchars($isEdit ? $funcionario->getFeriasProgramadas() : '') ?>">
                                </div>
                            </div>

                        </div>
                        <div class="card-observacao">
                            <h2><i class="bi bi-journal-text icone-titulo"></i> Observações</h2>
                            <textarea name="observacoes" maxlength="500"
                                placeholder="Digite alguma observação se for necessário"><?= htmlspecialchars($isEdit ? $funcionario->getObservacoes() : '') ?></textarea>
                        </div>
                    </div>
                </form>

            </section>
            <div class="acoes">
                <a href="/ideal/public/index.php?url=funcionarios"
                    class="btn novo">
                    <i class="bi bi-plus-lg"></i>
                    Cadastrar
                </a>

                <?php if (!$isEdit): ?>

                    <button type="submit" form="form-dados" class="btn salvar">
                        <i class="bi bi-floppy"></i>
                        Salvar
                    </button>
                <?php else: ?>

                    <button type="submit" form="form-dados" class="btn alterar">
                        <i class="bi bi-pencil-square"></i>
                        Alterar
                    </button>

                    <a href="/ideal/public/index.php?url=funcionarios/delete&id=<?= $funcionario->getIdFuncionario() ?>"
                        class="btn excluir"
                        onclick="return confirm('Tem certeza que deseja excluir este funcionário?')">
                        <i class="bi bi-trash"></i>
                        Excluir
                    </a>

                <?php endif; ?>

                <button type="reset" form="form-dados" class="btn limpar">
                    <i class="bi bi-eraser"></i>
                    Limpar
                </button>
            </div>

        </main>
    </div>

    <script>
        function mascaraCPF(input) {
            let valor = input.value.replace(/\D/g, '');
            valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
            valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
            valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            input.value = valor;
        }
    </script>

    <script>
        function mascaraCEP(input) {
            let valor = input.value.replace(/\D/g, '');
            valor = valor.substring(0, 8);
            valor = valor.replace(/(\d{5})(\d)/, '$1-$2');
            input.value = valor;
        }
    </script>
</body>

</html>