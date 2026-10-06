# A Passarela

Site da loja **A Passarela**, há 35 anos a loja mais completa de Itapuranga-GO
(Rua 45, nº 850, Centro). Moda feminina, masculina, infantil, acessórios e cama, mesa e banho.

## O que tem aqui

| Pasta / arquivo | O que é |
| --- | --- |
| `index.html` | O site (vitrine animada, departamentos, catálogo, lista pelo WhatsApp). |
| `dados.json` | Todo o conteúdo do site: peças, preços, fotos, contatos, endereço e textos. |
| `fotos/` | Fotos das peças. |
| `wordpress/a-passarela/` | Tema WordPress editável com o mesmo visual. |
| `wordpress/a-passarela-tema-wordpress.zip` | O mesmo tema, pronto para instalar no WordPress. |

## Como editar

Abra `dados.json` aqui no GitHub, clique no lápis (Editar) e mude o que quiser:
nome, preço, tamanhos, departamento ou foto de cada peça, e os dados da loja.
Para trocar uma foto, envie a imagem na pasta `fotos/` e coloque o caminho no campo `img`
(por exemplo `fotos/vestido-novo.jpg`). Deixe `price` vazio para mostrar "Sob consulta".

## Como colocar no ar de graça (GitHub Pages)

1. No repositório, abra **Settings → Pages**.
2. Em **Source**, escolha **Deploy from a branch**, branch **main** e pasta **/ (root)**.
3. Salve. Em alguns minutos o site fica no endereço que aparece nessa tela.

## Como usar o tema WordPress

No painel do WordPress, vá em **Aparência → Temas → Adicionar novo → Enviar tema**
e envie `wordpress/a-passarela-tema-wordpress.zip`. Ao ativar, ele cria as peças e os
departamentos. Os contatos e textos ficam em **Aparência → Personalizar → A Passarela**.

## Observação

O "Painel da loja" com login e senha funciona só na versão do site hospedada no claude.ai.
Fora de lá o site abre normalmente, mas sem o painel; a edição é feita pelo `dados.json`.
