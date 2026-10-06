<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<footer>
  <div class="wrap f-grid">
    <div class="f-brand">
      <div class="brand"><span class="logo-tile" style="width:52px;height:52px;border-radius:14px"><i class="mark"></i></span><span class="logo"><?php bloginfo( 'name' ); ?></span></div>
      <p><?php echo esc_html( passarela_opcao( 'rodape_texto' ) ); ?></p>
    </div>
    <div><h4>Departamentos</h4><ul>
      <?php foreach ( array_slice( passarela_departamentos(), 0, 5 ) as $t ) : ?>
      <li><a href="<?php echo esc_url( get_term_link( $t ) ); ?>"><?php echo esc_html( $t->name ); ?></a></li>
      <?php endforeach; ?>
    </ul></div>
    <div><h4>Atendimento</h4><ul>
      <?php foreach ( passarela_contatos() as $c ) : ?>
      <li><?php echo esc_html( $c['label'] ); ?>: <a class="sel" href="<?php echo esc_url( passarela_wa( 'Olá, A Passarela! Vim pelo site.', $c['num'] ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $c['show'] ); ?></a></li>
      <?php endforeach; ?>
    </ul></div>
    <div><h4>Endereço</h4><ul>
      <li><?php echo esc_html( passarela_opcao( 'endereco_1' ) ); ?></li>
      <li><?php echo esc_html( passarela_opcao( 'endereco_2' ) ); ?></li>
      <?php if ( passarela_opcao( 'mapa' ) ) : ?><li><a href="<?php echo esc_url( passarela_opcao( 'mapa' ) ); ?>" target="_blank" rel="noopener">Ver no mapa</a></li><?php endif; ?>
    </ul></div>
    <div><h4>Redes</h4><ul>
      <?php foreach ( array( 'instagram' => 'Instagram', 'facebook' => 'Facebook', 'linktree' => 'Linktree' ) as $k => $nome ) : if ( passarela_opcao( $k ) ) : ?>
      <li><a href="<?php echo esc_url( passarela_opcao( $k ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $nome ); ?></a></li>
      <?php endif; endforeach; ?>
    </ul></div>
  </div>
  <div class="legal"><div class="wrap"><span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?> · <?php echo esc_html( passarela_opcao( 'endereco_2' ) ); ?></span><span>Vestindo a cidade há 35 anos.</span></div></div>
</footer>

<div class="overlay" id="overlay" hidden></div>
<aside class="drawer" id="drawer" hidden aria-label="Minha lista">
  <div class="drawer-head"><div class="brand"><span class="logo-tile"><i class="mark"></i></span><h2>Minha lista</h2></div><button class="x" id="closeBag" aria-label="Fechar lista">×</button></div>
  <div class="items" id="items"></div>
  <div class="total"><b>Peças</b><span id="total">0</span></div>
  <a class="btn btn-lime" id="checkout" target="_blank" rel="noopener">Enviar lista pelo WhatsApp</a>
  <div class="note">A equipe responde com valores, tamanhos e disponibilidade.</div>
</aside>
<div class="toast" id="toast" hidden></div>
<?php wp_footer(); ?>
</body>
</html>
