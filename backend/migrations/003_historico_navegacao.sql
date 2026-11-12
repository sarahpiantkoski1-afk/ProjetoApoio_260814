CREATE TABLE IF NOT EXISTS historico_navegacao (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  egresso_id INT NOT NULL,
  tipo ENUM('conteudo', 'vaga') NOT NULL,
  item_id INT NOT NULL,
  visitado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_historico_egresso_item (egresso_id, tipo, item_id),
  KEY idx_historico_egresso_tipo (egresso_id, tipo),
  CONSTRAINT fk_historico_egresso FOREIGN KEY (egresso_id) REFERENCES egressos (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
