-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 06/11/2024 às 11:51
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `GreenBoard`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `adicionados`
--

CREATE TABLE `adicionados` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `cartao_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `adicionados`
--

INSERT INTO `adicionados` (`id`, `usuario_id`, `cartao_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cartoes`
--

CREATE TABLE `cartoes` (
  `id` int(11) NOT NULL,
  `corpo` varchar(1024) NOT NULL,
  `posicao` int(11) NOT NULL,
  `lista_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cartoes`
--

INSERT INTO `cartoes` (`id`, `corpo`, `posicao`, `lista_id`) VALUES
(1, 'Uma feature qualquer no Kanban', 0, 1),
(2, 'Uma outra feature qualquer existente no Kanban como exemplo de aparência dos cartões', 1, 1),
(3, 'Uma feature qualquer no Kanbann', 0, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `etiquetagens`
--

CREATE TABLE `etiquetagens` (
  `id` int(11) NOT NULL,
  `etiqueta_id` int(11) NOT NULL,
  `cartao_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `etiquetas`
--

CREATE TABLE `etiquetas` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cor` binary(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `listas`
--

CREATE TABLE `listas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(50) NOT NULL,
  `posicao` int(11) NOT NULL,
  `quadro_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `listas`
--

INSERT INTO `listas` (`id`, `titulo`, `posicao`, `quadro_id`) VALUES
(1, 'A fazer', 0, 1),
(2, 'Em andamento', 1, 1),
(3, 'Concluido', 2, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `permissoes`
--

CREATE TABLE `permissoes` (
  `id` int(11) NOT NULL,
  `eh_dono` tinyint(1) NOT NULL,
  `eh_admin` tinyint(1) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `quadro_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `permissoes`
--

INSERT INTO `permissoes` (`id`, `eh_dono`, `eh_admin`, `usuario_id`, `quadro_id`) VALUES
(1, 1, 1, 1, 1),
(2, 0, 1, 2, 1),
(3, 0, 0, 3, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `quadros`
--

CREATE TABLE `quadros` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `acessado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `quadros`
--

INSERT INTO `quadros` (`id`, `nome`, `acessado_em`) VALUES
(1, 'Kanban', '2024-11-01 15:36:18');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `email` varchar(320) NOT NULL,
  `senha` varchar(100) NOT NULL,
  `icone` varchar(4096) NOT NULL DEFAULT '/resources/icone.svg',
  `api_token` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `icone`, `api_token`) VALUES
(1, 'Dono', 'dono@mail', '$2y$10$R7IPG0NZiUE/uoDccfJEF.dqIuVzzsAHa/hCTyFeJSZTCe.7Eca5O', '/resources/icone.svg', NULL),
(2, 'Adm', 'adm@mail', '$2y$10$EyO4zUQHFPC1JKPzdAPqTubOQNDzAtFWSsh4/4TjW.BkUlg.IDZB6', '/resources/icone.svg', NULL),
(3, 'Comum', 'comum@mail', '$2y$10$Rt3UpQUIw87oRVmL/64coeU8Vue4CYmRVlvAwDks5CK3YqKNIQARW', '/resources/icone.svg', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `webhooks`
--

CREATE TABLE `webhooks` (
  `id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `quadro_id` int(11) NOT NULL,
  `lista_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `webhooks`
--

INSERT INTO `webhooks` (`id`, `token`, `usuario_id`, `quadro_id`, `lista_id`) VALUES
(1, '32a7942c40d5180e7f8624c5bb8f2ca0', 1, 1, 2);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `adicionados`
--
ALTER TABLE `adicionados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_adicionados_to_usuario_id` (`usuario_id`),
  ADD KEY `fk_adicionados_to_cartao_id` (`cartao_id`);

--
-- Índices de tabela `cartoes`
--
ALTER TABLE `cartoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cartoes_to_lista_id` (`lista_id`);

--
-- Índices de tabela `etiquetagens`
--
ALTER TABLE `etiquetagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_etiquetagens_to_etiqueta_id` (`etiqueta_id`),
  ADD KEY `fk_etiquetagens_to_cartao_id` (`cartao_id`);

--
-- Índices de tabela `etiquetas`
--
ALTER TABLE `etiquetas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `listas`
--
ALTER TABLE `listas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_listas_to_quadro_id` (`quadro_id`);

--
-- Índices de tabela `permissoes`
--
ALTER TABLE `permissoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_permissoes_to_usuario_id` (`usuario_id`),
  ADD KEY `fk_permissoes_to_quadro_id` (`quadro_id`);

--
-- Índices de tabela `quadros`
--
ALTER TABLE `quadros`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `webhooks`
--
ALTER TABLE `webhooks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_webhooks_to_usuario_id` (`usuario_id`),
  ADD KEY `fk_webhooks_to_quadro_id` (`quadro_id`),
  ADD KEY `fk_webhooks_to_lista_id` (`lista_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `adicionados`
--
ALTER TABLE `adicionados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `cartoes`
--
ALTER TABLE `cartoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `etiquetagens`
--
ALTER TABLE `etiquetagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `etiquetas`
--
ALTER TABLE `etiquetas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `listas`
--
ALTER TABLE `listas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `permissoes`
--
ALTER TABLE `permissoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `quadros`
--
ALTER TABLE `quadros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `webhooks`
--
ALTER TABLE `webhooks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `adicionados`
--
ALTER TABLE `adicionados`
  ADD CONSTRAINT `fk_adicionados_to_cartao_id` FOREIGN KEY (`cartao_id`) REFERENCES `cartoes` (`id`),
  ADD CONSTRAINT `fk_adicionados_to_usuario_id` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `cartoes`
--
ALTER TABLE `cartoes`
  ADD CONSTRAINT `fk_cartoes_to_lista_id` FOREIGN KEY (`lista_id`) REFERENCES `listas` (`id`);

--
-- Restrições para tabelas `etiquetagens`
--
ALTER TABLE `etiquetagens`
  ADD CONSTRAINT `fk_etiquetagens_to_cartao_id` FOREIGN KEY (`cartao_id`) REFERENCES `cartoes` (`id`),
  ADD CONSTRAINT `fk_etiquetagens_to_etiqueta_id` FOREIGN KEY (`etiqueta_id`) REFERENCES `etiquetas` (`id`);

--
-- Restrições para tabelas `listas`
--
ALTER TABLE `listas`
  ADD CONSTRAINT `fk_listas_to_quadro_id` FOREIGN KEY (`quadro_id`) REFERENCES `quadros` (`id`);

--
-- Restrições para tabelas `permissoes`
--
ALTER TABLE `permissoes`
  ADD CONSTRAINT `fk_permissoes_to_quadro_id` FOREIGN KEY (`quadro_id`) REFERENCES `quadros` (`id`),
  ADD CONSTRAINT `fk_permissoes_to_usuario_id` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `webhooks`
--
ALTER TABLE `webhooks`
  ADD CONSTRAINT `fk_webhooks_to_lista_id` FOREIGN KEY (`lista_id`) REFERENCES `listas` (`id`),
  ADD CONSTRAINT `fk_webhooks_to_quadro_id` FOREIGN KEY (`quadro_id`) REFERENCES `quadros` (`id`),
  ADD CONSTRAINT `fk_webhooks_to_usuario_id` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
