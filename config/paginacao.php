<?php
// Paginação simples por ?pagina=N (mantém os outros parâmetros do endereço, como filtros).

function pagina_atual()
{
    return max(1, (int) ($_GET['pagina'] ?? 1));
}

// Links "« Anterior 1 2 3 Seguinte »"; vazio quando tudo cabe numa página
function paginacao_html($total, $por_pagina, $pagina)
{
    $paginas = (int) ceil($total / $por_pagina);
    if ($paginas <= 1) {
        return '';
    }
    $link = function ($n, $texto, $atual = false) {
        $params = $_GET;
        $params['pagina'] = $n;
        $estilo = 'display:inline-block;margin:2px;padding:6px 12px;border-radius:5px;text-decoration:none;'
            . ($atual ? 'background:#6495ed;color:#fff;' : 'background:#eef2fb;color:#333;');
        return '<a href="?' . htmlspecialchars(http_build_query($params)) . '" style="' . $estilo . '"'
            . ($atual ? ' aria-current="page"' : '') . '>' . $texto . '</a>';
    };
    $html = '<nav class="paginacao" aria-label="Páginas" style="text-align:center;margin:24px 0;clear:both;">';
    if ($pagina > 1) {
        $html .= $link($pagina - 1, '« Anterior');
    }
    for ($n = 1; $n <= $paginas; $n++) {
        $html .= $link($n, (string) $n, $n === $pagina);
    }
    if ($pagina < $paginas) {
        $html .= $link($pagina + 1, 'Seguinte »');
    }
    return $html . '</nav>';
}
