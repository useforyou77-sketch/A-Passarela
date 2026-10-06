<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php passarela_icones(); ?>
<?php $faixa = passarela_opcao( 'faixa_topo' ); if ( $faixa ) : ?>
<div class="promo"><?php echo esc_html( $faixa ); ?></div>
<?php endif; ?>
<header class="top">
  <div class="wrap">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand"><span class="logo-tile"><i class="mark"></i></span><span class="logo"><?php bloginfo( 'name' ); ?></span></a>
    <nav class="nav" aria-label="Principal">
      <?php wp_nav_menu( array( 'theme_location' => 'principal', 'container' => false, 'depth' => 1, 'fallback_cb' => 'passarela_menu_padrao' ) ); ?>
    </nav>
    <div class="icons">
      <a class="icon-btn" href="<?php echo esc_url( home_url( '/#vitrine' ) ); ?>" aria-label="Ver vitrine"><svg><use href="#i-search"/></svg></a>
      <button class="icon-btn" id="openBag" aria-label="Abrir minha lista"><svg><use href="#i-bag"/></svg><span class="bag-count" id="bagCount">0</span></button>
    </div>
  </div>
</header>
