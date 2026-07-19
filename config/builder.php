<?php

return [
    'blocks' => [
        'heading' => ['label' => 'Título', 'icon' => 'pencil-square', 'premium' => false, 'defaults' => ['text' => 'Seu título aqui', 'level' => 'h2', 'align' => 'center', 'color' => '#1c1917']],
        'text' => ['label' => 'Texto', 'icon' => 'bars-3-bottom-left', 'premium' => false, 'defaults' => ['text' => 'Conte sua história ou compartilhe uma informação importante.', 'align' => 'left', 'color' => '#57534e']],
        'button' => ['label' => 'Botão', 'icon' => 'cursor-arrow-rays', 'premium' => false, 'defaults' => ['text' => 'Saiba mais', 'url' => '#', 'align' => 'center', 'background' => '#E58775', 'color' => '#1c1917']],
        'image' => ['label' => 'Imagem', 'icon' => 'photo', 'premium' => false, 'defaults' => ['url' => '', 'alt' => '', 'radius' => '16']],
        'divider' => ['label' => 'Divisor', 'icon' => 'minus', 'premium' => false, 'defaults' => ['color' => '#e7e5e4', 'width' => '100']],
        'spacer' => ['label' => 'Espaço', 'icon' => 'arrows-up-down', 'premium' => false, 'defaults' => ['height' => '48']],
        'countdown' => ['label' => 'Contagem', 'icon' => 'clock', 'premium' => true, 'feature' => 'builder.countdown', 'defaults' => ['title' => 'Faltam poucos dias', 'date' => '', 'align' => 'center']],
        'gallery' => ['label' => 'Galeria', 'icon' => 'squares-2x2', 'premium' => true, 'feature' => 'builder.gallery', 'defaults' => ['title' => 'Nossa galeria', 'images' => []]],
        'map' => ['label' => 'Mapa', 'icon' => 'map-pin', 'premium' => true, 'feature' => 'builder.map', 'defaults' => ['title' => 'Como chegar', 'address' => '']],
        'video' => ['label' => 'Vídeo', 'icon' => 'play-circle', 'premium' => true, 'feature' => 'builder.video', 'defaults' => ['url' => '', 'title' => 'Nosso vídeo']],
    ],
];
