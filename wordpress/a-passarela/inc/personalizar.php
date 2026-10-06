<?php
/**
 * Aparência > Personalizar > A Passarela: textos, contatos, endereço, redes e números.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function passarela_padroes() {
	return array(
		'faixa_topo'       => 'Há 35 anos a loja mais completa de Itapuranga-GO · Rua 45, nº 850, Centro',
		'dep_titulo'       => 'A loja mais completa da cidade',
		'dep_texto'        => 'Moda feminina, masculina, infantil e acessórios. E tudo para cama, mesa e banho.',
		'vitrine_titulo'   => 'Novidades da loja',
		'vitrine_texto'    => 'Escolha as peças, monte sua lista e mande para a nossa equipe pelo WhatsApp.',
		'sobre_titulo'     => '35 anos vestindo',
		'sobre_destaque'   => 'Itapuranga',
		'sobre_texto'      => 'A Passarela é a loja mais completa de Itapuranga, em Goiás. São 35 anos reunindo moda feminina, masculina, infantil e acessórios, além de tudo para cama, mesa e banho, num só endereço no Centro da cidade.',
		'sobre_imagem'     => '',
		'numero_1_valor'   => '35 anos',
		'numero_1_texto'   => 'de história na cidade',
		'numero_2_valor'   => '11,7 mil',
		'numero_2_texto'   => 'seguidores no Instagram',
		'numero_3_valor'   => '+10 mil',
		'numero_3_texto'   => 'publicações de novidades',
		'numero_4_valor'   => '3',
		'numero_4_texto'   => 'WhatsApps de atendimento',
		'whatsapp_1_nome'  => 'Vendas 1',
		'whatsapp_1_numero' => '556233551640',
		'whatsapp_2_nome'  => 'Vendas 2',
		'whatsapp_2_numero' => '556285230343',
		'whatsapp_3_nome'  => 'Consultora Lívia',
		'whatsapp_3_numero' => '5562998535706',
		'endereco_1'       => 'Rua 45, nº 850, Centro',
		'endereco_2'       => 'Itapuranga, Goiás',
		'mapa'             => 'https://www.google.com/maps/search/?api=1&query=Rua+45+850+Centro+Itapuranga+GO',
		'instagram'        => 'https://www.instagram.com/apassarelaoficial/',
		'instagram_nome'   => '@apassarelaoficial',
		'instagram_chamada' => '11,7 mil seguidores',
		'facebook'         => 'https://www.facebook.com/apassarelatecidos.econfeccoes',
		'linktree'         => 'https://linktr.ee/apassarelaoficial',
		'rodape_texto'     => 'Há 35 anos a loja mais completa de Itapuranga-GO. Moda feminina, masculina, infantil, acessórios e cama, mesa e banho.',
	);
}

function passarela_opcao( $chave ) {
	$padroes = passarela_padroes();
	return get_theme_mod( 'passarela_' . $chave, $padroes[ $chave ] ?? '' );
}

add_action( 'customize_register', function ( $wp_customize ) {
	$wp_customize->add_panel( 'passarela', array( 'title' => 'A Passarela', 'priority' => 20 ) );

	$secoes = array(
		'passarela_topo'     => array( 'Faixa do topo e títulos', array(
			'faixa_topo'     => array( 'Texto da faixa preta do topo', 'text' ),
			'dep_titulo'     => array( 'Departamentos: título', 'text' ),
			'dep_texto'      => array( 'Departamentos: texto', 'textarea' ),
			'vitrine_titulo' => array( 'Catálogo: título', 'text' ),
			'vitrine_texto'  => array( 'Catálogo: texto', 'textarea' ),
		) ),
		'passarela_sobre'    => array( 'A loja (sobre)', array(
			'sobre_titulo'   => array( 'Título', 'text' ),
			'sobre_destaque' => array( 'Palavra em itálico no título', 'text' ),
			'sobre_texto'    => array( 'Texto', 'textarea' ),
			'sobre_imagem'   => array( 'Foto', 'image' ),
		) ),
		'passarela_numeros'  => array( 'Números da loja', array(
			'numero_1_valor' => array( 'Número 1', 'text' ), 'numero_1_texto' => array( 'Legenda 1', 'text' ),
			'numero_2_valor' => array( 'Número 2', 'text' ), 'numero_2_texto' => array( 'Legenda 2', 'text' ),
			'numero_3_valor' => array( 'Número 3', 'text' ), 'numero_3_texto' => array( 'Legenda 3', 'text' ),
			'numero_4_valor' => array( 'Número 4', 'text' ), 'numero_4_texto' => array( 'Legenda 4', 'text' ),
		) ),
		'passarela_contato'  => array( 'WhatsApp e endereço', array(
			'whatsapp_1_nome'   => array( 'WhatsApp 1: nome (recebe os pedidos da lista)', 'text' ),
			'whatsapp_1_numero' => array( 'WhatsApp 1: número com 55 e DDD, só dígitos', 'text' ),
			'whatsapp_2_nome'   => array( 'WhatsApp 2: nome', 'text' ),
			'whatsapp_2_numero' => array( 'WhatsApp 2: número (vazio esconde)', 'text' ),
			'whatsapp_3_nome'   => array( 'WhatsApp 3: nome', 'text' ),
			'whatsapp_3_numero' => array( 'WhatsApp 3: número (vazio esconde)', 'text' ),
			'endereco_1'        => array( 'Endereço: linha 1', 'text' ),
			'endereco_2'        => array( 'Endereço: linha 2', 'text' ),
			'mapa'              => array( 'Link do mapa', 'url' ),
		) ),
		'passarela_redes'    => array( 'Redes sociais e rodapé', array(
			'instagram'         => array( 'Link do Instagram', 'url' ),
			'instagram_nome'    => array( 'Nome do Instagram', 'text' ),
			'instagram_chamada' => array( 'Chamada da seção do Instagram', 'text' ),
			'facebook'          => array( 'Link do Facebook', 'url' ),
			'linktree'          => array( 'Link do Linktree', 'url' ),
			'rodape_texto'      => array( 'Texto do rodapé', 'textarea' ),
		) ),
	);

	$padroes = passarela_padroes();
	foreach ( $secoes as $sid => list( $titulo, $campos ) ) {
		$wp_customize->add_section( $sid, array( 'title' => $titulo, 'panel' => 'passarela' ) );
		foreach ( $campos as $chave => list( $rotulo, $tipo ) ) {
			$san = 'url' === $tipo || 'image' === $tipo ? 'esc_url_raw' : ( 'textarea' === $tipo ? 'sanitize_textarea_field' : 'sanitize_text_field' );
			$wp_customize->add_setting( 'passarela_' . $chave, array( 'default' => $padroes[ $chave ], 'sanitize_callback' => $san ) );
			if ( 'image' === $tipo ) {
				$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'passarela_' . $chave, array( 'label' => $rotulo, 'section' => $sid ) ) );
			} else {
				$wp_customize->add_control( 'passarela_' . $chave, array( 'label' => $rotulo, 'section' => $sid, 'type' => $tipo ) );
			}
		}
	}
} );
