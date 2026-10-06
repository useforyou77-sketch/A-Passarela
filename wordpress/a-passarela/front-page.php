<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$sobre_img = passarela_opcao( 'sobre_imagem' );
?>
<main id="inicio">
  <section class="show" aria-label="Destaques">
    <div class="wrap">
      <div class="show-card" id="show">
        <div class="show-left">
          <div class="arrows"><button id="prev" aria-label="Peça anterior">←</button><button id="next" aria-label="Próxima peça">→</button></div>
          <div class="kicker fade" id="sKicker"></div>
          <h1 class="fade d2" id="sTitle"></h1>
          <p class="fade d3" id="sText"></p>
          <button class="cta" id="sCta">Quero essa <span aria-hidden="true">→</span></button>
          <div class="socials">
            <?php if ( passarela_opcao( 'instagram' ) ) : ?><a href="<?php echo esc_url( passarela_opcao( 'instagram' ) ); ?>" target="_blank" rel="noopener">Instagram</a><?php endif; ?>
            <?php if ( passarela_opcao( 'facebook' ) ) : ?><a href="<?php echo esc_url( passarela_opcao( 'facebook' ) ); ?>" target="_blank" rel="noopener">Facebook</a><?php endif; ?>
            <a href="<?php echo esc_url( passarela_wa( 'Olá, A Passarela! Vim pelo site e gostaria de mais informações.' ) ); ?>" target="_blank" rel="noopener">WhatsApp</a>
          </div>
        </div>
        <div class="stage">
          <div class="seal" aria-hidden="true"><i class="mark"></i><svg viewBox="0 0 100 100"><defs><path id="ring" d="M50,50 m-38,0 a38,38 0 1,1 76,0 a38,38 0 1,1 -76,0"/></defs><text font-family="Jost, sans-serif" font-size="9.2" letter-spacing="2.8" fill="#111111"><textPath href="#ring">A PASSARELA · 35 ANOS · </textPath></text></svg></div>
          <div class="obj-wrap" id="sObj"></div>
          <div class="tagline fade d2" id="sTag"></div>
        </div>
        <div class="show-right">
          <div class="inst">Valor</div>
          <div class="ask fade" id="sPrice">Sob consulta</div>
          <div class="inst fade d2">Resposta rápida no WhatsApp</div>
          <div class="size-label" id="sSizeLabel"></div>
          <div class="sizes" id="sSizes" role="group"></div>
          <button class="next" id="sNext"><span class="thumb" id="sThumb"></span><span>Próxima peça</span></button>
        </div>
        <div class="bars" id="sBars"></div>
      </div>
    </div>
  </section>

  <section id="categorias">
    <div class="wrap center">
      <div class="eyebrow">Departamentos</div>
      <h2><?php echo esc_html( passarela_opcao( 'dep_titulo' ) ); ?></h2>
      <p class="sub"><?php echo esc_html( passarela_opcao( 'dep_texto' ) ); ?></p>
      <div class="cats" id="cats"></div>
    </div>
  </section>

  <section id="vitrine" class="best">
    <div class="wrap">
      <div class="best-head">
        <div>
          <div class="eyebrow">Vitrine</div>
          <h2><?php echo esc_html( passarela_opcao( 'vitrine_titulo' ) ); ?></h2>
          <p class="sub"><?php echo esc_html( passarela_opcao( 'vitrine_texto' ) ); ?></p>
        </div>
        <div class="chips" role="group" aria-label="Filtrar por departamento" id="chips"></div>
      </div>
      <div class="grid" id="grid"></div>
    </div>
  </section>

  <section id="sobre" class="life">
    <div class="life-art">
      <?php if ( $sobre_img ) : ?><img class="photo" src="<?php echo esc_url( $sobre_img ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"><?php endif; ?>
    </div>
    <div class="life-copy">
      <div class="eyebrow">A loja</div>
      <h2><?php echo esc_html( passarela_opcao( 'sobre_titulo' ) ); ?> <em><?php echo esc_html( passarela_opcao( 'sobre_destaque' ) ); ?></em></h2>
      <p><?php echo esc_html( passarela_opcao( 'sobre_texto' ) ); ?></p>
      <div class="values">
        <div class="value"><i><svg><use href="#i-gem"/></svg></i>35 anos de<br>tradição</div>
        <div class="value"><i><svg><use href="#i-spark"/></svg></i>Novidades<br>toda semana</div>
        <div class="value"><i><svg><use href="#i-moon"/></svg></i>Cama, mesa<br>e banho</div>
        <div class="value"><i><svg><use href="#i-chat"/></svg></i>Atendimento<br>no WhatsApp</div>
      </div>
    </div>
  </section>

  <div class="facts">
    <div class="wrap">
      <?php for ( $i = 1; $i <= 4; $i++ ) : ?>
      <div class="fact"><b><?php echo esc_html( passarela_opcao( "numero_{$i}_valor" ) ); ?></b><span><?php echo esc_html( passarela_opcao( "numero_{$i}_texto" ) ); ?></span></div>
      <?php endfor; ?>
    </div>
  </div>

  <section id="medidas">
    <div class="wrap m-grid">
      <div>
        <div class="eyebrow">Cama posta</div>
        <h2>Qual jogo serve na <em>sua cama?</em></h2>
        <p>Use as medidas de colchão abaixo como referência e escolha a coberta de 30 a 40 cm maior de cada lado. Em dúvida, mande a medida do seu colchão no WhatsApp que a gente ajuda.</p>
      </div>
      <div class="tbl">
        <table>
          <thead><tr><th>Tamanho</th><th>Colchão</th><th>Coberta ideal</th></tr></thead>
          <tbody>
            <tr><td>Solteiro</td><td>0,88 × 1,88 m</td><td>1,50 × 2,20 m</td></tr>
            <tr><td>Casal</td><td>1,38 × 1,88 m</td><td>1,80 × 2,20 m</td></tr>
            <tr><td>Queen</td><td>1,58 × 1,98 m</td><td>2,40 × 2,20 m</td></tr>
            <tr><td>King</td><td>1,93 × 2,03 m</td><td>2,60 × 2,40 m</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <?php if ( passarela_opcao( 'instagram' ) ) : ?>
  <section style="padding-top:0">
    <div class="wrap">
      <div class="insta-head">
        <div>
          <div class="eyebrow"><?php echo esc_html( passarela_opcao( 'instagram_chamada' ) ); ?></div>
          <h2>Novidades toda semana <em>no Instagram</em></h2>
        </div>
        <a class="btn btn-line" href="<?php echo esc_url( passarela_opcao( 'instagram' ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( passarela_opcao( 'instagram_nome' ) ); ?></a>
      </div>
      <div class="insta" id="insta"></div>
    </div>
  </section>
  <?php endif; ?>

  <section class="community" id="contato">
    <div class="wrap">
      <div class="band">
        <div class="band-l">
          <span class="logo-tile"><i class="mark"></i></span>
          <div><h2>Fale com a nossa equipe</h2><p>Tire dúvidas, peça fotos e reserve suas peças direto pelo WhatsApp.</p></div>
        </div>
        <div class="wa-list">
          <?php foreach ( passarela_contatos() as $c ) : ?>
          <a class="btn" href="<?php echo esc_url( passarela_wa( 'Olá, A Passarela! Vim pelo site e gostaria de atendimento.', $c['num'] ) ); ?>" target="_blank" rel="noopener"><span><?php echo esc_html( $c['label'] ); ?></span><b><?php echo esc_html( $c['show'] ); ?></b></a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>
