<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<main class="page-wrap">
  <div class="wrap">
    <?php if ( is_post_type_archive( 'peca' ) || is_tax( 'departamento' ) ) : ?>
      <div class="eyebrow">Vitrine</div>
      <h2><?php echo esc_html( is_tax() ? single_term_title( '', false ) : 'Todas as peças' ); ?></h2>
      <div class="grid">
        <?php while ( have_posts() ) : the_post(); $preco = get_post_meta( get_the_ID(), 'preco', true ); ?>
        <article class="card">
          <a class="ph" href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'passarela-peca', array( 'class' => 'photo' ) ); } ?></a>
          <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <div class="spec"><?php echo esc_html( get_the_excerpt() ); ?></div>
          <div class="card-foot"><div class="price"><?php echo esc_html( $preco ? $preco : 'Sob consulta' ); ?></div><a class="add" href="<?php the_permalink(); ?>">Ver peça</a></div>
        </article>
        <?php endwhile; ?>
      </div>
      <?php the_posts_pagination(); ?>
    <?php else : ?>
      <?php while ( have_posts() ) : the_post(); ?>
      <article <?php post_class( 'entry' ); ?>>
        <h1><?php the_title(); ?></h1>
        <?php the_content(); ?>
      </article>
      <?php endwhile; ?>
      <?php if ( ! have_posts() ) : ?><p>Nada encontrado por aqui. <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Voltar para o início</a>.</p><?php endif; ?>
    <?php endif; ?>
  </div>
</main>
<?php get_footer(); ?>
