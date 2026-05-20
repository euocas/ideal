<?php
// Lógica para definir se estamos no modo de Edição ou Criação
$isEdit = isset($funcionario) && !empty($funcionario);
$actionUrl = $isEdit ? "/ideal/public/index.php?url=funcionarios/update&id={$funcionario['idFuncionario']}" : "/ideal/public/index.php?url=funcionarios/store";
$cpfValue = $isEdit ? $funcionario['cpf'] : ($cpfBusca ?? '');
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funcionários</title>
    <link rel="shortcut icon" href="/ideal/public/assets/icons/funcionario.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/ideal/public/assets/css/dashboard.css">
    <link rel="stylesheet" href="/ideal/public/assets/css/funcionarios.css">
</head>

<body>
    <div class="dashboard-container">

        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

        <main class="main-content">

            <section class="card">
                <h2>Buscar Funcionário</h2>
                <div class="grid-busca">
                    <div class="busca-box">
                        <h2>BUSCAR FUNCIONÁRIO</h2>

                        <?php if (!empty($mensagem)): ?>
                            <p style="color: #e74c3c; margin-bottom: 10px; font-weight: bold;">
                                <?= htmlspecialchars($mensagem); ?>
                            </p>
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
                        <h3>DICA</h3>
                        <p>Digite o CPF do funcionário e clique em <strong>BUSCAR</strong>. Se não existir, você poderá
                            cadastrar um novo funcionário.</p>
                    </div>
                </div>
            </section>

            <section class="card">
                <h2>Dados do Funcionário</h2>

                <form id="form-dados" action="<?= $actionUrl ?>" method="POST">
                    <div class="grid-form">

                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" name="nome" value="<?= htmlspecialchars($funcionario['nome'] ?? '') ?>"
                                minlength="3" pattern="[A-Za-zÀ-ÿ\s]+" title="Digite pelo menos 3 letras"
                                placeholder="Digite o Nome Completo" required>
                        </div>

                        <div class="form-group">
                            <label>Sexo</label>
                            <select name="sexo">
                                <option value="">Selecione</option>
                                <option value="Masculino" <?= ($funcionario['sexo'] ?? '') === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                                <option value="Feminino" <?= ($funcionario['sexo'] ?? '') === 'Feminino' ? 'selected' : '' ?>>Feminino</option>
                                <option value="Outro" <?= ($funcionario['sexo'] ?? '') === 'Outro' ? 'selected' : '' ?>>
                                    Outro</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Data Nascimento</label>
                            <input type="date" name="dataNascimento"
                                value="<?= htmlspecialchars($funcionario['dataNascimento'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="naturalidade">Naturalidade</label>
                            <input type="text" name="naturalidade"
                                value="<?= htmlspecialchars($funcionario['naturalidade'] ?? '') ?>" minlength="3"
                                title="Digite apenas letras" placeholder="Digite apenas o nome da cidade">
                        </div>

                        <div class="form-group">
                            <label>Estado Nasc.</label>
                            <select name="estadoNascimento" required>
                                <option value="">Selecione o Estado</option>

                                <option value="AC" <?= ($funcionario['estado'] ?? '') === 'AC' ? 'selected' : '' ?>>Acre
                                </option>
                                <option value="AL" <?= ($funcionario['estado'] ?? '') === 'AL' ? 'selected' : '' ?>>Alagoas
                                </option>
                                <option value="AP" <?= ($funcionario['estado'] ?? '') === 'AP' ? 'selected' : '' ?>>Amapá
                                </option>
                                <option value="AM" <?= ($funcionario['estado'] ?? '') === 'AM' ? 'selected' : '' ?>>
                                    Amazonas</option>
                                <option value="BA" <?= ($funcionario['estado'] ?? '') === 'BA' ? 'selected' : '' ?>>Bahia
                                </option>
                                <option value="CE" <?= ($funcionario['estado'] ?? '') === 'CE' ? 'selected' : '' ?>>Ceará
                                </option>
                                <option value="DF" <?= ($funcionario['estado'] ?? '') === 'DF' ? 'selected' : '' ?>>
                                    Distrito Federal</option>
                                <option value="ES" <?= ($funcionario['estado'] ?? '') === 'ES' ? 'selected' : '' ?>>
                                    Espírito Santo</option>
                                <option value="GO" <?= ($funcionario['estado'] ?? '') === 'GO' ? 'selected' : '' ?>>Goiás
                                </option>
                                <option value="MA" <?= ($funcionario['estado'] ?? '') === 'MA' ? 'selected' : '' ?>>
                                    Maranhão</option>
                                <option value="MT" <?= ($funcionario['estado'] ?? '') === 'MT' ? 'selected' : '' ?>>Mato
                                    Grosso</option>
                                <option value="MS" <?= ($funcionario['estado'] ?? '') === 'MS' ? 'selected' : '' ?>>Mato
                                    Grosso do Sul</option>
                                <option value="MG" <?= ($funcionario['estado'] ?? '') === 'MG' ? 'selected' : '' ?>>Minas
                                    Gerais</option>
                                <option value="PA" <?= ($funcionario['estado'] ?? '') === 'PA' ? 'selected' : '' ?>>Pará
                                </option>
                                <option value="PB" <?= ($funcionario['estado'] ?? '') === 'PB' ? 'selected' : '' ?>>Paraíba
                                </option>
                                <option value="PR" <?= ($funcionario['estado'] ?? '') === 'PR' ? 'selected' : '' ?>>Paraná
                                </option>
                                <option value="PE" <?= ($funcionario['estado'] ?? '') === 'PE' ? 'selected' : '' ?>>
                                    Pernambuco</option>
                                <option value="PI" <?= ($funcionario['estado'] ?? '') === 'PI' ? 'selected' : '' ?>>Piauí
                                </option>
                                <option value="RJ" <?= ($funcionario['estado'] ?? '') === 'RJ' ? 'selected' : '' ?>>Rio de
                                    Janeiro</option>
                                <option value="RN" <?= ($funcionario['estado'] ?? '') === 'RN' ? 'selected' : '' ?>>Rio
                                    Grande do Norte</option>
                                <option value="RS" <?= ($funcionario['estado'] ?? '') === 'RS' ? 'selected' : '' ?>>Rio
                                    Grande do Sul</option>
                                <option value="RO" <?= ($funcionario['estado'] ?? '') === 'RO' ? 'selected' : '' ?>>
                                    Rondônia</option>
                                <option value="RR" <?= ($funcionario['estado'] ?? '') === 'RR' ? 'selected' : '' ?>>Roraima
                                </option>
                                <option value="SC" <?= ($funcionario['estado'] ?? '') === 'SC' ? 'selected' : '' ?>>Santa
                                    Catarina</option>
                                <option value="SP" <?= ($funcionario['estado'] ?? '') === 'SP' ? 'selected' : '' ?>>São
                                    Paulo</option>
                                <option value="SE" <?= ($funcionario['estado'] ?? '') === 'SE' ? 'selected' : '' ?>>Sergipe
                                </option>
                                <option value="TO" <?= ($funcionario['estado'] ?? '') === 'TO' ? 'selected' : '' ?>>
                                    Tocantins</option>

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
                                <option value="Azulejista" <?= ($funcionario['cargoFuncao'] ?? '') === 'Azulejista' ? 'selected' : '' ?>>Azulejista</option>
                                <option value="Eletricista" <?= ($funcionario['cargoFuncao'] ?? '') === 'Eletricista' ? 'selected' : '' ?>>Eletricista</option>
                                <option value="Marceneiro" <?= ($funcionario['cargoFuncao'] ?? '') === 'Marceneiro' ? 'selected' : '' ?>>Marceneiro</option>
                                <option value="Pintor" <?= ($funcionario['cargoFuncao'] ?? '') === 'Pintor' ? 'selected' : '' ?>>Pintor</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="endereco">Endereço</label>
                            <input type="text" name="nomeLogradouro"
                                value="<?= htmlspecialchars($funcionario['nomeLogradouro'] ?? '') ?>" minlength="3"
                                title="Digite apenas letras"
                                placeholder="Digite apenas o nome da Rua/Avenida/Alameda/Viela">
                        </div>

                        <div class="form-group">
                            <label>Número</label>
                            <input type="text" name="numero"
                                value="<?= htmlspecialchars($funcionario['numero'] ?? '') ?>" pattern="[0-9]+"
                                placeholder="Somente números">
                        </div>

                        <div class="form-group">
                            <label>Complemento</label>
                            <input type="text" name="complemento"
                                value="<?= htmlspecialchars($funcionario['complemento'] ?? '') ?>"
                                placeholder="Números e letras">
                        </div>

                        <div class="form-group">
                            <label>Cidade</label>
                            <input type="text" name="cidade"
                                value="<?= htmlspecialchars($funcionario['cidade'] ?? '') ?>" minlength="3"
                                pattern="[A-Za-zÀ-ÿ\s]+" title="Digite pelo menos 3 letras"
                                placeholder="Digite o nome da cidade" required>
                        </div>

                        <div class="form-group">
                            <label>CEP</label>
                            <input type="text" name="cep" value="<?= htmlspecialchars($funcionario['cep'] ?? '') ?>"
                                maxlength="9" inputmode="numeric" oninput="mascaraCEP(this)" placeholder="00000-000">
                        </div>

                        <div class="form-group">
                            <label>Estado</label>
                            <select name="estado" required>
                                <option value="">Selecione o Estado</option>
                                <option value="AC" <?= ($funcionario['estado'] ?? '') === 'AC' ? 'selected' : '' ?>>Acre
                                </option>
                                <option value="AL" <?= ($funcionario['estado'] ?? '') === 'AL' ? 'selected' : '' ?>>Alagoas
                                </option>
                                <option value="AP" <?= ($funcionario['estado'] ?? '') === 'AP' ? 'selected' : '' ?>>Amapá
                                </option>
                                <option value="AM" <?= ($funcionario['estado'] ?? '') === 'AM' ? 'selected' : '' ?>>
                                    Amazonas</option>
                                <option value="BA" <?= ($funcionario['estado'] ?? '') === 'BA' ? 'selected' : '' ?>>Bahia
                                </option>
                                <option value="CE" <?= ($funcionario['estado'] ?? '') === 'CE' ? 'selected' : '' ?>>Ceará
                                </option>
                                <option value="DF" <?= ($funcionario['estado'] ?? '') === 'DF' ? 'selected' : '' ?>>
                                    Distrito Federal</option>
                                <option value="ES" <?= ($funcionario['estado'] ?? '') === 'ES' ? 'selected' : '' ?>>
                                    Espírito Santo</option>
                                <option value="GO" <?= ($funcionario['estado'] ?? '') === 'GO' ? 'selected' : '' ?>>Goiás
                                </option>
                                <option value="MA" <?= ($funcionario['estado'] ?? '') === 'MA' ? 'selected' : '' ?>>
                                    Maranhão</option>
                                <option value="MT" <?= ($funcionario['estado'] ?? '') === 'MT' ? 'selected' : '' ?>>Mato
                                    Grosso</option>
                                <option value="MS" <?= ($funcionario['estado'] ?? '') === 'MS' ? 'selected' : '' ?>>Mato
                                    Grosso do Sul</option>
                                <option value="MG" <?= ($funcionario['estado'] ?? '') === 'MG' ? 'selected' : '' ?>>Minas
                                    Gerais</option>
                                <option value="PA" <?= ($funcionario['estado'] ?? '') === 'PA' ? 'selected' : '' ?>>Pará
                                </option>
                                <option value="PB" <?= ($funcionario['estado'] ?? '') === 'PB' ? 'selected' : '' ?>>Paraíba
                                </option>
                                <option value="PR" <?= ($funcionario['estado'] ?? '') === 'PR' ? 'selected' : '' ?>>Paraná
                                </option>
                                <option value="PE" <?= ($funcionario['estado'] ?? '') === 'PE' ? 'selected' : '' ?>>
                                    Pernambuco</option>
                                <option value="PI" <?= ($funcionario['estado'] ?? '') === 'PI' ? 'selected' : '' ?>>Piauí
                                </option>
                                <option value="RJ" <?= ($funcionario['estado'] ?? '') === 'RJ' ? 'selected' : '' ?>>Rio de
                                    Janeiro</option>
                                <option value="RN" <?= ($funcionario['estado'] ?? '') === 'RN' ? 'selected' : '' ?>>Rio
                                    Grande do Norte</option>
                                <option value="RS" <?= ($funcionario['estado'] ?? '') === 'RS' ? 'selected' : '' ?>>Rio
                                    Grande do Sul</option>
                                <option value="RO" <?= ($funcionario['estado'] ?? '') === 'RO' ? 'selected' : '' ?>>
                                    Rondônia</option>
                                <option value="RR" <?= ($funcionario['estado'] ?? '') === 'RR' ? 'selected' : '' ?>>Roraima
                                </option>
                                <option value="SC" <?= ($funcionario['estado'] ?? '') === 'SC' ? 'selected' : '' ?>>Santa
                                    Catarina</option>
                                <option value="SP" <?= ($funcionario['estado'] ?? '') === 'SP' ? 'selected' : '' ?>>São
                                    Paulo</option>
                                <option value="SE" <?= ($funcionario['estado'] ?? '') === 'SE' ? 'selected' : '' ?>>Sergipe
                                </option>
                                <option value="TO" <?= ($funcionario['estado'] ?? '') === 'TO' ? 'selected' : '' ?>>
                                    Tocantins</option>x

                            </select>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email"
                                value="<?= htmlspecialchars($funcionario['email'] ?? '') ?>" minlength="5"
                                maxlength="100" placeholder="seuemail@dominio.com" title="Digite um e-mail válido"
                                pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                        </div>

                        <div class="form-group">
                            <label>Tipo Contrato</label>
                            <select name="tipoContrato">
                                <option value="">Selecione</option>
                                <option value="CLT" <?= ($funcionario['tipoContrato'] ?? '') === 'CLT' ? 'selected' : '' ?>>CLT</option>
                                <option value="CONTRATO TEMPORARIO" <?= ($funcionario['tipoContrato'] ?? '') === 'CONTRATO TEMPORARIO' ? 'selected' : '' ?>>Contrato Temporário</option>
                                <option value="PESSOA JURÍRIDICA" <?= ($funcionario['tipoContrato'] ?? '') === 'PESSOA JURÍRIDICA' ? 'selected' : '' ?>>Pessoa Jurídica</option>
                                <option value="TERCERIZADA" <?= ($funcionario['tipoContrato'] ?? '') === 'TERCERIZADA' ? 'selected' : '' ?>>Tercerizada</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select name="status">
                                <option value="">Selecione</option>
                                <option value="ativo" <?= ($funcionario['status'] ?? '') === 'ativo' ? 'selected' : '' ?>>
                                    Ativo</option>
                                <option value="inativo" <?= ($funcionario['status'] ?? '') === 'inativo' ? 'selected' : '' ?>>Inativo</option>
                            </select>
                        </div>

                        <div class="form-group observacoes">
                            <label>Observações</label>
                            <textarea
                                name="observacoes"><?= htmlspecialchars($funcionario['observacoes'] ?? '') ?></textarea>
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
                    <a href="/ideal/public/index.php?url=funcionarios/delete&id=<?= $funcionario['idFuncionario'] ?>"
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