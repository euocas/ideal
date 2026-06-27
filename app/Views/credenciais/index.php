<?php

// TÍTULO
$titulo = 'Credenciais';
$favicon = '/ideal/public/assets/icon/chave.png';

require_once __DIR__ . '/../includes/header.php';

?>

<link rel="stylesheet" href="/ideal/public/assets/css/variables.css">
<link rel="stylesheet" href="/ideal/public/assets/css/base.css">
<link rel="stylesheet" href="/ideal/public/assets/css/component.css">
<link rel="stylesheet" href="/ideal/public/assets/css/forms.css">
<link rel="stylesheet" href="/ideal/public/assets/css/alerts.css">
<link rel="stylesheet" href="/ideal/public/assets/css/tables.css">
<link rel="stylesheet" href="/ideal/public/assets/css/dashboard.css">
<link rel="stylesheet" href="/ideal/public/assets/css/credenciais.css?v=<?= time() ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="layout">
        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

        <div class="content">

            <?php if (isset($_SESSION['mensagem_erro'])): ?>
                <div class="alert error"><?= $_SESSION['mensagem_erro'];
                unset($_SESSION['mensagem_erro']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['mensagem_sucesso'])): ?>
                <div class="alert success"><?= $_SESSION['mensagem_sucesso'];
                unset($_SESSION['mensagem_sucesso']); ?></div>
            <?php endif; ?>

            <form action="/ideal/public/index.php?url=credenciais/alterar" method="POST" id="formCredenciais">

                <input type="hidden" name="idUsuario" id="idUsuario" value="">

                <!-- TOPO: BUSCA + DICA -->
                <div class="grid-busca">
                    <div class="card-busca">
                        <h3><i class="fas fa-lock"></i> ALTERAR ACESSO</h3>
                        <div class="form-busca">
                            <div class="campo">
                                <label>Tipo de Alteração</label>
                                <select name="tipoAlteracao" id="tipoAlteracao">
                                    <option value="senha">Senha</option>
                                    <option value="email">E-mail</option>
                                    <option value="ambos">E-mail e Senha</option>
                                </select>
                            </div>
                            <div class="campo">
                                <label>Usuário</label>
                                <input type="text" id="inputBuscaUsuario" placeholder="Digite o usuário">
                            </div>
                            <button type="button" class="btn-buscar" id="btnBuscar">
                                <i class="bi bi-search"></i> Buscar
                            </button>
                        </div>
                    </div>

                    <div class="card-dica">
                        <h4><i class="fas fa-info-circle"></i> DICA</h4>
                        <p>Selecione o tipo de alteração, localize o usuário e preencha os campos necessários para concluir a operação.</p>
                    </div>
                </div>

                <!-- FORMULÁRIO PRINCIPAL -->
                <div class="card-formulario">

                    <!-- DADOS DE ACESSO (sempre visível) -->
                    <div class="bloco-formulario">
                        <h2>Dados de Acesso</h2>
                        <div class="grid-form">
                            <div class="campo">
                                <label>Nome do Usuário</label>
                                <input type="text" name="nomeUsuario" id="nomeUsuario" readonly>
                            </div>
                            <div class="campo">
                                <label>Login</label>
                                <input type="text" name="login" id="loginUsuario" readonly>
                            </div>
                            <div class="campo">
                                <label>E-mail Atual</label>
                                <input type="email" name="emailAtual" id="emailAtual" readonly>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- BLOCO SENHA -->
                    <div id="blocoSenha">
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
                    </div>

                    <!-- HR entre senha e email (só aparece quando ambos visíveis) -->
                    <!-- <hr id="hrEntreBlockos" style="display:none;"> -->

                    <!-- BLOCO EMAIL -->
                    <div id="blocoEmail">
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

                </div>

                <div class="acoes">
                    <button type="submit" class="btn-alterar">
                        <i class="bi bi-pencil-square"></i> Alterar
                    </button>
                    <button type="reset" class="btn-limpar">
                        <i class="bi bi-eraser"></i> Limpar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const tipoAlteracao = document.getElementById('tipoAlteracao');
        const blocoSenha    = document.getElementById('blocoSenha');
        const blocoEmail    = document.getElementById('blocoEmail');
        const hrEntre       = document.getElementById('hrEntreBlockos');

        function atualizarCampos() {
            if (tipoAlteracao.value === 'senha') {
                blocoSenha.style.display = 'block';
                blocoEmail.style.display = 'none';
                hrEntre.style.display    = 'none';
            } else if (tipoAlteracao.value === 'email') {
                blocoSenha.style.display = 'none';
                blocoEmail.style.display = 'block';
                hrEntre.style.display    = 'none';
            } else {
                blocoSenha.style.display = 'block';
                blocoEmail.style.display = 'block';
                hrEntre.style.display    = 'block';
            }
        }

        tipoAlteracao.addEventListener('change', atualizarCampos);
        atualizarCampos();

        document.getElementById('btnBuscar').addEventListener('click', function () {
            const usuarioDigitado = document.getElementById('inputBuscaUsuario').value;

            if (usuarioDigitado.trim() === '') {
                alert('Por favor, digite o nome do usuário para buscar.');
                return;
            }

            const formData = new FormData();
            formData.append('login', usuarioDigitado);

            fetch('/ideal/public/index.php?url=credenciais/buscar', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.id) {
                    document.getElementById('idUsuario').value   = data.id;
                    document.getElementById('nomeUsuario').value = data.nome;
                    document.getElementById('loginUsuario').value = data.login ?? data.nome;
                    document.getElementById('emailAtual').value  = data.email;
                    alert('Usuário encontrado e carregado.');
                } else {
                    document.getElementById('idUsuario').value   = '';
                    document.getElementById('nomeUsuario').value = '';
                    document.getElementById('loginUsuario').value = '';
                    document.getElementById('emailAtual').value  = '';
                    alert('Usuário não encontrado no sistema.');
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                alert('Ocorreu um erro ao buscar o usuário.');
            });
        });
    </script>
</body>