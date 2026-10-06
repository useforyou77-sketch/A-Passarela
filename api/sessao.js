const { faltando, logado } = require("./_lib");

module.exports = (req, res) => {
  res.setHeader("Cache-Control", "no-store");
  res.json({ logado: logado(req), falta: faltando() });
};
