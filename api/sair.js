const { apagarSessao } = require("./_lib");

module.exports = (req, res) => {
  apagarSessao(res);
  res.json({ ok: true });
};
