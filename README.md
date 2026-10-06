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

## Painel da loja (na Vercel)

O site tem um painel com login e senha em **/painel** (link "Área da loja" no rodapé).
Nele você edita peças, preços, fotos, WhatsApps, endereço e textos. Ao salvar, o painel grava
no GitHub e a Vercel publica o site de novo sozinha, em cerca de 1 minuto.

Para o painel funcionar, cadastre na Vercel, em **Settings → Environment Variables**:

| Nome | O que colocar |
| --- | --- |
| `ADMIN_USER` | O usuário do painel (ex.: `passarela`). |
| `ADMIN_PASSWORD` | A senha do painel. |
| `SESSION_SECRET` | Um texto longo e aleatório (40 letras e números quaisquer). |
| `GITHUB_TOKEN` | Um token do GitHub com permissão de escrever neste repositório (veja abaixo). |

Depois de cadastrar, vá em **Deployments** e faça **Redeploy**.

**Como criar o `GITHUB_TOKEN`:** no GitHub, abra **Settings → Developer settings →
Personal access tokens → Fine-grained tokens → Generate new token**. Em **Repository access**
escolha **Only select repositories** e marque **A-Passarela**. Em **Permissions → Contents**
escolha **Read and write**. Gere o token, copie e cole na Vercel.

Para trocar usuário ou senha, mude `ADMIN_USER` e `ADMIN_PASSWORD` na Vercel e faça Redeploy.

## Editar sem o painel

Também dá para abrir `dados.json` aqui no GitHub, clicar no lápis (Editar) e mudar o conteúdo.
Fotos novas vão na pasta `fotos/`, com o caminho no campo `img` (ex.: `fotos/vestido-novo.jpg`).
Deixe `price` vazio para mostrar "Sob consulta".

## Como colocar no ar de graça (GitHub Pages)

1. No repositório, abra **Settings → Pages**.
2. Em **Source**, escolha **Deploy from a branch**, branch **main** e pasta **/ (root)**.
3. Salve. Em alguns minutos o site fica no endereço que aparece nessa tela.

## Como usar o tema WordPress

No painel do WordPress, vá em **Aparência → Temas → Adicionar novo → Enviar tema**
e envie `wordpress/a-passarela-tema-wordpress.zip`. Ao ativar, ele cria as peças e os
departamentos. Os contatos e textos ficam em **Aparência → Personalizar → A Passarela**.
