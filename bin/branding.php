<?php
declare(strict_types=1);

// Retired assets must stay hidden even before the production database is migrated.
function retired_media_names(): array
{
    return ['paulo-barete.png', 'about_paulo-barete.png', 'pictures_gallery_paulo-barete.png', 'parceiros.png'];
}

function visible_media_filters(array $filters): array
{
    $filters[] = ['status', '=', 1];
    foreach (retired_media_names() as $name) $filters[] = ['nome', '!=', $name];
    return $filters;
}

function brand_text(string $value, string $fallback = ''): string
{
    // Do not transfer a real person's biography or contacts to a fictitious identity.
    return preg_match('/paulo[\s_-]*bare(?:ta|te)|\bbareta\b|30\s+anos/iu', $value) ? $fallback : $value;
}

function brand_defaults(): array
{
    return [
        'textarea_capa' => 'Um olhar sobre luz, expressão e movimento.',
        'textarea_about_capa' => 'Nilo Vesper Fotografia é uma identidade fictícia criada para este projeto demonstrativo da Spartun Digital. O site explora retratos, moda e composição visual, sem representar a trajetória de uma pessoa real.',
        'textarea_clients_capa' => 'Uma seleção visual para explorar diferentes possibilidades de fotografia. Este projeto demonstrativo não representa vínculos comerciais com as marcas retratadas.',
    ];
}
