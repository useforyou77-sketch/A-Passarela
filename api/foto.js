const { exigirLogin, gravarArquivo, erroGithub } = require("./_lib");

const TIPOS = { "image/jpeg": "jpg", "image/png": "png", "image/webp": "webp" };

// Recebe a foto (já reduzida no navegador) e grava em fotos/ no repositório.
module.exports = async (req, res) => {
  if (req.method !== "POST") return res.status(405).end();
  if (!exigirLogin(req, res)) return;
  const { tipo, base64 } = req.body || {};
  const ext = TIPOS[tipo];
  if (!ext || !base64) return res.status(400).json({ erro: "foto" });
  if (base64.length > 4_000_000) return res.status(413).json({ erro: "grande" });
  const caminho = `fotos/painel-${Date.now().toString(36)}.${ext}`;
  try {
    await gravarArquivo(caminho, base64, "Painel: nova foto");
    res.json({ ok: true, caminho });
  } catch (err) {
    erroGithub(res, err);
  }
};
