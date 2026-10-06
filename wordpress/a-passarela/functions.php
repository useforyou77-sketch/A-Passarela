<?php
/**
 * Tema A Passarela.
 *
 * Peças (tipo de post "peca") e Departamentos (taxonomia "departamento") alimentam
 * a vitrine animada, os departamentos em arco e o catálogo. Textos, contatos e
 * números ficam em Aparência > Personalizar > A Passarela.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PASSARELA_VERSION', '1.0.0' );

require get_template_directory() . '/inc/personalizar.php';
require get_template_directory() . '/inc/conteudo-inicial.php';

/* ---------- Configuração do tema ---------- */

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	register_nav_menus( array( 'principal' => 'Menu principal' ) );
	add_image_size( 'passarela-peca', 800, 1000, true );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'passarela-fontes', 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;1,500;1,600&family=Jost:wght@400;500;600&display=swap', array(), null );
	wp_enqueue_style( 'passarela', get_stylesheet_uri(), array( 'passarela-fontes' ), PASSARELA_VERSION );
	wp_enqueue_script( 'passarela', get_template_directory_uri() . '/assets/js/site.js', array(), PASSARELA_VERSION, true );
	wp_localize_script( 'passarela', 'PASSARELA', passarela_dados_js() );
} );

/* ---------- Peças e Departamentos ---------- */

add_action( 'init', function () {
	register_post_type( 'peca', array(
		'labels'        => array(
			'name'               => 'Peças',
			'singular_name'      => 'Peça',
			'add_new'            => 'Adicionar peça',
			'add_new_item'       => 'Adicionar nova peça',
			'edit_item'          => 'Editar peça',
			'new_item'           => 'Nova peça',
			'view_item'          => 'Ver peça',
			'search_items'       => 'Buscar peças',
			'not_found'          => 'Nenhuma peça encontrada',
			'not_found_in_trash' => 'Nenhuma peça na lixeira',
			'all_items'          => 'Todas as peças',
			'featured_image'     => 'Foto da peça',
			'set_featured_image' => 'Escolher foto da peça',
			'menu_name'          => 'Peças',
		),
		'public'        => true,
		'has_archive'   => true,
		'rewrite'       => array( 'slug' => 'pecas' ),
		'menu_icon'     => 'dashicons-store',
		'menu_position' => 5,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
		'show_in_rest'  => true,
	) );

	register_taxonomy( 'departamento', 'peca', array(
		'labels'            => array(
			'name'          => 'Departamentos',
			'singular_name' => 'Departamento',
			'add_new_item'  => 'Adicionar departamento',
			'edit_item'     => 'Editar departamento',
			'all_items'     => 'Todos os departamentos',
			'search_items'  => 'Buscar departamentos',
		),
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'departamento' ),
	) );

	register_term_meta( 'departamento', 'ordem', array( 'type' => 'integer', 'single' => true, 'show_in_rest' => true ) );
} );

/** Campos da peça. A chave é o nome do campo salvo no banco. */
function passarela_campos_peca() {
	return array(
		'preco'          => 'Preço (deixe vazio para mostrar "Sob consulta")',
		'tamanhos'       => 'Tamanhos, separados por vírgula (ex.: P, M, G, GG)',
		'selo'           => 'Selo na foto (ex.: Nova coleção, Festas)',
		'vitrine_titulo' => 'Título grande',
		'vitrine_texto'  => 'Texto curto',
		'vitrine_frase'  => 'Frase embaixo da foto',
	);
}

function passarela_cores_vitrine() {
	return array(
		'limao'  => array( 'nome' => 'Limão', 'bg' => '#D8FE47', 'ink' => '#111111', 'line' => 'rgb(17 17 17 / .2)', 'btn' => '#111111', 'btnInk' => '#D8FE47' ),
		'preto'  => array( 'nome' => 'Preto', 'bg' => '#161616', 'ink' => '#F4F4EC', 'line' => 'rgb(244 244 236 / .25)', 'btn' => '#D8FE47', 'btnInk' => '#111111' ),
		'claro'  => array( 'nome' => 'Limão claro', 'bg' => '#EEFBC2', 'ink' => '#111111', 'line' => 'rgb(17 17 17 / .18)', 'btn' => '#111111', 'btnInk' => '#D8FE47' ),
		'creme'  => array( 'nome' => 'Creme', 'bg' => '#F4F4EC', 'ink' => '#111111', 'line' => 'rgb(17 17 17 / .18)', 'btn' => '#111111', 'btnInk' => '#D8FE47' ),
		'oliva'  => array( 'nome' => 'Verde-oliva', 'bg' => '#2B3110', 'ink' => '#EEFBC2', 'line' => 'rgb(238 251 194 / .25)', 'btn' => '#D8FE47', 'btnInk' => '#111111' ),
	);
}

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'passarela_peca', 'Detalhes da peça e vitrine do topo', 'passarela_caixa_peca', 'peca', 'normal', 'high' );
} );

