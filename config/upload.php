<?php
// Funções de upload de imagens.
// Gera sempre um nome novo com extensão segura, para que não seja possível
// enviar ficheiros executáveis (.php) nem sobrescrever imagens existentes.

const UPLOAD_EXTENSOES = [
    IMAGETYPE_JPEG => 'jpg',
    IMAGETYPE_PNG  => 'png',
    IMAGETYPE_GIF  => 'gif',
    IMAGETYPE_WEBP => 'webp',
    IMAGETYPE_BMP  => 'bmp',
];

/**
 * Valida e guarda uma imagem enviada por formulário.
 *
 * @param array  $ficheiro   Elemento de $_FILES
 * @param string $pasta      Pasta de destino
 * @param int    $tamanho_max Tamanho máximo em bytes
 * @return string|null Nome do ficheiro guardado, ou null em caso de erro
 */
function salvar_imagem($ficheiro, $pasta, $tamanho_max = 5000000)
{
    if (!isset($ficheiro['error']) || $ficheiro['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    if ($ficheiro['size'] > $tamanho_max) {
        return null;
    }

    // Verifica o conteúdo real do ficheiro (não confia na extensão nem no tipo enviado pelo navegador)
    $info = @getimagesize($ficheiro['tmp_name']);
    if ($info === false || !isset(UPLOAD_EXTENSOES[$info[2]])) {
        return null;
    }

    if (!is_dir($pasta)) {
        mkdir($pasta, 0755, true);
    }

    $nome = bin2hex(random_bytes(8)) . '.' . UPLOAD_EXTENSOES[$info[2]];
    if (!move_uploaded_file($ficheiro['tmp_name'], rtrim($pasta, '/') . '/' . $nome)) {
        return null;
    }
    return $nome;
}
