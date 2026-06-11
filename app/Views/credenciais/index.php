<?php

// TÍTULO
$titulo = 'Credenciais';
$favicon = '/ideal/public/assets/icon/chave.png';



require_once __DIR__ . '/../includes/header.php'; ?>

<link rel="shortcut icon" href="/ideal/public/assets/icons/financeiro3.png" type="image/x-icon">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="/ideal/public/assets/css/dashboard.css">
<link rel="stylesheet" href="/ideal/public/assets/css/variables.css">
<link rel="stylesheet" href="/ideal/public/assets/css/credenciais.css?v=<?= time() ?>">


<body>
<div class="layout">
    <?php include __DIR__ . '/../includes/sidebar.php'; ?>


    <div class="content">

            <!-- BUSCA -->
            <div class="grid-busca">
                <div class="card-busca">
                    <h3><i class="fas fa-lock"></i> ALTERAR ACESSO</h3>
                    <div class="form-busca">
                        <div class="campo">
                            <label>Tipo de Alteração</label>
                            <select name="tipoAlteracao">
                                <option value="senha">Senha</option>
                                <option value="email">E-mail</option>
                            </select>
                        </div>

                        <div class="campo">
                            <label>Usuário</label>
                            <input type="text" name="usuario" placeholder="Digite o usuário">

                        </div>
                        <button type="button" class="btn-buscar">Buscar</button>

                    </div>

                </div>


                <div class="card-dica">
                    <h4><i class="fas fa-info-circle"></i> DICA</h4>
                    <p>Selecione o tipo de alteração, localize o usuário e preencha os campos necessários para concluir a operação.</p>

                </div>

            </div>

           

            <!-- FORMULÁRIO -->

            <div class="card-formulario">

                <h2>Dados de Acesso</h2>

                <div class="grid-form">

                    <div class="campo">

                        <label>Nome do Usuário</label>

                        <input type="text" name="nomeUsuario" readonly>

                    </div>

                    <div class="campo">

                        <label>Login</label>

                        <input type="text" name="login" readonly>

                    </div>

                    <div class="campo">

                        <label>E-mail Atual</label>

                        <input type="email" name="emailAtual" readonly>

                    </div>

                </div>

               

                <hr>

               

                <h2>Alteração de Senha</h2>

                <div class="grid-form">

                    <div class="campo">

                        <label>Senha Atual</label>

                        <input type="password" name="senhaAtual">

                    </div>

                    <div class="campo">

                        <label>Nova Senha</label>

                        <input type="password" name="novaSenha">

                    </div>

                    <div class="campo">

                        <label>Confirmar Nova Senha</label>

                        <input type="password" name="confirmarSenha">

                    </div>

                </div>

               

                <hr>

               

                <h2>Alteração de E-mail</h2>

                <div class="grid-form">

                    <div class="campo">

                        <label>Novo E-mail</label>

                        <input type="email" name="novoEmail">

                    </div>

                    <div class="campo">

                        <label>Confirmar Novo E-mail</label>

                        <input type="email" name="confirmarEmail">

                    </div>

                </div>

            </div>

           

            <!-- BOTÕES -->

            <div class="acoes">

                <button type="submit" class="btn-azul">Salvar</button>

                <button type="reset" class="btn-cinza">Limpar</button>

            </div>

           

        </div>

    </div>

   

    </body> 