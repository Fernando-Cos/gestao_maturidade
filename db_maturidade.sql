-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 20-Set-2021 às 14:35
-- Versão do servidor: 10.4.19-MariaDB
-- versão do PHP: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_maturidade`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_area`
--

CREATE TABLE `tb_area` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_area`
--

INSERT INTO `tb_area` (`id`, `nome`) VALUES
(1, 'TECNOLOGIA DA INFORMÃÇÃO'),
(2, 'RECURSOS HUMANOS'),
(3, 'FINANCEIRO');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_atividades`
--

CREATE TABLE `tb_atividades` (
  `id` int(11) NOT NULL,
  `descricao` varchar(2000) NOT NULL,
  `ativo` char(3) NOT NULL,
  `id_resposta_fk` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_diagnostico_body`
--

CREATE TABLE `tb_diagnostico_body` (
  `id` int(11) NOT NULL,
  `id_diagnostico_header_fk` int(11) NOT NULL,
  `id_pergunta_fk` int(11) NOT NULL,
  `id_resposta_fk` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_diagnostico_header`
--

CREATE TABLE `tb_diagnostico_header` (
  `id` int(11) NOT NULL,
  `id_unidade_fk` int(11) NOT NULL,
  `id_modelo_header_fk` int(11) NOT NULL,
  `total_pontos` int(11) DEFAULT NULL COMMENT 'preenchido após o cálculo do diagnostico',
  `nivel_maturidade` decimal(10,2) DEFAULT NULL COMMENT 'preenchido após o cálculo do diagnostico',
  `id_usuario_fk` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_modelo_body`
--

CREATE TABLE `tb_modelo_body` (
  `id` int(11) NOT NULL,
  `id_modelo_header_fk` int(11) NOT NULL,
  `id_pergunta_fk` int(11) NOT NULL,
  `id_resposta_fk` int(11) NOT NULL,
  `id_atividade_fk` int(11) NOT NULL,
  `id_usuario_fk` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_modelo_header`
--

CREATE TABLE `tb_modelo_header` (
  `id` int(11) NOT NULL,
  `id_area_fk` int(11) NOT NULL,
  `id_subarea_fk` int(11) NOT NULL,
  `id_parametro_fk` int(11) NOT NULL,
  `data` varchar(10) NOT NULL,
  `ativo` char(3) NOT NULL,
  `id_usuario_fk` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_nivel_area`
--

CREATE TABLE `tb_nivel_area` (
  `id` int(11) NOT NULL,
  `id_unidade_fk` int(11) NOT NULL,
  `id_area_fk` int(11) NOT NULL,
  `valor_nivel_area` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_nivel_maturidade`
--

CREATE TABLE `tb_nivel_maturidade` (
  `id` int(11) NOT NULL,
  `descricao` varchar(250) NOT NULL,
  `intervalo_ini` decimal(10,2) NOT NULL,
  `intervalo_fim` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_nivel_maturidade`
--

INSERT INTO `tb_nivel_maturidade` (`id`, `descricao`, `intervalo_ini`, `intervalo_fim`, `created_at`, `updated_at`) VALUES
(1, 'Nível 1 - Não existem processos gerenciais aplicados ao negócio e os resultados  são obtidos através de iniciativas desestruturadas. Cada funcionário desempenha  sua tarefa de diferentes formas, não existe um fluxo de trabalho padronizado.', '0.00', '20.00', '2021-09-20 08:25:37', NULL),
(2, 'Nível 2- Não existem processos gerenciais formais, mas algumas áreas de gestão já  possuem rotinas para gerar os resultados esperados.', '20.10', '40.00', '2021-09-20 08:25:42', NULL),
(3, 'Nível 3- Existem processos gerenciais formais, no entanto eles são aplicados de  maneira descoordenada para gerar os resultados esperados. O acompanhamento  e a revisão dos processos ainda é uma dificuldade', '40.10', '60.00', '2021-09-20 08:25:48', NULL),
(4, 'Nível 4 - Existem processos gerenciais formais e eles são aplicados de maneira  coordenada para atingir os resultados esperados. Já consegue monitorar as  tarefas, propor melhorias, identificar gargalos e atrasos, uso de uma ferramenta de  gestão.', '60.10', '80.00', '2021-09-20 08:25:53', NULL),
(5, 'Nível 5 - Os processos gerenciais são práticas padrão da empresa, o fluxo de trabalho segue o caminho desejado. Eles são monitorados, afetam o negócio e são melhorados continuamente.', '80.10', '100.00', '2021-09-20 08:26:01', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_nivel_unidade`
--

CREATE TABLE `tb_nivel_unidade` (
  `id` int(11) NOT NULL,
  `id_unidade_fk` int(11) NOT NULL,
  `valor_nivel_unidade` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_parametros`
--

CREATE TABLE `tb_parametros` (
  `id` int(11) NOT NULL,
  `qtd_perguntas` int(11) NOT NULL COMMENT 'quantidade de perguntas para o modelo',
  `qtd_respostas` int(11) NOT NULL COMMENT 'quantidade de respostas para cada pergunta',
  `maximo_pontos` int(11) NOT NULL COMMENT 'pontuação máxima para cada pergunta',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_parametros`
--

INSERT INTO `tb_parametros` (`id`, `qtd_perguntas`, `qtd_respostas`, `maximo_pontos`, `created_at`, `updated_at`) VALUES
(1, 3, 3, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_perguntas`
--

CREATE TABLE `tb_perguntas` (
  `id` int(11) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `ativo` char(3) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_permissao`
--

CREATE TABLE `tb_permissao` (
  `id` int(11) NOT NULL,
  `descricao` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_permissao`
--

INSERT INTO `tb_permissao` (`id`, `descricao`) VALUES
(1, 'admin'),
(2, 'gestor_unidade'),
(3, 'gestor_area'),
(4, 'gestor_subarea');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_plano_acao`
--

CREATE TABLE `tb_plano_acao` (
  `id` int(11) NOT NULL,
  `id_atividade_fk` int(11) NOT NULL,
  `id_diagnostico_body_fk` int(11) NOT NULL,
  `id_responsavel_fk` int(11) DEFAULT NULL,
  `data_realizada` varchar(10) DEFAULT NULL,
  `data_prevista` varchar(10) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_responsaveis`
--

CREATE TABLE `tb_responsaveis` (
  `id` int(11) NOT NULL,
  `nome` int(11) NOT NULL,
  `id_unidade_fk` int(11) NOT NULL,
  `id_subarea_fk` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_respostas`
--

CREATE TABLE `tb_respostas` (
  `id` int(11) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `nota` tinyint(4) NOT NULL,
  `id_pergunta_fk` int(11) NOT NULL,
  `ativo` char(3) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_subarea`
--

CREATE TABLE `tb_subarea` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `id_area_fk` int(11) NOT NULL,
  `imagem` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_subarea`
--

INSERT INTO `tb_subarea` (`id`, `nome`, `id_area_fk`, `imagem`) VALUES
(1, 'GSI', 1, 'gsi.png'),
(2, 'GIT', 1, 'git.png'),
(4, 'GST', 1, 'gst.ong');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_tipounidade`
--

CREATE TABLE `tb_tipounidade` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tb_tipounidade`
--

INSERT INTO `tb_tipounidade` (`id`, `nome`) VALUES
(1, 'UBS'),
(2, 'HOSPITAL'),
(3, 'SPA'),
(4, 'MATERNIDADE'),
(5, 'CENTRAL');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_unidades`
--

CREATE TABLE `tb_unidades` (
  `id` int(11) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `id_tipounidade_fk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tb_unidades`
--

INSERT INTO `tb_unidades` (`id`, `nome`, `id_tipounidade_fk`) VALUES
(2, 'HPS - Dr. João Lúcio Pereira Machado', 2),
(3, 'SPA Alvorada', 3),
(4, 'Maternidade Ana Braga', 4),
(5, 'Maternidade Balbina Mestrinho', 4),
(6, 'Secretaria de Estado de Saúde - SES', 6);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_unidadesareas`
--

CREATE TABLE `tb_unidadesareas` (
  `id` int(11) NOT NULL,
  `id_unidade_fk` int(11) NOT NULL,
  `id_area_fk` int(11) NOT NULL,
  `id_subarea_fk` int(11) DEFAULT NULL COMMENT 'quando null, considerar somente a área para realizar o diagnóstico'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_unidadesareas`
--

INSERT INTO `tb_unidadesareas` (`id`, `id_unidade_fk`, `id_area_fk`, `id_subarea_fk`) VALUES
(2, 6, 1, 1),
(1, 6, 1, 2),
(3, 6, 1, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_usuario`
--

CREATE TABLE `tb_usuario` (
  `id` int(11) UNSIGNED NOT NULL,
  `nome` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cpf` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_unidade_fk` int(11) DEFAULT NULL,
  `id_area_fk` int(11) DEFAULT NULL,
  `id_subarea_fk` int(11) DEFAULT NULL COMMENT 'caso haja area e subarea null, subarea=area',
  `id_permissao_fk` int(11) NOT NULL COMMENT 'admin/gestorunidade/gestorarea/gestorsubarea',
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `tb_area`
--
ALTER TABLE `tb_area`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_atividades`
--
ALTER TABLE `tb_atividades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_resposta_fk` (`id_resposta_fk`);

--
-- Índices para tabela `tb_diagnostico_body`
--
ALTER TABLE `tb_diagnostico_body`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_diagnostico_header_fk` (`id_diagnostico_header_fk`),
  ADD KEY `id_perguntas_fk` (`id_pergunta_fk`),
  ADD KEY `id_respostas_fk` (`id_resposta_fk`);

--
-- Índices para tabela `tb_diagnostico_header`
--
ALTER TABLE `tb_diagnostico_header`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario_fk` (`id_usuario_fk`),
  ADD KEY `id_modelo_fk` (`id_modelo_header_fk`),
  ADD KEY `diagnostico_header_unidade_fk` (`id_unidade_fk`);

--
-- Índices para tabela `tb_modelo_body`
--
ALTER TABLE `tb_modelo_body`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_perguntas_fk_2` (`id_pergunta_fk`,`id_resposta_fk`,`id_modelo_header_fk`) USING BTREE,
  ADD KEY `id_modelo_header_fk` (`id_modelo_header_fk`),
  ADD KEY `id_perguntas_fk` (`id_pergunta_fk`),
  ADD KEY `id_atividades_fk` (`id_atividade_fk`),
  ADD KEY `id_respostas_fk` (`id_resposta_fk`),
  ADD KEY `id_usuario_fk` (`id_usuario_fk`);

--
-- Índices para tabela `tb_modelo_header`
--
ALTER TABLE `tb_modelo_header`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_subarea_fk` (`id_subarea_fk`,`ativo`),
  ADD KEY `tb_modelo_header_usuario_fk` (`id_usuario_fk`),
  ADD KEY `id_area_fk` (`id_area_fk`),
  ADD KEY `id_parametro_fk` (`id_parametro_fk`);

--
-- Índices para tabela `tb_nivel_area`
--
ALTER TABLE `tb_nivel_area`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_unidade_fk` (`id_unidade_fk`),
  ADD KEY `id_area_fk` (`id_area_fk`);

--
-- Índices para tabela `tb_nivel_maturidade`
--
ALTER TABLE `tb_nivel_maturidade`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_nivel_unidade`
--
ALTER TABLE `tb_nivel_unidade`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_unidade_fk` (`id_unidade_fk`);

--
-- Índices para tabela `tb_parametros`
--
ALTER TABLE `tb_parametros`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_perguntas`
--
ALTER TABLE `tb_perguntas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_permissao`
--
ALTER TABLE `tb_permissao`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_plano_acao`
--
ALTER TABLE `tb_plano_acao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_atividade_fk` (`id_atividade_fk`),
  ADD KEY `id_diagnostico_body_fk` (`id_diagnostico_body_fk`),
  ADD KEY `id_responsavel_fk` (`id_responsavel_fk`);

--
-- Índices para tabela `tb_responsaveis`
--
ALTER TABLE `tb_responsaveis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_subarea_fk` (`id_subarea_fk`),
  ADD KEY `id_unidade_fk` (`id_unidade_fk`);

--
-- Índices para tabela `tb_respostas`
--
ALTER TABLE `tb_respostas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_perguntas_fk` (`id_pergunta_fk`);

--
-- Índices para tabela `tb_subarea`
--
ALTER TABLE `tb_subarea`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`),
  ADD KEY `id_area_fk` (`id_area_fk`);

--
-- Índices para tabela `tb_tipounidade`
--
ALTER TABLE `tb_tipounidade`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tb_unidades`
--
ALTER TABLE `tb_unidades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_tipounidade` (`id_tipounidade_fk`);

--
-- Índices para tabela `tb_unidadesareas`
--
ALTER TABLE `tb_unidadesareas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_unidade_fk_2` (`id_unidade_fk`,`id_area_fk`,`id_subarea_fk`),
  ADD KEY `id_unidade_fk` (`id_unidade_fk`),
  ADD KEY `id_area_fk` (`id_area_fk`),
  ADD KEY `id_subarea_fk` (`id_subarea_fk`);

--
-- Índices para tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_area_fk` (`id_area_fk`),
  ADD KEY `id_subarea_fk` (`id_subarea_fk`),
  ADD KEY `id_unidades_fk` (`id_unidade_fk`),
  ADD KEY `id_permissao_fk` (`id_permissao_fk`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_area`
--
ALTER TABLE `tb_area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tb_atividades`
--
ALTER TABLE `tb_atividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_diagnostico_body`
--
ALTER TABLE `tb_diagnostico_body`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_diagnostico_header`
--
ALTER TABLE `tb_diagnostico_header`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_modelo_body`
--
ALTER TABLE `tb_modelo_body`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_modelo_header`
--
ALTER TABLE `tb_modelo_header`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_nivel_area`
--
ALTER TABLE `tb_nivel_area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_nivel_maturidade`
--
ALTER TABLE `tb_nivel_maturidade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tb_nivel_unidade`
--
ALTER TABLE `tb_nivel_unidade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_parametros`
--
ALTER TABLE `tb_parametros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tb_perguntas`
--
ALTER TABLE `tb_perguntas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_permissao`
--
ALTER TABLE `tb_permissao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tb_plano_acao`
--
ALTER TABLE `tb_plano_acao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_responsaveis`
--
ALTER TABLE `tb_responsaveis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_respostas`
--
ALTER TABLE `tb_respostas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_subarea`
--
ALTER TABLE `tb_subarea`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tb_tipounidade`
--
ALTER TABLE `tb_tipounidade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tb_unidades`
--
ALTER TABLE `tb_unidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `tb_unidadesareas`
--
ALTER TABLE `tb_unidadesareas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `tb_atividades`
--
ALTER TABLE `tb_atividades`
  ADD CONSTRAINT `tb_atividades_ibfk_1` FOREIGN KEY (`id_resposta_fk`) REFERENCES `tb_respostas` (`id`);

--
-- Limitadores para a tabela `tb_diagnostico_body`
--
ALTER TABLE `tb_diagnostico_body`
  ADD CONSTRAINT `tb_diagnostico_body_ibfk_1` FOREIGN KEY (`id_diagnostico_header_fk`) REFERENCES `tb_diagnostico_header` (`id`),
  ADD CONSTRAINT `tb_diagnostico_body_ibfk_2` FOREIGN KEY (`id_pergunta_fk`) REFERENCES `tb_perguntas` (`id`),
  ADD CONSTRAINT `tb_diagnostico_body_ibfk_3` FOREIGN KEY (`id_resposta_fk`) REFERENCES `tb_respostas` (`id`);

--
-- Limitadores para a tabela `tb_diagnostico_header`
--
ALTER TABLE `tb_diagnostico_header`
  ADD CONSTRAINT `diagnostico_header_modelo_header_fk` FOREIGN KEY (`id_modelo_header_fk`) REFERENCES `tb_modelo_header` (`id`),
  ADD CONSTRAINT `diagnostico_header_unidade_fk` FOREIGN KEY (`id_unidade_fk`) REFERENCES `tb_unidades` (`id`),
  ADD CONSTRAINT `diagnostico_header_usuario_fk` FOREIGN KEY (`id_usuario_fk`) REFERENCES `tb_usuario` (`id`);

--
-- Limitadores para a tabela `tb_modelo_body`
--
ALTER TABLE `tb_modelo_body`
  ADD CONSTRAINT `tb_modelo_body_ibfk_1` FOREIGN KEY (`id_modelo_header_fk`) REFERENCES `tb_modelo_header` (`id`),
  ADD CONSTRAINT `tb_modelo_body_ibfk_2` FOREIGN KEY (`id_resposta_fk`) REFERENCES `tb_respostas` (`id`),
  ADD CONSTRAINT `tb_modelo_body_ibfk_3` FOREIGN KEY (`id_pergunta_fk`) REFERENCES `tb_perguntas` (`id`),
  ADD CONSTRAINT `tb_modelo_body_ibfk_5` FOREIGN KEY (`id_atividade_fk`) REFERENCES `tb_atividades` (`id`),
  ADD CONSTRAINT `tb_modelo_body_ibfk_6` FOREIGN KEY (`id_usuario_fk`) REFERENCES `tb_usuario` (`id`);

--
-- Limitadores para a tabela `tb_modelo_header`
--
ALTER TABLE `tb_modelo_header`
  ADD CONSTRAINT `tb_modelo_header_areafk_1` FOREIGN KEY (`id_area_fk`) REFERENCES `tb_area` (`id`),
  ADD CONSTRAINT `tb_modelo_header_ibfk_1` FOREIGN KEY (`id_subarea_fk`) REFERENCES `tb_subarea` (`id`),
  ADD CONSTRAINT `tb_modelo_header_parametros_id` FOREIGN KEY (`id_parametro_fk`) REFERENCES `tb_parametros` (`id`),
  ADD CONSTRAINT `tb_modelo_header_usuario_fk` FOREIGN KEY (`id_usuario_fk`) REFERENCES `tb_usuario` (`id`);

--
-- Limitadores para a tabela `tb_nivel_area`
--
ALTER TABLE `tb_nivel_area`
  ADD CONSTRAINT `nivel_area_id_area` FOREIGN KEY (`id_area_fk`) REFERENCES `tb_area` (`id`),
  ADD CONSTRAINT `nivel_area_id_unidade` FOREIGN KEY (`id_unidade_fk`) REFERENCES `tb_unidades` (`id`);

--
-- Limitadores para a tabela `tb_nivel_unidade`
--
ALTER TABLE `tb_nivel_unidade`
  ADD CONSTRAINT `tb_nivel_unidade_ibfk_1` FOREIGN KEY (`id_unidade_fk`) REFERENCES `tb_unidades` (`id`);

--
-- Limitadores para a tabela `tb_plano_acao`
--
ALTER TABLE `tb_plano_acao`
  ADD CONSTRAINT `tb_plano_acao_ibfk_1` FOREIGN KEY (`id_atividade_fk`) REFERENCES `tb_atividades` (`id`),
  ADD CONSTRAINT `tb_plano_acao_ibfk_2` FOREIGN KEY (`id_diagnostico_body_fk`) REFERENCES `tb_diagnostico_body` (`id`),
  ADD CONSTRAINT `tb_plano_acao_ibfk_3` FOREIGN KEY (`id_responsavel_fk`) REFERENCES `tb_responsaveis` (`id`);

--
-- Limitadores para a tabela `tb_responsaveis`
--
ALTER TABLE `tb_responsaveis`
  ADD CONSTRAINT `tb_responsaveis_ibfk_1` FOREIGN KEY (`id_subarea_fk`) REFERENCES `tb_subarea` (`id`),
  ADD CONSTRAINT `tb_responsaveis_ibfk_2` FOREIGN KEY (`id_unidade_fk`) REFERENCES `tb_unidades` (`id`);

--
-- Limitadores para a tabela `tb_respostas`
--
ALTER TABLE `tb_respostas`
  ADD CONSTRAINT `tb_respostas_ibfk_1` FOREIGN KEY (`id_pergunta_fk`) REFERENCES `tb_perguntas` (`id`);

--
-- Limitadores para a tabela `tb_subarea`
--
ALTER TABLE `tb_subarea`
  ADD CONSTRAINT `tb_subarea_ibfk_1` FOREIGN KEY (`id_area_fk`) REFERENCES `tb_area` (`id`);

--
-- Limitadores para a tabela `tb_unidades`
--
ALTER TABLE `tb_unidades`
  ADD CONSTRAINT `tb_unidades_ibfk_1` FOREIGN KEY (`id_tipounidade_fk`) REFERENCES `tb_unidades` (`id`);

--
-- Limitadores para a tabela `tb_unidadesareas`
--
ALTER TABLE `tb_unidadesareas`
  ADD CONSTRAINT `tb_unidadesareas_ibfk_1` FOREIGN KEY (`id_area_fk`) REFERENCES `tb_area` (`id`),
  ADD CONSTRAINT `tb_unidadesareas_ibfk_2` FOREIGN KEY (`id_subarea_fk`) REFERENCES `tb_subarea` (`id`),
  ADD CONSTRAINT `tb_unidadesareas_ibfk_3` FOREIGN KEY (`id_unidade_fk`) REFERENCES `tb_unidades` (`id`);

--
-- Limitadores para a tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD CONSTRAINT `tb_usuario_ibfk_1` FOREIGN KEY (`id_area_fk`) REFERENCES `tb_area` (`id`),
  ADD CONSTRAINT `tb_usuario_ibfk_2` FOREIGN KEY (`id_subarea_fk`) REFERENCES `tb_subarea` (`id`),
  ADD CONSTRAINT `tb_usuario_ibfk_4` FOREIGN KEY (`id_unidade_fk`) REFERENCES `tb_unidades` (`id`),
  ADD CONSTRAINT `tb_usuario_ibfk_5` FOREIGN KEY (`id_permissao_fk`) REFERENCES `tb_permissao` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
