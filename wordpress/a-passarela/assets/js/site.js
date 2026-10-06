/* A Passarela: vitrine animada, departamentos, catálogo e lista de pedidos pelo WhatsApp.
   Os dados vêm do painel do WordPress (window.PASSARELA). */
(function () {
  const D = window.PASSARELA || {};
  const CONTACTS = D.contacts || [];
  const PRODUCTS = (D.products || []).map(p => ({ ...p, id: +p.id }));
  const WHATSAPP = CONTACTS.length ? CONTACTS[0].num : "";
  const $ = id => document.getElementById(id);
  const esc = s => String(s == null ? "" : s).replace(/[&<>"']/g, c => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c]));
  const waLink = (text, num = WHATSAPP) => `https://wa.me/${num}?text=${encodeURIComponent(text)}`;
  const photo = (src, alt) => src ? `<img class="photo" src="${esc(src)}" alt="${esc(alt)}" loading="lazy">` : "";
  const priceOf = p => p.price ? p.price : "Sob consulta";

  // Tecidos desenhados em CSS para departamentos ainda sem foto.
  function pattern(type, colors) {
    const [a, b, c] = colors.split(",");
    switch (type) {
      case "knit": return `background-color:${a};background-image:linear-gradient(135deg, ${b} 25%, transparent 25%), linear-gradient(225deg, ${b} 25%, transparent 25%), linear-gradient(315deg, ${b} 25%, transparent 25%), linear-gradient(45deg, ${b} 25%, transparent 25%);background-size:22px 22px`;
      case "twill": return `background:repeating-linear-gradient(45deg, ${a} 0 3px, ${b} 3px 6px)`;
      case "quilt": return `background:repeating-linear-gradient(45deg, transparent 0 34px, ${b} 34px 36px),repeating-linear-gradient(-45deg, transparent 0 34px, ${b} 34px 36px), radial-gradient(circle at 50% 40%, ${a}, ${b})`;
      case "herring": return `background-color:${a};background-image:linear-gradient(135deg, ${b} 25%, transparent 25%), linear-gradient(225deg, ${b} 25%, transparent 25%);background-size:28px 56px`;
      case "stripe": return `background:repeating-linear-gradient(90deg, ${a} 0 26px, ${b} 26px 32px, ${a} 32px 52px, ${c} 52px 58px)`;
    }
    return `background:${a}`;
  }
  const sw = (pat, c) => `<div class="sw" style="${pattern(pat, c)}"></div>`;

  /* ---------- Minha lista ---------- */
  let bag = {};
  try { bag = JSON.parse(localStorage.getItem("passarela-lista")) || {}; } catch (e) { bag = {}; }
  const save = () => { try { localStorage.setItem("passarela-lista", JSON.stringify(bag)); } catch (e) {} };
  const parseKey = k => { const [id, size] = String(k).split("|"); return { id: +id, size: size || "" }; };
  let tt;
  function toast(t) { const el = $("toast"); if (!el) return; el.textContent = t; el.hidden = false; clearTimeout(tt); tt = setTimeout(() => el.hidden = true, 1600); }
  function addToBag(id, size = "") { const k = `${id}|${size}`; bag[k] = (bag[k] || 0) + 1; save(); renderBag(); toast("Adicionado à sua lista"); }
  function changeQty(k, d) { bag[k] = (bag[k] || 0) + d; if (bag[k] <= 0) delete bag[k]; save(); renderBag(); }
  function renderBag() {
    const entries = Object.entries(bag).map(([k, q]) => { const { id, size } = parseKey(k); return { k, p: PRODUCTS.find(x => x.id === id), size, q }; }).filter(x => x.p);
    const count = entries.reduce((s, x) => s + x.q, 0);
    if ($("bagCount")) $("bagCount").textContent = count;
    if ($("total")) $("total").textContent = count;
    if (!$("items")) return;
    $("items").innerHTML = entries.length ? entries.map(({ k, p, size, q }) => `
      <div class="item">
        <div class="mini">${photo(p.img, p.name)}</div>
        <div><b>${esc(p.name)}${size ? ` · ${esc(size)}` : ""}</b><div class="qty"><button data-d="-1" data-k="${esc(k)}" aria-label="Diminuir">−</button>${q}<button data-d="1" data-k="${esc(k)}" aria-label="Aumentar">+</button></div></div>
        <div class="p">${esc(priceOf(p))}</div>
      </div>`).join("") : `<div class="empty">Sua lista está vazia.<br>Escolha peças na vitrine.</div>`;
    const msg = entries.length
      ? "Olá, A Passarela! Vi estas peças no site e gostaria de saber valores e disponibilidade:\n\n" + entries.map(({ p, size, q }) => `• ${q}x ${p.name}${size ? ` (tamanho ${size})` : ""}`).join("\n")
      : "Olá, A Passarela! Gostaria de mais informações.";
    if ($("checkout")) $("checkout").href = waLink(msg);
  }
  if ($("items")) $("items").addEventListener("click", e => { const b = e.target.closest("button[data-k]"); if (b) changeQty(b.dataset.k, +b.dataset.d); });
  const drawer = $("drawer"), overlay = $("overlay");
  const openBag = () => { drawer.hidden = overlay.hidden = false; $("closeBag").focus(); };
  const closeBag = () => { drawer.hidden = overlay.hidden = true; };
  if (drawer && overlay) {
    $("openBag").onclick = openBag;
    $("closeBag").onclick = closeBag;
    overlay.onclick = closeBag;
    document.addEventListener("keydown", e => { if (e.key === "Escape") closeBag(); });
  }

  /* ---------- Página da peça ---------- */
  if ($("pecaAdd")) {
    let sz = "";
    const box = $("pecaSizes");
    if (box) {
      const first = box.querySelector('[aria-pressed="true"]'); sz = first ? first.textContent : "";
      box.addEventListener("click", e => { const b = e.target.closest(".size"); if (!b) return; sz = b.textContent; box.querySelectorAll(".size").forEach(x => x.setAttribute("aria-pressed", x === b)); });
    }
    $("pecaAdd").onclick = () => addToBag(+$("pecaAdd").dataset.id, sz);
  }

  /* ---------- Departamentos ---------- */
  if ($("cats")) {
    $("cats").innerHTML = (D.categories || []).map(k => k.ask
      ? `<a class="cat" href="${esc(waLink(`Olá, A Passarela! Quero ver as opções de ${k.label}.`))}" target="_blank" rel="noopener"><div class="frame">${sw(k.pat, k.c)}<svg style="${k.light ? "color:rgb(216 254 71 / .8)" : ""}"><use href="#${esc(k.icon)}"/></svg></div><b>${esc(k.label)}</b></a>`
      : `<button class="cat" data-go="${esc(k.go)}"><div class="frame">${photo(k.img, k.label)}</div><b>${esc(k.label)}</b></button>`).join("");
  }

  /* ---------- Instagram ---------- */
  if ($("insta")) {
    $("insta").innerHTML = (D.insta || []).map(src => `<a href="${esc(D.instagram)}" target="_blank" rel="noopener" aria-label="Ver no Instagram">${photo(src, "Publicação da loja no Instagram")}</a>`).join("");
  }

  /* ---------- Catálogo ---------- */
  let filter = "Todos";
  const favs = new Set();
  function setFilter(c) {
    filter = c;
    if ($("chips")) $("chips").querySelectorAll(".chip").forEach(b => b.setAttribute("aria-pressed", b.dataset.cat === c));
    renderGrid();
  }
  function renderGrid() {
    if (!$("grid")) return;
    const list = PRODUCTS.filter(p => filter === "Todos" || p.cat === filter);
    $("grid").innerHTML = list.map(p => `
      <article class="card">
        <a class="ph" href="${esc(p.url)}">${photo(p.img, p.name)}${p.tag ? `<span class="badge">${esc(p.tag)}</span>` : ""}
          <button class="fav" data-fav="${p.id}" aria-pressed="${favs.has(p.id)}" aria-label="Favoritar ${esc(p.name)}"><svg><use href="#i-heart"/></svg></button></a>
        <h3><a href="${esc(p.url)}">${esc(p.name)}</a></h3>
        <div class="spec">${esc(p.spec)}</div>
        <div class="card-foot">
          <div class="price">${esc(priceOf(p))}<small>${esc(p.cat)}</small></div>
          <button class="add" data-id="${p.id}">Adicionar</button>
        </div>
      </article>`).join("");
  }
  if ($("chips")) {
    const cats = ["Todos", ...new Set(PRODUCTS.map(p => p.cat).filter(Boolean))];
    $("chips").innerHTML = cats.map((c, i) => `<button class="chip" id="chip-${i}" data-cat="${esc(c)}" aria-pressed="${c === filter}">${esc(c)}</button>`).join("");
    $("chips").addEventListener("click", e => { const b = e.target.closest(".chip"); if (b) setFilter(b.dataset.cat); });
  }
  if ($("grid")) {
    $("grid").addEventListener("click", e => {
      const f = e.target.closest(".fav");
      if (f) { e.preventDefault(); const id = +f.dataset.fav; favs.has(id) ? favs.delete(id) : favs.add(id); f.setAttribute("aria-pressed", favs.has(id)); return; }
      const b = e.target.closest(".add"); if (!b) return;
      const p = PRODUCTS.find(x => x.id === +b.dataset.id);
      addToBag(p.id, p.sizes && p.sizes.length ? p.sizes[0] : "");
      b.textContent = "Na lista ✓"; b.classList.add("done");
      setTimeout(() => { b.textContent = "Adicionar"; b.classList.remove("done"); }, 1400);
    });
  }
  document.addEventListener("click", e => {
    const c = e.target.closest("[data-go]"); if (!c) return;
    e.preventDefault(); setFilter(c.dataset.go); $("vitrine").scrollIntoView();
  });

  /* ---------- Vitrine animada ---------- */
  const SLIDES = (D.slides || []).filter(s => PRODUCTS.some(p => p.id === +s.id));
  if ($("show") && SLIDES.length) {
    const DUR = 6500;
    const reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    let cur = 0, size = "", timer = null, paused = false, started = 0, remaining = DUR;
    const objHTML = p => `<div class="obj photo-obj"><div class="float"><div class="photo-frame">${photo(p.img, p.name)}</div></div><div class="ground"></div></div>`;
    const restart = el => { el.classList.remove("fade"); void el.offsetWidth; el.classList.add("fade"); };
    const show = (i, dir = 1) => {
      const prevObj = $("sObj").querySelector(".obj");
      cur = (i + SLIDES.length) % SLIDES.length;
      const s = SLIDES[cur], p = PRODUCTS.find(x => x.id === +s.id);
      const card = $("show");
      card.style.setProperty("--dir", dir);
      Object.entries({ "--s-bg": s.bg, "--s-ink": s.ink, "--s-line": s.line, "--s-btn": s.btn, "--s-btn-ink": s.btnInk }).forEach(([k, v]) => card.style.setProperty(k, v));
      const place = () => { $("sObj").innerHTML = objHTML(p); };
      if (prevObj && !reduce) { prevObj.classList.add("out"); setTimeout(place, 380); } else place();
      $("sKicker").textContent = s.kicker; $("sTitle").textContent = s.title; $("sText").textContent = s.text; $("sTag").textContent = s.tag || "";
      $("sPrice").textContent = priceOf(p);
      ["sKicker", "sTitle", "sText", "sTag", "sPrice"].forEach(id => restart($(id)));
      const sizes = p.sizes || [];
      $("sSizeLabel").textContent = sizes.length ? "Escolha o tamanho" : "";
      size = sizes[Math.min(1, sizes.length - 1)] || "";
      $("sSizes").setAttribute("aria-label", "Tamanhos");
      $("sSizes").innerHTML = sizes.map(z => `<button class="size" aria-pressed="${z === size}">${esc(z)}</button>`).join("");
      const n = PRODUCTS.find(x => x.id === +SLIDES[(cur + 1) % SLIDES.length].id);
      $("sThumb").innerHTML = photo(n.img, n.name);
      $("sNext").setAttribute("aria-label", `Próxima peça: ${n.name}`);
      $("sBars").innerHTML = SLIDES.map((x, k) => `<button aria-label="${esc(x.kicker)}" class="${k < cur ? "done" : k === cur ? "on" : ""}"><i></i></button>`).join("");
      $("sBars").style.setProperty("--dur", DUR + "ms");
      schedule();
    };
    const schedule = () => { clearTimeout(timer); remaining = DUR; started = Date.now(); if (!reduce && !paused && SLIDES.length > 1) timer = setTimeout(() => show(cur + 1, 1), remaining); };
    const pause = () => { if (paused) return; paused = true; $("show").classList.add("paused"); clearTimeout(timer); remaining -= Date.now() - started; };
    const resume = () => { if (!paused) return; paused = false; $("show").classList.remove("paused"); started = Date.now(); if (!reduce && SLIDES.length > 1) timer = setTimeout(() => show(cur + 1, 1), Math.max(remaining, 400)); };
    $("prev").onclick = () => show(cur - 1, -1);
    $("next").onclick = () => show(cur + 1, 1);
    $("sNext").onclick = () => show(cur + 1, 1);
    $("sBars").addEventListener("click", e => { const b = e.target.closest("button"); if (!b) return; const k = [...$("sBars").children].indexOf(b); if (k !== cur) show(k, k > cur ? 1 : -1); });
    $("sSizes").addEventListener("click", e => { const b = e.target.closest(".size"); if (!b) return; size = b.textContent; $("sSizes").querySelectorAll(".size").forEach(x => x.setAttribute("aria-pressed", x === b)); });
    $("sCta").onclick = () => addToBag(+SLIDES[cur].id, size);
    $("show").addEventListener("mouseenter", pause); $("show").addEventListener("mouseleave", resume);
    $("show").addEventListener("focusin", pause); $("show").addEventListener("focusout", e => { if (!$("show").contains(e.relatedTarget)) resume(); });
    let tx = null;
    $("show").addEventListener("touchstart", e => { tx = e.touches[0].clientX; }, { passive: true });
    $("show").addEventListener("touchend", e => { if (tx === null) return; const dx = e.changedTouches[0].clientX - tx; if (Math.abs(dx) > 50) show(cur + (dx < 0 ? 1 : -1), dx < 0 ? 1 : -1); tx = null; });
    show(0);
  } else if ($("show")) {
    $("show").closest("section").hidden = true;
  }

  renderGrid();
  renderBag();
})();
