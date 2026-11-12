CREATE TABLE IF NOT EXISTS conteudos_educativos (
  id INT NOT NULL AUTO_INCREMENT,
  ong_id INT NOT NULL,
  titulo VARCHAR(200) NOT NULL,
  descricao VARCHAR(1200) NOT NULL,
  youtube_url VARCHAR(500) NOT NULL,
  youtube_id CHAR(11) NOT NULL,
  nichos VARCHAR(120) NOT NULL,
  criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_conteudos_educativos_ong (ong_id),
  CONSTRAINT fk_conteudos_educativos_ong FOREIGN KEY (ong_id)
    REFERENCES ongs (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE historico_navegacao
  MODIFY tipo VARCHAR(8) NOT NULL,
  ADD CONSTRAINT chk_historico_tipo
    CHECK (tipo IN ('conteudo', 'vaga', 'video'));
