<?php
/**
 * Ao ativar o tema pela primeira vez, cria os departamentos e as peças de exemplo
 * com as fotos da loja, para o site já abrir completo. Depois disso tudo é editável
 * em Peças e Departamentos, e nada é recriado.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_switch_theme', 'passarela_conteudo_inicial' );

function passarela_conteudo_inicial() {
	if ( get_option( 'passarela_conteudo_criado' ) ) {
		return;
	}
	// Roda depois do "init", quando Peças e Departamentos já estão registrados.
	if ( ! post_type_exists( 'peca' ) || ! taxonomy_exists( 'departamento' ) ) {
		return;
	}

	$departamentos = array( 'Moda Feminina', 'Jeans Feminino', 'Moda Masculina', 'Moda Infantil', 'Bolsas e Carteiras', 'Cama Posta', 'Travesseiros', 'Toalhas' );
	$ids = array();
	foreach ( $departamentos as $ordem => $nome ) {
		$t = term_exists( $nome, 'departamento' );
		if ( ! $t ) {
			$t = wp_insert_term( $nome, 'departamento' );
		}
		if ( ! is_wp_error( $t ) ) {
			$ids[ $nome ] = (int) $t['term_id'];
			update_term_meta( (int) $t['term_id'], 'ordem', $ordem + 1 );
		}
	}

	$f = 'P, M, G, GG';
	$c = 'Solteiro, Casal, Queen, King';
	$pecas = array(
		array( 'Conjunto Top e Saia Marrom', 'Moda Feminina', 'Top tomara que caia e saia longa', 'conjunto-marrom', $f, 'Nova coleção', array( 'Elegância para todos os dias', 'Conjunto de top e saia longa em tom terroso, do jeito que a cidade gosta de usar.', 'Chegou na Passarela', 'limao' ) ),
		array( 'Vestido Longo Azul', 'Moda Feminina', 'Malha com caimento fluido', 'vestido-azul', $f, 'Nova coleção', array( 'Leveza que chama atenção', 'Malha macia, cintura marcada e saia que acompanha cada passo.', 'Feito para desfilar', 'preto' ) ),
		array( 'Jogo de Cama Rosé', 'Cama Posta', 'Colcha, lençóis e porta-travesseiros', 'quarto-rose', $c, '', array( 'Seu quarto merece essa vitrine', 'Jogos de cama, colchas e almofadas para deixar o quarto do jeito que você sonhou.', 'Conforto e beleza', 'claro' ) ),
		array( 'Looks para Celebrar', 'Moda Feminina', 'Coleção branca: renda, tricô e plissado', 'looks-brancos', $f, 'Festas', array( 'Brancos para brilhar nas festas', 'Vestidos e conjuntos em renda, tricô e plissado para celebrar com elegância.', 'Looks para você celebrar', 'creme' ) ),
		array( 'Vestido Rosa em Renda', 'Moda Feminina', 'Com cinto e mangas em tule', 'vestido-rosa-renda', $f, 'Nova coleção', array( 'Para os dias especiais', 'Renda delicada, cinto marcando a cintura e mangas em tule.', 'Delicadeza em cada detalhe', 'oliva' ) ),
		array( 'Vestido Midi Marrom', 'Moda Feminina', 'Modelagem ajustada com franzido', 'vestido-marrom', $f, '', null ),
		array( 'Vestido Longo Amarelo', 'Moda Feminina', 'Franzido no corpo, saia fluida', 'vestido-amarelo', $f, '', null ),
		array( 'Conjunto Rosa Plissado', 'Moda Feminina', 'Blusa drapeada e calça plissada', 'conjunto-rosa', $f, '', null ),
		array( 'Vestido Chemise Preto', 'Moda Feminina', 'Midi com botões e faixa', 'chemise-preto', $f, '', null ),
		array( 'Regata Branca e Pantalona Preta', 'Moda Feminina', 'Look alfaiataria', 'look-pantalona-preta', $f, '', null ),
		array( 'Blusa Rosa e Pantalona Off-White', 'Moda Feminina', 'Look leve para o dia a dia', 'look-rosa-off', $f, '', null ),
		array( 'Vestido Plissado Caramelo', 'Moda Feminina', 'Longo com amarração na cintura', 'vestido-caramelo', $f, '', null ),
		array( 'Vestido Branco em Renda', 'Moda Feminina', 'Longo com babados', 'vestido-branco', $f, 'Festas', null ),
		array( 'Cama Posta Bege Matelassê', 'Cama Posta', 'Edredom, lençóis e almofadas', 'cama-bege', $c, 'Cama posta', null ),
		array( 'Cama Posta Rosa', 'Cama Posta', 'Delicadeza e elegância', 'cama-rosa', $c, 'Cama posta', null ),
	);

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	foreach ( $pecas as $ordem => list( $titulo, $dep, $resumo, $foto, $tamanhos, $selo, $vitrine ) ) {
		$post_id = wp_insert_post( array(
			'post_type'    => 'peca',
			'post_status'  => 'publish',
			'post_title'   => $titulo,
			'post_excerpt' => $resumo,
			'post_content' => $resumo . '. Consulte valores, tamanhos e disponibilidade pelo WhatsApp.',
			'menu_order'   => $ordem + 1,
		) );
		if ( ! $post_id || is_wp_error( $post_id ) ) {
			continue;
		}
		if ( isset( $ids[ $dep ] ) ) {
			wp_set_object_terms( $post_id, array( $ids[ $dep ] ), 'departamento' );
		}
		update_post_meta( $post_id, 'tamanhos', $tamanhos );
		update_post_meta( $post_id, 'selo', $selo );
		update_post_meta( $post_id, 'preco', '' );
		if ( $vitrine ) {
			update_post_meta( $post_id, 'vitrine', '1' );
			update_post_meta( $post_id, 'vitrine_titulo', $vitrine[0] );
			update_post_meta( $post_id, 'vitrine_texto', $vitrine[1] );
			update_post_meta( $post_id, 'vitrine_frase', $vitrine[2] );
			update_post_meta( $post_id, 'vitrine_cor', $vitrine[3] );
		}
		$anexo = passarela_importar_foto( $foto, $post_id, $titulo );
		if ( $anexo ) {
			set_post_thumbnail( $post_id, $anexo );
			if ( 'looks-brancos' === $foto && ! get_theme_mod( 'passarela_sobre_imagem' ) ) {
				set_theme_mod( 'passarela_sobre_imagem', wp_get_attachment_url( $anexo ) );
			}
		}
	}

	if ( in_array( get_option( 'blogname' ), array( '', 'My WordPress Website', 'Meu site', 'Meu blog' ), true ) ) {
		update_option( 'blogname', 'A Passarela' );
	}
	update_option( 'passarela_conteudo_criado', 1 );
	flush_rewrite_rules();
}

function passarela_importar_foto( $nome, $post_id, $titulo ) {
	$origem = get_template_directory() . '/assets/fotos/' . $nome . '.jpg';
	if ( ! file_exists( $origem ) ) {
		return 0;
	}
	$tmp = wp_tempnam( $nome . '.jpg' );
	if ( ! $tmp || ! copy( $origem, $tmp ) ) {
		return 0;
	}
	$id = media_handle_sideload( array( 'name' => $nome . '.jpg', 'tmp_name' => $tmp ), $post_id, $titulo );
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp ); // phpcs:ignore
		return 0;
	}
	return $id;
}