function passarela_caixa_peca( $post ) {
	wp_nonce_field( 'passarela_salvar_peca', 'passarela_nonce' );
	$campos = passarela_campos_peca();
	$na_vitrine = get_post_meta( $post->ID, 'vitrine', true );
	$cor = get_post_meta( $post->ID, 'vitrine_cor', true ) ?: 'limao';
	echo '<style>.passarela-campos p{margin:0 0 14px}.passarela-campos label{display:block;font-weight:600;margin-bottom:4px}.passarela-campos input[type=text],.passarela-campos select{width:100%;max-width:520px}.passarela-campos h4{margin:22px 0 6px;padding-top:14px;border-top:1px solid #ddd}</style>';
	echo '<div class="passarela-campos">';
	foreach ( array( 'preco', 'tamanhos', 'selo' ) as $chave ) {
		printf( '<p><label for="pas_%1$s">%2$s</label><input type="text" id="pas_%1$s" name="pas_%1$s" value="%3$s"></p>', esc_attr( $chave ), esc_html( $campos[ $chave ] ), esc_attr( get_post_meta( $post->ID, $chave, true ) ) );
	}
	echo '<p style="color:#666">A descrição curta da peça é o campo "Resumo". A foto é a "Foto da peça", na lateral.</p>';
	echo '<h4>Vitrine animada do topo do site</h4>';
	printf( '<p><label><input type="checkbox" name="pas_vitrine" value="1" %s> Mostrar esta peça na vitrine do topo</label></p>', checked( $na_vitrine, '1', false ) );
	foreach ( array( 'vitrine_titulo', 'vitrine_texto', 'vitrine_frase' ) as $chave ) {
		printf( '<p><label for="pas_%1$s">%2$s</label><input type="text" id="pas_%1$s" name="pas_%1$s" value="%3$s"></p>', esc_attr( $chave ), esc_html( $campos[ $chave ] ), esc_attr( get_post_meta( $post->ID, $chave, true ) ) );
	}
	echo '<p><label for="pas_vitrine_cor">Cor de fundo</label><select id="pas_vitrine_cor" name="pas_vitrine_cor">';
	foreach ( passarela_cores_vitrine() as $id => $c ) {
		printf( '<option value="%s" %s>%s</option>', esc_attr( $id ), selected( $cor, $id, false ), esc_html( $c['nome'] ) );
	}
	echo '</select></p><p style="color:#666">A ordem das peças segue o campo "Ordem" (em Atributos da página). Números menores aparecem primeiro.</p></div>';
}

