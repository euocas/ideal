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

//HEADER
require_once __DIR__ . '/../includes/header.php';
?>

<link rel="shortcut icon" href="/ideal/public/assets/icons/funcionario2.png" type="image/x-icon">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="/ideal/public/assets/css/dashboard.css">
<link rel="stylesheet" href="/ideal/public/assets/css/variables.css">
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
                            <i class="fa-solid fa-magnifying-glass"></i>
                            BUSCAR FUNCIONÁRIO
                        </h2>

                        <?php if (!empty($mensagem)): ?>
                            <div class="alerta-cadastro">
                                <?= $mensagem ?>
                            </div>
                        <?php endif; ?>

                        <form class="form-busca" action="/ideal/public/index.php?url=funcionarios" method="POST">
                            <div class="input-group">
                                <label>CPF</label>
                                <input type="text" name="cpf" oninput="mascaraCPF(this)" placeholder="000.000.000-00"
                                    required maxlength="14">
                            </div>
                            <button type="submit" class="btn-buscar">BUSCAR</button>
                        </form>
                    </div>

                    <div class="dica-box">
                        <h3>
                            <i class="fa-solid fa-circle-info"></i>
                            DICA
                        </h3>
                        <p>Digite o CPF do funcionário e clique em <strong>BUSCAR</strong>. Se não existir, você poderá
                            cadastrar um novo funcionário.</p>
                    </div>
                </div>
            </section>

            <section class="card">

                <?php if (isset($_SESSION['mensagem_sucesso'])): ?>
                    <div
                        style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb; font-weight: bold;">
                        ✅ <?= $_SESSION['mensagem_sucesso']; ?>
                    </div>
                    <?php unset($_SESSION['mensagem_sucesso']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['mensagem_erro'])): ?>
                    <div
                        style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb; font-weight: bold;">
                        ❌ <?= $_SESSION['mensagem_erro']; ?>
                    </div>
                    <?php unset($_SESSION['mensagem_erro']); ?>
                <?php endif; ?>

                <h2>Dados do Funcionário</h2>

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
                            <select name="estadoNascimento" required>
                                <option value="">Selecione o Estado</option>
                                <option value="AC" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'AC' ? 'selected' : '' ?>>Acre</option>
                                <option value="AL" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'AL' ? 'selected' : '' ?>>Alagoas</option>
                                <option value="AP" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'AP' ? 'selected' : '' ?>>Amapá</option>
                                <option value="AM" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'AM' ? 'selected' : '' ?>>Amazonas</option>
                                <option value="BA" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'BA' ? 'selected' : '' ?>>Bahia</option>
                                <option value="CE" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'CE' ? 'selected' : '' ?>>Ceará</option>
                                <option value="DF" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'DF' ? 'selected' : '' ?>>Distrito Federal</option>
                                <option value="ES" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'ES' ? 'selected' : '' ?>>Espírito Santo</option>
                                <option value="GO" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'GO' ? 'selected' : '' ?>>Goiás</option>
                                <option value="MA" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'MA' ? 'selected' : '' ?>>Maranhão</option>
                                <option value="MT" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'MT' ? 'selected' : '' ?>>Mato Grosso</option>
                                <option value="MS" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'MS' ? 'selected' : '' ?>>Mato Grosso do Sul</option>
                                <option value="MG" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'MG' ? 'selected' : '' ?>>Minas Gerais</option>
                                <option value="PA" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'PA' ? 'selected' : '' ?>>Pará</option>
                                <option value="PB" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'PB' ? 'selected' : '' ?>>Paraíba</option>
                                <option value="PR" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'PR' ? 'selected' : '' ?>>Paraná</option>
                                <option value="PE" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'PE' ? 'selected' : '' ?>>Pernambuco</option>
                                <option value="PI" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'PI' ? 'selected' : '' ?>>Piauí</option>
                                <option value="RJ" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'RJ' ? 'selected' : '' ?>>Rio de Janeiro</option>
                                <option value="RN" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'RN' ? 'selected' : '' ?>>Rio Grande do Norte</option>
                                <option value="RS" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'RS' ? 'selected' : '' ?>>Rio Grande do Sul</option>
                                <option value="RO" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'RO' ? 'selected' : '' ?>>Rondônia</option>
                                <option value="RR" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'RR' ? 'selected' : '' ?>>Roraima</option>
                                <option value="SC" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'SC' ? 'selected' : '' ?>>Santa Catarina</option>
                                <option value="SP" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'SP' ? 'selected' : '' ?>>São Paulo</option>
                                <option value="SE" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'SE' ? 'selected' : '' ?>>Sergipe</option>
                                <option value="TO" <?= ($isEdit ? $funcionario->getEstadoNascimento() : '') === 'TO' ? 'selected' : '' ?>>Tocantins</option>
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
                                <option value="Auxiliar Administrativo" <?= ($isEdit ? $funcionario->getCargoFuncao() : '') === 'Auxiliar Administrativo' ? 'selected' : '' ?>>Auxiliar Administrativo
                                </option>
                                <option value="Auxiliar de RH" <?= ($isEdit ? $funcionario->getCargoFuncao() : '') === 'Auxiliar de RH' ? 'selected' : '' ?>>Auxiliar de RH</option>
                                <option value="Azulejista" <?= ($isEdit ? $funcionario->getCargoFuncao() : '') === 'Azulejista' ? 'selected' : '' ?>>Azulejista</option>
                                <option value="Eletricista" <?= ($isEdit ? $funcionario->getCargoFuncao() : '') === 'Eletricista' ? 'selected' : '' ?>>Eletricista</option>
                                <option value="Encarregado" <?= ($isEdit ? $funcionario->getCargoFuncao() : '') === 'Encarregado' ? 'selected' : '' ?>>Encarregado</option>
                                <option value="Marceneiro" <?= ($isEdit ? $funcionario->getCargoFuncao() : '') === 'Marceneiro' ? 'selected' : '' ?>>Marceneiro</option>
                                <option value="Pintor" <?= ($isEdit ? $funcionario->getCargoFuncao() : '') === 'Pintor' ? 'selected' : '' ?>>Pintor</option>
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
                                value="<?= htmlspecialchars($isEdit ? $funcionario->getComplemento() : '') ?>"
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
                            <input type="text" name="cep"
                                value="<?= htmlspecialchars($isEdit ? $funcionario->getCep() : '') ?>" maxlength="9"
                                inputmode="numeric" oninput="mascaraCEP(this)" placeholder="00000-000">
                        </div>

                        <div class="form-group">
                            <label>Estado</label>
                            <select name="estado" required>
                                <option value="">Selecione o Estado</option>
                                <option value="AC" <?= ($isEdit ? $funcionario->getEstado() : '') === 'AC' ? 'selected' : '' ?>>Acre</option>
                                <option value="AL" <?= ($isEdit ? $funcionario->getEstado() : '') === 'AL' ? 'selected' : '' ?>>Alagoas</option>
                                <option value="AP" <?= ($isEdit ? $funcionario->getEstado() : '') === 'AP' ? 'selected' : '' ?>>Amapá</option>
                                <option value="AM" <?= ($isEdit ? $funcionario->getEstado() : '') === 'AM' ? 'selected' : '' ?>>Amazonas</option>
                                <option value="BA" <?= ($isEdit ? $funcionario->getEstado() : '') === 'BA' ? 'selected' : '' ?>>Bahia</option>
                                <option value="CE" <?= ($isEdit ? $funcionario->getEstado() : '') === 'CE' ? 'selected' : '' ?>>Ceará</option>
                                <option value="DF" <?= ($isEdit ? $funcionario->getEstado() : '') === 'DF' ? 'selected' : '' ?>>Distrito Federal</option>
                                <option value="ES" <?= ($isEdit ? $funcionario->getEstado() : '') === 'ES' ? 'selected' : '' ?>>Espírito Santo</option>
                                <option value="GO" <?= ($isEdit ? $funcionario->getEstado() : '') === 'GO' ? 'selected' : '' ?>>Goiás</option>
                                <option value="MA" <?= ($isEdit ? $funcionario->getEstado() : '') === 'MA' ? 'selected' : '' ?>>Maranhão</option>
                                <option value="MT" <?= ($isEdit ? $funcionario->getEstado() : '') === 'MT' ? 'selected' : '' ?>>Mato Grosso</option>
                                <option value="MS" <?= ($isEdit ? $funcionario->getEstado() : '') === 'MS' ? 'selected' : '' ?>>Mato Grosso do Sul</option>
                                <option value="MG" <?= ($isEdit ? $funcionario->getEstado() : '') === 'MG' ? 'selected' : '' ?>>Minas Gerais</option>
                                <option value="PA" <?= ($isEdit ? $funcionario->getEstado() : '') === 'PA' ? 'selected' : '' ?>>Pará</option>
                                <option value="PB" <?= ($isEdit ? $funcionario->getEstado() : '') === 'PB' ? 'selected' : '' ?>>Paraíba</option>
                                <option value="PR" <?= ($isEdit ? $funcionario->getEstado() : '') === 'PR' ? 'selected' : '' ?>>Paraná</option>
                                <option value="PE" <?= ($isEdit ? $funcionario->getEstado() : '') === 'PE' ? 'selected' : '' ?>>Pernambuco</option>
                                <option value="PI" <?= ($isEdit ? $funcionario->getEstado() : '') === 'PI' ? 'selected' : '' ?>>Piauí</option>
                                <option value="RJ" <?= ($isEdit ? $funcionario->getEstado() : '') === 'RJ' ? 'selected' : '' ?>>Rio de Janeiro</option>
                                <option value="RN" <?= ($isEdit ? $funcionario->getEstado() : '') === 'RN' ? 'selected' : '' ?>>Rio Grande do Norte</option>
                                <option value="RS" <?= ($isEdit ? $funcionario->getEstado() : '') === 'RS' ? 'selected' : '' ?>>Rio Grande do Sul</option>
                                <option value="RO" <?= ($isEdit ? $funcionario->getEstado() : '') === 'RO' ? 'selected' : '' ?>>Rondônia</option>
                                <option value="RR" <?= ($isEdit ? $funcionario->getEstado() : '') === 'RR' ? 'selected' : '' ?>>Roraima</option>
                                <option value="SC" <?= ($isEdit ? $funcionario->getEstado() : '') === 'SC' ? 'selected' : '' ?>>Santa Catarina</option>
                                <option value="SP" <?= ($isEdit ? $funcionario->getEstado() : '') === 'SP' ? 'selected' : '' ?>>São Paulo</option>
                                <option value="SE" <?= ($isEdit ? $funcionario->getEstado() : '') === 'SE' ? 'selected' : '' ?>>Sergipe</option>
                                <option value="TO" <?= ($isEdit ? $funcionario->getEstado() : '') === 'TO' ? 'selected' : '' ?>>Tocantins</option>
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
                        <!--  
                        <div class="form-group observacao">
                            <label>Observações</label>
                            <textarea
                                name="observacoes"><?= htmlspecialchars($isEdit ? $funcionario->getObservacoes() : '') ?></textarea>
                        </div> -->
                        <div class="secao-inferior">
                            <!-- Dados de Contratação -->
                            <div class="card-contratacao">
                                <h2>Dados de Contratação</h2>
                                <div class="form-group">
                                    <label>Data de Admissão</label>
                                    <input type="date" name="dataAdmissao"
                                        value="<?= htmlspecialchars($isEdit ? $funcionario->getDataAdmissao() : '') ?>">
                                </div>
                                <div class="form-group">
                                    <label>Data de Desligamento</label>
                                    <input type="date" name="dataDesligamento"
                                        value="<?= htmlspecialchars($isEdit ? $funcionario->getDataDesligamento() : '') ?>">
                                </div>
                                <div class="form-group">
                                    <label>Férias Programadas</label>
                                    <input type="date" name="feriasProgramadas"
                                        value="<?= htmlspecialchars($isEdit ? $funcionario->getFeriasProgramadas() : '') ?>">
                                </div>
                            </div>

                            <!-- Observações -->
                            <div class="card-observacao">
                                <h2>Observações</h2>
                                <textarea
                                    name="observacoes" maxlength="500" placeholder="Digite alguma observação se for necessário" ><?= htmlspecialchars($isEdit ? $funcionario->getObservacoes() : '') ?></textarea>
                            </div>
                        </div>


                    </div>
                </form>

            </section>

            <div class="acoes">
                <a href="/ideal/public/index.php?url=funcionarios" class="btn novo"
                    style="text-decoration:none; text-align:center; display:inline-block; line-height: 40px;">Novo</a>

                <?php if (!$isEdit): ?>
                    <button type="submit" form="form-dados" class="btn salvar">Salvar</button>
                <?php else: ?>
                    <button type="submit" form="form-dados" class="btn alterar">Alterar</button>
                    <a href="/ideal/public/index.php?url=funcionarios/delete&id=<?= $funcionario->getIdFuncionario() ?>"
                        class="btn excluir"
                        style="text-decoration:none; text-align:center; display:inline-block; line-height: 40px;"
                        onclick="return confirm('Tem certeza que deseja excluir este funcionário?')">Excluir</a>
                <?php endif; ?>

                <button type="reset" form="form-dados" class="btn limpar">Limpar</button>
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