<?php
use App\Config\SistemaConstantes;
use App\Config\FuncionarioConstantes;

$actionUrl ??= '/ideal/public/index.php?url=obras/store';
$titulo = 'Obras';
$favicon = '/ideal/public/assets/icon/obra2.png';

// Instanciando os Models diretamente para contornar a alteração do Controller
$modelFuncionario = new \App\Models\Funcionario();
$funcionarios = $modelFuncionario->listar();

$modelVeiculo = new \App\Models\Veiculo();
$veiculos = $modelVeiculo->listar();

// Estado da Tela
$modoNovo = isset($_GET['novo']);
$modoEdicao = isset($obra);


require_once __DIR__ . '/../includes/header.php';
?>

<link rel="stylesheet" href="/ideal/public/assets/css/variables.css">
<link rel="stylesheet" href="/ideal/public/assets/css/base.css">
<link rel="stylesheet" href="/ideal/public/assets/css/component.css">
<link rel="stylesheet" href="/ideal/public/assets/css/forms.css">
<link rel="stylesheet" href="/ideal/public/assets/css/alerts.css">
<link rel="stylesheet" href="/ideal/public/assets/css/tables.css">
<link rel="stylesheet" href="/ideal/public/assets/css/dashboard.css">

<link rel="stylesheet" href="/ideal/public/assets/css/obras.css?v=<?= time() ?>">
</head>

