-- Adicionar campos para práticas de aprendizagem e neurodivergência
ALTER TABLE `plano_aula` ADD COLUMN `gerar_praticas_aprend` TINYINT(1) DEFAULT 0 AFTER `conteudo_gerado`;
ALTER TABLE `plano_aula` ADD COLUMN `qtd_praticas` TINYINT(3) DEFAULT 0 AFTER `gerar_praticas_aprend`;
ALTER TABLE `plano_aula` ADD COLUMN `nivel_inicial` TINYINT(3) DEFAULT 0 AFTER `qtd_praticas`;
ALTER TABLE `plano_aula` ADD COLUMN `nivel_intermediario` TINYINT(3) DEFAULT 0 AFTER `nivel_inicial`;
ALTER TABLE `plano_aula` ADD COLUMN `nivel_avancado` TINYINT(3) DEFAULT 0 AFTER `nivel_intermediario`;
ALTER TABLE `plano_aula` ADD COLUMN `data_inicio_praticas` DATE DEFAULT NULL AFTER `nivel_avancado`;
ALTER TABLE `plano_aula` ADD COLUMN `data_fim_praticas` DATE DEFAULT NULL AFTER `data_inicio_praticas`;
ALTER TABLE `plano_aula` ADD COLUMN `neurodivergencia` TINYINT(1) DEFAULT 0 AFTER `data_fim_praticas`;
