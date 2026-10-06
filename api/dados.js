const { exigirLogin, lerArquivo, gravarArquivo, erroGithub } = require("./_lib");

// GET: conteúdo atual do site direto do GitHub. POST: salva o conteúdo novo.
module.exports = async (req, res) => {
  res.setHeader("Cache-Control", "no-store");
  if (!exigirLogin(req, res)) return;
  try {
    if (req.method === "GET") {
      const { texto, sha } = await lerArquivo("dados.json");
      return res.json({ dados: JSON.parse(texto), sha });
    }
    if (req.method === "POST") {
      const { dados, sha } = req.body || {};
      if (!dados || !Array.isArray(dados.pecas) || !dados.loja) return res.status(400).json({ erro: "dados" });
      const base64 = Buffer.from(JSON.stringify(dados, null, 1) + "\n").toString("base64");
      const novo = await gravarArquivo("dados.json", base64, "Painel: atualiza o conteúdo do site", sha);
      return res.json({ ok: true, sha: novo });
    }
    res.status(405).end();
  } catch (err) {
    erroGithub(res, err);
  }
};
