<?php
require_once('../../backend/funcionalidades/config.php');

$arquivoMapa = __DIR__ . '/../dados/municipios-rmc.geojson';
$geojsonMapa = json_decode(file_get_contents($arquivoMapa), true);
$configuracaoMunicipios = [
    '4106902' => ['chave' => 'curitiba', 'nome' => 'Curitiba'],
    '4119152' => ['chave' => 'pinhais', 'nome' => 'Pinhais'],
    '4105805' => ['chave' => 'colombo', 'nome' => 'Colombo'],
    '4119509' => ['chave' => 'piraquara', 'nome' => 'Piraquara'],
    '4125506' => ['chave' => 'sao-jose', 'nome' => 'São José dos Pinhais']
];
$municipiosMapa = [];
$pontosMapa = [];

foreach ($geojsonMapa['features'] ?? [] as $feature) {
    $codigo = (string) ($feature['properties']['codibge'] ?? '');
    if (!isset($configuracaoMunicipios[$codigo])) continue;
    $aneis = $feature['geometry']['type'] === 'MultiPolygon'
        ? array_merge(...$feature['geometry']['coordinates'])
        : $feature['geometry']['coordinates'];
    $municipiosMapa[$codigo] = $aneis;
    foreach ($aneis as $anel) foreach ($anel as $ponto) $pontosMapa[] = $ponto;
}

$larguraMapa = 760;
$alturaMapa = 520;
$margemMapa = 18;
$longitudes = array_column($pontosMapa, 0);
$latitudes = array_column($pontosMapa, 1);
$minX = min($longitudes); $maxX = max($longitudes);
$minY = min($latitudes); $maxY = max($latitudes);
$escalaMapa = min(
    ($larguraMapa - 2 * $margemMapa) / ($maxX - $minX),
    ($alturaMapa - 2 * $margemMapa) / ($maxY - $minY)
);
$offsetX = ($larguraMapa - (($maxX - $minX) * $escalaMapa)) / 2;
$offsetY = ($alturaMapa - (($maxY - $minY) * $escalaMapa)) / 2;

function projetarPontoMapa($ponto, $minX, $maxY, $escala, $offsetX, $offsetY) {
    return [
        round($offsetX + ($ponto[0] - $minX) * $escala, 1),
        round($offsetY + ($maxY - $ponto[1]) * $escala, 1)
    ];
}

