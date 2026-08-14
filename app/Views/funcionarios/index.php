<?php

/** @var \App\Models\Funcionario|null $funcionario */ //

// Valores padrão para evitar notices de variáveis indefinidas
$mensagem = $mensagem ?? '';
$cpfBusca = $cpfBusca ?? '';

// Lógica para definir se estamos no modo de Edição (agora validando se é Objeto)
$isEdit = isset($funcionario) && is_object($funcionario);

$actionUrl = $isEdit

    ? BASE_URL . "/index.php?url=funcionarios/update&id={$funcionario->getIdFuncionario()}"
    : BASE_URL . "/index.php?url=funcionarios/store";

$cpfValue = $isEdit ? $funcionario->getCpf() : ($cpfBusca ?? '');

$telefoneValue = $isEdit ? $funcionario->getTelefone() : '';
$whatsappValue = $isEdit ? $funcionario->getWhatsapp() : '';

// TÍTULO DA PÁGINA
$titulo = 'Funcionários';
$favicon = BASE_URL . "/assets/icon/funcionario2.png";
$pageStyles = [
    BASE_URL . '/assets/css/funcionarios.css?v=' . time(),
];

// Estado da tela
$isNovo = isset($_GET['novo']);
$isEdit = isset($funcionario) && is_object($funcionario);

$camposBloqueados = !$isNovo && !$isEdit;

use App\Config\SistemaConstantes;
use App\Config\FuncionarioConstantes;


//HEADER
require_once __DIR__ . '/../includes/header.php';
?>