<body>

    <div class="dashboard-container">

        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

        <main class="main-content">

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

            <section class="card">
                <div class="grid-busca">

                    <div class="busca-box">
                        <h2>
                            <i class="fa-solid fa-building"></i>
                            BUSCAR OBRA
                        </h2>

                        <?php if (!empty($mensagem)): ?>
                            <div class="alert alert-warning">
                                <?= htmlspecialchars($mensagem) ?>
                            </div>
                        <?php endif; ?>

                        <form class="form-busca" action="/ideal/public/index.php?url=obras" method="POST">
                            <div class="input-group">
                                <label>Contrato</label>
                                <input type="text" name="contratoBusca" placeholder="Digite o número do contrato">
                            </div>
                            <button type="submit" class="btn-buscar">
                                <i class="bi bi-search"></i> BUSCAR
                            </button>
                        </form>
                    </div>

                    <div class="dica-box">
                        <h3>
                            <i class="fa-solid fa-circle-info"></i>
                            DICA
                        </h3>
                        <p>
                            Digite o número do contrato e clique em
                            <strong>BUSCAR</strong>.
                            Se não existir, você poderá cadastrar uma nova obra.
                        </p>
                    </div>

                </div>
            </section>

            <form id="form-dados" action="<?= $actionUrl ?>" method="POST">

                <section class="card">
                    <h2 class="titulo-card">
                        <i class="fa-regular fa-clipboard"></i>
                        Dados da Obra
                    </h2>

                    <div class="grid-topo-obra">

                        <?php
                        $contratoValue = isset($obra)
                            ? $obra->getContrato()
                            : ($contratoBusca ?? '');
                        ?>
                        <div class="form-group">
                            <label>Contrato</label>
                            <input type="text" name="contrato" maxlength="45" placeholder="Digite o contrato"
                                value="<?= htmlspecialchars($contratoValue) ?>">
                        </div>


                        <div class="form-group">
                            <label>Status da Obra</label>
                            <select name="status" required>
                                <option value="">Selecione</option>
                                <option value="Em andamento" <?= isset($obra) && $obra->getStatus() === 'Em andamento' ? 'selected' : '' ?>>Em andamento</option>
                                <option value="Concluida" <?= isset($obra) && $obra->getStatus() === 'Concluida' ? 'selected' : '' ?>>Concluída</option>
                                <option value="Cancelada" <?= isset($obra) && $obra->getStatus() === 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Data de Início</label>
                            <input type="datetime-local" name="dataInicio"
                                value="<?= isset($obra) && $obra->getDataInicio() ? $obra->getDataInicio()->format('Y-m-d\TH:i') : '' ?>"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Data de Finalização</label>
                            <input type="datetime-local" name="dataFim"
                                value="<?= isset($obra) && $obra->getDataFim() ? $obra->getDataFim()->format('Y-m-d\TH:i') : '' ?>">
                        </div>
                    </div>

                    <div class="cliente-area">

                        <input type="hidden" name="idCliente" id="idCliente"
                            value="<?= isset($obra) ? $obra->getIdCliente() : '' ?>">

                        <?php
                        $docCliente = '';
                        $nomeCli = '-';
                        $whatsappCli = '-';

                        if (isset($cliente) && $cliente) {
                            $docCliente = (method_exists($cliente, 'getCnpj') && $cliente->getCnpj())
                                ? $cliente->getCnpj()
                                : ((method_exists($cliente, 'getCpf')) ? $cliente->getCpf() : '');

                            $nomeCli = method_exists($cliente, 'getNomeCliente')
                                ? $cliente->getNomeCliente()
                                : (method_exists($cliente, 'getNome') ? $cliente->getNome() : '-');

                            $whatsappCli = (method_exists($cliente, 'getTelefone') && $cliente->getTelefone())
                                ? $cliente->getTelefone()
                                : '-';
                        }
                        ?>

                        <div class="form-group">
                            <label>CNPJ / CPF Cliente</label>

                            <?php
                            $documento = preg_replace('/\D/', '', $docCliente);
                            if (strlen($documento) === 11) {
                                $documentoFormatado = preg_replace(
                                    '/(\d{3})(\d{3})(\d{3})(\d{2})/',
                                    '$1.$2.$3-$4',
                                    $documento
                                );

                            } elseif (strlen($documento) === 14) {
                                $documentoFormatado = preg_replace(
                                    '/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/',
                                    '$1.$2.$3/$4-$5',
                                    $documento
                                );

                            } else {
                                $documentoFormatado = '';
                            }
                            ?>

                            <input type="text" id="cnpjCliente" name="cnpjCliente" maxlength="18"
                                placeholder="00.000.000/0000-00" value="<?= htmlspecialchars($documentoFormatado) ?>"
                                oninput="mascaraCNPJ(this)">
                        </div>



                        <div class="cliente-card">
                            <h3>
                                <i class="fa-solid fa-user"></i>
                                Dados do Cliente
                            </h3>

                            <div class="cliente-grid">

                                <div class="cliente-info">
                                    <span>Nome / Razão Social</span>
                                    <strong id="clienteNome">
                                        <?= htmlspecialchars($nomeCli) ?>
                                    </strong>
                                </div>

                                <div class="cliente-info">
                                    <span>CPF/CNPJ</span>
                                    <strong id="clienteCnpj">
                                        <?= htmlspecialchars($documentoFormatado ?: '-') ?>
                                    </strong>

                                </div>

                                <?php
                                $telefone = preg_replace('/\D/', '', $whatsappCli);
                                if (strlen($telefone) === 11) {
                                    $telefoneFormatado = preg_replace(
                                        '/(\d{2})(\d{5})(\d{4})/',
                                        '($1) $2-$3',
                                        $telefone
                                    );
                                } elseif (strlen($telefone) === 10) {
                                    $telefoneFormatado = preg_replace(
                                        '/(\d{2})(\d{4})(\d{4})/',
                                        '($1) $2-$3',
                                        $telefone
                                    );
                                } else {
                                    $telefoneFormatado = $whatsappCli ?: '-';
                                }
                                ?>

                                <div class="cliente-info">
                                    <span>
                                        WhatsApp / Telefone
                                    </span>
                                    <strong id="clienteWhatsapp">
                                        <?= htmlspecialchars($telefoneFormatado) ?>
                                    </strong>
                                </div>

                            </div>
                        </div>

                    </div>
                </section>

                <section class="card card-endereco">
                    <h2 class="titulo-card"><i class="fa-solid fa-location-dot"></i> Endereço da Obra</h2>
                    <div class="grid-endereco">

                        <?php

                        $cepFormatado = !empty($obra) && $obra->getCep()
                            ? preg_replace(
                                '/(\d{5})(\d{3})/',
                                '$1-$2',
                                preg_replace('/\D/', '', $obra->getCep())
                            )
                            : '';
                        ?>
                        <div class="form-group endereco-cep"><label>CEP</label><input type="text" name="cep"
                                value="<?= htmlspecialchars($cepFormatado) ?>" placeholder="00000-000" maxlength="9"
                                oninput="mascaraCEP(this)" required></div>

                        <div class="form-group endereco-cidade"><label>Cidade</label><input type="text" name="cidade"
                                placeholder="Digite a cidade" value="<?= isset($obra) ? $obra->getCidade() : '' ?>">
                        </div>

                        <div class="form-group endereco-estado">
                            <label>Estado</label>
                            <select name="estado">
                                <option value="">UF</option>
                                <?php foreach (SistemaConstantes::ESTADOS as $uf => $nome): ?>
                                    <option value="<?= $uf ?>" <?= (isset($obra) && $obra->getEstado() === $uf) ? 'selected' : '' ?>>
                                        <?= $uf ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group endereco-logradouro"><label>Logradouro</label><input type="text"
                                name="logradouro" placeholder="Rua, Avenida, Alameda..."
                                value="<?= isset($obra) ? $obra->getLogradouro() : '' ?>">
                        </div>
                        <div class="form-group endereco-endereco"><label>Endereço</label><input type="text"
                                name="endereco" placeholder="Digite o endereço"
                                value="<?= isset($obra) ? $obra->getEndereco() : '' ?>">
                        </div>
                        <div class="form-group endereco-numero"><label>Número</label><input type="text" name="numero"
                                placeholder="1234" value="<?= isset($obra) ? $obra->getNumero() : '' ?>"></div>
                        <div class="form-group endereco-complemento"><label>Complemento</label><input type="text"
                                name="complemento" placeholder="Apartamento, bloco, sala..."
                                value="<?= isset($obra) ? $obra->getComplemento() : '' ?>"></div>
                        <div class="form-group endereco-observacoes"><label>Observações</label><textarea
                                name="observacoes" placeholder="Digite as observações (opcional)"></textarea></div>
                    </div>
                </section>

                <section class="card">
                    <h2 class="titulo-card"><i class="fa-solid fa-users"></i> Funcionários Vinculados à Obra</h2>
                    <div class="grid-funcionario">
                        <div class="form-group">
                            <label>Funcionário</label>
                            <select name="idFuncionario" id="idFuncionarioSelect">
                                <option value="">Selecione</option>
                                <?php foreach ($funcionarios as $f): ?>
                                    <?php if ($f['status'] === 'ativo'): ?>
                                        <option value="<?= $f['idFuncionario'] ?>"><?= htmlspecialchars($f['nome']) ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Função</label>
                            <?php
                            $cargos = FuncionarioConstantes::CARGOS;
                            sort($cargos);
                            ?>

                            <select name="funcao" id="funcaoSelect">
                                <option value="">Selecione</option>
                                <?php foreach ($cargos as $cargo): ?>
                                    <option value="<?= htmlspecialchars($cargo) ?>">
                                        <?= htmlspecialchars($cargo) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Veículo</label>
                            <select name="idVeiculo" id="idVeiculoSelect">
                                <option value="">Selecione</option>
                                <?php foreach ($veiculos as $v): ?>
                                    <?php if ($v['statusVeiculo'] === 'ATIVO'): ?>
                                        <option value="<?= $v['idVeiculo'] ?>">
                                            <?= htmlspecialchars($v['modelo'] . ' - ' . $v['placa']) ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="statusFuncionario" id="statusFuncionarioSelect">
                                <option value="Ativo">Ativo</option>
                                <option value="Inativo">Inativo</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Data Início</label><input type="date"
                                name="dataInicioFuncionario" id="dataInicioFuncionario"></div>
                        <div class="form-group"><label>Data Saída</label><input type="date" name="dataSaidaFuncionario"
                                id="dataSaidaFuncionario"></div>

                        <div class="form-group btn-area"><button type="button" class="btn-adicionar"
                                onclick="adicionarFuncionarioNaTabela()"><i class="fa-solid fa-plus"></i>
                                Adicionar</button></div>
                    </div>

                    <div class="tabela-funcionarios">
                        <table>
                            <thead>
                                <tr>
                                    <th>Funcionário</th>
                                    <th>Função / Cargo</th>
                                    <th>Veículo</th>
                                    <th>Placa</th>
                                    <th>Data de Início</th>
                                    <th>Data de Saída</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody id="tabela-funcionarios-body">
                                <?php
                                $indiceFuncionario = 0;
                                if (isset($obra) && !empty($obra->getFuncionariosVinculados())):
                                    foreach ($obra->getFuncionariosVinculados() as $func):
                                        $nome = htmlspecialchars($func['nomeFuncionario'] ?? '—');
                                        $funcao = htmlspecialchars($func['funcao'] ?? '—');
                                        $modelo = htmlspecialchars($func['modelo'] ?? '—');
                                        $placa = htmlspecialchars($func['placa'] ?? '—');

                                        $dtInicio = !empty($func['dataAdmissao']) ? date('d/m/Y', strtotime($func['dataAdmissao'])) : '—';
                                        $dtSaida = !empty($func['dataDesligamento']) ? date('d/m/Y', strtotime($func['dataDesligamento'])) : '—';
                                        $status = htmlspecialchars($func['statusFuncionario'] ?? 'Ativo');
                                        $statusClass = strtolower($status) === 'ativo' ? 'ativo' : 'inativo';

                                        $idFunc = $func['idFuncionario'];
                                        $idVeic = $func['idVeiculo'] ?? '';
                                        ?>
                                        <tr>
                                            <td>
                                                <?= $nome ?>
                                                <input type="hidden"
                                                    name="funcionariosObra[<?= $indiceFuncionario ?>][idFuncionario]"
                                                    value="<?= $idFunc ?>">
                                                <input type="hidden"
                                                    name="funcionariosObra[<?= $indiceFuncionario ?>][idVeiculo]"
                                                    value="<?= $idVeic ?>">
                                            </td>
                                            <td><?= $funcao ?></td>
                                            <td><?= $modelo ?></td>
                                            <td><?= $placa ?></td>
                                            <td><?= $dtInicio ?></td>
                                            <td><?= $dtSaida ?></td>
                                            <td><span class="status <?= $statusClass ?>"><?= $status ?></span></td>
                                            <td class="acoes-tabela">
                                                <button type="button" class="btn-excluir"
                                                    onclick="removerFuncionarioDaTabela(this)"><i
                                                        class="fa-solid fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        <?php
                                        $indiceFuncionario++;
                                    endforeach;
                                endif;
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="info-tabela"><i class="fa-solid fa-circle-info"></i> Informe o veículo utilizado pelo
                        funcionário para deslocamento até a obra.
                    </div>
                </section>

                <div class="acoes">

                    <button type="submit" form="form-dados" class="btn novo"
                        formaction="/ideal/public/index.php?url=obras/store" <?= $modoNovo ? '' : 'disabled' ?>>
                        <i class="bi bi-plus-lg"></i>
                        Cadastrar
                    </button>

                    <button type="submit" form="form-dados" class="btn alterar"
                        formaction="/ideal/public/index.php?url=obras/update&id=<?= $modoEdicao ? $obra->getIdObra() : '' ?>"
                        <?= $modoEdicao ? '' : 'disabled' ?>>
                        <i class="bi bi-pencil-square"></i>
                        Alterar
                    </button>

                    <button type="submit" form="form-dados" class="btn excluir"
                        formaction="/ideal/public/index.php?url=obras/delete&id=<?= $modoEdicao ? $obra->getIdObra() : '' ?>"
                        onclick="return confirm('Tem certeza que deseja excluir esta obra?');" <?= $modoEdicao ? '' : 'disabled' ?>>
                        <i class="bi bi-trash"></i>
                        Excluir
                    </button>

                    <button type="reset" form="form-dados" class="btn limpar" onclick="limparCliente()">
                        <i class="bi bi-eraser"></i>
                        Limpar
                    </button>

                </div>

            </form>
        </main>
    </div>
    <script src="/ideal/public/assets/js/mascara.js"></script>
    <script>
        // Lógica de Funcionários e Tabela
        let indiceFuncionario = <?= isset($indiceFuncionario) ? $indiceFuncionario : 0 ?>;

        function adicionarFuncionarioNaTabela() {
            const selectFuncionario = document.getElementById('idFuncionarioSelect');
            const selectFuncao = document.getElementById('funcaoSelect');
            const selectVeiculo = document.getElementById('idVeiculoSelect');
            const selectStatus = document.getElementById('statusFuncionarioSelect');
            const inputInicio = document.getElementById('dataInicioFuncionario');
            const inputSaida = document.getElementById('dataSaidaFuncionario');

            const idFunc = selectFuncionario.value;

            if (!idFunc) {
                alert('Por favor, selecione um funcionário.');
                return;
            }

            const nomeFunc = selectFuncionario.options[selectFuncionario.selectedIndex].text;
            const funcao = selectFuncao.value || '—';
            const idVeic = selectVeiculo.value;
            const textoVeiculo = idVeic ? selectVeiculo.options[selectVeiculo.selectedIndex].text : '—';
            const status = selectStatus.value;

            let modeloVeic = '—';
            let placaVeic = '—';
            if (idVeic) {
                const partes = textoVeiculo.split(' - ');
                modeloVeic = partes[0];
                placaVeic = partes[1] || '—';
            }

            const formataData = (dataStr) => dataStr ? dataStr.split('-').reverse().join('/') : '—';

            const tbody = document.getElementById('tabela-funcionarios-body');
            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>
                    ${nomeFunc}
                    <input type="hidden" name="funcionariosObra[${indiceFuncionario}][idFuncionario]" value="${idFunc}">
                    <input type="hidden" name="funcionariosObra[${indiceFuncionario}][idVeiculo]" value="${idVeic}">
                </td>
                <td>${funcao}</td>
                <td>${modeloVeic}</td>
                <td>${placaVeic}</td>
                <td>${formataData(inputInicio.value)}</td>
                <td>${formataData(inputSaida.value)}</td>
                <td><span class="status ${status.toLowerCase() === 'ativo' ? 'ativo' : 'inativo'}">${status}</span></td>
                <td class="acoes-tabela">
                    <button type="button" class="btn-excluir" onclick="removerFuncionarioDaTabela(this)"><i class="fa-solid fa-trash"></i></button>
                </td>
            `;

            tbody.appendChild(tr);
            indiceFuncionario++;

            selectFuncionario.value = '';
            selectFuncao.value = '';
            selectVeiculo.value = '';
            selectStatus.value = 'Ativo';
            inputInicio.value = '';
            inputSaida.value = '';
        }

        function removerFuncionarioDaTabela(botao) {
            botao.closest('tr').remove();
        }



    </script>
</body>

</html>