function criarPathMunicipio($aneis, $minX, $maxY, $escala, $offsetX, $offsetY) {
    $partes = [];
    foreach ($aneis as $anel) {
        $comandos = [];
        foreach ($anel as $indice => $ponto) {
            [$x, $y] = projetarPontoMapa($ponto, $minX, $maxY, $escala, $offsetX, $offsetY);
            $comandos[] = ($indice === 0 ? 'M' : 'L') . $x . ',' . $y;
        }
        $partes[] = implode(' ', $comandos) . ' Z';
    }
    return implode(' ', $partes);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONGs - Apoio+</title>
    <link rel="stylesheet" href="../estilos/estilos.css?v=13">
</head>
<body>
    <header>
        <picture id="logo-img">
            <img src="../imagens/apoiomais.png" alt="Apoio+" class="logo-header">
        </picture>
        <nav id="options-header" aria-label="Navegação principal">
            <ul>
                <li><a href="inicio.php">Inicio</a></li>
                <li><a href="conteudo_educativo.php">Conteúdo Educativo</a></li>
                <li><a href="postagens.php">Postagens</a></li>
                <li><a href="ongs.php" class="active" aria-current="page">ONGs</a></li>
                <li><a href="contato.php">Contato</a></li>
                <?php
                    if (!isset($_SESSION['usuario']) && !isset($_SESSION['ong'])) {
                        echo '<li><a href="cadastro.php">Cadastre-se</a></li>';
                        echo '<li><a href="login.php">Entrar</a></li>';
                    } else {
                        if (isset($_SESSION['ong']) && !isset($_SESSION['usuario'])) {
                            echo '<li><a href="post_ongs.php">Criar Post</a></li>';
                        }
                        echo '<li><a href="logout.php" class="logout">Sair &rarr;</a></li>';
                    }
                ?>
            </ul>
        </nav>
    </header>

    <main class="ongs-main">
        <section class="ongs-apresentacao" aria-labelledby="ongs-titulo">
            <h1 id="ongs-titulo">Encontre ONGs e Serviços de Acolhimento perto de você</h1>
            <p>Aqui você localiza redes de suporte, projetos sociais e instituições parceiras em Curitiba e Região Metropolitana. Clique sobre o município no mapa para descobrir endereços, contatos e os serviços disponíveis para te ajudar nessa nova jornada de autonomia.</p>
        </section>

        <section class="rede-regional" aria-label="Mapa de municípios e resumo dos serviços">
            <div class="mapa-cidades" aria-label="Selecione um município no mapa">
                <svg class="mapa-svg" viewBox="0 0 <?= $larguraMapa ?> <?= $alturaMapa ?>" role="group" aria-label="Mapa de Curitiba e municípios vizinhos">
                    <?php foreach ($configuracaoMunicipios as $codigo => $municipio):
                        $aneis = $municipiosMapa[$codigo] ?? [];
                        $path = criarPathMunicipio($aneis, $minX, $maxY, $escalaMapa, $offsetX, $offsetY);
                        $pontosMunicipio = array_merge(...$aneis);
                        $xs = array_column($pontosMunicipio, 0); $ys = array_column($pontosMunicipio, 1);
                        [$labelX, $labelY] = projetarPontoMapa(
                            [(min($xs) + max($xs)) / 2, (min($ys) + max($ys)) / 2],
                            $minX, $maxY, $escalaMapa, $offsetX, $offsetY
                        );
                        $selecionada = $municipio['chave'] === 'curitiba';
                    ?>
                        <path
                            id="mapa-<?= htmlspecialchars($municipio['chave']) ?>"
                            class="cidade cidade-<?= htmlspecialchars($municipio['chave']) ?><?= $selecionada ? ' selecionada' : '' ?>"
                            data-cidade="<?= htmlspecialchars($municipio['chave']) ?>"
                            d="<?= htmlspecialchars($path) ?>"
                            tabindex="0" role="button"
                            aria-label="Selecionar <?= htmlspecialchars($municipio['nome']) ?>"
                            aria-pressed="<?= $selecionada ? 'true' : 'false' ?>"
                        />
                        <text class="cidade-label cidade-label-<?= htmlspecialchars($municipio['chave']) ?>" x="<?= $labelX ?>" y="<?= $labelY ?>"><?= htmlspecialchars($municipio['nome']) ?></text>
                    <?php endforeach; ?>
                </svg>
            </div>

            <aside class="painel-municipio" aria-live="polite">
                <div class="municipio-cabecalho">
                    <div class="icone-circular icone-local" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><path d="M12 21s7-6.1 7-12A7 7 0 1 0 5 9c0 5.9 7 12 7 12Z"/><circle cx="12" cy="9" r="2.4"/></svg>
                    </div>
                    <div><span>Município Selecionado:</span><h2 id="municipio-nome">Curitiba</h2></div>
                </div>
                <div class="painel-divisor"></div>
                <div class="info-servico">
                    <div class="icone-circular" aria-hidden="true">⌂</div>
                    <div><h3>ONGs e Instituições</h3><p id="municipio-ongs">78 organizações encontradas</p></div>
                </div>
                <div class="info-servico">
                    <div class="icone-circular" aria-hidden="true">♡</div>
                    <div><h3>Serviços Disponíveis</h3><p id="municipio-servicos">Apoio psicológico, moradia, cursos, alimentação e mais</p></div>
                </div>
                <div class="info-servico">
                    <div class="icone-circular" aria-hidden="true">☎</div>
                    <div><h3>Como entrar em contato</h3><p>Telefones, e-mails e redes sociais</p></div>
                </div>
                <a class="botao-servicos" id="botao-servicos" href="servicos_ongs.php?cidade=curitiba">Ver todos os serviços em Curitiba</a>
            </aside>
        </section>
    </main>

    <footer><p>Apoio+ O guia colaborativo para jovens egressos de abrigos institucionais</p></footer>
    <script src="header-menu.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const municipios = {
                curitiba: { nome: 'Curitiba', ongs: '78 organizações encontradas', servicos: 'Apoio psicológico, moradia, cursos, alimentação e mais' },
                pinhais: { nome: 'Pinhais', ongs: '16 organizações encontradas', servicos: 'Cursos, orientação profissional, saúde e assistência social' },
                colombo: { nome: 'Colombo', ongs: '21 organizações encontradas', servicos: 'Acolhimento, alimentação, capacitação e apoio familiar' },
                piraquara: { nome: 'Piraquara', ongs: '12 organizações encontradas', servicos: 'Assistência social, saúde mental, cursos e documentação' },
                'sao-jose': { nome: 'São José dos Pinhais', ongs: '27 organizações encontradas', servicos: 'Moradia, emprego, apoio psicológico e alimentação' }
            };
            const cidades = document.querySelectorAll('.cidade');
            const nome = document.getElementById('municipio-nome');
            const ongs = document.getElementById('municipio-ongs');
            const servicos = document.getElementById('municipio-servicos');
            const link = document.getElementById('botao-servicos');

            function selecionar(botao) {
                const chave = botao.dataset.cidade;
                const dados = municipios[chave];
                cidades.forEach(cidade => {
                    const ativa = cidade === botao;
                    cidade.classList.toggle('selecionada', ativa);
                    cidade.setAttribute('aria-pressed', String(ativa));
                });
                nome.textContent = dados.nome;
                ongs.textContent = dados.ongs;
                servicos.textContent = dados.servicos;
                link.textContent = `Ver todos os serviços em ${dados.nome}`;
                link.href = `servicos_ongs.php?cidade=${encodeURIComponent(chave)}`;
            }

            cidades.forEach(cidade => {
                cidade.addEventListener('click', () => selecionar(cidade));
                cidade.addEventListener('keydown', event => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        selecionar(cidade);
                    }
                });
            });
        });
    </script>
</body>
</html>
