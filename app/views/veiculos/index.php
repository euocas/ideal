<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veículos</title>

    <link rel="stylesheet" href="/ideal/public/assets/css/dashboard.css">
    <link rel="stylesheet" href="/ideal/public/assets/css/veiculos.css?v=<?= time() ?>">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

    <div class="dashboard-container">

        <!-- SIDEBAR -->
        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

        <!-- CONTEÚDO -->
        <main class="main-content">

            <!-- Section para BUSCA pelo Renavam -->
            <section class="card">
                <!-- <h2>🚘 Buscar Veículo</h2> -->
                <div class="grid-busca">
                    <!-- FORM BUSCA -->
                    <div class="busca-box">
                        <h2>🚘 BUSCAR VEÍCULO</h2>
                        <form class="form-busca" action="/ideal/public/index.php?url=veiculo" method="POST">
                            <div class="input-group">
                                <label>RENAVAM</label>
                                <input type="text" name="renavam" oninput="mascaraRenavam(this)"
                                    placeholder="0000.000000-0" required maxlength="13">
                            </div>
                            <button type="submit" class="btn-buscar">
                                BUSCAR
                            </button>
                        </form>

                    </div>

                    <!-- DICA -->
                    <div class="dica-box">

                        <h3>DICA</h3>

                        <p>
                            Digite o Renavam do veículo e clique em
                            <strong>BUSCAR</strong>.
                            Se não existir, você poderá cadastrar um novo veículo.
                        </p>

                    </div>

                </div>

            </section>

            <!-- Section para informações dos DADOS DO VEÍCULO -->
            <section class="card">

                <h2>Dados do Veículo</h2>
                <form id="form-dados" action="<?= $actionUrl ?>" method="POST">

                    <div class="grid-form">

                        <!-- DADOS -->
                        <div class="form-group">
                            <label>Renavam</label>
                            <input type="text" name="renavam" oninput="mascaraRenavam(this)" placeholder="0000.000000-0"
                                maxlength="13" required>
                        </div>

                        <div class="form-group">
                            <label>Placa</label>
                            <input type="text" name="placa" placeholder="ABC1D23" maxlength="7" required>
                        </div>

                        <div class="form-group">
                            <label>Chassi</label>
                            <input type="text" name="chassi" oninput="mascaraChassi(this)" maxlength="17"
                                placeholder="9BWZZZ377VT004251">
                        </div>

                        <div class="form-group">
                            <label>Marca</label>
                            <select name="marca" required>
                                <option value="">Selecione a marca</option>
                                <optgroup label="Utilitários leves">
                                    <option value="Fiat">Fiat</option>
                                    <option value="Volkswagen">Volkswagen</option>
                                    <option value="Chevrolet">Chevrolet</option>
                                    <option value="Renault">Renault</option>
                                </optgroup>
                                <optgroup label="Picapes médias">
                                    <option value="Toyota">Toyota</option>
                                    <option value="Ford">Ford</option>
                                    <option value="Chevrolet">Chevrolet</option>
                                    <option value="Fiat">Fiat</option>
                                </optgroup>
                                <optgroup label="SUVs e apoio">
                                    <option value="Toyota">Toyota</option>
                                    <option value="Chevrolet">Chevrolet</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Modelo</label>
                            <select name="modelo" required>
                                <option value="">Selecione o modelo</option>
                                <optgroup label="Utilitários leves">
                                    <option value="Fiat Strada">Fiat Strada</option>
                                    <option value="Volkswagen Saveiro">Volkswagen Saveiro</option>
                                    <option value="Chevrolet Montana">Chevrolet Montana</option>
                                    <option value="Fiat Fiorino">Fiat Fiorino</option>
                                    <option value="Renault Kangoo">Renault Kangoo</option>
                                </optgroup>
                                <optgroup label="Picapes médias">
                                    <option value="Toyota Hilux">Toyota Hilux</option>
                                    <option value="Ford Ranger">Ford Ranger</option>
                                    <option value="Chevrolet S10">Chevrolet S10</option>
                                    <option value="Fiat Toro">Fiat Toro</option>
                                </optgroup>
                                <optgroup label="SUVs e apoio">
                                    <option value="Toyota SW4">Toyota SW4</option>
                                    <option value="Chevrolet Trailblazer">Chevrolet Trailblazer</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Ano Fabricação</label>
                            <input type="date" name="anoFabricacao">
                        </div>

                        <div class="form-group">
                            <label>Ano Modelo</label>
                            <input type="date" name="anoModelo">
                        </div>

                        <div class="form-group">
                            <label>Cor</label>
                            <select name="cor">
                                <option value="">Selecione</option>
                                <option value="Branco">Branco</option>
                                <option value="Preto">Preto</option>
                                <option value="Prata">Prata</option>
                                <option value="Cinza">Cinza</option>
                            </select>
                        </div>

                        <!-- SITUAÇÃO -->
                        <h2 class="subtitulo-form">
                            Situação do Veículo
                        </h2>

                        <div class="form-group">
                            <label>Status</label>
                            <select name="status">
                                <option value="">Selecione</option>
                                <option value="ATIVO">Ativo</option>
                                <option value="EM MANUTENCAO">Em manutenção</option>
                                <option value="INATIVO">Inativo</option>
                                <option value="VENDIDO">Vendido</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Quilometragem</label>
                            <input type="text" name="quilometragem" maxlength="9" pattern="\d{1,7}" inputmode="numeric"
                                placeholder="Ex: 125000">
                        </div>

                        <div class="form-group">
                            <label>Última Revisão</label>
                            <input type="date" name="ultimaRevisao">
                        </div>

                        <div class="form-group">
                            <label>Próxima Revisão</label>
                            <input type="date" name="proximaRevisao">
                        </div>

                        <!-- RESPONSÁVEL -->

                        <h2 class="subtitulo-form">
                            Responsável
                        </h2>

                        <div class="form-group">
                            <label>Propriedade do Veículo</label>
                            <select name="posse">

                                <option value="">Selecione</option>

                                <option value="PROPRIO">Próprio</option>
                                <option value="ALUGADO">Alugado</option>
                                <option value="EMPRESTADO">Emprestado</option>
                                <option value="TERCEIRIZADO">Terceirizado</option>

                            </select>
                        </div>

                        <div class="form-group">
                            <label>Responsável pelo veículo</label>

                            <input type="text" name="responsavel" minlength="3" pattern="[A-Za-zÀ-ÿ\s]+"
                                placeholder="Digite o propreitário do veículo">
                        </div>

                        <div class="form-group observacoes">

                            <label>Observações</label>

                            <textarea name="observacoes"></textarea>

                        </div>

                    </div>

                </form>

            </section>

            <!-- BOTÕES de Ações -->
            <div class="acoes">

                <button type="submit" form="form-dados" class="btn novo">
                    Novo
                </button>

                <button type="submit" form="form-dados" class="btn alterar">
                    Alterar
                </button>

                <button type="submit" form="form-dados" class="btn excluir">
                    Excluir
                </button>

                <button type="reset" form="form-dados" class="btn limpar">
                    Limpar
                </button>

            </div>

        </main>

    </div>

    <!-- SCRIPT RENAVAM -->
    <script>

        function mascaraRenavam(input) {

            let valor = input.value.replace(/\D/g, '');

            valor = valor.substring(0, 11);

            valor = valor.replace(/^(\d{4})(\d)/, '$1.$2');

            valor = valor.replace(
                /^(\d{4})\.(\d{6})(\d)/,
                '$1.$2-$3'
            );

            input.value = valor;
        }

    </script>

    <!-- SCRIPT CHASSI -->
    <script>

        function mascaraChassi(input) {

            let valor = input.value.toUpperCase();

            valor = valor.replace(/[^A-Z0-9]/g, '');

            valor = valor.replace(/[IOQ]/g, '');

            valor = valor.substring(0, 17);

            input.value = valor;
        }

    </script>

</body>

</html>