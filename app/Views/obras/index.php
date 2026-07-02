<?php
$actionUrl ??= '/ideal/public/index.php?url=obras/store';
$titulo = 'Obras';
$favicon = '/ideal/public/assets/icon/obra2.png';

// Instanciando os Models diretamente para contornar a alteração do Controller
$modelFuncionario = new \App\Models\Funcionario();
$funcionarios = $modelFuncionario->listar();

$modelVeiculo = new \App\Models\Veiculo();
$veiculos = $modelVeiculo->listar();

// Array de cargos (mesmo padrão utilizado em funcionários)
$cargos = [
    'Almoxarife', 'Analista Financeiro', 'Assistente Administrativo', 'Assistente de RH',
    'Auxiliar Administrativo', 'Auxiliar de Eletricista', 'Cabista', 'Comprador',
    'Designer Gráfico', 'Eletricista de Manutenção', 'Eletricista Industrial',
    'Eletricista Montador', 'Eletricista Predial', 'Encarregado de Obras Elétricas',
    'Instalador Elétrico', 'Mestre de Obras', 'Montador de Painéis Elétricos',
    'Oficial Eletricista', 'Social Midia'
];
sort($cargos);

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
                    
                    <div class="grid-form">
                        
                        <div class="form-group">
                            <label>Contrato</label>
                            <input type="text" name="contrato" maxlength="45" placeholder="Digite o contrato"
                                value="<?= isset($obra) ? $obra->getContrato() : '' ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Status da Obra</label>
                            <select name="status" required>
                                <option value="">Selecione</option>
                                <option value="Em andamento" <?= isset($obra) && $obra->getStatus() === 'Em andamento' ? 'selected' : '' ?>>Em andamento</option>
                                <option value="Concluida" <?= isset($obra) && $obra->getStatus() === 'Concluida'    ? 'selected' : '' ?>>Concluída</option>
                                <option value="Cancelada" <?= isset($obra) && $obra->getStatus() === 'Cancelada'    ? 'selected' : '' ?>>Cancelada</option>
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

                        <div class="cliente-area">
                            <input type="hidden" name="idCliente" id="idCliente" value="<?= isset($obra) ? $obra->getIdCliente() : '' ?>">
                            
                            <?php 
                            // Validação segura para resgatar os dados do objeto Cliente
                            $docCliente = '';
                            $nomeCli = '-';
                            $whatsappCli = '-';
                            
                            if (isset($cliente) && $cliente) {
                                // Tenta pegar o CNPJ ou CPF
                                $docCliente = (method_exists($cliente, 'getCnpj') && $cliente->getCnpj()) ? $cliente->getCnpj() : ((method_exists($cliente, 'getCpf')) ? $cliente->getCpf() : '');
                                // Tenta pegar o Nome ou Razão Social
                                $nomeCli = method_exists($cliente, 'getNomeCliente') ? $cliente->getNomeCliente() : (method_exists($cliente, 'getNome') ? $cliente->getNome() : '-');
                                // Tenta pegar o WhatsApp
                                $whatsappCli = (method_exists($cliente, 'getTelefone') && $cliente->getTelefone()) ? $cliente->getTelefone() : '-';
                            }
                            ?>

                            <div class="form-group">
                                <label>CNPJ / CPF Cliente</label>
                                <input type="text" id="cnpjCliente" name="cnpjCliente" oninput="mascaraCNPJ(this)" maxlength="18" placeholder="00.000.000/0000-00" value="<?= htmlspecialchars($docCliente) ?>">
                            </div>
                            
                            <div class="cliente-card">
                                <h3><i class="fa-solid fa-user"></i> Dados do Cliente</h3>
                                <div class="cliente-grid">
                                    <div class="cliente-info"><span>Nome / Razão Social</span><strong id="clienteNome"><?= htmlspecialchars($nomeCli) ?></strong></div>
                                    <div class="cliente-info"><span>CPF/CNPJ</span><strong id="clienteCnpj"><?= htmlspecialchars($docCliente ?: '-') ?></strong></div>
                                    <div class="cliente-info"><i class="fa-brands fa-whatsapp"></i><span>WhatsApp</span><strong id="clienteWhatsapp"><?= htmlspecialchars($whatsappCli) ?></strong></div>
                                </div>
                            </div>
                        </div> </div> </section>

                <section class="card">
                    <h2 class="titulo-card"><i class="fa-solid fa-location-dot"></i> Endereço da Obra</h2>
                    <div class="grid-form">
                        <div class="form-group"><label>CEP</label><input type="text" name="cep" value="<?= isset($obra) ? $obra->getCep() : '' ?>" placeholder="00000-000" maxlength="9" oninput="mascaraCEP(this)" required></div>
                        <div class="form-group"><label>Estado</label><input type="text" name="estado" placeholder="UF" value="<?= isset($obra) ? $obra->getEstado() : '' ?>"></div>
                        <div class="form-group"><label>Cidade</label><input type="text" name="cidade" placeholder="Digite a cidade" value="<?= isset($obra) ? $obra->getCidade() : '' ?>"></div>
                        <div class="form-group"><label>Logradouro</label><input type="text" name="logradouro" placeholder="Rua, Avenida, Alameda..." value="<?= isset($obra) ? $obra->getLogradouro() : '' ?>"></div>
                        <div class="form-group"><label>Endereço</label><input type="text" name="endereco" placeholder="Digite o endereço" value="<?= isset($obra) ? $obra->getEndereco() : '' ?>"></div>
                        <div class="form-group"><label>Número</label><input type="text" name="numero" placeholder="1234" value="<?= isset($obra) ? $obra->getNumero() : '' ?>"></div>
                        <div class="form-group"><label>Complemento</label><input type="text" name="complemento" placeholder="Apartamento, bloco, sala..." value="<?= isset($obra) ? $obra->getComplemento() : '' ?>"></div>
                        <div class="form-group observacoes"><label>Observações</label><textarea name="observacoes" placeholder="Digite as observações (opcional)"></textarea></div>
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
                            <select name="funcao" id="funcaoSelect">
                                <option value="">Selecione</option>
                                <?php foreach ($cargos as $cargo): ?>
                                    <option value="<?= htmlspecialchars($cargo) ?>"><?= htmlspecialchars($cargo) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Veículo</label>
                            <select name="idVeiculo" id="idVeiculoSelect">
                                <option value="">Selecione</option>
                                <?php foreach ($veiculos as $v): ?>
                                    <?php if ($v['statusVeiculo'] === 'ATIVO'): ?>
                                        <option value="<?= $v['idVeiculo'] ?>"><?= htmlspecialchars($v['modelo'] . ' - ' . $v['placa']) ?></option>
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
                        <div class="form-group"><label>Data Início</label><input type="date" name="dataInicioFuncionario" id="dataInicioFuncionario"></div>
                        <div class="form-group"><label>Data Saída</label><input type="date" name="dataSaidaFuncionario" id="dataSaidaFuncionario"></div>
                        
                        <div class="form-group btn-area"><button type="button" class="btn-adicionar" onclick="adicionarFuncionarioNaTabela()"><i class="fa-solid fa-plus"></i> Adicionar</button></div>
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
                                            <input type="hidden" name="funcionariosObra[<?= $indiceFuncionario ?>][idFuncionario]" value="<?= $idFunc ?>">
                                            <input type="hidden" name="funcionariosObra[<?= $indiceFuncionario ?>][idVeiculo]" value="<?= $idVeic ?>">
                                        </td>
                                        <td><?= $funcao ?></td>
                                        <td><?= $modelo ?></td>
                                        <td><?= $placa ?></td>
                                        <td><?= $dtInicio ?></td>
                                        <td><?= $dtSaida ?></td>
                                        <td><span class="status <?= $statusClass ?>"><?= $status ?></span></td>
                                        <td class="acoes-tabela">
                                            <button type="button" class="btn-excluir" onclick="removerFuncionarioDaTabela(this)"><i class="fa-solid fa-trash"></i></button>
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
                    <div class="info-tabela"><i class="fa-solid fa-circle-info"></i> Informe o veículo utilizado pelo funcionário para deslocamento até a obra.</div>
                </section>

                <div class="acoes">
                    <button type="submit" form="form-dados" class="btn novo"><i class="bi bi-plus-lg"></i> Cadastrar</button>
                    <button type="submit" form="form-dados" class="btn alterar"><i class="bi bi-pencil-square"></i> Alterar</button>
                    <button type="submit" form="form-dados" class="btn excluir"><i class="bi bi-trash"></i> Excluir</button>
                    <button type="reset" form="form-dados" class="btn limpar" onclick="limparCliente()"><i class="bi bi-eraser"></i> Limpar</button>
                </div>
                
            </form>
        </main>
    </div>

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

        // Lógica de Máscaras e Busca de Clientes
        function mascaraCEP(input) {
            let valor = input.value.replace(/\D/g, '');
            valor = valor.substring(0, 8);
            valor = valor.replace(/^(\d{5})(\d)/, '$1-$2');
            input.value = valor;
        }

        function mascaraCNPJ(input) {
            let valor = input.value.replace(/\D/g, '');
            valor = valor.substring(0, 14);
            valor = valor.replace(/^(\d{2})(\d)/, '$1.$2');
            valor = valor.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
            valor = valor.replace(/\.(\d{3})(\d)/, '.$1/$2');
            valor = valor.replace(/(\d{4})(\d)/, '$1-$2');
            input.value = valor;

            const apenasNumeros = valor.replace(/\D/g, '');
            if (apenasNumeros.length === 14) {
                buscarClientePorCnpj(apenasNumeros);
            }
        }

        function buscarClientePorCnpj(cnpj) {
            fetch(`/ideal/public/index.php?url=clientes/buscarPorCnpj&cnpj=${cnpj}`)
                .then(res => res.json())
                .then(data => {
                    if (data && data.idCliente) {
                        document.getElementById('idCliente').value = data.idCliente;
                        document.getElementById('clienteNome').textContent = data.nomeCliente ?? '-';
                        document.getElementById('clienteCnpj').textContent = data.cnpj ?? data.cpf ?? '-';
                        document.getElementById('clienteWhatsapp').textContent = data.whatsapp ?? '-';
                    } else {
                        document.getElementById('idCliente').value = '';
                        document.getElementById('clienteNome').textContent = '-';
                        document.getElementById('clienteCnpj').textContent = '-';
                        document.getElementById('clienteWhatsapp').textContent = '-';
                    }
                })
                .catch(() => {
                    console.error('Erro na busca');
                });
        }

        function limparCliente() {
            document.getElementById('idCliente').value = '';
            document.getElementById('clienteNome').textContent = '-';
            document.getElementById('clienteCnpj').textContent = '-';
            document.getElementById('clienteWhatsapp').textContent = '-';
            const cnpjInput = document.getElementById('cnpjCliente');
            if (cnpjInput) cnpjInput.value = '';
        }
    </script>
</body>

</html>