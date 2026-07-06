<?php

/** @var \App\Models\Funcionario|null $funcionario */ //

// Valores padrão para evitar notices de variáveis indefinidas
$mensagem = $mensagem ?? '';
$cpfBusca = $cpfBusca ?? '';

// Lógica para definir se estamos no modo de Edição (agora validando se é Objeto)
$isEdit = isset($funcionario) && is_object($funcionario);

$actionUrl = $isEdit ? "/ideal/public/index.php?url=funcionarios/update&id={$funcionario->getIdFuncionario()}" : "/ideal/public/index.php?url=funcionarios/store";

$cpfValue = $isEdit ? $funcionario->getCpf() : ($cpfBusca ?? '');

$telefoneValue = $isEdit ? $funcionario->getTelefone() : '';
$whatsappValue = $isEdit ? $funcionario->getWhatsapp() : '';

// TÍTULO DA PÁGINA
$titulo = 'Funcionários';
$favicon = '/ideal/public/assets/icon/funcionario2.png';

// Estado da tela
$isNovo = isset($_GET['novo']);
$isEdit = isset($funcionario) && is_object($funcionario);

use App\Config\SistemaConstantes;
use App\Config\FuncionarioConstantes;


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

                        <form class="form-busca" action="/ideal/public/index.php?url=funcionarios" method="POST">

                            <div class="input-group">
                                <label>CPF</label>

                                <input type="text" name="cpf" placeholder="000.000.000-00" maxlength="14"
                                    oninput="mascaraCPF(this)" required>
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

                </div> <!-- FECHA grid-busca -->

            </section>
            <!-- SEGUNDO FORM -->
            <section class="card card-funcionario">
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
                            <?php $sexo = $isEdit ? $funcionario->getSexo() : ''; ?>
                            <select name="sexo">
                                <option value="">Selecione</option>
                                <?php foreach (SistemaConstantes::SEXOS as $opcao): ?>
                                    <option value="<?= $opcao ?>" <?= $sexo === $opcao ? 'selected' : '' ?>>
                                        <?= $opcao ?>
                                    </option>
                                <?php endforeach; ?>
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
                            <?php $estadoNascimento = $isEdit ? $funcionario->getEstadoNascimento() : ''; ?>
                            <select name="estadoNascimento">
                                <option value="">Selecione</option>
                                <?php foreach (SistemaConstantes::ESTADOS as $sigla => $nome): ?>
                                    <option value="<?= $sigla ?>" <?= $estadoNascimento === $sigla ? 'selected' : '' ?>>
                                        <?= $sigla ?> - <?= $nome ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>CPF</label>
                            <!--  mascara para exibir para o usuário cpf com pontuação -->
                            <?php
                            $cpfFormatado = !empty($cpfValue)
                                ? preg_replace(
                                    '/(\d{3})(\d{3})(\d{3})(\d{2})/',
                                    '$1.$2.$3-$4',
                                    preg_replace('/\D/', '', $cpfValue)
                                )
                                : '';
                            ?>
                            <input type="text" name="cpf" value="<?= htmlspecialchars($cpfFormatado) ?>" maxlength="14"
                                inputmode="numeric" placeholder="000.000.000-00" oninput="mascaraCPF(this)" <?= $isEdit ? 'readonly style="background-color: #eee;"' : 'required' ?>>

                        </div>

                        <div class="form-group">
                            <label>Cargo</label>
                            <?php $cargo = $isEdit ? $funcionario->getCargoFuncao() : ''; ?>

                            <select name="cargoFuncao">
                                <option value="">Selecione</option>
                                <?php
                                $cargos = FuncionarioConstantes::CARGOS;
                                sort($cargos);
                                foreach ($cargos as $item):
                                    ?>
                                    <option value="<?= $item ?>" <?= $cargo === $item ? 'selected' : '' ?>>
                                        <?= $item ?>
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
                                value="<?= htmlspecialchars($isEdit ? ($funcionario->getComplemento() ?? '') : '') ?>"
                                placeholder="Números e letras">
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
                            <?php
                            $cepFormatado = '';
                            if ($isEdit && !empty($funcionario->getCep())) {
                                $cepFormatado = preg_replace(
                                    '/(\d{5})(\d{3})/',
                                    '$1-$2',
                                    preg_replace('/\D/', '', $funcionario->getCep())
                                );
                            }

                            ?>
                            <input type="text" name="cep" value="<?= htmlspecialchars($cepFormatado) ?>" maxlength="9"
                                inputmode="numeric" oninput="mascaraCEP(this)" placeholder="00000-000">
                        </div>

                        <div class="form-group">
                            <label>Estado</label>
                            <?php $estado = $isEdit ? $funcionario->getEstado() : ''; ?>
                            <select name="estado">
                                <option value="">Selecione</option>
                                <?php foreach (SistemaConstantes::ESTADOS as $sigla => $nome): ?>
                                    <option value="<?= $sigla ?>" <?= $estado === $sigla ? 'selected' : '' ?>>
                                        <?= $sigla ?> - <?= $nome ?>
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
                            <label>Tipo de Contrato</label>
                            <?php $tipoContrato = $isEdit ? $funcionario->getTipoContrato() : ''; ?>

                            <select name="tipoContrato">
                                <option value="">Selecione</option>
                                <?php foreach (FuncionarioConstantes::TIPOS_CONTRATO as $valor => $descricao): ?>
                                    <option value="<?= $valor ?>" <?= $tipoContrato === $valor ? 'selected' : '' ?>>
                                        <?= $descricao ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <?php $status = $isEdit ? $funcionario->getStatus() : ''; ?>
                            <select name="status">
                                <option value="">Selecione</option>
                                <?php foreach (SistemaConstantes::STATUS as $valor => $descricao): ?>
                                    <option value="<?= $valor ?>" <?= $status === $valor ? 'selected' : '' ?>>
                                        <?= $descricao ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>
                        </div>

                        <?php
                        $telefoneFormatado = !empty($telefoneValue)
                            ? (strlen(preg_replace('/\D/', '', $telefoneValue)) == 11
                                ? preg_replace(
                                    '/(\d{2})(\d{5})(\d{4})/',
                                    '($1) $2-$3',
                                    preg_replace('/\D/', '', $telefoneValue)
                                )
                                : preg_replace(
                                    '/(\d{2})(\d{4})(\d{4})/',
                                    '($1) $2-$3',
                                    preg_replace('/\D/', '', $telefoneValue)
                                ))
                            : '';

                        $whatsappFormatado = !empty($whatsappValue)
                            ? (strlen(preg_replace('/\D/', '', $whatsappValue)) == 11
                                ? preg_replace(
                                    '/(\d{2})(\d{5})(\d{4})/',
                                    '($1) $2-$3',
                                    preg_replace('/\D/', '', $whatsappValue)
                                )
                                : preg_replace(
                                    '/(\d{2})(\d{4})(\d{4})/',
                                    '($1) $2-$3',
                                    preg_replace('/\D/', '', $whatsappValue)
                                ))
                            : '';
                        ?>

                        <div class="form-group">
                            <label>Telefone</label>
                            <input type="text" name="telefone" placeholder="(XX) 0000-0000"
                                oninput="mascaraTelefone(this)" value="<?= htmlspecialchars($telefoneFormatado) ?>">
                        </div>

                        <div class="form-group">
                            <label>WhatsApp</label>
                            <input type="text" name="whatsapp" placeholder="(XX) 00000-0000"
                                oninput="mascaraTelefone(this)" value="<?= htmlspecialchars($whatsappFormatado) ?>">
                        </div>



                        <div class="secao-inferior">

                            <!-- DADOS CONTRATAÇÃO -->
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

                            <!-- DADOS BANCÁRIOS -->

                            <div class="card-bancario">
                                <h2><i class="fa-solid fa-building-columns icone-titulo"></i> Dados Bancários</h2>
                                <div class="grupo-conta">
                                    <div class="form-group">
                                        <label for="agencia">Agência</label>
                                        <input type="text" id="agencia" name="agencia"
                                            value="<?= htmlspecialchars($funcionario?->getAgencia() ?? '') ?>"
                                            placeholder="Ex.: 1234 (sem dígito)" maxlength="5" inputmode="numeric">

                                    </div>

                                    <div class="form-group">
                                        <label for="conta">Número da Conta</label>
                                        <input type="text" id="agencia" name="conta"
                                            value="<?= htmlspecialchars($funcionario?->getConta() ?? '') ?>"
                                            placeholder="Ex.: 1234" maxlength="15" inputmode="numeric">
                                    </div>

                                    <div class="form-group">
                                        <label for="tipoConta">Tipo de Conta</label>
                                        <?php
                                        $tipoConta = isset($funcionario) ? $funcionario->getTipoConta() : '';
                                        ?>
                                        <select id="tipoConta" name="tipoConta">
                                            <option value="">Selecione</option>
                                            <?php foreach (FuncionarioConstantes::TIPOS_CONTA as $valor => $descricao): ?>
                                                <option value="<?= $valor ?>" <?= $tipoConta === $valor ? 'selected' : '' ?>>
                                                    <?= $descricao ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>


                                    <div class="form-group">
                                        <label for="agencia">Chave Pix</label>
                                        <input type="text" id="chavePix" name="chavePix"
                                            value="<?= htmlspecialchars($funcionario?->getChavePix() ?? '') ?>"
                                            placeholder="CPF, e-mail, telefone ou chave aleatória" maxlength="77">
                                    </div>
                                </div>

                            </div>

                            <!-- OBSERVAÇÃO -->
                            <div class="card-observacao">
                                <h2><i class="fa-solid fa-clipboard icone-titulo"></i> Observações</h2>
                                <textarea name="observacoes" maxlength="500"
                                    placeholder="Digite alguma observação se for necessário"><?= htmlspecialchars($isEdit ? $funcionario->getObservacoes() : '') ?></textarea>
                            </div>

                        </div>
                    </div>
                </form>

            </section>
            <div class="acoes">

                <button type="submit" form="form-dados" class="btn novo"
                    formaction="/ideal/public/index.php?url=funcionarios/store" <?= $isNovo ? '' : 'disabled' ?>>
                    <i class="bi bi-plus-lg"></i>
                    Cadastrar
                </button>

                <button type="submit" form="form-dados" class="btn alterar"
                    formaction="/ideal/public/index.php?url=funcionarios/update&id=<?= $isEdit ? $funcionario->getIdFuncionario() : '' ?>"
                    <?= $isEdit ? '' : 'disabled' ?>>

                    <i class="bi bi-pencil-square"></i>
                    Alterar
                </button>

                <button type="submit" form="form-dados" class="btn excluir"
                    formaction="/ideal/public/index.php?url=funcionarios/delete&id=<?= $isEdit ? $funcionario->getIdFuncionario() : '' ?>"
                    onclick="return confirm('Tem certeza que deseja excluir este funcionário?');" <?= $isEdit ? '' : 'disabled' ?>>

                    <i class="bi bi-trash"></i>
                    Excluir
                </button>

                <button type="reset" form="form-dados" class="btn limpar">

                    <i class="bi bi-eraser"></i>
                    Limpar
                </button>

            </div>

        </main>
    </div>
    <script src="/ideal/public/assets/js/mascaras.js?v=<?= time() ?>"></script>
</body>

</html>