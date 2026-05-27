<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
   <link rel="shortcut icon" href="/ideal/public/assets/icons/clientes2.png" type="image/x-icon">
    <link rel="stylesheet" href="/ideal/public/assets/css/dashboard.css">
    <link rel="stylesheet" href="/ideal/public/assets/css/clientes.css?v=<?= time() ?>">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

    <div class="dashboard-container">

        <!-- SIDEBAR -->
        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

        <!-- CONTEÚDO -->
        <main class="main-content">

            <!-- BUSCA CLIENTE -->
            <section class="card">

                <div class="grid-busca">

                    <!-- FORM BUSCA -->
                    <div class="busca-box">

                        <h2>
                            <i class="fa-solid fa-users"></i>
                            BUSCAR CLIENTE
                        </h2>

                        <form class="form-busca"
                            action="/ideal/public/index.php?url=cliente"
                            method="POST">

                            <div class="input-group">

                                <label>Tipo</label>

                                <select name="tipoDocumento" id="tipoDocumento"
                                    onchange="alterarMascaraDocumento()">

                                    <option value="cpf">CPF</option>
                                    <option value="cnpj">CNPJ</option>

                                </select>

                            </div>

                            <div class="input-group">

                                <label id="labelDocumento">
                                    CPF
                                </label>

                                <input type="text"
                                    id="documento"
                                    name="documento"
                                    placeholder="000.000.000-00"
                                    maxlength="14"
                                    oninput="mascaraDocumento(this)">

                            </div>

                            <button type="submit" class="btn-buscar">
                                BUSCAR
                            </button>

                        </form>

                    </div>

                    <!-- DICA -->
                    <div class="dica-box">

                        <h3>
                            <i class="fa-solid fa-circle-info"></i>
                            DICA
                        </h3>

                        <p>
                            Selecione CPF ou CNPJ, digite o documento do cliente e clique em
                            <strong>BUSCAR</strong>.
                            Se não existir, você poderá cadastrar um novo cliente.
                        </p>

                    </div>

                </div>

            </section>

            <!-- DADOS CLIENTE -->
            <section class="card">

                <h2>Dados do Cliente</h2>

                <form id="form-dados"
                    action="<?= $actionUrl ?>"
                    method="POST">

                    <div class="grid-form">

                        <!-- DADOS PRINCIPAIS -->

                        <div class="form-group">

                            <label>Nome do Cliente</label>

                            <input type="text"
                                name="nomeCliente"
                                minlength="3"
                                maxlength="45"
                                placeholder="Digite o nome do cliente"
                                required>

                        </div>

                        <div class="form-group">

                            <label>CPF</label>

                            <input type="text"
                                name="cpf"
                                id="cpf"
                                placeholder="000.000.000-00"
                                maxlength="14"
                                oninput="mascaraCPF(this)">

                        </div>

                        <div class="form-group">

                            <label>CNPJ</label>

                            <input type="text"
                                name="cnpj"
                                id="cnpj"
                                placeholder="00.000.000/0000-00"
                                maxlength="18"
                                oninput="mascaraCNPJ(this)">

                        </div>

                        <!-- CONTATO -->

                        <h2 class="subtitulo-form">
                            Contato
                        </h2>

                        <div class="form-group">

                            <label>Telefone</label>

                            <input type="text"
                                name="telefone"
                                placeholder="(00) 00000-0000"
                                maxlength="15"
                                oninput="mascaraTelefone(this)">

                        </div>

                        <div class="form-group">

                            <label>E-mail</label>

                            <input type="email"
                                name="email"
                                placeholder="cliente@email.com">

                        </div>

                        <div class="form-group">

                            <label>Tipo de Cliente</label>

                            <select name="tipoCliente">

                                <option value="">Selecione</option>

                                <option value="PESSOA_FISICA">
                                    Pessoa Física
                                </option>

                                <option value="PESSOA_JURIDICA">
                                    Pessoa Jurídica
                                </option>

                            </select>

                        </div>

                        <!-- ENDEREÇO -->

                        <h2 class="subtitulo-form">
                            Endereço
                        </h2>

                        <div class="form-group">

                            <label>CEP</label>

                            <input type="text"
                                name="cep"
                                placeholder="00000-000"
                                maxlength="9"
                                oninput="mascaraCEP(this)">

                        </div>

                        <div class="form-group">

                            <label>Cidade</label>

                            <input type="text"
                                name="cidade"
                                placeholder="Digite a cidade">

                        </div>

                        <div class="form-group">

                            <label>Estado</label>

                            <select name="estado">

                                <option value="">Selecione</option>

                                <option value="SP">SP</option>
                                <option value="RJ">RJ</option>
                                <option value="MG">MG</option>
                                <option value="PR">PR</option>
                                <option value="SC">SC</option>

                            </select>

                        </div>

                        <div class="form-group observacoes">

                            <label>Observações</label>

                            <textarea name="observacoes"></textarea>

                        </div>

                    </div>

                </form>

            </section>

            <!-- BOTÕES -->
            <div class="acoes">

                <button type="submit"
                    form="form-dados"
                    class="btn novo">

                    Novo

                </button>

                <button type="submit"
                    form="form-dados"
                    class="btn alterar">

                    Alterar

                </button>

                <button type="submit"
                    form="form-dados"
                    class="btn excluir">

                    Excluir

                </button>

                <button type="reset"
                    form="form-dados"
                    class="btn limpar">

                    Limpar

                </button>

            </div>

        </main>

    </div>

    <!-- SCRIPT DOCUMENTO -->
    <script>

        function alterarMascaraDocumento() {

            const tipo = document.getElementById('tipoDocumento').value;
            const input = document.getElementById('documento');
            const label = document.getElementById('labelDocumento');

            input.value = '';

            if (tipo === 'cpf') {

                label.innerText = 'CPF';
                input.placeholder = '000.000.000-00';
                input.maxLength = 14;

            } else {

                label.innerText = 'CNPJ';
                input.placeholder = '00.000.000/0000-00';
                input.maxLength = 18;

            }

        }

        function mascaraDocumento(input) {

            const tipo = document.getElementById('tipoDocumento').value;

            if (tipo === 'cpf') {

                mascaraCPF(input);

            } else {

                mascaraCNPJ(input);

            }

        }

    </script>

    <!-- SCRIPT CPF -->
    <script>

        function mascaraCPF(input) {

            let valor = input.value.replace(/\D/g, '');

            valor = valor.substring(0, 11);

            valor = valor.replace(/(\d{3})(\d)/, '$1.$2');

            valor = valor.replace(/(\d{3})(\d)/, '$1.$2');

            valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

            input.value = valor;

        }

    </script>

    <!-- SCRIPT CNPJ -->
    <script>

        function mascaraCNPJ(input) {

            let valor = input.value.replace(/\D/g, '');

            valor = valor.substring(0, 14);

            valor = valor.replace(/^(\d{2})(\d)/, '$1.$2');

            valor = valor.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');

            valor = valor.replace(/\.(\d{3})(\d)/, '.$1/$2');

            valor = valor.replace(/(\d{4})(\d)/, '$1-$2');

            input.value = valor;

        }

    </script>

    <!-- SCRIPT TELEFONE -->
    <script>

        function mascaraTelefone(input) {

            let valor = input.value.replace(/\D/g, '');

            valor = valor.substring(0, 11);

            valor = valor.replace(/^(\d{2})(\d)/g, '($1) $2');

            valor = valor.replace(/(\d)(\d{4})$/, '$1-$2');

            input.value = valor;

        }

    </script>

    <!-- SCRIPT CEP -->
    <script>

        function mascaraCEP(input) {

            let valor = input.value.replace(/\D/g, '');

            valor = valor.substring(0, 8);

            valor = valor.replace(/^(\d{5})(\d)/, '$1-$2');

            input.value = valor;

        }

    </script>

</body>

</html>