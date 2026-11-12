<?php
require_once('../../backend/funcionalidades/config.php');
require_once(__DIR__ . '/../componentes/layout.php');

$municipios = require(__DIR__ . '/../dados/municipios.php');
$chave = $_GET['cidade'] ?? 'curitiba';
$cidade = $municipios[$chave] ?? $municipios['curitiba'];
$quantidadeLocais = count($cidade['itens']);

function normalizarLocal(string|array $item): array
{
    if (is_array($item)) {
        return $item;
    }

    return [
        'nome' => $item,
        'descricao' => 'Atendimento, orientação e encaminhamento para a rede de proteção social do município.',
        'telefone' => '',
        'email' => '',
        'endereco' => '',
    ];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php renderizarFavicon(); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serviços em <?= htmlspecialchars($cidade['nome']) ?> - Apoio+</title>
    <link rel="stylesheet" href="<?= caminhoEstilo('estilos.css') ?>">
</head>
<body>
    <?php renderizarCabecalho('ongs'); ?>
    <main class="ongs-main">
        <section class="ongs-apresentacao">
            <h1 class="titulo-tela">Serviços e instituições em <?= htmlspecialchars($cidade['nome']) ?></h1>
            <p><?= $quantidadeLocais ?> <?= $quantidadeLocais === 1 ? 'local cadastrado' : 'locais cadastrados' ?> para este município. Consulte abaixo os serviços disponíveis.</p>
        </section>
        <section class="servicos-lista">
            <div class="servicos-topo">
                <a class="botao-servicos servicos-voltar" href="ongs.php">&larr; Voltar ao mapa</a>
            </div>
            <?php foreach ($cidade['itens'] as $item): ?>
                <?php
                    $local = normalizarLocal($item);
                    $possuiDetalhes = $local['telefone'] !== '' || $local['email'] !== '' || $local['endereco'] !== '';
                    $telefoneLink = preg_replace('/[^0-9+]/', '', $local['telefone']);
                ?>
                <article class="card servico-card<?= $possuiDetalhes ? ' servico-card-com-detalhes' : '' ?>">
                    <h2><?= htmlspecialchars($local['nome'], ENT_QUOTES, 'UTF-8') ?></h2>
                    <p class="servico-descricao"><?= htmlspecialchars($local['descricao'], ENT_QUOTES, 'UTF-8') ?></p>

                    <?php if ($possuiDetalhes): ?>
                        <div class="servico-card-detalhes" id="detalhes-<?= htmlspecialchars(md5($local['nome']), ENT_QUOTES, 'UTF-8') ?>">
                            <p class="servico-endereco"><strong>Endereço:</strong> <?= htmlspecialchars($local['endereco'], ENT_QUOTES, 'UTF-8') ?></p>
                            <p class="servico-contato">
                                <strong>Contato:</strong>
                                <?php if ($local['telefone'] !== ''): ?>
                                    <a href="tel:<?= htmlspecialchars($telefoneLink, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($local['telefone'], ENT_QUOTES, 'UTF-8') ?></a>
                                <?php endif; ?>
                                <?php if ($local['telefone'] !== '' && $local['email'] !== ''): ?>
                                    <span aria-hidden="true"> · </span>
                                <?php endif; ?>
                                <?php if ($local['email'] !== ''): ?>
                                    <a href="mailto:<?= htmlspecialchars($local['email'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($local['email'], ENT_QUOTES, 'UTF-8') ?></a>
                                <?php endif; ?>
                                <?php if ($local['telefone'] === '' && $local['email'] === ''): ?>
                                    Não informado
                                <?php endif; ?>
                            </p>
                        </div>
                        <button
                            class="servico-card-toggle"
                            type="button"
                            aria-expanded="false"
                            aria-controls="detalhes-<?= htmlspecialchars(md5($local['nome']), ENT_QUOTES, 'UTF-8') ?>"
                        >Ver endereço e contato</button>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </section>
    </main>
    <?php renderizarRodape(); ?>
    <script>
        document.querySelectorAll('.servico-card-toggle').forEach((botao) => {
            botao.addEventListener('click', () => {
                const card = botao.closest('.servico-card');
                const aberto = card.classList.toggle('aberto');
                botao.setAttribute('aria-expanded', String(aberto));
                botao.textContent = aberto ? 'Ocultar endereço e contato' : 'Ver endereço e contato';
            });
        });
    </script>
</body>
</html>
