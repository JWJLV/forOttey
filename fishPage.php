<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="manifest" href="/manifest.json">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="apple-mobile-web-app-title" content="Adventure Sightings">
  <link rel="apple-touch-icon" href="/pwa-icons/icon-192-maskable.png">
  <title>Adventure Sightings – All Sightings</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root {
      --sand: #f5f0e8; --ink: #1a1814; --muted: #7a7166;
      --accent: #2d6a4f; --card: #fffdf9;
      --border: #e5e7eb; --nav-h: 64px;
    }
    body { font-family: 'DM Sans', sans-serif; background: var(--sand); color: var(--ink); min-height: 100vh; }

    /* NAV */
    nav { position: fixed; top: 0; left: 0; right: 0; height: var(--nav-h); background: var(--ink);
          display: flex; align-items: center; justify-content: space-between; padding: 0 32px; z-index: 100; }
    .nav-logo { font-family: 'Playfair Display', serif; font-size: 20px; color: var(--sand); }
    .nav-logo em { font-style: italic; color: #a8c5b5; }
    .nav-links { display: flex; gap: 8px; }
    .nav-link { font-size: 13px; color: #b8b0a4; text-decoration: none; padding: 7px 16px;
                border-radius: 6px; transition: background 0.15s, color 0.15s; }
    .nav-link:hover { background: rgba(255,255,255,0.08); color: #fffdf9; }
    .nav-link-primary { background: var(--accent); color: #fffdf9 !important; font-weight: 500; }
    .nav-link-primary:hover { background: #245c43; }

    /* PAGE */
    .page { max-width: 900px; margin: 0 auto; padding: calc(var(--nav-h) + 32px) 20px 60px; }
    .page-header { margin-bottom: 28px; display: flex; align-items: flex-end; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
    .page-header-text h1 { font-family: 'Playfair Display', serif; font-size: 28px; font-weight: 700; }
    .page-header-text p { font-size: 14px; color: var(--muted); margin-top: 4px; font-weight: 300; }

    /* FISH COUNT BADGE */
    .fish-count-badge {
      font-size: 12px; font-weight: 500; color: var(--muted);
      background: var(--card); border: 1px solid var(--border);
      padding: 5px 12px; border-radius: 20px;
    }

    /* LIGHTBOX */
    .lightbox {
    position: fixed; inset: 0; z-index: 500;
    background: rgba(10, 9, 8, 0.85);
    backdrop-filter: blur(6px);
    display: flex; align-items: center; justify-content: center;
    padding: 24px;
    opacity: 0; pointer-events: none;
    transition: opacity 0.2s;
    }
    .lightbox.open { opacity: 1; pointer-events: all; }
    .lightbox-inner {
    position: relative; max-width: 860px; width: 100%;
    transform: scale(0.96);
    transition: transform 0.22s ease;
    }
    .lightbox.open .lightbox-inner { transform: scale(1); }
    .lightbox-img {
    width: 100%; max-height: 80vh; object-fit: contain;
    border-radius: 10px; display: block;
    }
    .lightbox-caption {
    text-align: center; color: rgba(255,255,255,0.6);
    font-size: 13px; margin-top: 10px;
    }
    .lightbox-close {
    position: absolute; top: -14px; right: -14px;
    width: 32px; height: 32px; border-radius: 50%;
    background: rgba(255,255,255,0.15); border: none;
    color: #fff; font-size: 18px; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background 0.15s; line-height: 1;
    }
    .lightbox-close:hover { background: rgba(255,255,255,0.28); }

    /* BACK BUTTON */
    .btn-back {
      display: inline-flex; align-items: center; gap: 6px;
      font-size: 13px; font-weight: 500; color: var(--muted);
      background: var(--card); border: 1px solid var(--border);
      padding: 7px 14px; border-radius: 8px; cursor: pointer;
      font-family: inherit; transition: all 0.15s; text-decoration: none;
      margin-bottom: 20px;
    }
    .btn-back:hover { background: #f0ede6; color: var(--ink); border-color: #ccc; }

    /* CARD */
    .card { background: var(--card); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; margin-bottom: 18px; }
    .card-head {
      font-size: 11px; font-weight: 500; letter-spacing: 0.08em; text-transform: uppercase;
      color: var(--muted); padding: 13px 20px; border-bottom: 1px solid var(--border);
      background: #f9f7f3; display: flex; align-items: center; justify-content: space-between;
    }

    /* TABLE */
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; }
    thead th {
      font-size: 11px; font-weight: 500; letter-spacing: 0.06em; text-transform: uppercase;
      color: var(--muted); padding: 11px 16px; text-align: left;
      border-bottom: 1px solid var(--border); white-space: nowrap;
    }
    tbody tr {
      border-bottom: 1px solid #f0ede6; cursor: pointer;
      transition: background 0.1s;
    }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #f5f0e8; }
    td { padding: 13px 16px; font-size: 14px; vertical-align: middle; }
    td.td-name { font-weight: 500; }
    td.td-sci { font-style: italic; color: var(--muted); font-size: 13px; }
    td.td-num { font-variant-numeric: tabular-nums; color: var(--muted); font-size: 13px; }
    td.td-date { color: var(--muted); font-size: 13px; white-space: nowrap; }

    /* PILL BADGE */
    .pill {
      display: inline-block; font-size: 11px; font-weight: 500; padding: 2px 8px;
      border-radius: 20px; background: #edf7f2; color: #1a5e3f;
      border: 1px solid #c2e0d0;
    }

    /* FISH DETAIL VIEW */
    .detail-fish-header {
      background: #edf7f2; padding: 20px 24px;
      display: flex; align-items: flex-start; gap: 18px;
      border-bottom: 1px solid #c2e0d0;
    }
    .detail-fish-photo {
      width: 88px; height: 88px; object-fit: cover; border-radius: 10px;
      border: 1px solid #a7d4bc; flex-shrink: 0; background: #d1ead9;
      display: none;
    }
    .detail-fish-photo.loaded { display: block; }
    .detail-fish-names { flex: 1; min-width: 0; }
    .detail-fish-common { font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 700; color: var(--ink); }
    .detail-fish-sci { font-size: 14px; font-style: italic; color: var(--muted); margin-top: 3px; }
    .detail-fish-meta { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 10px; }
    .detail-meta-chip {
      font-size: 11px; font-weight: 500; padding: 3px 10px; border-radius: 20px;
      background: #d1f0e4; color: #1a5e3f; border: 1px solid #a7d4bc;
    }

    /* SIGHTING CARDS */
    .sightings-list { display: flex; flex-direction: column; gap: 0; }
    .sighting-item { padding: 18px 24px; border-bottom: 1px solid var(--border); }
    .sighting-item:last-child { border-bottom: none; }
    .sighting-item-header { display: flex; align-items: baseline; gap: 12px; margin-bottom: 8px; }
    .sighting-date { font-size: 13px; font-weight: 500; color: var(--ink); }
    .sighting-location { font-size: 13px; color: var(--muted); }
    .sighting-notes { font-size: 13px; color: #4b5563; line-height: 1.55; margin-bottom: 12px; }
    .sighting-photos { display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 8px; }
    .sighting-photo-wrap { border-radius: 8px; overflow: hidden; border: 1px solid var(--border); }
    .sighting-photo-wrap img { width: 100%; aspect-ratio: 4/3; object-fit: cover; display: block; }
    .sighting-photo-caption { font-size: 11px; color: var(--muted); padding: 4px 7px; background: #f9f7f3; }

    /* EMPTY / ERROR */
    .empty-state { padding: 48px 24px; text-align: center; }
    .empty-state p { font-size: 14px; color: var(--muted); }
    .state-error { padding: 16px 20px; background: #fee2e2; color: #991b1b;
                   border: 1px solid #fca5a5; border-radius: 10px; font-size: 14px; }

    /* LOADING SKELETON */
    .skeleton-row { display: flex; gap: 16px; padding: 14px 16px; border-bottom: 1px solid #f0ede6; }
    .skeleton-block { background: linear-gradient(90deg,#ede9e1 25%,#e5e1d8 50%,#ede9e1 75%);
                      background-size: 200% 100%; animation: shimmer 1.2s infinite; border-radius: 4px; height: 14px; }
    @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

    @media (max-width: 600px) {
      nav { padding: 0 16px; }
      .page { padding-top: calc(var(--nav-h) + 20px); }
      .detail-fish-header { flex-direction: column; }
      td { padding: 10px 12px; }
    }
  </style>
</head>
<body>

<nav>
  <span class="nav-logo">Adventure <em>Sightings</em></span>
  <div class="nav-links">
    <a href="index.html" class="nav-link">Gallery</a>
    <a href="fishPage.php" class="nav-link">All Sightings</a>
    <a href="addSighting.html" class="nav-link nav-link-primary">+ Add sighting</a>
  </div>
</nav>

<div class="page">
  <div id="page-header" class="page-header">
    <div class="page-header-text">
      <h1>All Sightings</h1>
      <p>Every sighting recorded.</p>
    </div>
    <span class="fish-count-badge" id="fish-count" style="display:none"></span>
  </div>

  <div style="margin-bottom:18px">
    <div style="display:flex;align-items:center;gap:0;background:#fff;border:1px solid #d1d5db;border-radius:10px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,0.06);transition:box-shadow 0.15s,border-color 0.15s" id="fish-search-bar">
      <span style="padding:0 14px;color:#7a7166;font-size:16px;pointer-events:none">🔍</span>
      <input id="fish-search-input" type="text" placeholder="Search species…" autocomplete="off"
        style="flex:1;border:none;outline:none;font-family:inherit;font-size:14px;color:#1a1814;background:transparent;padding:11px 0">
      <button id="fish-search-clear" onclick="clearFishSearch()" style="padding:0 14px;background:none;border:none;cursor:pointer;color:#7a7166;font-size:18px;display:none">×</button>
    </div>
  </div>

  <div id="result-panel">
    <!-- Loading skeletons -->
    <div class="card">
      <div class="card-head">Species</div>
      <div class="table-wrap">
        ${[1,2,3,4,5].map(() => `
          <div class="skeleton-row">
            <div class="skeleton-block" style="width:22%;flex-shrink:0"></div>
            <div class="skeleton-block" style="width:28%;flex-shrink:0"></div>
            <div class="skeleton-block" style="width:14%;flex-shrink:0"></div>
            <div class="skeleton-block" style="width:10%;flex-shrink:0"></div>
            <div class="skeleton-block" style="width:10%;flex-shrink:0"></div>
          </div>
        `).join('')}
      </div>
    </div>
  </div>
</div>

<div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-label="Photo lightbox">
  <div class="lightbox-inner">
    <button class="lightbox-close" id="lightbox-close" aria-label="Close">×</button>
    <img class="lightbox-img" id="lightbox-img" src="" alt="">
    <div class="lightbox-caption" id="lightbox-caption"></div>
  </div>
</div>

<script>
const API = 'get_fish.php';

let allFishData = [];

function setupFishSearch() {
  const input = document.getElementById('fish-search-input');
  const clear = document.getElementById('fish-search-clear');
  if (!input) return;
  input.addEventListener('input', () => {
    const q = input.value.trim().toLowerCase();
    clear.style.display = q ? 'block' : 'none';
    filterFishTable(q);
  });
}

function filterFishTable(q) {
  const rows = document.querySelectorAll('#fish-tbody tr');
  let visible = 0;
  rows.forEach(row => {
    const text = row.textContent.toLowerCase();
    const show = !q || text.includes(q);
    row.style.display = show ? '' : 'none';
    if (show) visible++;
  });
}

function clearFishSearch() {
  document.getElementById('fish-search-input').value = '';
  document.getElementById('fish-search-clear').style.display = 'none';
  filterFishTable('');
}

// ── Load all fish ─────────────────────────────────────────────────────────────
async function loadAllFish() {
  // Reset header to list view
  document.getElementById('page-header').style.display = 'flex';
  document.getElementById('page-header').querySelector('h1').textContent = 'All sightings';
  document.getElementById('page-header').querySelector('p').textContent = "Every species you've recorded a sighting of.";

  try {
    const res  = await fetch(API);
    const fish = await res.json();
    if (!res.ok) { showError(fish.error || 'API error.'); return; }
    renderFishList(fish);
  } catch (err) {
    showError(err.message);
  }
}

function renderFishList(fish) {
  const countEl = document.getElementById('fish-count');
  if (fish.length) {
    countEl.textContent = `${fish.length} species`;
    countEl.style.display = 'inline-block';
  } else {
    countEl.style.display = 'none';
  }

  if (!fish.length) {
    document.getElementById('result-panel').innerHTML = `
      <div class="card">
        <div class="empty-state"><p>No fish entries found yet.</p></div>
      </div>`;
    return;
  }

  const rows = fish.map(f => `
    <tr onclick="selectFish(${f.fish_id})" title="View sightings">
      <td class="td-name">${escHtml(f.common_name)}</td>
      <td class="td-sci">${escHtml(f.scientific_name)}</td>
      <td class="td-num">${escHtml(f.habitat || '—')}</td>
      <td class="td-date">${new Date(f.date_added).toLocaleDateString('en-GB', {day:'numeric',month:'short',year:'numeric'})}</td>
      <td><span class="pill">${f.sighting_count} sighting${f.sighting_count !== 1 ? 's' : ''}</span></td>
      <td class="td-num">${f.photo_count} photo${f.photo_count !== 1 ? 's' : ''}</td>
    </tr>
  `).join('');

  document.getElementById('result-panel').innerHTML = `
    <div class="card">
      <div class="card-head">
        <span>Species</span>
      </div>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Common name</th>
              <th>Scientific name</th>
              <th>Habitat</th>
              <th>First added</th>
              <th>Sightings</th>
              <th>Photos</th>
            </tr>
          </thead>
          <tbody id="fish-tbody">${rows}</tbody>
        </table>
      </div>
    </div>`;

    setupFishSearch();
}

// ── Select a fish → show sightings ───────────────────────────────────────────
async function selectFish(fishId) {
  document.getElementById('result-panel').innerHTML = `
    <button class="btn-back" onclick="loadAllFish()">← Back to all sightings</button>
    <div class="card">
      <div class="card-head">Loading sightings…</div>
    </div>`;

  try {
    const res  = await fetch(`${API}?fish_id=${encodeURIComponent(fishId)}`);
    const rows = await res.json();
    if (!res.ok) { showError(rows.error || 'Failed to load fish.'); return; }
    renderFishDetail(rows);
  } catch (err) {
    showError(err.message);
  }
}

function renderFishDetail(rows) {
  if (!rows.length) { showError('No sightings found.'); return; }

  const f = rows[0];

  // Update page header now that we have the data
  document.getElementById('fish-count').style.display = 'none';
  document.getElementById('page-header').querySelector('h1').textContent = f.common_name;
  document.getElementById('page-header').querySelector('p').textContent = f.scientific_name;

  // Group rows by sighting
  const sightingMap = {};
  rows.forEach(row => {
    if (!sightingMap[row.sighting_id]) {
      sightingMap[row.sighting_id] = {
        sighting_id:   row.sighting_id,
        sighting_date: row.sighting_date,
        location:      row.location,
        notes:         row.notes,
        photos:        []
      };
    }
    if (row.photo_id) {
      sightingMap[row.sighting_id].photos.push({ filepath: row.filepath, caption: row.caption });
    }
  });

  const sightings = Object.values(sightingMap);
  const totalPhotos = sightings.reduce((n, s) => n + s.photos.length, 0);

  const sightingItems = sightings.map(s => {
    const photos = s.photos.map(p => `
    <div class="sighting-photo-wrap" onclick="openLightbox('${escHtml(p.filepath)}', '${escHtml(p.caption || '')}')" style="cursor:zoom-in">
        <img src="${escHtml(p.filepath)}" alt="${escHtml(p.caption || '')}">
        ${p.caption ? `<div class="sighting-photo-caption">${escHtml(p.caption)}</div>` : ''}
    </div>
    `).join('');

    return `
      <div class="sighting-item">
        <div class="sighting-item-header">
          <span class="sighting-date">${new Date(s.sighting_date).toLocaleDateString('en-GB', {day:'numeric',month:'long',year:'numeric'})}</span>
          ${s.location ? `<span class="sighting-location"> ${escHtml(s.location)}</span>` : ''}
        </div>
        ${s.notes ? `<div class="sighting-notes">${escHtml(s.notes)}</div>` : ''}
        ${photos ? `<div class="sighting-photos">${photos}</div>` : ''}
      </div>`;
  }).join('');

  document.getElementById('result-panel').innerHTML = `
    <button class="btn-back" onclick="loadAllFish()">← Back to all sightings</button>
    <div class="card">
      <div class="detail-fish-header">
        <img class="detail-fish-photo" id="detail-photo" src="" alt="">
        <div class="detail-fish-names">
          <div class="detail-fish-common">${escHtml(f.common_name)}</div>
          <div class="detail-fish-sci">${escHtml(f.scientific_name)}</div>
          <div class="detail-fish-meta">
            <span class="detail-meta-chip">${sightings.length} sighting${sightings.length !== 1 ? 's' : ''}</span>
            ${totalPhotos ? `<span class="detail-meta-chip">${totalPhotos} photo${totalPhotos !== 1 ? 's' : ''}</span>` : ''}
            ${f.habitat ? `<span class="detail-meta-chip">${escHtml(f.habitat)}</span>` : ''}
          </div>
          <div id="detail-desc" style="margin-top:10px;font-size:13px;color:#4b5563;line-height:1.6;font-style:italic;color:var(--muted)">Loading description…</div>
        </div>
      </div>
      <div class="card-head" style="border-radius:0">Sightings</div>
      <div class="sightings-list">${sightingItems}</div>
    </div>`;

  // Try to load Wikipedia thumbnail for the species
  loadSpeciesPhoto(f.scientific_name, f.common_name);
}

async function loadSpeciesPhoto(sciName, commonName) {
  const names = [sciName, commonName].filter(Boolean);
  for (const name of names) {
    try {
      const encoded = encodeURIComponent(name.replace(/ /g, '_'));
      const res  = await fetch(`https://en.wikipedia.org/api/rest_v1/page/summary/${encoded}`);
      if (!res.ok) continue;
      const data = await res.json();
      if (data.type === 'disambiguation') continue;
      // Photo
      if (data.thumbnail?.source) {
        const img = document.getElementById('detail-photo');
        if (img) { img.src = data.thumbnail.source; img.classList.add('loaded'); }
      }
      // Description
      const descEl = document.getElementById('detail-desc');
      if (descEl) {
        if (data.extract && data.extract.length > 40) {
          const link = data.content_urls?.desktop?.page
            ? ` <a href="${data.content_urls.desktop.page}" target="_blank" rel="noopener" style="color:var(--accent);text-decoration:none;font-size:12px;font-style:normal">Read more ↗</a>`
            : '';
          descEl.style.fontStyle = 'normal';
          descEl.style.color = '#4b5563';
          descEl.innerHTML = escHtml(data.extract) + link;
        } else {
          descEl.textContent = 'No description available.';
        }
      }
      break;
    } catch(e) {
      const descEl = document.getElementById('detail-desc');
      if (descEl) descEl.textContent = 'No description available.';
    }
  }
}

// ── Helpers ───────────────────────────────────────────────────────────────────
function showError(msg) {
  document.getElementById('result-panel').innerHTML =
    `<div class="state-error">${escHtml(msg)}</div>`;
}

function escHtml(str) {
  if (!str && str !== 0) return '';
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Lightbox ──────────────────────────────────────────────────────────────────
function openLightbox(src, caption) {
  document.getElementById('lightbox-img').src = src;
  document.getElementById('lightbox-caption').textContent = caption || '';
  document.getElementById('lightbox').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeLightbox() {
  document.getElementById('lightbox').classList.remove('open');
  document.body.style.overflow = '';
  document.getElementById('lightbox-img').src = '';
}

document.getElementById('lightbox-close').addEventListener('click', closeLightbox);
document.getElementById('lightbox').addEventListener('click', e => {
  if (e.target === document.getElementById('lightbox')) closeLightbox();
});
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });


loadAllFish();
</script>
</body>
</html>