add_action( 'save_post_peca', function ( $post_id ) {
	if ( ! isset( $_POST['passarela_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['passarela_nonce'] ) ), 'passarela_salvar_peca' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( array_keys( passarela_campos_peca() ) as $chave ) {
		$valor = isset( $_POST[ 'pas_' . $chave ] ) ? sanitize_text_field( wp_unslash( $_POST[ 'pas_' . $chave ] ) ) : '';
		update_post_meta( $post_id, $chave, $valor );
	}
	update_post_meta( $post_id, 'vitrine', empty( $_POST['pas_vitrine'] ) ? '' : '1' );
	$cor = isset( $_POST['pas_vitrine_cor'] ) ? sanitize_key( wp_unslash( $_POST['pas_vitrine_cor'] ) ) : 'limao';
	update_post_meta( $post_id, 'vitrine_cor', array_key_exists( $cor, passarela_cores_vitrine() ) ? $cor : 'limao' );
} );

/* Ordem dos departamentos (campo "Ordem" na tela do departamento) */
add_action( 'departamento_add_form_fields', function () {
	echo '<div class="form-field"><label for="ordem">Ordem</label><input type="number" name="ordem" id="ordem" value="0"><p>Números menores aparecem primeiro nos arcos do site.</p></div>';
} );
add_action( 'departamento_edit_form_fields', function ( $term ) {
	printf( '<tr class="form-field"><th scope="row"><label for="ordem">Ordem</label></th><td><input type="number" name="ordem" id="ordem" value="%d"><p class="description">Números menores aparecem primeiro nos arcos do site.</p></td></tr>', (int) get_term_meta( $term->term_id, 'ordem', true ) );
} );
$passarela_salvar_ordem = function ( $term_id ) {
	if ( isset( $_POST['ordem'] ) && current_user_can( 'manage_categories' ) ) {
		update_term_meta( $term_id, 'ordem', (int) $_POST['ordem'] );
	}
};
add_action( 'created_departamento', $passarela_salvar_ordem );
add_action( 'edited_departamento', $passarela_salvar_ordem );

/* Colunas úteis na lista de peças */
add_filter( 'manage_peca_posts_columns', function ( $cols ) {
	$novo = array();
	foreach ( $cols as $k => $v ) {
		if ( 'title' === $k ) {
			$novo['foto'] = 'Foto';
		}
		$novo[ $k ] = $v;
		if ( 'title' === $k ) {
			$novo['preco']   = 'Preço';
			$novo['vitrine'] = 'Vitrine do topo';
		}
	}
	return $novo;
} );
add_action( 'manage_peca_posts_custom_column', function ( $col, $post_id ) {
	if ( 'foto' === $col ) {
		echo get_the_post_thumbnail( $post_id, array( 48, 60 ), array( 'style' => 'width:48px;height:60px;object-fit:cover;border-radius:4px' ) );
	} elseif ( 'preco' === $col ) {
		echo esc_html( get_post_meta( $post_id, 'preco', true ) ?: 'Sob consulta' );
	} elseif ( 'vitrine' === $col ) {
		echo get_post_meta( $post_id, 'vitrine', true ) ? 'Sim' : '—';
	}
}, 10, 2 );

/* ---------- Dados para o site ---------- */

function passarela_departamentos() {
	$termos = get_terms( array( 'taxonomy' => 'departamento', 'hide_empty' => false ) );
	if ( is_wp_error( $termos ) ) {
		return array();
	}
	usort( $termos, function ( $a, $b ) {
		$oa = (int) get_term_meta( $a->term_id, 'ordem', true );
		$ob = (int) get_term_meta( $b->term_id, 'ordem', true );
		return $oa === $ob ? $a->term_id - $b->term_id : $oa - $ob;
	} );
	return $termos;
}

function passarela_pecas() {
	return get_posts( array(
		'post_type'      => 'peca',
		'posts_per_page' => -1,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
	) );
}

function passarela_foto( $post_id, $tamanho = 'passarela-peca' ) {
	$url = get_the_post_thumbnail_url( $post_id, $tamanho );
	return $url ? $url : '';
}

function passarela_contatos() {
	$lista = array();
	for ( $i = 1; $i <= 3; $i++ ) {
		$num = preg_replace( '/\D/', '', (string) passarela_opcao( "whatsapp_{$i}_numero" ) );
		if ( $num ) {
			$lista[] = array(
				'label' => passarela_opcao( "whatsapp_{$i}_nome" ),
				'num'   => $num,
				'show'  => passarela_formatar_telefone( $num ),
			);
		}
	}
	return $lista;
}

function passarela_formatar_telefone( $num ) {
	$n = preg_replace( '/^55/', '', $num );
	if ( strlen( $n ) === 11 ) {
		return sprintf( '(%s) %s-%s', substr( $n, 0, 2 ), substr( $n, 2, 5 ), substr( $n, 7 ) );
	}
	if ( strlen( $n ) === 10 ) {
		return sprintf( '(%s) %s-%s', substr( $n, 0, 2 ), substr( $n, 2, 4 ), substr( $n, 6 ) );
	}
	return $num;
}

function passarela_wa( $texto, $num = '' ) {
	if ( ! $num ) {
		$contatos = passarela_contatos();
		$num = $contatos ? $contatos[0]['num'] : '';
	}
	return 'https://wa.me/' . $num . '?text=' . rawurlencode( $texto );
}

function passarela_dados_js() {
	$pecas = array();
	$slides = array();
	$cores = passarela_cores_vitrine();
	foreach ( passarela_pecas() as $p ) {
		$deps = get_the_terms( $p->ID, 'departamento' );
		$dep = ( $deps && ! is_wp_error( $deps ) ) ? $deps[0]->name : '';
		$tam = array_values( array_filter( array_map( 'trim', explode( ',', (string) get_post_meta( $p->ID, 'tamanhos', true ) ) ) ) );
		$item = array(
			'id'    => $p->ID,
			'cat'   => $dep,
			'name'  => get_the_title( $p ),
			'spec'  => wp_strip_all_tags( get_the_excerpt( $p ) ),
			'img'   => passarela_foto( $p->ID ),
			'tag'   => get_post_meta( $p->ID, 'selo', true ),
			'price' => get_post_meta( $p->ID, 'preco', true ),
			'sizes' => $tam,
			'url'   => get_permalink( $p ),
		);
		$pecas[] = $item;
		if ( get_post_meta( $p->ID, 'vitrine', true ) ) {
			$c = $cores[ get_post_meta( $p->ID, 'vitrine_cor', true ) ] ?? $cores['limao'];
			$slides[] = array_merge( array(
				'id'     => $p->ID,
				'kicker' => get_the_title( $p ),
				'title'  => get_post_meta( $p->ID, 'vitrine_titulo', true ) ?: get_the_title( $p ),
				'text'   => get_post_meta( $p->ID, 'vitrine_texto', true ) ?: $item['spec'],
				'tag'    => get_post_meta( $p->ID, 'vitrine_frase', true ),
			), array_diff_key( $c, array( 'nome' => 1 ) ) );
		}
	}
	if ( ! $slides ) {
		foreach ( array_slice( $pecas, 0, 4 ) as $i => $p ) {
			$c = array_values( $cores )[ $i % count( $cores ) ];
			$slides[] = array_merge( array( 'id' => $p['id'], 'kicker' => $p['name'], 'title' => $p['name'], 'text' => $p['spec'], 'tag' => '' ), array_diff_key( $c, array( 'nome' => 1 ) ) );
		}
	}
	$padroes = array(
		array( 'twill', '#3D5A80,#4A6990', 'i-pants', false ),
		array( 'stripe', '#F4F4EC,#2A2A2A,#D8FE47', 'i-shirt', false ),
		array( 'herring', '#1E1E1E,#2C2C2C', 'i-bag', true ),
		array( 'quilt', '#FAFAF5,#E2E2D6', 'i-throw', false ),
		array( 'knit', '#EEFBC2,#E0EDAE', 'i-throw', false ),
	);
	$deps = array();
	$k = 0;
	foreach ( passarela_departamentos() as $t ) {
		$com_foto = get_posts( array( 'post_type' => 'peca', 'posts_per_page' => 1, 'tax_query' => array( array( 'taxonomy' => 'departamento', 'terms' => $t->term_id ) ), 'meta_key' => '_thumbnail_id', 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ) ) );
		if ( $t->count > 0 && $com_foto ) {
			$deps[] = array( 'label' => $t->name, 'go' => $t->name, 'img' => passarela_foto( $com_foto[0]->ID ) );
		} else {
			$pd = $padroes[ $k++ % count( $padroes ) ];
			$deps[] = array( 'label' => $t->name, 'ask' => true, 'pat' => $pd[0], 'c' => $pd[1], 'icon' => $pd[2], 'light' => $pd[3] );
		}
	}
	$insta = array_values( array_filter( array_map( function ( $p ) { return $p['img']; }, $pecas ) ) );
	return array(
		'contacts'   => passarela_contatos(),
		'products'   => $pecas,
		'slides'     => $slides,
		'categories' => $deps,
		'insta'      => array_slice( array_reverse( $insta ), 0, 4 ),
		'instagram'  => passarela_opcao( 'instagram' ),
	);
}

/* Menu padrão quando nenhum menu foi criado */
function passarela_menu_padrao() {
	$base = is_front_page() ? '' : home_url( '/' );
	echo '<ul>';
	foreach ( array( '#inicio' => 'Início', '#categorias' => 'Departamentos', '#vitrine' => 'Vitrine', '#sobre' => 'A loja', '#contato' => 'Contato' ) as $ancora => $nome ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( $base . $ancora ), esc_html( $nome ) );
	}
	echo '</ul>';
}

function passarela_icones() {
	// SVG estático do próprio tema (ícones de linha).
	echo file_get_contents( get_template_directory() . '/inc/icones.svg' ); // phpcs:ignore
}
