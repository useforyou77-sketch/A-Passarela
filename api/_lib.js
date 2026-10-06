// Funções compartilhadas pelas rotas do painel (arquivos com "_" não viram rota na Vercel).
const crypto = require("crypto");

const REPO = process.env.GITHUB_REPO || "useforyou77-sketch/A-Passarela";
const BRANCH = process.env.GITHUB_BRANCH || "main";
const API = process.env.GITHUB_API || "https://api.github.com";
const DIAS = 7;

function faltando() {
  return ["ADMIN_USER", "ADMIN_PASSWORD", "SESSION_SECRET", "GITHUB_TOKEN"].filter(k => !process.env[k]);
}

const assinar = txt => crypto.createHmac("sha256", process.env.SESSION_SECRET).update(txt).digest("base64url");

function iguais(a, b) {
  const x = Buffer.from(String(a)), y = Buffer.from(String(b));
  return x.length === y.length && crypto.timingSafeEqual(x, y);
}

function criarSessao(res) {
  const exp = Date.now() + DIAS * 864e5;
  const valor = `${exp}.${assinar(String(exp))}`;
  res.setHeader("Set-Cookie", `sessao=${valor}; Path=/; HttpOnly; Secure; SameSite=Strict; Max-Age=${DIAS * 86400}`);
}

function apagarSessao(res) {
  res.setHeader("Set-Cookie", "sessao=; Path=/; HttpOnly; Secure; SameSite=Strict; Max-Age=0");
}

function logado(req) {
  if (faltando().length) return false;
  const m = /(?:^|;\s*)sessao=([^;]+)/.exec(req.headers.cookie || "");
  if (!m) return false;
  const [exp, sig] = m[1].split(".");
  return !!sig && iguais(sig, assinar(exp)) && Number(exp) > Date.now();
}

// Responde 401 e devolve false quando não há sessão válida.
function exigirLogin(req, res) {
  if (logado(req)) return true;
  res.status(401).json({ erro: "sessao" });
  return false;
}

async function github(caminho, opcoes = {}) {
  const r = await fetch(`${API}/repos/${REPO}/contents/${caminho}`, {
    ...opcoes,
    headers: {
      Authorization: `Bearer ${process.env.GITHUB_TOKEN}`,
      Accept: "application/vnd.github+json",
      "X-GitHub-Api-Version": "2022-11-28",
      "User-Agent": "painel-a-passarela",
      ...(opcoes.body ? { "Content-Type": "application/json" } : {}),
    },
  });
  const corpo = await r.json().catch(() => ({}));
  return { status: r.status, corpo };
}

async function lerArquivo(caminho) {
  const { status, corpo } = await github(`${caminho}?ref=${BRANCH}`);
  if (status !== 200) throw Object.assign(new Error("github"), { status });
  return { texto: Buffer.from(corpo.content, "base64").toString("utf8"), sha: corpo.sha };
}

// Grava um arquivo no repositório; a Vercel publica de novo sozinha a cada commit.
async function gravarArquivo(caminho, base64, mensagem, sha) {
  const { status, corpo } = await github(caminho, {
    method: "PUT",
    body: JSON.stringify({ message: mensagem, content: base64, branch: BRANCH, ...(sha ? { sha } : {}) }),
  });
  if (status !== 200 && status !== 201) throw Object.assign(new Error("github"), { status });
  return corpo.content.sha;
}

function erroGithub(res, err) {
  const s = err && err.status;
  if (s === 409 || s === 422) return res.status(409).json({ erro: "conflito" });
  if (s === 401 || s === 403 || s === 404) return res.status(502).json({ erro: "token" });
  return res.status(502).json({ erro: "github" });
}

module.exports = { faltando, iguais, criarSessao, apagarSessao, logado, exigirLogin, lerArquivo, gravarArquivo, erroGithub };
