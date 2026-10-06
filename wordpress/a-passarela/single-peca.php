<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) :
	the_post();
	$preco = get_post_meta( get_the_ID(), 'preco', true );
	$tam   = array_filter( array_map( 'trim', explode( ',', (string) get_post_meta( get_the_ID(), 'tamanhos', true ) ) ) );
	$deps  = get_the_terms( get_the_ID(), 'departamento' );
	?>
<main class="page-wrap">
  <div class="wrap">
    <a class="voltar" href="<?php echo esc_url( home_url( '/#vitrine' ) ); ?>">← Voltar para a vitrine</a>
    <article class="peca">
      <div class="peca-foto"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'passarela-peca', array( 'class' => 'photo' ) ); } ?></div>
      <div class="peca-info">
        <?php if ( $deps && ! is_wp_error( $deps ) ) : ?><div class="eyebrow"><?php echo esc_html( $deps[0]->name ); ?></div><?php endif; ?>
        <h1><?php the_title(); ?></h1>
        <div class="peca-preco"><?php echo esc_html( $preco ? $preco : 'Sob consulta' ); ?></div>
        <div class="entry"><?php the_content(); ?></div>
        <?php if ( $tam ) : ?>
        <div class="size-label">Tamanhos</div>
        <div class="sizes" id="pecaSizes" role="group" aria-label="Tamanhos">
          <?php foreach ( array_values( $tam ) as $i => $t ) : ?><button class="size" aria-pressed="<?php echo 0 === $i ? 'true' : 'false'; ?>"><?php echo esc_html( $t ); ?></button><?php endforeach; ?>
        </div>
        <?php endif; ?>
        <div class="peca-acoes">
          <button class="btn btn-lime" id="pecaAdd" data-id="<?php the_ID(); ?>">Adicionar à minha lista</button>
          <a class="btn btn-line" href="<?php echo esc_url( passarela_wa( 'Olá, A Passarela! Tenho interesse nesta peça: ' . get_the_title() . ' (' . get_permalink() . ')' ) ); ?>" target="_blank" rel="noopener">Perguntar no WhatsApp</a>
        </div>
      </div>
    </article>
  </div>
</main>
	<?php
endwhile;
get_footer();
