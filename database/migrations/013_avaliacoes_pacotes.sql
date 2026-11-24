-- Avaliações também para os pacotes de viagem (só quem fez a viagem, ou seja, com o último dia no passado).
ALTER TABLE `t_avaliacoes` MODIFY `tipo_item` enum('atividade','hospedagem','pacote') NOT NULL;
