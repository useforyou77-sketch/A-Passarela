const { faltando, iguais, criarSessao } = require("./_lib");

// Pequena pausa a cada tentativa errada para dificultar adivinhar a senha.
const espera = ms => new Promise(r => setTimeout(r, ms));

module.exports = async (req, res) => {
  if (req.method !== "POST") return res.status(405).end();
  const falta = faltando();
  if (falta.length) return res.status(500).json({ erro: "config", falta });
  const { usuario = "", senha = "" } = req.body || {};
  const ok = iguais(String(usuario).trim().toLowerCase(), process.env.ADMIN_USER.trim().toLowerCase())
    & iguais(senha, process.env.ADMIN_PASSWORD);
  if (!ok) { await espera(800); return res.status(401).json({ erro: "senha" }); }
  criarSessao(res);
  res.json({ ok: true });
};