<div class="layout">

    <button type="button" class="menu-toggle" id="menuToggle" aria-label="Abrir menu">
        <i class="fa-solid fa-bars"></i>
    </button>

    <aside class="sidebar" id="sidebar">
        <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>
    </aside>

    <main class="content">

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

                    <form class="form-busca" action="<?= BASE_URL ?>/index.php?url=funcionarios" method="POST">

                        <div class="input-group">
                            <label>CPF</label>

                            <input type="text" class="documento" name="cpf" placeholder="000.000.000-00" maxlength="14"
                                oninput="mascaraCPF(this)" <?= (!$isNovo && !$isEdit) ? 'autofocus' : '' ?> required>
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
                        Para consultar um funcionário, primeiro informe o <strong>CPF</strong> e clique em
                        <strong>BUSCAR</strong>. Se o funcionário ainda
                        não estiver cadastrado, os campos serão liberados para um Novo Cadastro.
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

                <fieldset <?= $camposBloqueados ? 'disabled' : '' ?>>
                    <div class="grid-form">

                        <div class="form-group">
                            <label>Nome <span class="obrigatorio">*</span></label>
                            <input type="text" name="nome"
                                value="<?= htmlspecialchars($isEdit ? $funcionario->getNome() : '') ?>" minlength="3"
                                pattern="[A-Za-zÀ-ÿ\s]+" title="Digite pelo menos 3 letras"
                                placeholder="Digite o Nome Completo" required>
                        </div>

                        <div class="form-group">
                            <label>Sexo <span class="obrigatorio">*</span></label>
                            <?php $sexo = $isEdit ? $funcionario->getSexo() : ''; ?>
                            <select name="sexo" required>
                                <option value="">Selecione</option>
                                <?php foreach (SistemaConstantes::SEXOS as $opcao): ?>
                                    <option value="<?= $opcao ?>" <?= $sexo === $opcao ? 'selected' : '' ?>>
                                        <?= $opcao ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Data Nascimento <span class="obrigatorio">*</span></label>
                            <input type="date" name="dataNascimento"
                                value="<?= htmlspecialchars($isEdit ? $funcionario->getDataNascimento() : '') ?>"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="naturalidade">Naturalidade <span class="obrigatorio">*</span></label>
                            <input type="text" name="naturalidade"
                                value="<?= htmlspecialchars($isEdit ? $funcionario->getNaturalidade() : '') ?>"
                                minlength="3" title="Digite apenas letras" placeholder="Digite apenas o nome da cidade"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Estado Nasc. <span class="obrigatorio">*</span></label>
                            <?php $estadoNascimento = $isEdit ? $funcionario->getEstadoNascimento() : ''; ?>
                            <select name="estadoNascimento" required>
                                <option value="">Selecione</option>
                                <?php foreach (SistemaConstantes::ESTADOS as $sigla => $nome): ?>
                                    <option value="<?= $sigla ?>" <?= $estadoNascimento === $sigla ? 'selected' : '' ?>>
                                        <?= $sigla ?> - <?= $nome ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>CPF <span class="obrigatorio">*</span></label>

                            <?php
                            $cpfFormatado = !empty($cpfValue)
                                ? preg_replace(
                                    '/(\d{3})(\d{3})(\d{3})(\d{2})/',
                                    '$1.$2.$3-$4',
                                    preg_replace('/\D/', '', $cpfValue)
                                )
                                : '';
                            ?>

                            <input type="text" name="cpf" id="cpf" value="<?= htmlspecialchars($cpfFormatado) ?>"
                                maxlength="14" inputmode="numeric" placeholder="000.000.000-00"
                                oninput="mascaraCPF(this)" <?= !empty($cpfValue) ? 'readonly style="background-color: #eee;"' : 'required' ?>>
                        </div>

                        <div class="form-group">
                            <label>Cargo <span class="obrigatorio">*</span></label>
                            <?php $cargo = $isEdit ? $funcionario->getCargoFuncao() : ''; ?>

                            <select name="cargoFuncao" required>
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
                            <label for="tipoLogradouro">
                                Tipo de Logradouro <span class="obrigatorio">*</span>
                            </label>

                            <input type="text" name="tipoLogradouro" id="tipoLogradouro"
                                value="<?= htmlspecialchars($isEdit ? $funcionario->getTipoLogradouro() : '') ?>"
                                minlength="3" title="Digite o tipo de logradouro"
                                placeholder="Ex.: Rua, Avenida, Alameda, Viela" required>
                        </div>

                        <div class="form-group">
                            <label for="nomeLogradouro">
                                Logradouro <span class="obrigatorio">*</span>
                            </label>

                            <input type="text" name="nomeLogradouro" id="nomeLogradouro"
                                value="<?= htmlspecialchars($isEdit ? $funcionario->getNomeLogradouro() : '') ?>"
                                minlength="3" title="Digite o nome do logradouro"
                                placeholder="Digite o nome da Rua/Avenida/Alameda/Viela" required>
                        </div>

                        <div class="form-group">
                            <label>Número <span class="obrigatorio">*</span></label>
                            <input type="text" name="numero"
                                value="<?= htmlspecialchars($isEdit ? $funcionario->getNumero() : '') ?>"
                                pattern="[0-9]+" placeholder="Somente números" required>
                        </div>

                        <div class="form-group">
                            <label>Complemento</label>
                            <input type="text" name="complemento"
                                value="<?= htmlspecialchars($isEdit ? ($funcionario->getComplemento() ?? '') : '') ?>"
                                placeholder="Números e letras">
                        </div>

                        <div class="form-group">
                            <label>Cidade <span class="obrigatorio">*</span></label>
                            <input type="text" name="cidade" id="cidade"
                                value="<?= htmlspecialchars($isEdit ? $funcionario->getCidade() : '') ?>" minlength="3"
                                pattern="[A-Za-zÀ-ÿ\s]+" title="Digite pelo menos 3 letras"
                                placeholder="Digite o nome da cidade" required>
                        </div>

                        <div class="form-group">
                            <label>CEP <span class="obrigatorio">*</span></label>
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
                                inputmode="numeric" oninput="mascaraCEP(this)" placeholder="00000-000" required>
                        </div>

                        <div class="form-group">
                            <label>Estado <span class="obrigatorio">*</span></label>
                            <?php $estado = $isEdit ? $funcionario->getEstado() : ''; ?>
                            <select name="estado" id="estado" required>
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
                            <label>Tipo de Contrato <span class="obrigatorio">*</span></label>
                            <?php $tipoContrato = $isEdit ? $funcionario->getTipoContrato() : ''; ?>

                            <select name="tipoContrato" required>
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
                            <label>Telefone <span class="obrigatorio">*</span></label>
                            <input type="text" name="telefone" placeholder="(XX) 0000-0000"
                                oninput="mascaraTelefone(this)" value="<?= htmlspecialchars($telefoneFormatado) ?>"
                                required>
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
                                        <label>Admissão <span class="obrigatorio">*</span></label>

                                        <input type="date" name="dataAdmissao"
                                            value="<?= htmlspecialchars($isEdit ? $funcionario->getDataAdmissao() : '') ?>"
                                            required>

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
                        <label class="obrigatorio">* Campos de preenchimento obrigatório.</label>
                    </div>
                </fieldset>
            </form>

        </section>
        <div class="acoes">

            <button type="submit" form="form-dados" class="btn novo"
                formaction="<?= BASE_URL ?>/index.php?url=funcionarios/store" <?= $isNovo ? '' : 'disabled' ?>>
                <i class="bi bi-plus-lg"></i>
                Cadastrar
            </button>

            <button type="submit" form="form-dados" class="btn alterar"
                formaction="<?= BASE_URL ?>/index.php?url=funcionarios/update&id=<?= $isEdit ? $funcionario->getIdFuncionario() : '' ?>"
                <?= $isEdit ? '' : 'disabled' ?>>

                <i class="bi bi-pencil-square"></i>
                Alterar
            </button>

            <button type="submit" form="form-dados" class="btn excluir"
                formaction="<?= BASE_URL ?>/index.php?url=funcionarios/delete&id=<?= $isEdit ? $funcionario->getIdFuncionario() : '' ?>"
                onclick="return confirm('Tem certeza que deseja excluir este funcionário?');" <?= $isEdit ? '' : 'disabled' ?>>

                <i class="bi bi-trash"></i>
                Excluir
            </button>

            <button type="button" class="btn cancelar"
                onclick="window.location.href='<?= BASE_URL ?>/index.php?url=funcionarios'">
                Cancelar
            </button>

            <button type="button" class="btn limpar" id="btnLimpar">
                Limpar
            </button>

        </div>

    </main>
</div>
<script src="<?= BASE_URL ?>/assets/js/mascaras.js?v=<?= time() ?>"></script>
<script src="<?= BASE_URL ?>/assets/js/funcionario.js?v=<?= time() ?>"></script>
</body>

</html>