<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Funcionários</title>

    <!-- Ícone -->
    <link rel="shortcut icon" href="/ideal/public/assets/icons/funcionario.png" type="image/x-icon">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS DO DASHBOARD -->
    <link rel="stylesheet" href="/ideal/public/assets/css/dashboard.css">

    <!-- CSS DA TELA FUNCIONÁRIOS -->
    <link rel="stylesheet" href="/ideal/public/assets/css/funcionarios.css">

</head>

<body>

    <div class="dashboard-container">

        <!-- SIDEBAR -->
        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

        <!-- CONTEÚDO -->
        <main class="main-content">

            <!-- TÍTULO -->
            <!-- <div class="page-header">
                <h1>Funcionários</h1>
            </div> -->

            <!-- CARD BUSCA -->
            <section class="card">

                <h2>Buscar Funcionário</h2>

                <div class="grid-busca">

                    <!-- ESQUERDA -->
                    <div class="busca-box">

                        <h2>BUSCAR FUNCIONÁRIO</h2>

                        <form class="form-busca">

                            <div class="input-group">
                                <label>CPF</label>
                                <input type="text" name="cpf" oninput="mascaraCPF(this)" placeholder="000.000.000-00"
                                    required>
                            </div>

                            <button class="btn-buscar">
                                BUSCAR
                            </button>

                        </form>

                    </div>

                    <!-- DIREITA -->
                    <div class="dica-box">

                        <h3>DICA</h3>

                        <p>
                            Digite o CPF sem pontuaçao do funcionário e clique em <strong> BUSCAR</strong>.
                            Se não existir, você poderá cadastrar um novo funcionário.
                        </p>

                    </div>

                </div>

            </section>

            <!-- CARD DADOS -->
            <section class="card">

                <h2>Dados do Funcionário</h2>

                <form>

                    <div class="grid-form">

                        <!-- Nome -->
                        <div class="form-group">

                            <label>Nome</label>
                            <input type="text" name="nome" minlength="3" pattern="[A-Za-zÀ-ÿ\s]+"
                                title="Digite pelo menos 3 letras" placeholder="Digite o Nome Completo" required>

                        </div>

                        <!-- Sexo -->
                        <div class="form-group">
                            <label>Sexo</label>
                            <select name="sexo">
                                <option>Selecione</option>
                                <option>Masculino</option>
                                <option>Feminino</option>
                                <option>Outro</option>
                            </select>

                        </div>

                        <!-- Data nascimento -->
                        <div class="form-group">
                            <label>Data Nascimento</label>
                            <input type="date" name="data_nascimento">

                        </div>

                        <!-- Naturalidade -->
                        <div class="form-group">
                            <label for="naturalidade">Naturalidade</label>
                            <input type="text" name="naturalidade" minlength="3" title="Digite apenas letras"
                                placeholder="Digite apenas o nome da cidade">
                        </div>

                        <!-- Estado de Nascimento -->
                        <div class="form-group">
                            <label>Estado Nasc.</label>
                            <select name="estado_nasc" required>
                                <option value="">Selecione o Estado</option>
                                <option value="AC">Acre</option>
                                <option value="AL">Alagoas</option>
                                <option value="AP">Amapá</option>
                                <option value="AM">Amazonas</option>
                                <option value="BA">Bahia</option>
                                <option value="CE">Ceará</option>
                                <option value="DF">Distrito Federal</option>
                                <option value="ES">Espírito Santo</option>
                                <option value="GO">Goiás</option>
                                <option value="MA">Maranhão</option>
                                <option value="MT">Mato Grosso</option>
                                <option value="MS">Mato Grosso do Sul</option>
                                <option value="MG">Minas Gerais</option>
                                <option value="PA">Pará</option>
                                <option value="PB">Paraíba</option>
                                <option value="PR">Paraná</option>
                                <option value="PE">Pernambuco</option>
                                <option value="PI">Piauí</option>
                                <option value="RJ">Rio de Janeiro</option>
                                <option value="RN">Rio Grande do Norte</option>
                                <option value="RS">Rio Grande do Sul</option>
                                <option value="RO">Rondônia</option>
                                <option value="RR">Roraima</option>
                                <option value="SC">Santa Catarina</option>
                                <option value="SP">São Paulo</option>
                                <option value="SE">Sergipe</option>
                                <option value="TO">Tocantins</option>
                            </select>
                        </div>

                        <!-- CPF  inputmode serve para qdo o usuário tiver usando o celular-->
                        <div class="form-group">
                            <label>CPF</label>
                            <input type="text" name="cpf" maxlength="14" inputmode="numeric"
                                placeholder="000.000.000-00" oninput="mascaraCPF(this)" required>
                        </div>

                        <!-- Cargo -->
                        <div class="form-group">
                            <label>Cargo / Função</label>
                            <select name="cargo">
                                <option>Selecione</option>
                                <option>Azulegista</option>
                                <option>Eletrecista</option>
                                <option>Marceneiro</option>
                                <option>Pintor</option>
                            </select>
                        </div>

                        <!-- Endereço -->
                        <div class="form-group">
                            <label for="endereco">Endereço</label>
                            <input type="text" name="endereco" minlength="3" title="Digite apenas letras"
                                placeholder="Digite apenas o nome da Rua/Avenida/Alameda/Viela">
                        </div>

                        <!-- Número -->
                        <div class="form-group">
                            <label>Número</label>
                            <input type="text" name="numero" pattern="[0-9]+" placeholder="Somente números">
                        </div>

                        <!-- Complemento -->
                        <div class="form-group">
                            <label>Complemento</label>
                            <input type="text" name="complemento" placeholder="Números e letras">
                        </div>

                        <!-- Cidade -->
                        <div class="form-group">
                            <label>Cidade</label>
                            <input type="text" name="cidade" minlength="3" pattern="[A-Za-zÀ-ÿ\s]+"
                                title="Digite pelo menos 3 letras" placeholder="Digite o nome da cidade" required>
                        </div>

                        <!-- CEP -->
                        <div class="form-group">
                            <label>CEP</label>
                            <input type="text" name="cep" maxlength="9" inputmode="numeric" oninput="mascaraCEP(this)"
                                placeholder="00000-000">
                        </div>

                        <!-- Estado -->
                        <div class="form-group">
                            <label>Estado</label>

                            <select name="estado" required>
                                <option value="">Selecione o Estado</option>

                                <option value="AC">Acre</option>
                                <option value="AL">Alagoas</option>
                                <option value="AP">Amapá</option>
                                <option value="AM">Amazonas</option>
                                <option value="BA">Bahia</option>
                                <option value="CE">Ceará</option>
                                <option value="DF">Distrito Federal</option>
                                <option value="ES">Espírito Santo</option>
                                <option value="GO">Goiás</option>
                                <option value="MA">Maranhão</option>
                                <option value="MT">Mato Grosso</option>
                                <option value="MS">Mato Grosso do Sul</option>
                                <option value="MG">Minas Gerais</option>
                                <option value="PA">Pará</option>
                                <option value="PB">Paraíba</option>
                                <option value="PR">Paraná</option>
                                <option value="PE">Pernambuco</option>
                                <option value="PI">Piauí</option>
                                <option value="RJ">Rio de Janeiro</option>
                                <option value="RN">Rio Grande do Norte</option>
                                <option value="RS">Rio Grande do Sul</option>
                                <option value="RO">Rondônia</option>
                                <option value="RR">Roraima</option>
                                <option value="SC">Santa Catarina</option>
                                <option value="SP">São Paulo</option>
                                <option value="SE">Sergipe</option>
                                <option value="TO">Tocantins</option>
                            </select>
                        </div>



                        <!-- Email -->
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" minlength="5" maxlength="100"
                                placeholder="seuemail@dominio.com" title="Digite um e-mail válido"
                                pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                        </div>

                        <!-- Tipo contrato -->
                        <div class="form-group">
                            <label>Tipo Contrato</label>
                            <select name="tipo_contrato">
                                <option>Selecione</option>
                                <option>CLT</option>
                                <option>Contrato Temporário</option>
                                <option>Pessoa Jurídica</option>
                                <option>Tercerizada</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status">
                                <option>Selecione</option>
                                <option>Ativo</option>
                                <option>Inativo</option>

                            </select>
                        </div>

                        <!-- Observações -->
                        <div class="form-group observacoes">

                            <label>Observações</label>

                            <textarea></textarea>

                        </div>

                    </div>

                </form>
            </section>
            <!-- BOTÕES -->
            <div class="acoes">
                <button type="button" class="btn novo">
                    Novo
                </button>
                <button type="submit" class="btn salvar">
                    Salvar
                </button>
                <button type="button" class="btn alterar">
                    Alterar
                </button>
                <button type="button" class="btn excluir">
                    Excluir
                </button>
                <button type="reset" class="btn limpar">
                    Limpar
                </button>
            </div>
        </main>

    </div>
    <!-- Máscara do CPF-->
    <script>
        function mascaraCPF(input) {

            let valor = input.value.replace(/\D/g, '');

            valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
            valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
            valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

            input.value = valor;
        }
    </script>

    <!-- MÁSCARA do CEP -->
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