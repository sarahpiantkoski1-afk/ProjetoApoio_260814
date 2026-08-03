SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP DATABASE IF EXISTS `apoio`;
CREATE DATABASE `apoio`;
USE `apoio`;

DROP TABLE IF EXISTS `egressos`;
CREATE TABLE `egressos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL UNIQUE,
  `senha` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `egressos` (`id`, `nome`, `email`, `senha`) VALUES
(1, 'Lucas Almeida', 'lucas.egresso@gmail.com', 'senha123'),
(2, 'Mariana Santos', 'mariana.santos.egresso@gmail.com', 'senha123'),
(3, 'Joao Pereira', 'joao.pereira.egresso@gmail.com', 'senha123'),
(4, 'Carla Mota', 'carla.mota.egresso@gmail.com', 'senha123'),
(5, 'Rafael Souza', 'rafael.souza.egresso@gmail.com', 'senha123');

DROP TABLE IF EXISTS `ongs`;
CREATE TABLE `ongs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `cnpj` varchar(18) NOT NULL UNIQUE,
  `email` varchar(150) NOT NULL UNIQUE,
  `senha` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `ongs` (`id`, `nome`, `cnpj`, `email`, `senha`) VALUES
(1, 'Casa de Apoio Jovem Futuro', '12.345.678/0001-10', 'contato@jovemfuturo.org', 'senha123'),
(2, 'Instituto Caminhos Livres', '23.456.789/0001-20', 'contato@caminhoslivres.org', 'senha123'),
(3, 'Rede Acolher', '34.567.890/0001-30', 'contato@redeacolher.org', 'senha123'),
(4, 'Projeto Renascer', '45.678.901/0001-40', 'contato@renascer.org', 'senha123'),
(5, 'FAS - Fundacao de Acao Social', '56.789.012/0001-50', 'contato@fas.pr.gov.br', 'senha123');

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_type` enum('egresso','ong') NOT NULL,
  `user_id` int NOT NULL,
  `email` varchar(150) NOT NULL,
  `token_hash` char(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_password_reset_token_hash` (`token_hash`),
  KEY `idx_password_reset_lookup` (`token_hash`,`expires_at`,`used_at`),
  KEY `idx_password_reset_user` (`user_type`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `postagens`;
CREATE TABLE `postagens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ong_id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descricao` varchar(60) NOT NULL,
  `link` varchar(255),
  `categoria` varchar(120) NOT NULL DEFAULT '',
  `data_postagem` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `ong_id` (`ong_id`),
  CONSTRAINT `postagens_ibfk_1` FOREIGN KEY (`ong_id`) REFERENCES `ongs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `postagens` (`id`, `ong_id`, `titulo`, `descricao`, `link`, `categoria`, `data_postagem`) VALUES
(1, 1, 'Curso Gratuito de Atendimento ao Cliente SENAC', 'Curso EAD com certificado', 'https://www.ead.senac.br/cursos-livres/atendimento-ao-cliente', 'curso', CURRENT_TIMESTAMP),
(2, 1, 'Moradia Temporaria Cadastro Beneficio de Moradia', 'Programa governamental de moradia social', 'https://www.gov.br/mds/pt-br/acoes-e-programas/auxilio-moradia', 'moradia', CURRENT_TIMESTAMP),
(3, 2, 'Curso de Informatica Basica Fundacao Bradesco', 'Curso gratuito com certificado', 'https://www.ev.org.br/cursos/informatica-basica', 'curso', CURRENT_TIMESTAMP),
(4, 2, 'Vagas de Jovem Aprendiz CIEE', 'Vagas disponiveis para jovens egressos', 'https://portal.ciee.org.br/estudantes/', 'emprego', CURRENT_TIMESTAMP),
(5, 3, 'Acolhimento e Assistencia Social GOV.BR', 'Rede publica de assistencia social', 'https://www.gov.br/mds/pt-br/acoes-e-programas/assistencia-social', 'acolhimento', CURRENT_TIMESTAMP),
(6, 3, 'Atendimento Psicossocial Gratuito CVV', 'Apoio emocional 24h', 'https://www.cvv.org.br/', 'apoio-psicologico', CURRENT_TIMESTAMP),
(7, 4, 'Cursos de Empreendedorismo Sebrae', 'Cursos gratuitos para insercao no mercado', 'https://www.sebrae.com.br/sites/PortalSebrae/cursosonline', 'curso,emprego', CURRENT_TIMESTAMP),
(8, 4, 'Capacitacao Profissional SENAI', 'Cursos profissionalizantes gratuitos', 'https://www.portaldaindustria.com.br/senai/cursos/gratuitos/', 'curso,emprego', CURRENT_TIMESTAMP),
(9, 5, 'Acolhimento Social Prefeitura de Curitiba FAS', 'Informacoes sobre os servicos de acolhimento', 'https://fas.curitiba.pr.gov.br/servicos', 'acolhimento', CURRENT_TIMESTAMP),
(10, 5, 'Vagas de Emprego Atualizadas Indeed Brasil', 'Busca de vagas em todo o Brasil', 'https://br.indeed.com/jobs', 'emprego', CURRENT_TIMESTAMP);

COMMIT;
