<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPRAKATA — Sistem Informasi Akademik Kampus</title>
<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Crect width='24' height='24' rx='6' fill='%23F59E0B'/%3E%3Cpath d='M12 4L2 9.5 12 15l9-4.5V17h2V9.5L12 4zM6 13.3v3.8L12 21l6-3.9v-3.8L12 16.5 6 13.3z' fill='white'/%3E%3C/svg%3E">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@500;600;700;800;900&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  *{ margin:0; padding:0; box-sizing:border-box; }
  html{ scroll-behavior:smooth; }
  body{
    font-family:'Inter',system-ui,sans-serif;
    background:#07050f;
    color:#ebe5f0;
    -webkit-font-smoothing:antialiased;
    overflow-x:hidden;
  }
  .container{ max-width:1100px; margin:0 auto; padding:0 24px; }

  /* ─── Particles ─── */
  #particles-canvas{ position:fixed; inset:0; z-index:0; pointer-events:none; }

  /* ─── Navbar ─── */
  nav{
    position:fixed; top:0; left:0; right:0; z-index:100;
    padding:14px 0;
    transition:background .35s,backdrop-filter .35s,box-shadow .35s;
  }
  nav.scrolled{ background:rgba(7,5,15,.75); backdrop-filter:blur(18px) saturate(1.3); box-shadow:0 1px 0 rgba(255,255,255,.05); }
  nav .nav-inner{ display:flex; align-items:center; justify-content:space-between; max-width:1100px; margin:0 auto; padding:0 24px; }
  .brand{ display:flex; align-items:center; gap:12px; font-weight:800; font-size:18px; color:#fff; text-decoration:none; font-family:'Plus Jakarta Sans',sans-serif; }
  .brand-mark{
    width:38px; height:38px; border-radius:11px;
    background:linear-gradient(135deg,#F59E0B,#FCD34D);
    display:flex; align-items:center; justify-content:center;
    box-shadow:0 4px 16px rgba(245,158,11,.35);
    flex-shrink:0;
  }
  .brand-mark svg{ width:22px; height:22px; }
  .nav-links{ display:flex; gap:28px; align-items:center; font-size:14px; font-weight:500; }
  .nav-links a{ color:rgba(255,255,255,.6); text-decoration:none; transition:color .25s; }
  .nav-links a:hover{ color:#fff; }
  .nav-links .btn-login{
    background:linear-gradient(135deg,#4338CA,#6366F1);
    color:#fff!important; padding:9px 22px; border-radius:10px;
    font-weight:600; font-size:13px; transition:transform .25s,box-shadow .25s;
    box-shadow:0 4px 16px rgba(67,56,202,.25);
  }
  .nav-links .btn-login:hover{ transform:translateY(-2px); box-shadow:0 8px 24px rgba(67,56,202,.35); }
  .nav-links .btn-github{
    display:inline-flex; align-items:center; gap:7px;
    color:rgba(255,255,255,.85)!important; padding:9px 18px; border-radius:10px;
    font-weight:600; font-size:13px; transition:transform .25s,box-shadow .25s,background .25s;
    background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.12);
  }
  .nav-links .btn-github:hover{ transform:translateY(-2px); background:rgba(255,255,255,.12); color:#fff!important; box-shadow:0 8px 24px rgba(0,0,0,.35); }
  .nav-toggle{ display:none; flex-direction:column; gap:5px; cursor:pointer; background:none; border:none; padding:4px; }
  .nav-toggle span{ width:24px; height:2px; background:rgba(255,255,255,.6); border-radius:2px; transition:.3s; }
  @media(max-width:720px){
    .nav-links{ position:fixed; top:0; right:-300px; width:280px; height:100vh; background:#0f0d1e; flex-direction:column; padding:80px 32px; gap:20px; border-left:1px solid rgba(255,255,255,.06); transition:right .35s cubic-bezier(.4,0,.2,1); align-items:flex-start; z-index:200; }
    .nav-links.open{ right:0; }
    .nav-toggle{ display:flex; position:relative; z-index:201; }
    .nav-toggle.open span:nth-child(1){ transform:rotate(45deg) translate(5px,5px); }
    .nav-toggle.open span:nth-child(2){ opacity:0; }
    .nav-toggle.open span:nth-child(3){ transform:rotate(-45deg) translate(5px,-5px); }
  }

  .section{ padding:80px 0; position:relative; z-index:1; }
  .section-label{
    display:inline-block;
    font-family:'JetBrains Mono',monospace; font-size:11px; font-weight:500;
    color:#6366F1; letter-spacing:2px; text-transform:uppercase;
    margin-bottom:10px;
  }
  .section-label::before{ content:'// '; color:#F59E0B; }
  .section-title{
    font-family:'Plus Jakarta Sans',sans-serif;
    font-size:clamp(26px,3.2vw,38px); font-weight:800;
    letter-spacing:-.02em; line-height:1.15;
    margin-bottom:10px;
  }
  .section-desc{ color:rgba(235,229,240,.5); font-size:15px; max-width:540px; line-height:1.7; }

  /* ─── Hero ─── */
  .hero{ min-height:100vh; display:flex; align-items:center; position:relative; z-index:1; padding-top:80px; }
  .hero-grid{ display:grid; grid-template-columns:1fr 1fr; gap:40px; align-items:center; }
  @media(max-width:860px){ .hero-grid{ grid-template-columns:1fr; text-align:center; } }
  .hero h1{
    font-family:'Plus Jakarta Sans',sans-serif;
    font-size:clamp(38px,5.5vw,60px); font-weight:900;
    letter-spacing:-.03em; line-height:1.05;
  }
  .hero h1 .gradient{ background:linear-gradient(135deg,#818CF8 0%,#A78BFA 40%,#FCD34D 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
  .hero h1 .accent-text{ color:#FCD34D; }
  .hero p{ font-size:16.5px; color:rgba(235,229,240,.5); margin:20px 0 32px; max-width:460px; line-height:1.8; }
  @media(max-width:860px){ .hero p{ margin-left:auto; margin-right:auto; } }
  .hero-badges{ display:flex; gap:10px; flex-wrap:wrap; margin-bottom:28px; }
  @media(max-width:860px){ .hero-badges{ justify-content:center; } }
  .badge{
    display:inline-flex; align-items:center; gap:6px;
    padding:6px 14px; border-radius:8px; font-size:12px; font-weight:600;
    background:rgba(99,102,241,.1); color:#818CF8; border:1px solid rgba(99,102,241,.15);
  }
  .badge-green{ background:rgba(34,197,94,.1); color:#4ade80; border-color:rgba(34,197,94,.15); }
  .badge-gold{ background:rgba(245,158,11,.1); color:#FCD34D; border-color:rgba(245,158,11,.15); }
  .hero-btns{ display:flex; gap:12px; flex-wrap:wrap; }
  @media(max-width:860px){ .hero-btns{ justify-content:center; } }

  .btn{
    display:inline-flex; align-items:center; gap:10px;
    padding:16px 30px; border-radius:12px; font-weight:600; font-size:14.5px;
    text-decoration:none; transition:all .3s; border:none; cursor:pointer;
  }
  .btn-primary{
    background:linear-gradient(135deg,#4338CA,#6366F1);
    color:#fff; box-shadow:0 4px 24px rgba(67,56,202,.35);
  }
  .btn-primary:hover{ transform:translateY(-3px); box-shadow:0 10px 32px rgba(67,56,202,.45); }
  .btn-secondary{
    background:rgba(255,255,255,.05); color:#fff;
    border:1px solid rgba(255,255,255,.1); backdrop-filter:blur(4px);
  }
  .btn-secondary:hover{ background:rgba(255,255,255,.09); transform:translateY(-3px); }
  .btn-gold{
    background:linear-gradient(135deg,#D97706,#F59E0B);
    color:#fff; box-shadow:0 4px 24px rgba(245,158,11,.3);
  }
  .btn-gold:hover{ transform:translateY(-3px); box-shadow:0 10px 32px rgba(245,158,11,.4); }

  .hero-visual{ position:relative; display:flex; justify-content:center; align-items:center; }
  .hero-glow{
    width:400px; height:400px; border-radius:50%;
    background:radial-gradient(circle,rgba(67,56,202,.15),transparent 70%);
    position:absolute; animation:pulseSlow 6s ease-in-out infinite;
  }
  @keyframes pulseSlow{ 0%,100%{ transform:scale(1); opacity:.6; } 50%{ transform:scale(1.2); opacity:1; } }
  .hero-box{
    background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.06);
    border-radius:24px; padding:32px; backdrop-filter:blur(8px);
    width:280px; position:relative;
  }
  .hero-box .row{ display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid rgba(255,255,255,.04); }
  .hero-box .row:last-child{ border-bottom:none; }
  .hero-box .dot{ width:8px; height:8px; border-radius:50%; flex-shrink:0; }
  .hero-box .dot-green{ background:#22c55e; box-shadow:0 0 12px rgba(34,197,94,.4); }
  .hero-box .dot-blue{ background:#6366F1; box-shadow:0 0 12px rgba(99,102,241,.4); }
  .hero-box .dot-gold{ background:#F59E0B; box-shadow:0 0 12px rgba(245,158,11,.4); }
  .hero-box .r-label{ font-size:13px; font-weight:500; flex:1; }
  .hero-box .r-val{ font-size:12px; color:rgba(235,229,240,.45); font-family:'JetBrains Mono',monospace; }
  .hero-floating{
    position:absolute; width:52px; height:52px; border-radius:16px;
    background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.06);
    backdrop-filter:blur(4px); display:flex; align-items:center; justify-content:center;
    font-size:22px; animation:float 8s ease-in-out infinite;
    box-shadow:0 8px 32px rgba(0,0,0,.2);
  }
  .hero-floating:nth-child(2){ top:-20px; right:-30px; animation-delay:.5s; }
  .hero-floating:nth-child(3){ bottom:-10px; left:-40px; animation-delay:2s; }
  .hero-floating:nth-child(4){ bottom:40px; right:-20px; animation-delay:4s; }
  @keyframes float{ 0%,100%{ transform:translateY(0); } 50%{ transform:translateY(-16px); } }

  /* ─── Stats ─── */
  .stats{ display:grid; grid-template-columns:repeat(4,1fr); gap:1px; background:rgba(255,255,255,.06); border-radius:20px; overflow:hidden; border:1px solid rgba(255,255,255,.06); }
  @media(max-width:640px){ .stats{ grid-template-columns:repeat(2,1fr); } }
  .stat{ background:#0b0918; padding:36px 16px; text-align:center; }
  .stat .num{ font-family:'Plus Jakarta Sans',sans-serif; font-size:clamp(28px,3vw,38px); font-weight:800; background:linear-gradient(135deg,#818CF8 20%,#FCD34D 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
  .stat .lbl{ font-size:12.5px; color:rgba(235,229,240,.4); margin-top:5px; font-weight:500; letter-spacing:.3px; }

  /* ─── Screen Preview Cards ─── */
  .preview-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:20px; margin-bottom:60px; }
  @media(max-width:860px){ .preview-grid{ grid-template-columns:1fr; } }
  .preview-card{
    background:rgba(255,255,255,.02); border:1px solid rgba(255,255,255,.06);
    border-radius:20px; padding:32px 24px;
    transition:all .4s cubic-bezier(.4,0,.2,1);
    position:relative; overflow:hidden;
  }
  .preview-card::before{
    content:''; position:absolute; top:0; left:0; right:0; height:2px;
    background:linear-gradient(90deg,transparent,#4338CA,transparent);
    opacity:0; transition:opacity .4s;
  }
  .preview-card:hover{ transform:translateY(-8px); background:rgba(255,255,255,.04); border-color:rgba(99,102,241,.2); }
  .preview-card:hover::before{ opacity:1; }
  .preview-card .p-icon{ font-size:28px; margin-bottom:18px; display:block; }
  .preview-card h3{ font-size:16px; font-weight:700; margin-bottom:8px; }
  .preview-card p{ font-size:13px; color:rgba(235,229,240,.45); line-height:1.7; }
  .preview-card .p-tag{
    display:inline-block; margin-top:16px;
    font-size:11px; font-weight:600; font-family:'JetBrains Mono',monospace;
    padding:4px 12px; border-radius:6px;
    color:#818CF8; background:rgba(99,102,241,.08);
  }

  /* ─── Features Grid ─── */
  .features-grid{ display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:8px; }
  .feat{
    background:rgba(255,255,255,.02); border:1px solid rgba(255,255,255,.06);
    border-radius:12px; padding:16px 20px;
    display:flex; align-items:center; gap:14px;
    transition:all .3s; cursor:default;
  }
  .feat:hover{ background:rgba(99,102,241,.06); border-color:rgba(99,102,241,.15); transform:translateY(-2px); }
  .feat .f-icon{ font-size:18px; flex-shrink:0; width:28px; text-align:center; }
  .feat .f-txt{ font-size:13.5px; font-weight:600; }
  .feat .f-sub{ font-size:12px; color:rgba(235,229,240,.35); margin-top:2px; }

  /* ─── How It Works ─── */
  .how-grid{ display:grid; grid-template-columns:repeat(4,1fr); gap:20px; position:relative; }
  @media(max-width:860px){ .how-grid{ grid-template-columns:repeat(2,1fr); } }
  @media(max-width:480px){ .how-grid{ grid-template-columns:1fr; } }
  .how-grid::before{
    content:''; position:absolute; top:32px; left:12%; right:12%; height:1px;
    background:linear-gradient(90deg,transparent,rgba(99,102,241,.2),transparent);
  }
  @media(max-width:860px){ .how-grid::before{ display:none; } }
  .how-step{ text-align:center; position:relative; }
  .how-step .step-num{
    width:64px; height:64px; border-radius:50%;
    background:linear-gradient(135deg,rgba(67,56,202,.2),rgba(99,102,241,.1));
    border:1px solid rgba(99,102,241,.15);
    display:flex; align-items:center; justify-content:center;
    margin:0 auto 16px; font-size:22px; font-weight:800;
    font-family:'Plus Jakarta Sans',sans-serif;
    color:#818CF8; transition:.3s;
  }
  .how-step:hover .step-num{ background:linear-gradient(135deg,rgba(67,56,202,.3),rgba(99,102,241,.2)); border-color:rgba(99,102,241,.3); transform:scale(1.06); }
  .how-step h4{ font-size:15px; font-weight:700; margin-bottom:6px; }
  .how-step p{ font-size:12.5px; color:rgba(235,229,240,.4); line-height:1.6; max-width:180px; margin:0 auto; }

  /* ─── Technology Stack ─── */
  .tech-wrap{ display:flex; gap:10px; flex-wrap:wrap; justify-content:center; }
  .tech-badge{
    display:inline-flex; align-items:center; gap:8px;
    padding:10px 20px; border-radius:10px;
    background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.06);
    font-size:13px; font-weight:500; transition:.25s;
  }
  .tech-badge:hover{ background:rgba(99,102,241,.06); border-color:rgba(99,102,241,.15); transform:translateY(-2px); }
  .tech-badge .t-icon{ font-size:18px; }

  /* ─── Roles ─── */
  .roles{ display:grid; grid-template-columns:repeat(4,1fr); gap:20px; }
  @media(max-width:720px){ .roles{ grid-template-columns:repeat(2,1fr); } }
  .role{
    background:rgba(255,255,255,.02); border:1px solid rgba(255,255,255,.06);
    border-radius:16px; padding:28px 20px; text-align:center;
    transition:all .35s;
  }
  .role:hover{ background:rgba(99,102,241,.05); border-color:rgba(99,102,241,.12); transform:translateY(-4px); }
  .role .r-icon{ font-size:36px; margin-bottom:12px; }
  .role h4{ font-size:15px; font-weight:700; margin-bottom:4px; }
  .role p{ font-size:12.5px; color:rgba(235,229,240,.4); }

  /* ─── CTA ─── */
  .cta{
    background:linear-gradient(160deg,rgba(67,56,202,.15),rgba(245,158,11,.04));
    border:1px solid rgba(67,56,202,.12);
    border-radius:24px; padding:60px 40px; text-align:center;
  }
  .cta h2{ font-family:'Plus Jakarta Sans',sans-serif; font-size:clamp(24px,3vw,32px); font-weight:800; margin-bottom:12px; }
  .cta p{ color:rgba(235,229,240,.5); margin-bottom:28px; max-width:480px; margin-left:auto; margin-right:auto; }
  .cta-btns{ display:flex; gap:12px; justify-content:center; flex-wrap:wrap; }
  .cta .note{ margin-top:18px; font-size:12px; color:rgba(235,229,240,.25); font-family:'JetBrains Mono',monospace; }

  footer{ text-align:center; padding:36px 0; border-top:1px solid rgba(255,255,255,.05); color:rgba(235,229,240,.25); font-size:13px; position:relative; z-index:1; }
  footer a{ color:rgba(235,229,240,.45); text-decoration:none; transition:color .2s; }
  footer a:hover{ color:#ebe5f0; }

  .reveal{ opacity:0; transform:translateY(24px); transition:opacity .6s ease,transform .6s cubic-bezier(.4,0,.2,1); }
  .reveal.show{ opacity:1; transform:translateY(0); }

  .mobile-overlay{ position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:150; opacity:0; pointer-events:none; transition:opacity .35s; }
  .mobile-overlay.show{ opacity:1; pointer-events:all; }

  /* ─── Dokumentasi ─── */
  .doc-tabs{ display:flex; gap:8px; flex-wrap:wrap; justify-content:center; margin-bottom:44px; }  .doc-tab{
    display:inline-flex; align-items:center; gap:8px;
    padding:12px 24px; border-radius:12px; font-size:15px; font-weight:600;
    background:rgba(255,255,255,.03); border:1px solid rgba(255,255,255,.08); color:rgba(255,255,255,.55);
    cursor:pointer; transition:all .3s; font-family:'Inter',sans-serif;
  }
  .doc-tab:hover{ background:rgba(99,102,241,.08); color:#fff; }
  .doc-tab.active{ background:rgba(99,102,241,.15); border-color:rgba(99,102,241,.4); color:#818CF8; }
  .doc-pane{ display:none; }
  .doc-pane.active{ display:block; animation:fadeUp .45s ease; }
  @keyframes fadeUp{ from{ opacity:0; transform:translateY(14px); } to{ opacity:1; transform:translateY(0); } }

  .doc-sub{ display:flex; align-items:center; gap:12px; margin:32px 0 16px; }
  .doc-sub:first-child{ margin-top:0; }
  .doc-sub .sub-num{
    font-family:'JetBrains Mono',monospace; font-size:11px; font-weight:700;
    width:30px; height:30px; border-radius:9px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    background:rgba(99,102,241,.15); color:#818CF8; border:1px solid rgba(99,102,241,.3);
  }
  .doc-sub h3{ font-size:15px; font-weight:700; }
  .doc-sub .sub-note{ font-size:12px; color:rgba(235,229,240,.4); margin-left:auto; }
  .doc-sub .sub-note code{ font-family:'JetBrains Mono',monospace; font-size:11px; color:#A5B4FC; background:rgba(99,102,241,.1); padding:2px 7px; border-radius:5px; }

  .diagram-grid{ display:grid; grid-template-columns:1fr 1fr; gap:24px; }
  @media(max-width:860px){ .diagram-grid{ grid-template-columns:1fr; } }
  .diagram-card{
    background:rgba(255,255,255,.02); border:1px solid rgba(255,255,255,.06);
    border-radius:20px; padding:20px; transition:all .4s; position:relative;
  }
  .diagram-card:hover{ background:rgba(255,255,255,.04); border-color:rgba(99,102,241,.25); transform:translateY(-4px); }
  .diagram-card .d-head{ display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:14px; }
  .diagram-card .d-title{ font-size:15px; font-weight:700; }
  .diagram-card .d-tag{ font-size:11px; font-weight:600; font-family:'JetBrains Mono',monospace; padding:4px 10px; border-radius:6px; color:#818CF8; background:rgba(99,102,241,.08); white-space:nowrap; }
  .diagram-card .d-desc{ font-size:12.5px; color:rgba(235,229,240,.45); line-height:1.7; margin-bottom:14px; }
  .diagram-card .d-img{
    width:100%; border-radius:12px; background:rgba(255,255,255,.03);
    border:1px solid rgba(255,255,255,.04); cursor:zoom-in;
  }
  .diagram-card .d-img.loading{ filter:blur(6px); opacity:.4; }

  .api-table-wrap{ overflow-x:auto; border:1px solid rgba(255,255,255,.06); border-radius:16px; }
  .api-table{ width:100%; border-collapse:collapse; min-width:640px; font-size:13px; }
  .api-table th{
    text-align:left; padding:14px 16px; font-size:11px; font-weight:600;
    text-transform:uppercase; letter-spacing:1px; color:rgba(235,229,240,.4);
    border-bottom:1px solid rgba(255,255,255,.06); background:rgba(255,255,255,.02);
  }
  .api-table td{ padding:12px 16px; border-bottom:1px solid rgba(255,255,255,.04); color:rgba(235,229,240,.7); vertical-align:top; }
  .api-table tr:last-child td{ border-bottom:none; }
  .api-table tr:hover td{ background:rgba(99,102,241,.04); }
  .api-table code{ font-family:'JetBrains Mono',monospace; font-size:12px; color:#A5B4FC; background:rgba(99,102,241,.1); padding:3px 8px; border-radius:6px; white-space:nowrap; }
  .m-badge{ display:inline-block; min-width:52px; text-align:center; padding:3px 10px; border-radius:6px; font-size:11px; font-weight:700; font-family:'JetBrains Mono',monospace; }
  .m-get{ background:rgba(34,197,94,.12); color:#4ade80; }
  .m-post{ background:rgba(99,102,241,.12); color:#818CF8; }
  .m-put{ background:rgba(245,158,11,.12); color:#FCD34D; }
  .m-del{ background:rgba(239,68,68,.12); color:#f87171; }
  .m-split{ display:flex; flex-direction:column; gap:4px; }

  .db-grid{ display:grid; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); gap:16px; }
  .db-card{
    background:rgba(255,255,255,.02); border:1px solid rgba(255,255,255,.06);
    border-radius:14px; padding:18px 20px; transition:all .3s;
  }
  .db-card:hover{ border-color:rgba(99,102,241,.2); background:rgba(99,102,241,.04); transform:translateY(-3px); }
  .db-card .db-name{ font-family:'JetBrains Mono',monospace; font-size:13.5px; font-weight:600; color:#A5B4FC; margin-bottom:8px; }
  .db-card .db-name::before{ content:'▸ '; color:#F59E0B; }
  .db-card .db-desc{ font-size:12.5px; color:rgba(235,229,240,.45); line-height:1.6; margin-bottom:10px; }
  .db-card .db-fields{ display:flex; flex-wrap:wrap; gap:5px; }
  .db-field{ font-size:10.5px; font-family:'JetBrains Mono',monospace; padding:3px 8px; border-radius:5px; background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.06); color:rgba(235,229,240,.55); }
  .db-field.pk{ color:#FCD34D; border-color:rgba(245,158,11,.25); }
  .db-field.fk{ color:#4ade80; border-color:rgba(34,197,94,.25); }

  .guide-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:24px; }
  @media(max-width:860px){ .guide-grid{ grid-template-columns:1fr; } }
  .guide-card{
    background:rgba(255,255,255,.02); border:1px solid rgba(255,255,255,.06);
    border-radius:20px; padding:28px 24px; transition:all .35s; position:relative; overflow:hidden;
  }
  .guide-card:hover{ border-color:rgba(99,102,241,.25); background:rgba(255,255,255,.04); transform:translateY(-4px); }
  .guide-card .g-role{ font-size:12px; font-weight:600; font-family:'JetBrains Mono',monospace; color:#818CF8; margin-bottom:10px; }
  .guide-card h4{ font-size:17px; font-weight:800; margin-bottom:14px; }
  .guide-card ol{ padding-left:20px; }
  .guide-card li{ font-size:13px; color:rgba(235,229,240,.5); line-height:1.7; margin-bottom:8px; }
  .guide-card li strong{ color:#ebe5f0; font-weight:600; }

  .test-grid{ display:grid; grid-template-columns:1fr 1fr; gap:24px; margin-bottom:40px; }
  @media(max-width:860px){ .test-grid{ grid-template-columns:1fr; } }
  .test-table-wrap{ overflow-x:auto; border:1px solid rgba(255,255,255,.06); border-radius:16px; }
  .test-table{ width:100%; border-collapse:collapse; min-width:720px; font-size:13px; }
  .test-table th{
    text-align:left; padding:13px 16px; font-size:11px; font-weight:600;
    text-transform:uppercase; letter-spacing:1px; color:rgba(235,229,240,.4);
    border-bottom:1px solid rgba(255,255,255,.06); background:rgba(255,255,255,.02);
  }
  .test-table td{ padding:11px 16px; border-bottom:1px solid rgba(255,255,255,.04); color:rgba(235,229,240,.7); vertical-align:top; line-height:1.55; }
  .test-table tr:last-child td{ border-bottom:none; }
  .test-table tr:hover td{ background:rgba(99,102,241,.04); }
  .test-table .mod-row td{ background:rgba(99,102,241,.07); color:#A5B4FC; font-size:12px; font-weight:700; letter-spacing:.3px; }
  .test-table .mod-row:hover td{ background:rgba(99,102,241,.09); }
  .test-table .t-id{ font-family:'JetBrains Mono',monospace; font-size:11.5px; color:#818CF8; font-weight:600; white-space:nowrap; }
  .test-table .t-status{ font-size:11px; font-weight:700; font-family:'JetBrains Mono',monospace; padding:3px 9px; border-radius:6px; white-space:nowrap; }
  .t-pass{ background:rgba(34,197,94,.12); color:#4ade80; }
  .test-col h3{ font-size:16px; font-weight:700; margin-bottom:14px; display:flex; align-items:center; gap:8px; }
  .test-col h3 .t-ic{ font-size:18px; }
  .test-col .sub{ font-size:12.5px; color:rgba(235,229,240,.4); margin-bottom:16px; line-height:1.6; }
  .test-case{
    background:rgba(255,255,255,.02); border:1px solid rgba(255,255,255,.06);
    border-radius:12px; padding:14px 16px; margin-bottom:12px;
  }
  .test-case .tc-id{ font-family:'JetBrains Mono',monospace; font-size:11px; font-weight:600; color:#818CF8; margin-bottom:6px; }
  .test-case .tc-name{ font-size:13.5px; font-weight:600; margin-bottom:4px; }
  .test-case .tc-step{ font-size:12px; color:rgba(235,229,240,.45); line-height:1.6; margin-bottom:8px; }
  .test-case .tc-expected{ font-size:12px; color:rgba(235,229,240,.45); line-height:1.6; }
  .test-case .tc-expected b{ color:#4ade80; font-weight:600; }
  .test-case .tc-expected i{ color:#ebe5f0; font-style:normal; font-weight:600; }

  .fuzzy-calc{
    background:rgba(255,255,255,.02); border:1px solid rgba(255,255,255,.06);
    border-radius:20px; padding:28px; margin-top:8px;
  }
  .fuzzy-calc h3{ font-size:17px; font-weight:800; margin-bottom:6px; }
  .fuzzy-calc .sub{ font-size:13px; color:rgba(235,229,240,.45); margin-bottom:20px; line-height:1.7; }
  .fuzzy-calc .fc-grid{ display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:20px; }
  @media(max-width:720px){ .fuzzy-calc .fc-grid{ grid-template-columns:1fr; } }
  .fc-card{ background:rgba(99,102,241,.06); border:1px solid rgba(99,102,241,.15); border-radius:12px; padding:16px; }
  .fc-card .fc-var{ font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:1px; color:#818CF8; margin-bottom:8px; }
  .fc-card .fc-input{ font-family:'JetBrains Mono',monospace; font-size:22px; font-weight:800; color:#FCD34D; }
  .fc-card .fc-membership{ font-size:11.5px; color:rgba(235,229,240,.5); margin-top:8px; line-height:1.7; font-family:'JetBrains Mono',monospace; }
  .fc-card .fc-membership b{ color:#4ade80; font-weight:600; }
  .fc-table-wrap{ overflow-x:auto; border:1px solid rgba(255,255,255,.06); border-radius:12px; margin-bottom:20px; }
  .fc-table{ width:100%; border-collapse:collapse; min-width:600px; font-size:12.5px; }
  .fc-table th{ text-align:left; padding:11px 14px; font-size:10.5px; font-weight:600; text-transform:uppercase; letter-spacing:1px; color:rgba(235,229,240,.4); border-bottom:1px solid rgba(255,255,255,.06); background:rgba(255,255,255,.02); }
  .fc-table td{ padding:10px 14px; border-bottom:1px solid rgba(255,255,255,.04); color:rgba(235,229,240,.7); }
  .fc-table tr:last-child td{ border-bottom:none; }
  .fc-table td.active{ color:#FCD34D; font-weight:700; }
  .fc-kehadiran{ font-size:12.5px; color:rgba(235,229,240,.5); line-height:1.8; }
  .fc-kehadiran b{ color:#A5B4FC; }
  .fc-result{
    display:flex; align-items:center; gap:16px; flex-wrap:wrap;
    background:rgba(34,197,94,.08); border:1px solid rgba(34,197,94,.2);
    border-radius:14px; padding:20px 24px;
  }
  .fc-result .fr-lbl{ font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:1px; color:#4ade80; }
  .fc-result .fr-score{ font-family:'Plus Jakarta Sans',sans-serif; font-size:34px; font-weight:900; color:#fff; }
  .fc-result .fr-status{ font-family:'JetBrains Mono',monospace; font-size:13px; font-weight:700; padding:6px 14px; border-radius:8px; background:rgba(34,197,94,.15); color:#4ade80; }
  .fc-result .fr-note{ width:100%; font-size:12px; color:rgba(235,229,240,.5); line-height:1.6; }

  /* ─── Setup & Arsitektur ─── */
  .stack-table-wrap{ overflow-x:auto; border:1px solid rgba(255,255,255,.06); border-radius:16px; }
  .stack-table{ width:100%; border-collapse:collapse; min-width:520px; font-size:13px; }
  .stack-table th{ text-align:left; padding:13px 16px; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:1px; color:rgba(235,229,240,.4); border-bottom:1px solid rgba(255,255,255,.06); background:rgba(255,255,255,.02); }
  .stack-table td{ padding:12px 16px; border-bottom:1px solid rgba(255,255,255,.04); color:rgba(235,229,240,.7); }
  .stack-table tr:last-child td{ border-bottom:none; }
  .stack-table tr:hover td{ background:rgba(99,102,241,.04); }
  .stack-table td code{ font-family:'JetBrains Mono',monospace; font-size:12px; color:#A5B4FC; background:rgba(99,102,241,.1); padding:3px 8px; border-radius:6px; white-space:nowrap; }
  .stack-table td:first-child{ color:#ebe5f0; font-weight:600; white-space:nowrap; }
  .dir-tree{
    background:#0b0918; border:1px solid rgba(255,255,255,.06); border-radius:16px;
    padding:24px 26px; font-family:'JetBrains Mono',monospace; font-size:12.5px; line-height:1.8;
    overflow-x:auto; color:rgba(235,229,240,.65);
  }
  .dir-tree .d-name{ color:#A5B4FC; font-weight:600; }
  .dir-tree .d-com{ color:rgba(235,229,240,.3); }
  .dir-tree .d-dir{ color:#FCD34D; font-weight:600; }
  .cmd-block{ margin-bottom:14px; }
  .cmd-block .cmd-title{ font-size:12px; font-weight:700; color:#ebe5f0; margin-bottom:8px; display:flex; align-items:center; gap:8px; }
  .cmd-block .cmd-title .c-ic{ font-size:14px; }
  .cmd-block pre{
    background:#0b0918; border:1px solid rgba(255,255,255,.06); border-radius:12px;
    padding:16px 20px; font-family:'JetBrains Mono',monospace; font-size:12.5px;
    overflow-x:auto; color:#4ade80; line-height:1.8;
  }
  .cmd-block pre .c-cmt{ color:rgba(235,229,240,.3); }

  .role a{ color:#818CF8; text-decoration:none; font-weight:600; }
  .role a:hover{ text-decoration:underline; }

  /* ─── FAQ ─── */
  .faq{ max-width:720px; margin:0 auto; }
  .faq details{
    background:rgba(255,255,255,.02); border:1px solid rgba(255,255,255,.06);
    border-radius:14px; margin-bottom:12px; overflow:hidden; transition:border-color .3s,background .3s;
  }
  .faq details[open]{ border-color:rgba(99,102,241,.35); background:rgba(99,102,241,.03); }
  .faq summary{
    cursor:pointer; list-style:none; padding:18px 22px; font-size:14.5px; font-weight:600;
    display:flex; align-items:center; justify-content:space-between; gap:14px; color:#ebe5f0;
  }
  .faq summary::-webkit-details-marker{ display:none; }
  .faq summary .chev{ color:rgba(235,229,240,.35); font-size:12px; font-family:'JetBrains Mono',monospace; transition:transform .3s; flex-shrink:0; }
  .faq details[open] summary .chev{ transform:rotate(90deg); color:#818CF8; }
  .faq .faq-a{ padding:0 22px 20px; font-size:13.5px; color:rgba(235,229,240,.55); line-height:1.75; }
  .faq .faq-a code{ font-family:'JetBrains Mono',monospace; font-size:12px; color:#A5B4FC; background:rgba(99,102,241,.1); padding:2px 7px; border-radius:5px; }

  /* ─── Lisensi ─── */
  .license-box{
    max-width:760px; margin:0 auto;
    background:rgba(255,255,255,.02); border:1px solid rgba(255,255,255,.06);
    border-radius:20px; padding:36px;
  }
  .license-box .lic-head{ display:flex; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:18px; }
  .license-box .lic-badge{
    font-family:'JetBrains Mono',monospace; font-size:14px; font-weight:800;
    padding:8px 18px; border-radius:10px;
    background:rgba(34,197,94,.12); color:#4ade80; border:1px solid rgba(34,197,94,.25);
  }
  .license-box .lic-name{ font-family:'Plus Jakarta Sans',sans-serif; font-size:18px; font-weight:800; }
  .license-box p{ font-size:13.5px; color:rgba(235,229,240,.55); line-height:1.75; margin-bottom:12px; }
  .license-box ul{ padding-left:20px; margin-bottom:12px; }
  .license-box li{ font-size:13px; color:rgba(235,229,240,.5); line-height:1.75; margin-bottom:6px; }
  .license-box a{ color:#818CF8; text-decoration:none; }
  .license-box a:hover{ text-decoration:underline; }
</style>
</head>
<body>

<canvas id="particles-canvas"></canvas>
<div class="mobile-overlay" id="mobile-overlay"></div>

<nav id="navbar">
  <div class="nav-inner">
    <a href="/" class="brand"><span class="brand-mark"><svg viewBox="0 0 24 24" fill="white"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg></span> SIPRAKATA</a>
    <button class="nav-toggle" id="nav-toggle" aria-label="Menu">
      <span></span><span></span><span></span>
    </button>
    <div class="nav-links" id="nav-links">
      <a href="#tentang" onclick="closeNav()">Tentang</a>
      <a href="#fitur" onclick="closeNav()">Fitur</a>
      <a href="#tech" onclick="closeNav()">Teknologi</a>
      <a href="#dokumentasi" onclick="closeNav()">Dokumentasi</a>
      <a href="#faq" onclick="closeNav()">FAQ</a>
      <a href="#kontak" onclick="closeNav()">Kontak</a>
      <a href="#download" onclick="closeNav()">Download</a>
      <a href="https://github.com/YudhaMaulana18/Siprakata" target="_blank" rel="noopener" class="btn-github">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 .5C5.73.5.5 5.73.5 12a11.5 11.5 0 007.86 10.91c.58.1.79-.25.79-.56v-2.17c-3.2.7-3.87-1.37-3.87-1.37-.53-1.33-1.28-1.69-1.28-1.69-1.05-.71.08-.7.08-.7 1.16.08 1.77 1.19 1.77 1.19 1.03 1.77 2.7 1.26 3.36.96.1-.75.4-1.26.72-1.55-2.57-.29-5.27-1.28-5.27-5.7 0-1.26.45-2.29 1.19-3.1-.12-.29-.52-1.46.11-3.05 0 0 .97-.31 3.17 1.18a11 11 0 015.77 0c2.2-1.49 3.17-1.18 3.17-1.18.63 1.59.23 2.76.11 3.05.74.81 1.19 1.84 1.19 3.1 0 4.43-2.71 5.41-5.29 5.69.41.36.78 1.06.78 2.14v3.17c0 .31.21.67.8.56A11.5 11.5 0 0023.5 12C23.5 5.73 18.27.5 12 .5z"/></svg>
        GitHub
      </a>
      <a href="/flutter" class="btn-login">Login</a>
    </div>
  </div>
</nav>

<section class="hero">
  <div class="container">
    <div class="hero-grid">
      <div>
        <div class="hero-badges reveal">
          <span class="badge"><span>🎓</span> Kampus Digital</span>
          <span class="badge badge-green"><span>✅</span> Fuzzy Logic</span>
          <span class="badge badge-gold"><span>📱</span> Mobile + Web</span>
        </div>
        <h1 class="reveal" style="transition-delay:.1s">
          Akademik Mahasiswa<br>
          <span class="gradient">Dalam Genggaman</span>
        </h1>
        <p class="reveal" style="transition-delay:.2s">
          Aplikasi mobile untuk mahasiswa — KRS online, jadwal, presensi, nilai, materi, pengumuman, dan evaluasi kelayakan Fuzzy Mamdani. Semua real-time dari HP.
        </p>
        <div class="hero-btns reveal" style="transition-delay:.3s">
          <a href="/siprakata.apk" class="btn btn-primary" download>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
            Download APK
          </a>
          <a href="/flutter" class="btn btn-secondary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
            Demo Web App
          </a>
        </div>
      </div>
      <div class="hero-visual reveal" style="transition-delay:.25s">
        <div class="hero-glow"></div>
        <div class="hero-box">
          <div class="row">
            <span class="dot dot-green"></span>
            <span class="r-label">KRS Aktif</span>
            <span class="r-val">1.432 mahasiswa</span>
          </div>
          <div class="row">
            <span class="dot dot-blue"></span>
            <span class="r-label">Presensi Hari Ini</span>
            <span class="r-val">87% hadir</span>
          </div>
          <div class="row">
            <span class="dot dot-gold"></span>
            <span class="r-label">Evaluasi Kelayakan</span>
            <span class="r-val">Fuzzy Mamdani</span>
          </div>
          <div class="row">
            <span class="dot dot-green"></span>
            <span class="r-label">Server Status</span>
            <span class="r-val">Online</span>
          </div>
        </div>
        <div class="hero-floating" style="top:-20px;right:-30px;animation-delay:.5s">📋</div>
        <div class="hero-floating" style="bottom:-10px;left:-40px;animation-delay:2s">🎯</div>
        <div class="hero-floating" style="bottom:40px;right:-20px;animation-delay:4s">📊</div>
      </div>
    </div>
  </div>
</section>

<div class="container" style="position:relative;z-index:1">
  <div class="stats reveal">
    <div class="stat"><div class="num" data-to="50" data-suffix="+">0</div><div class="lbl">Endpoint API</div></div>
    <div class="stat"><div class="num" data-to="3">0</div><div class="lbl">Level User</div></div>
    <div class="stat"><div class="num" data-to="27">0</div><div class="lbl">Fuzzy Rules</div></div>
    <div class="stat"><div class="num" data-to="2">0</div><div class="lbl">Platform Terintegrasi</div></div>
  </div>
</div>

<section class="section" id="tentang">
  <div class="container">
    <div class="reveal" style="text-align:center;margin-bottom:48px">
      <span class="section-label">Tentang</span>
      <h2 class="section-title">Akses Akademik dalam Genggaman</h2>
      <p class="section-desc" style="margin:0 auto">Aplikasi mobile khusus mahasiswa — semua fitur akademik tanpa harus ke kampus. Web untuk admin & dosen.</p>
    </div>
    <div class="preview-grid">
      <div class="preview-card reveal">
        <span class="p-icon">📱</span>
        <h3>Aplikasi Mahasiswa</h3>
        <p>Flutter 3 — dashboard real-time, KRS online, jadwal kuliah, presensi, nilai, materi, pengumuman, dan evaluasi kelayakan berbasis Fuzzy Logic Mamdani.</p>
        <span class="p-tag">Flutter 3</span>
      </div>
      <div class="preview-card reveal" style="transition-delay:.15s">
        <span class="p-icon">🌐</span>
        <h3>Panel Web Admin & Dosen</h3>
        <p>Laravel 13 dengan RBAC — Admin kelola master data, RBAC, log aktivitas. Dosen kelola presensi, nilai, validasi KRS, materi, dan pengumuman.</p>
        <span class="p-tag">Laravel 13</span>
      </div>
      <div class="preview-card reveal" style="transition-delay:.3s">
        <span class="p-icon">🧠</span>
        <h3>Fuzzy Logic Mamdani</h3>
        <p>27 rules inferensi — 3 variabel input: kehadiran, nilai tugas, dan keaktifan diskusi. Output skor prediksi kelulusan lengkap grafik membership.</p>
        <span class="p-tag">Inferensi Fuzzy</span>
      </div>
    </div>

    <div class="roles reveal" style="margin-top:40px;grid-template-columns:repeat(3,1fr)">
      <div class="role"><span class="r-icon">🛡️</span><h4>Admin (Web)</h4><p>Master data, RBAC, log aktivitas, pengaturan sistem</p></div>
      <div class="role"><span class="r-icon">👨‍🏫</span><h4>Dosen (Web)</h4><p>Presensi, nilai, validasi KRS, materi kuliah, pengumuman</p></div>
      <div class="role"><span class="r-icon">🎓</span><h4>Mahasiswa (Mobile)</h4><p>Dashboard, KRS, jadwal, nilai, presensi, materi, fuzzy eval</p></div>
    </div>
    <div class="preview-grid reveal" style="margin-top:40px">
      <div class="preview-card">
        <span class="p-icon">📜</span>
        <h3>Latar Belakang</h3>
        <p>Layanan akademik sering berjalan manual dan terpisah-pisah: KRS antre di kampus, presensi kertas, nilai menunggu pengumuman. SIPRAKATA menyatukan semuanya dalam satu platform digital yang bisa diakses kapan saja.</p>
      </div>
      <div class="preview-card">
        <span class="p-icon">🎯</span>
        <h3>Tujuan</h3>
        <p>Menghadirkan layanan akademik online yang cepat, terintegrasi, dan mudah digunakan — dari pengajuan KRS, presensi, nilai, materi, hingga evaluasi kelayakan semester berbasis Fuzzy Logic Mamdani.</p>
      </div>
      <div class="preview-card">
        <span class="p-icon">👥</span>
        <h3>Target Pengguna</h3>
        <p>Mahasiswa memakai aplikasi mobile; Dosen memakai panel web untuk presensi, nilai, validasi KRS, materi & pengumuman; Admin mengelola master data, RBAC, dan log aktivitas.</p>
      </div>
      <div class="preview-card">
        <span class="p-icon">✅</span>
        <h3>Manfaat Utama</h3>
        <p>Mengurangi antrean dan pekerjaan manual, data akademik terpusat dalam satu sumber, keputusan kelayakan lebih objektif lewat fuzzy logic, serta monitoring real-time bagi dosen dan admin.</p>
      </div>
    </div>
  </div>
</section>

<section class="section" id="fitur">
  <div class="container">
    <div class="reveal" style="text-align:center;margin-bottom:44px">
      <span class="section-label">Fitur</span>
      <h2 class="section-title">Lengkap untuk Kampus</h2>
      <p class="section-desc" style="margin:0 auto">Semua fitur yang dibutuhkan untuk mengelola akademik — dari hulu ke hilir — dalam satu sistem terintegrasi.</p>
    </div>
    <div class="features-grid">
      <div class="feat reveal"><span class="f-icon">📊</span><div><div class="f-txt">Dashboard Akademik</div><div class="f-sub">IPO, SKS tempuh, grafik, rekap presensi</div></div></div>
      <div class="feat reveal" style="transition-delay:.05s"><span class="f-icon">📝</span><div><div class="f-txt">KRS Online</div><div class="f-sub">Ajukan KRS + status approval (pending/disetujui/ditolak)</div></div></div>
      <div class="feat reveal" style="transition-delay:.1s"><span class="f-icon">📅</span><div><div class="f-txt">Jadwal Kuliah</div><div class="f-sub">Jadwal harian lengkap dengan dosen & ruangan</div></div></div>
      <div class="feat reveal" style="transition-delay:.15s"><span class="f-icon">✅</span><div><div class="f-txt">Presensi Digital</div><div class="f-sub">Hadir, Izin, Sakit, Alpha — per pertemuan</div></div></div>
      <div class="feat reveal" style="transition-delay:.2s"><span class="f-icon">🎯</span><div><div class="f-txt">Nilai & Grade</div><div class="f-sub">Tugas 30% + UTS 30% + UAS 40% + grade A-E</div></div></div>
      <div class="feat reveal" style="transition-delay:.25s"><span class="f-icon">📄</span><div><div class="f-txt">Materi Kuliah</div><div class="f-sub">Download file materi & link tiap pertemuan</div></div></div>
      <div class="feat reveal" style="transition-delay:.3s"><span class="f-icon">🧠</span><div><div class="f-txt">Fuzzy Mamdani</div><div class="f-sub">Prediksi kelulusan — 27 rules, 3 input variabel</div></div></div>
      <div class="feat reveal" style="transition-delay:.35s"><span class="f-icon">📢</span><div><div class="f-txt">Pengumuman</div><div class="f-sub">Info terkini dari dosen per kelas</div></div></div>
    </div>
  </div>
</section>

<section class="section" id="keunggulan">
  <div class="container">
    <div class="reveal" style="text-align:center;margin-bottom:44px">
      <span class="section-label">Keunggulan</span>
      <h2 class="section-title">Mengapa Memilih SIPRAKATA?</h2>
      <p class="section-desc" style="margin:0 auto">Empat pilar utama yang membedakan SIPRAKATA dari sistem akademik konvensional.</p>
    </div>
    <div class="reveal">
      <div class="stats" style="margin-bottom:44px">
        <div class="stat"><div class="num" data-to="19">0</div><div class="lbl">Tabel Database</div></div>
        <div class="stat"><div class="num" data-to="82">0</div><div class="lbl">Route API</div></div>
        <div class="stat"><div class="num" data-to="27">0</div><div class="lbl">Rule Fuzzy Mamdani</div></div>
        <div class="stat"><div class="num" data-to="16">0</div><div class="lbl">Kasus Uji Tervalidasi</div></div>
      </div>
    </div>
    <div class="roles">
      <div class="role reveal"><span class="r-icon">🚀</span><h4>Cepat &amp; Ringan</h4><p>Aplikasi Flutter release AOT + API JSON ringan — responsif bahkan di ponsel kelas menengah.</p></div>
      <div class="role reveal" style="transition-delay:.1s"><span class="r-icon">🛡️</span><h4>Keamanan Berlapis</h4><p>Autentikasi token, RBAC granular per modul, dan password ter-hash di setiap sesi.</p></div>
      <div class="role reveal" style="transition-delay:.2s"><span class="r-icon">🧠</span><h4>Keputusan Objektif</h4><p>Evaluasi kelayakan via Fuzzy Mamdani 27 rules — konsisten, transparan, dan bisa dilacak.</p></div>
      <div class="role reveal" style="transition-delay:.3s"><span class="r-icon">🔗</span><h4>Terintegrasi</h4><p>Satu database terpusat menghubungkan mahasiswa, dosen, dan admin dalam satu alur.</p></div>
    </div>
  </div>
</section>

<section class="section" id="tech">
  <div class="container">
    <div class="reveal" style="text-align:center;margin-bottom:44px">
      <span class="section-label">Stack</span>
      <h2 class="section-title">Teknologi yang Digunakan</h2>
      <p class="section-desc" style="margin:0 auto">Dibangun dengan teknologi modern yang stabil, cepat, dan teruji untuk skala kampus.</p>
    </div>
    <div class="tech-wrap reveal">
      <span class="tech-badge"><span class="t-icon">⚡</span> Laravel 13</span>
      <span class="tech-badge"><span class="t-icon">💙</span> Flutter 3</span>
      <span class="tech-badge"><span class="t-icon">🐘</span> PHP 8.3</span>
      <span class="tech-badge"><span class="t-icon">🗄️</span> MySQL</span>
      <span class="tech-badge"><span class="t-icon">🧠</span> Fuzzy Mamdani</span>
      <span class="tech-badge"><span class="t-icon">📡</span> REST API</span>
      <span class="tech-badge"><span class="t-icon">🎨</span> Material 3</span>
    </div>

    <div class="how-grid" style="margin-top:56px">
      <div class="how-step reveal">
        <div class="step-num">01</div>
        <h4>Login Sebagai Mahasiswa</h4>
        <p>Masuk dengan akun yang terdaftar — khusus mahasiswa</p>
      </div>
      <div class="how-step reveal" style="transition-delay:.1s">
        <div class="step-num">02</div>
        <h4>Jadwal & KRS</h4>
        <p>Lihat jadwal kuliah, ajukan KRS, pantau status approval</p>
      </div>
      <div class="how-step reveal" style="transition-delay:.2s">
        <div class="step-num">03</div>
        <h4>Evaluasi Fuzzy</h4>
        <p>Fuzzy Mamdani menentukan kelayakan semester mahasiswa otomatis</p>
      </div>
      <div class="how-step reveal" style="transition-delay:.3s">
        <div class="step-num">04</div>
        <h4>Monitoring & Evaluasi</h4>
        <p>Pantau KRS, presensi, nilai, dan hasil evaluasi kelayakan</p>
      </div>
    </div>
  </div>
</section>

<section class="section" id="modul">
  <div class="container">
    <div class="reveal" style="text-align:center;margin-bottom:44px">
      <span class="section-label">Modul</span>
      <h2 class="section-title">Modul Sistem</h2>
      <p class="section-desc" style="margin:0 auto">Sembilan modul yang saling terhubung — diatur lewat RBAC agar setiap peran hanya mengakses haknya.</p>
    </div>
    <div class="db-grid">
      <div class="db-card reveal"><div class="db-name">Autentikasi &amp; RBAC</div><div class="db-desc">Login multi-peran (Admin, Dosen, Mahasiswa) dengan permission granular per modul.</div><div class="db-fields"><span class="db-field">Token Bearer</span><span class="db-field">Role &amp; Permission</span></div></div>
      <div class="db-card reveal"><div class="db-name">Master Data</div><div class="db-desc">Kelola mahasiswa, dosen, matakuliah, program studi, ruangan, dan tahun ajaran.</div><div class="db-fields"><span class="db-field">CRUD Admin</span><span class="db-field">6 Entitas</span></div></div>
      <div class="db-card reveal"><div class="db-name">KRS Online</div><div class="db-desc">Ajukan KRS dari aplikasi, pantau status pending/disetujui/ditolak, dan validasi oleh dosen &amp; admin.</div><div class="db-fields"><span class="db-field">Transaksi</span><span class="db-field">Validasi</span></div></div>
      <div class="db-card reveal"><div class="db-name">Jadwal Kuliah</div><div class="db-desc">Jadwal harian lengkap dengan dosen dan ruangan per kelas.</div><div class="db-fields"><span class="db-field">Per Kelas</span><span class="db-field">Harian</span></div></div>
      <div class="db-card reveal"><div class="db-name">Presensi Digital</div><div class="db-desc">Pencatatan kehadiran Hadir, Izin, Sakit, Alpha per pertemuan.</div><div class="db-fields"><span class="db-field">HIS-A</span><span class="db-field">Rekap</span></div></div>
      <div class="db-card reveal"><div class="db-name">Nilai &amp; Grade</div><div class="db-desc">Bobot Tugas 30% + UTS 30% + UAS 40% dengan grade A&ndash;E.</div><div class="db-fields"><span class="db-field">30/30/40</span><span class="db-field">A&ndash;E</span></div></div>
      <div class="db-card reveal"><div class="db-name">Materi Kuliah</div><div class="db-desc">Distribusi file materi dan link tiap pertemuan untuk mahasiswa.</div><div class="db-fields"><span class="db-field">Download</span><span class="db-field">Per Pertemuan</span></div></div>
      <div class="db-card reveal"><div class="db-name">Pengumuman</div><div class="db-desc">Informasi terkini dari dosen per kelas, tersinkron ke aplikasi mobile.</div><div class="db-fields"><span class="db-field">Per Kelas</span></div></div>
      <div class="db-card reveal"><div class="db-name">Evaluasi Kelayakan</div><div class="db-desc">Fuzzy Logic Mamdani 27 rules dengan 3 variabel input dan output skor kelayakan.</div><div class="db-fields"><span class="db-field">Fuzzy</span><span class="db-field">Batch</span></div></div>
    </div>
  </div>
</section>

<section class="section" id="dokumentasi">
  <div class="container">
    <div class="reveal" style="text-align:center;margin-bottom:44px">
      <span class="section-label">Dokumentasi</span>
      <h2 class="section-title">Spesifikasi Lengkap Sistem</h2>
      <p class="section-desc" style="margin:0 auto">7 diagram, 19 tabel, 82 route API (14 endpoint inti mobile didokumentasikan), 16 kasus uji, dan panduan penggunaan untuk setiap peran.</p>
    </div>

    <div class="doc-tabs reveal">
      <button class="doc-tab" data-tab="diagram">🖼️ Diagram</button>
      <button class="doc-tab" data-tab="database">🗄️ Database</button>
      <button class="doc-tab" data-tab="api">⚡ API</button>
      <button class="doc-tab" data-tab="setup">🚀 Setup &amp; Arsitektur</button>
      <button class="doc-tab" data-tab="panduan">📖 Panduan</button>
      <button class="doc-tab" data-tab="pengujian">🧪 Pengujian</button>
    </div>

    <!-- ─── Diagram ─── -->
    <div class="doc-pane" id="pane-diagram">
      <div class="doc-sub">
        <span class="sub-num">A</span>
        <h3>Pemodelan Proses</h3>
        <span class="sub-note">Use case &amp; activity per peran</span>
      </div>
      <div class="diagram-grid">
        <div class="diagram-card reveal">
          <div class="d-head"><div class="d-title">Use Case Diagram</div><span class="d-tag">UML · UC1–UC30</span></div>
          <p class="d-desc">Interaksi 3 aktor (Admin, Dosen, Mahasiswa) dengan sistem, termasuk relasi include evaluasi Fuzzy Mamdani.</p>
          <img data-src="/docs/use_case_diagram.png" alt="Use Case Diagram" class="d-img" loading="lazy" onclick="openDiagram(this)">
        </div>
        <div class="diagram-card reveal" style="transition-delay:.1s">
          <div class="d-head"><div class="d-title">Activity — Mahasiswa</div><span class="d-tag">Mobile</span></div>
          <p class="d-desc">Alur aktivitas mahasiswa: login, KRS, lihat jadwal/materi/nilai/presensi/pengumuman, evaluasi kelayakan Fuzzy.</p>
          <img data-src="/docs/activity_mahasiswa.png" alt="Activity Diagram Mahasiswa" class="d-img" loading="lazy" onclick="openDiagram(this)">
        </div>
        <div class="diagram-card reveal" style="transition-delay:.15s">
          <div class="d-head"><div class="d-title">Activity — Admin &amp; Dosen</div><span class="d-tag">Web</span></div>
          <p class="d-desc">Alur panel web: admin kelola master data/RBAC; dosen kelola jadwal, presensi, nilai, validasi KRS, materi, pengumuman.</p>
          <img data-src="/docs/activity_admin_dosen.png" alt="Activity Diagram Admin & Dosen" class="d-img" loading="lazy" onclick="openDiagram(this)">
        </div>
        <div class="diagram-card reveal" style="transition-delay:.2s">
          <div class="d-head"><div class="d-title">Sequence Diagram</div><span class="d-tag">UML</span></div>
          <p class="d-desc">Alur lengkap Login&rarr;KRS&rarr;Validasi&rarr;Fuzzy&rarr;Logout antara aplikasi, server Laravel, dan database.</p>
          <img data-src="/docs/sequence_siprakata.png" alt="Sequence Diagram" class="d-img" loading="lazy" onclick="openDiagram(this)">
        </div>
      </div>

      <div class="doc-sub">
        <span class="sub-num">B</span>
        <h3>Pemodelan Data</h3>
        <span class="sub-note">Relasi tabel database</span>
      </div>
      <div class="diagram-grid">
        <div class="diagram-card reveal">
          <div class="d-head"><div class="d-title">ERD</div><span class="d-tag">Database</span></div>
          <p class="d-desc">19 tabel (termasuk pivot RBAC). Relasi 1&ndash;1 nilai&harr;transaksi_krs, 1&ndash;M jadwal ke presensi/materi, dsb.</p>
          <img data-src="/docs/erd_siprakata.png" alt="ERD" class="d-img" loading="lazy" onclick="openDiagram(this)">
        </div>
      </div>

      <div class="doc-sub">
        <span class="sub-num">C</span>
        <h3>Arsitektur &amp; UX</h3>
        <span class="sub-note">Deployment &amp; alur pengguna</span>
      </div>
      <div class="diagram-grid">
        <div class="diagram-card reveal">
          <div class="d-head"><div class="d-title">Arsitektur &amp; Deployment</div><span class="d-tag">Deployment</span></div>
          <p class="d-desc">Laravel + MySQL di server, Flutter mobile via tunnel ngrok, web admin/dosen via localhost.</p>
          <img data-src="/docs/arsitektur_siprakata.png" alt="Arsitektur & Deployment" class="d-img" loading="lazy" onclick="openDiagram(this)">
        </div>
        <div class="diagram-card reveal" style="transition-delay:.1s">
          <div class="d-head"><div class="d-title">User Flow — Mobile</div><span class="d-tag">UX</span></div>
          <p class="d-desc">Navigasi layar aplikasi mobile mahasiswa: splash, login, dashboard, menu fitur, dan logout.</p>
          <img data-src="/docs/user_flow_mahasiswa.png" alt="User Flow Mobile" class="d-img" loading="lazy" onclick="openDiagram(this)">
        </div>
      </div>
    </div>

    <!-- ─── API ─── -->
    <div class="doc-pane" id="pane-api">
      <div class="reveal" style="text-align:center;margin-bottom:24px">
        <p class="section-desc" style="margin:0 auto">REST API untuk aplikasi mobile mahasiswa. Semua endpoint dilindungi token Bearer (Authorization: Bearer &lt;token&gt;), kecuali login.</p>
      </div>

      <div class="cmd-block reveal">
        <div class="cmd-title"><span class="c-ic">🔐</span> Alur Autentikasi — Login, simpan token, lalu kirim Bearer</div>
        <pre><span class="c-cmt"># 1. Login → dapatkan token</span>
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Accept: application/json" \
  -d "email=20230001@mhs.kampus.ac.id" -d "password=20230001"
<span class="c-cmt"># → { "status":"success", "data":{ "token":"API-xxxx-xxxx-xxxx", "user":{...} } }</span>

<span class="c-cmt"># 2. Panggil endpoint terproteksi dengan header Authorization</span>
curl http://127.0.0.1:8000/api/krs \
  -H "Authorization: Bearer API-xxxx-xxxx-xxxx" \
  -H "Accept: application/json"</pre>
      </div>

      <div class="doc-sub reveal">
        <span class="sub-num">A</span>
        <h3>Autentikasi</h3>
      </div>
      <div class="api-table-wrap reveal">
        <table class="api-table">
          <tbody>
            <tr><td><span class="m-badge m-post">POST</span></td><td><code>api/login</code></td><td>Login mahasiswa, menghasilkan token API</td></tr>
            <tr><td><span class="m-badge m-post">POST</span></td><td><code>api/logout</code></td><td>Logout, menghapus token aktif</td></tr>
            <tr><td><span class="m-badge m-get">GET</span></td><td><code>api/me</code></td><td>Data user yang sedang login</td></tr>
          </tbody>
        </table>
      </div>

      <div class="doc-sub reveal">
        <span class="sub-num">B</span>
        <h3>KRS &amp; Validasi</h3>
      </div>
      <div class="api-table-wrap reveal">
        <table class="api-table">
          <tbody>
            <tr><td><span class="m-badge m-get">GET</span></td><td><code>api/krs</code></td><td>Daftar KRS mahasiswa</td></tr>
            <tr><td><span class="m-badge m-post">POST</span></td><td><code>api/krs</code></td><td>Ajukan KRS baru (status pending)</td></tr>
            <tr><td><span class="m-badge m-put">PUT</span></td><td><code>api/krs/{id}/proses-validasi</code></td><td>Validasi KRS oleh dosen (setujui/tolak)</td></tr>
          </tbody>
        </table>
      </div>

      <div class="doc-sub reveal">
        <span class="sub-num">C</span>
        <h3>Data Akademik</h3>
      </div>
      <div class="api-table-wrap reveal">
        <table class="api-table">
          <tbody>
            <tr><td><span class="m-badge m-get">GET</span></td><td><code>api/jadwal</code></td><td>Jadwal kuliah</td></tr>
            <tr><td><span class="m-badge m-get">GET</span></td><td><code>api/presensi</code></td><td>Riwayat presensi mahasiswa</td></tr>
            <tr><td><span class="m-badge m-get">GET</span></td><td><code>api/nilai</code></td><td>Nilai tugas, UTS, UAS &amp; grade</td></tr>
            <tr><td><span class="m-badge m-get">GET</span></td><td><code>api/materi</code></td><td>Daftar materi kuliah</td></tr>
            <tr><td><span class="m-badge m-get">GET</span></td><td><code>api/pengumuman</code></td><td>Daftar pengumuman</td></tr>
          </tbody>
        </table>
      </div>

      <div class="doc-sub reveal">
        <span class="sub-num">D</span>
        <h3>Evaluasi Kelayakan (Fuzzy)</h3>
      </div>
      <div class="api-table-wrap reveal">
        <table class="api-table">
          <tbody>
            <tr><td><span class="m-badge m-post">POST</span></td><td><code>api/kelayakan/proses</code></td><td>Proses prediksi kelayakan Fuzzy Mamdani (1 mahasiswa &ndash; 1 mata kuliah)</td></tr>
            <tr><td><span class="m-badge m-post">POST</span></td><td><code>api/kelayakan/batch</code></td><td>Proses prediksi batch (semua mahasiswa)</td></tr>
            <tr><td><span class="m-badge m-get">GET</span></td><td><code>api/kelayakan</code></td><td>Hasil evaluasi kelayakan</td></tr>
          </tbody>
        </table>
      </div>

      <div class="doc-sub reveal">
        <span class="sub-num">E</span>
        <h3>Contoh Request &amp; Response</h3>
        <span class="sub-note">Format JSON aktual</span>
      </div>

      <div class="cmd-block reveal">
        <div class="cmd-title"><span class="c-ic">📝</span> POST <code>/api/krs</code> — Ajukan KRS baru</div>
        <pre><span class="c-cmt"># Request body (JSON)</span>
{ "mahasiswa_id": 5, "matakuliah_id": 12, "dosen_id": 3,
  "tahun_ajaran": "2025/2026", "semester": "Ganjil" }

<span class="c-cmt"># Response 201 Created</span>
{ "status": "success", "message": "Data KRS berhasil dibuat",
  "data": { "id": 87, "mahasiswa_id": 5, "matakuliah_id": 12, "dosen_id": 3,
            "tahun_ajaran": "2025/2026", "semester": "Ganjil",
            "status": "aktif", "status_validasi": "pending",
            "mahasiswa": { ... }, "matakuliah": { ... }, "dosen": { ... } } }

<span class="c-cmt"># Response 422 (duplikat, krs_unique)</span>
{ "status": "error", "message": "Data KRS sudah ada untuk mahasiswa, matakuliah,
  tahun ajaran, dan semester yang sama", "data": null }</pre>
      </div>

      <div class="cmd-block reveal">
        <div class="cmd-title"><span class="c-ic">🧠</span> POST <code>/api/kelayakan/proses</code> — Analisis Fuzzy Mamdani</div>
        <pre><span class="c-cmt"># Request body (JSON)</span>
{ "mahasiswa_id": 5, "matakuliah_id": 12,
  "tahun_ajaran": "2025/2026", "semester": "Ganjil" }

<span class="c-cmt"># Response 200 (skor &amp; hasil + detail perhitungan tersimpan)</span>
{ "status": "success", "message": "Analisis kelayakan berhasil diproses",
  "data": { "id": 42, "mahasiswa_id": 5, "matakuliah_id": 12,
            "kehadiran": 80, "nilai_tugas": 75, "keaktifan_diskusi": 70,
            "skor_prediksi": 65, "hasil_prediksi": "lulus",
            "detail_perhitungan": "{ fuzzification: {...}, rules_aktif: [...] }" } }

<span class="c-cmt"># Response 404 (KRS belum disetujui)</span>
{ "status": "error",
  "message": "KRS tidak ditemukan atau belum disetujui", "data": null }</pre>
      </div>
    </div>

    <!-- ─── Setup & Arsitektur ─── -->
    <div class="doc-pane" id="pane-setup">
      <div class="reveal" style="text-align:center;margin-bottom:24px">
        <p class="section-desc" style="margin:0 auto">Ringkasan tech stack, struktur direktori, dan langkah menjalankan proyek di lingkungan lokal.</p>
      </div>

      <div class="doc-sub reveal">
        <span class="sub-num">A</span>
        <h3>Tech Stack</h3>
        <span class="sub-note">Backend &rarr; Mobile</span>
      </div>
      <div class="stack-table-wrap reveal">
        <table class="stack-table">
          <tbody>
            <tr><td>Backend</td><td>Laravel Framework <code>^13.0</code> &mdash; PHP <code>^8.3</code>, Eloquent ORM, Blade + Tailwind CSS 4 (Vite 8)</td></tr>
            <tr><td>Mobile</td><td>Flutter &mdash; Dart SDK <code>^3.11.5</code>, state <code>provider</code>, <code>http</code>, <code>flutter_secure_storage</code>, <code>intl</code>, <code>url_launcher</code></td></tr>
            <tr><td>Database</td><td>MySQL &mdash; 19 tabel bisnis + default Laravel; unique constraint <code>krs_unique</code> &amp; <code>presensi_unique</code></td></tr>
            <tr><td>Autentikasi API</td><td>Token custom (<code>api_tokens</code>) &mdash; middleware <code>api.auth</code>, role <code>api.role:admin,dosen</code></td></tr>
            <tr><td>RBAC Web</td><td>4 middleware: <code>CheckRole</code>, <code>CheckPermission</code>, <code>ApiAuth</code>, <code>CheckApiRole</code></td></tr>
            <tr><td>Fuzzy Logic</td><td>Mamdani &mdash; <code>FuzzyLogicService.php</code>, 27 rule, defuzzifikasi weighted average (centroid 20/50/80)</td></tr>
            <tr><td>Pengujian</td><td>PHPUnit <code>^12.5</code> &mdash; <code>php artisan test</code>; manual black-box 16 kasus uji</td></tr>
          </tbody>
        </table>
      </div>

      <div class="doc-sub reveal">
        <span class="sub-num">B</span>
        <h3>Struktur Direktori</h3>
        <span class="sub-note">Backend + Mobile</span>
      </div>
      <div class="dir-tree reveal">
        <div><span class="d-dir">siprakata/</span> <span class="d-com"># Backend Laravel</span></div>
        <div>&nbsp;&nbsp;<span class="d-dir">app/</span></div>
        <div>&nbsp;&nbsp;&nbsp;&nbsp;<span class="d-dir">Http/Controllers/Api/</span> <span class="d-com"># 18 controller REST API</span></div>
        <div>&nbsp;&nbsp;&nbsp;&nbsp;<span class="d-dir">Http/Middleware/</span> <span class="d-com"># ApiAuth · CheckApiRole · CheckRole · CheckPermission</span></div>
        <div>&nbsp;&nbsp;&nbsp;&nbsp;<span class="d-name">Models/</span> <span class="d-com"># 17 model Eloquent (User, Mahasiswa, TransaksiKrs, Kelayakan, ...)</span></div>
        <div>&nbsp;&nbsp;&nbsp;&nbsp;<span class="d-name">Services/FuzzyLogicService.php</span> <span class="d-com"># inferensi Fuzzy Mamdani 27 rule</span></div>
        <div>&nbsp;&nbsp;<span class="d-dir">database/migrations/</span> <span class="d-com"># 22 file migrasi (19 tabel bisnis + pivot &amp; default)</span></div>
        <div>&nbsp;&nbsp;<span class="d-dir">database/seeders/</span> <span class="d-com"># akun contoh: admin@kampus.ac.id · dosen@kampus.ac.id · mahasiswa (password = NIM)</span></div>
        <div>&nbsp;&nbsp;<span class="d-dir">public/docs/</span> <span class="d-com"># 7 PNG diagram untuk halaman ini</span></div>
        <div>&nbsp;&nbsp;<span class="d-name">resources/views/landing.blade.php</span> <span class="d-com"># halaman landing + dokumentasi ini</span></div>
        <div>&nbsp;&nbsp;<span class="d-dir">routes/</span> <span class="d-com"># api.php (REST) · web.php (panel admin/dosen + landing)</span></div>
        <div>&nbsp;&nbsp;<span class="d-name">dokumentasi_uts_fuzzy.md</span> <span class="d-com"># dokumen perancangan lengkap</span></div>
        <br>
        <div><span class="d-dir">flutter_siprakata/</span> <span class="d-com"># Frontend Mobile</span></div>
        <div>&nbsp;&nbsp;<span class="d-dir">lib/config/</span> <span class="d-com"># api_config.dart (base URL) · app_theme.dart (Material 3)</span></div>
        <div>&nbsp;&nbsp;<span class="d-dir">lib/models/</span> <span class="d-com"># model JSON (Krs, Jadwal, Presensi, Nilai, Materi, Kelayakan)</span></div>
        <div>&nbsp;&nbsp;<span class="d-dir">lib/providers/</span> <span class="d-com"># state management Provider</span></div>
        <div>&nbsp;&nbsp;<span class="d-dir">lib/screens/</span> <span class="d-com"># auth · home · krs · jadwal · presensi · nilai · materi · pengumuman · kelayakan</span></div>
        <div>&nbsp;&nbsp;<span class="d-dir">lib/services/</span> <span class="d-com"># API client &amp; penyimpanan token (flutter_secure_storage)</span></div>
      </div>

      <div class="doc-sub reveal">
        <span class="sub-num">C</span>
        <h3>Setup &amp; Instalasi</h3>
        <span class="sub-note">Lokal + Perangkat Android</span>
      </div>
      <div class="cmd-block reveal">
        <div class="cmd-title"><span class="c-ic">⚡</span> Backend Laravel</div>
        <pre><span class="c-cmt"># 1. Dependensi &amp; environment</span>
composer install
copy .env.example .env        <span class="c-cmt"># isi DB_DATABASE / DB_USERNAME / DB_PASSWORD</span>
php artisan key:generate
<span class="c-cmt"># 2. Skema &amp; data awal</span>
php artisan migrate --seed
<span class="c-cmt"># 3. Jalankan server + queue</span>
php artisan serve             <span class="c-cmt"># web: http://127.0.0.1:8000</span>
php artisan queue:work --tries=1</pre>
      </div>
      <div class="cmd-block reveal">
        <div class="cmd-title"><span class="c-ic">📱</span> Aplikasi Mobile Flutter</div>
        <pre><span class="c-cmt"># pastikan base URL API di lib/config/api_config.dart</span>
cd flutter_siprakata
flutter pub get
flutter run                    <span class="c-cmt"># emulator / perangkat Android 8+</span></pre>
      </div>
      <div class="cmd-block reveal">
        <div class="cmd-title"><span class="c-ic">🧪</span> Menjalankan Tes</div>
        <pre>composer test                 <span class="c-cmt"># php artisan test (PHPUnit)</span></pre>
      </div>

      <div class="doc-sub reveal">
        <span class="sub-num">D</span>
        <h3>Artefak Dokumentasi</h3>
        <span class="sub-note">File sumber &amp; tautan</span>
      </div>
      <div class="stack-table-wrap reveal">
        <table class="stack-table">
          <tbody>
            <tr><td>Dokumen perancangan</td><td><code>dokumentasi_uts_fuzzy.md</code> &mdash; akar proyek siprakata</td></tr>
            <tr><td>Diagram PlantUML</td><td><code>documentation/diagrams/</code> &mdash; 7 file <code>.puml</code> + <code>.png</code></td></tr>
            <tr><td>PNG ter-render</td><td><code>public/docs/*.png</code> &mdash; direferensikan halaman ini</td></tr>
            <tr><td>Halaman dokumentasi</td><td><code>resources/views/landing.blade.php</code> &mdash; section #dokumentasi</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ─── Database ─── -->
    <div class="doc-pane" id="pane-database">
      <div class="reveal" style="text-align:center;margin-bottom:24px">
        <p class="section-desc" style="margin:0 auto">Struktur database utama sistem SIPRAKATA. Semua relasi memakai foreign key antara tabel terkait.</p>
      </div>
      <div class="doc-sub reveal">
        <span class="sub-num">A</span>
        <h3>Autentikasi &amp; RBAC</h3>
        <span class="sub-note">6 tabel</span>
      </div>
      <div class="db-grid">
        <div class="db-card reveal">
          <div class="db-name">users</div>
          <p class="db-desc">Akun login semua peran (admin, dosen, mahasiswa)</p>
          <div class="db-fields"><span class="db-field pk">id (PK)</span><span class="db-field">name</span><span class="db-field">email</span><span class="db-field">password</span></div>
        </div>
        <div class="db-card reveal">
          <div class="db-name">roles</div>
          <p class="db-desc">Peran pengguna: admin, dosen, mahasiswa</p>
          <div class="db-fields"><span class="db-field pk">id (PK)</span><span class="db-field">name</span></div>
        </div>
        <div class="db-card reveal">
          <div class="db-name">permissions</div>
          <p class="db-desc">Hak akses granular per modul</p>
          <div class="db-fields"><span class="db-field pk">id (PK)</span><span class="db-field">name</span></div>
        </div>
        <div class="db-card reveal">
          <div class="db-name">role_permission</div>
          <p class="db-desc">Relasi M&ndash;M role &rarr; permission</p>
          <div class="db-fields"><span class="db-field fk">role_id (FK)</span><span class="db-field fk">permission_id (FK)</span></div>
        </div>
        <div class="db-card reveal">
          <div class="db-name">role_user</div>
          <p class="db-desc">Relasi M&ndash;M user &rarr; role</p>
          <div class="db-fields"><span class="db-field fk">user_id (FK)</span><span class="db-field fk">role_id (FK)</span></div>
        </div>
        <div class="db-card reveal">
          <div class="db-name">api_tokens</div>
          <p class="db-desc">Token autentikasi aplikasi mobile</p>
          <div class="db-fields"><span class="db-field pk">id (PK)</span><span class="db-field fk">user_id (FK)</span><span class="db-field">token</span><span class="db-field">expires_at</span></div>
        </div>
      </div>

      <div class="doc-sub reveal">
        <span class="sub-num">B</span>
        <h3>Master Data</h3>
        <span class="sub-note">6 tabel</span>
      </div>
      <div class="db-grid">
        <div class="db-card reveal">
          <div class="db-name">program_studi</div>
          <p class="db-desc">Program studi</p>
          <div class="db-fields"><span class="db-field pk">id (PK)</span><span class="db-field">kode_prodi</span><span class="db-field">nama_prodi</span></div>
        </div>
        <div class="db-card reveal">
          <div class="db-name">tahun_ajaran</div>
          <p class="db-desc">Periode tahun ajaran &amp; semester</p>
          <div class="db-fields"><span class="db-field pk">id (PK)</span><span class="db-field">tahun_ajaran</span><span class="db-field">semester</span><span class="db-field">status</span></div>
        </div>
        <div class="db-card reveal">
          <div class="db-name">ruangan</div>
          <p class="db-desc">Data ruang kuliah</p>
          <div class="db-fields"><span class="db-field pk">id (PK)</span><span class="db-field">kode_ruang</span><span class="db-field">kapasitas</span></div>
        </div>
        <div class="db-card reveal">
          <div class="db-name">mahasiswa</div>
          <p class="db-desc">Data profil mahasiswa</p>
          <div class="db-fields"><span class="db-field pk">id (PK)</span><span class="db-field fk">prodi_id (FK)</span><span class="db-field">NIM</span><span class="db-field">nama</span><span class="db-field">status</span></div>
        </div>
        <div class="db-card reveal">
          <div class="db-name">dosen</div>
          <p class="db-desc">Data profil dosen</p>
          <div class="db-fields"><span class="db-field pk">id (PK)</span><span class="db-field fk">prodi_id (FK)</span><span class="db-field">NIDN</span><span class="db-field">nama</span><span class="db-field">jabatan</span></div>
        </div>
        <div class="db-card reveal">
          <div class="db-name">matakuliah</div>
          <p class="db-desc">Data mata kuliah</p>
          <div class="db-fields"><span class="db-field pk">id (PK)</span><span class="db-field">kode_mk</span><span class="db-field">nama_mk</span><span class="db-field">sks</span><span class="db-field">semester</span></div>
        </div>
      </div>

      <div class="doc-sub reveal">
        <span class="sub-num">C</span>
        <h3>Transaksi Akademik</h3>
        <span class="sub-note">5 tabel</span>
      </div>
      <div class="db-grid">
        <div class="db-card reveal">
          <div class="db-name">transaksi_krs</div>
          <p class="db-desc">Pengajuan KRS + status validasi (unique krs_unique)</p>
          <div class="db-fields"><span class="db-field pk">id (PK)</span><span class="db-field fk">mahasiswa_id (FK)</span><span class="db-field fk">matakuliah_id (FK)</span><span class="db-field fk">dosen_id (FK)</span><span class="db-field">status_validasi</span></div>
        </div>
        <div class="db-card reveal">
          <div class="db-name">jadwal_kuliah</div>
          <p class="db-desc">Jadwal perkuliahan</p>
          <div class="db-fields"><span class="db-field pk">id (PK)</span><span class="db-field fk">matakuliah_id (FK)</span><span class="db-field fk">dosen_id (FK)</span><span class="db-field fk">ruangan_id (FK)</span><span class="db-field">hari</span><span class="db-field">jam</span></div>
        </div>
        <div class="db-card reveal">
          <div class="db-name">presensi</div>
          <p class="db-desc">Kehadiran per pertemuan (unique presensi_unique)</p>
          <div class="db-fields"><span class="db-field pk">id (PK)</span><span class="db-field fk">jadwal_id (FK)</span><span class="db-field fk">mahasiswa_id (FK)</span><span class="db-field">status_hadir</span><span class="db-field">pertemuan_ke</span></div>
        </div>
        <div class="db-card reveal">
          <div class="db-name">nilai</div>
          <p class="db-desc">Nilai tugas, UTS, UAS &amp; grade (akhir dihitung otomatis)</p>
          <div class="db-fields"><span class="db-field pk">id (PK)</span><span class="db-field fk">krs_id (FK)</span><span class="db-field">nilai_tugas</span><span class="db-field">nilai_uts</span><span class="db-field">nilai_uas</span></div>
        </div>
        <div class="db-card reveal">
          <div class="db-name">materi_kuliah</div>
          <p class="db-desc">Materi &amp; file lampiran per pertemuan</p>
          <div class="db-fields"><span class="db-field pk">id (PK)</span><span class="db-field fk">jadwal_id (FK)</span><span class="db-field">judul</span><span class="db-field">file_path</span><span class="db-field">link_materi</span></div>
        </div>
      </div>

      <div class="doc-sub reveal">
        <span class="sub-num">D</span>
        <h3>Evaluasi &amp; Pengumuman</h3>
        <span class="sub-note">2 tabel</span>
      </div>
      <div class="db-grid">
        <div class="db-card reveal">
          <div class="db-name">kelayakan_mahasiswa</div>
          <p class="db-desc">Hasil evaluasi Fuzzy Mamdani (skor &amp; detail perhitungan)</p>
          <div class="db-fields"><span class="db-field pk">id (PK)</span><span class="db-field fk">mahasiswa_id (FK)</span><span class="db-field fk">matakuliah_id (FK)</span><span class="db-field">kehadiran</span><span class="db-field">nilai_tugas</span><span class="db-field">keaktifan_diskusi</span><span class="db-field">skor_prediksi</span><span class="db-field">hasil_prediksi</span></div>
        </div>
        <div class="db-card reveal">
          <div class="db-name">pengumuman</div>
          <p class="db-desc">Informasi dari dosen dengan prioritas</p>
          <div class="db-fields"><span class="db-field pk">id (PK)</span><span class="db-field fk">dosen_id (FK)</span><span class="db-field fk">jadwal_id (FK)</span><span class="db-field">judul</span><span class="db-field">isi</span><span class="db-field">prioritas</span><span class="db-field">tgl_posting</span></div>
        </div>
      </div>
    </div>

    <!-- ─── Panduan ─── -->
    <div class="doc-pane" id="pane-panduan">
      <div class="reveal" style="text-align:center;margin-bottom:24px">
        <p class="section-desc" style="margin:0 auto">Langkah penggunaan sistem berdasarkan peran pengguna.</p>
      </div>

      <div class="doc-sub reveal">
        <span class="sub-num">A</span>
        <h3>Matriks Hak Akses (RBAC)</h3>
        <span class="sub-note">Role &times; modul</span>
      </div>
      <div class="api-table-wrap reveal">
        <table class="api-table">
          <tbody>
            <tr>
              <th>Modul</th><th>🛡️ Admin</th><th>👨‍🏫 Dosen</th><th>🎓 Mahasiswa</th>
            </tr>
            <tr><td>Master data (prodi, TA, ruangan, mahasiswa, dosen, matakuliah)</td><td><span class="m-badge m-put">CRUD</span></td><td><span class="m-badge m-get">Baca</span></td><td><span class="m-badge m-get">Baca</span></td></tr>
            <tr><td>RBAC (roles, permissions, user-roles)</td><td><span class="m-badge m-put">CRUD</span></td><td><span class="m-badge" style="background:rgba(255,255,255,.04);color:rgba(235,229,240,.3)">—</span></td><td><span class="m-badge" style="background:rgba(255,255,255,.04);color:rgba(235,229,240,.3)">—</span></td></tr>
            <tr><td>Transaksi KRS</td><td><span class="m-badge m-put">CRUD</span></td><td><span class="m-badge m-post">Validasi</span></td><td><span class="m-badge m-post">Ajukan</span></td></tr>
            <tr><td>Jadwal kuliah</td><td><span class="m-badge m-put">CRUD</span></td><td><span class="m-badge m-post">Kelola</span></td><td><span class="m-badge m-get">Lihat</span></td></tr>
            <tr><td>Presensi</td><td><span class="m-badge m-put">Kelola</span></td><td><span class="m-badge m-post">Input</span></td><td><span class="m-badge m-get">Riwayat</span></td></tr>
            <tr><td>Nilai</td><td><span class="m-badge m-put">Kelola</span></td><td><span class="m-badge m-post">Input</span></td><td><span class="m-badge m-get">Lihat</span></td></tr>
            <tr><td>Materi kuliah</td><td><span class="m-badge m-put">Kelola</span></td><td><span class="m-badge m-post">Unggah</span></td><td><span class="m-badge m-get">Unduh</span></td></tr>
            <tr><td>Pengumuman</td><td><span class="m-badge m-put">Kelola</span></td><td><span class="m-badge m-post">Buat</span></td><td><span class="m-badge m-get">Lihat</span></td></tr>
            <tr><td>Evaluasi kelayakan (Fuzzy)</td><td><span class="m-badge m-post">Batch</span></td><td><span class="m-badge m-post">Proses</span></td><td><span class="m-badge m-get">Lihat</span></td></tr>
          </tbody>
        </table>
      </div>

      <div class="doc-sub reveal" style="margin-top:44px">
        <span class="sub-num">B</span>
        <h3>Alur Penggunaan per Peran</h3>
        <span class="sub-note">Langkah demi langkah</span>
      </div>
      <div class="guide-grid">
        <div class="guide-card reveal">
          <div class="g-role">ROLE 1 · MAHASISWA</div>
          <h4>Aplikasi Mobile</h4>
          <ol>
            <li><strong>Download APK</strong> dan install di Android 8+</li>
            <li><strong>Login</strong> dengan email &amp; password akun mahasiswa</li>
            <li><strong>Ajukan KRS</strong> di menu Transaksi KRS</li>
            <li>Tunggu <strong>validasi dosen</strong> (pending &rarr; disetujui/ditolak)</li>
            <li>Lihat <strong>jadwal, presensi, nilai, materi</strong>, dan pengumuman</li>
            <li><strong>Evaluasi kelayakan</strong>: pilih mata kuliah lalu proses analisis Fuzzy</li>
            <li>Keluar lewat menu <strong>logout</strong></li>
          </ol>
        </div>
        <div class="guide-card reveal" style="transition-delay:.1s">
          <div class="g-role">ROLE 2 · DOSEN</div>
          <h4>Panel Web</h4>
          <ol>
            <li><strong>Login</strong> ke panel web dengan akun dosen</li>
            <li>Kelola <strong>jadwal mengajar</strong> (CRUD)</li>
            <li>Input <strong>presensi</strong> mahasiswa per pertemuan</li>
            <li>Input <strong>nilai</strong> tugas/UTS/UAS</li>
            <li><strong>Validasi KRS</strong> mahasiswa (setujui / tolak)</li>
            <li>Unggah <strong>materi kuliah</strong> &amp; buat <strong>pengumuman</strong></li>
          </ol>
        </div>
        <div class="guide-card reveal" style="transition-delay:.2s">
          <div class="g-role">ROLE 3 · ADMIN</div>
          <h4>Panel Web</h4>
          <ol>
            <li><strong>Login</strong> ke panel web dengan akun admin</li>
            <li>Kelola <strong>master data</strong>: prodi, tahun ajaran, ruangan</li>
            <li>Kelola <strong>mahasiswa, dosen, matakuliah</strong></li>
            <li>Atur <strong>RBAC</strong>: roles, permissions, user-roles</li>
            <li>Pantau <strong>hasil evaluasi kelayakan</strong> semua mahasiswa</li>
          </ol>
        </div>
      </div>

      <div class="doc-sub reveal" style="margin-top:44px">
        <span class="sub-num">C</span>
        <h3>Halaman Web per Peran</h3>
        <span class="sub-note">URL panel web &amp; portal</span>
      </div>

      <div class="doc-sub reveal" style="margin:20px 0 12px">
        <span class="sub-num">1</span>
        <h3>🛡️ Admin</h3>
      </div>
      <div class="api-table-wrap reveal">
        <table class="api-table">
          <tbody>
            <tr><td><code>/dashboard</code></td><td>Dashboard utama admin &amp; dosen (rekap + statistik)</td></tr>
            <tr><td><code>/data-mahasiswa</code> · <code>/create-mahasiswa</code> · <code>/edit-mahasiswa/{id}</code></td><td>Kelola data mahasiswa (CRUD)</td></tr>
            <tr><td><code>/dosen</code> · <code>/matakuliah</code> · <code>/prodi</code> · <code>/ruangan</code> · <code>/tahun_ajaran</code></td><td>Master data lengkap (CRUD) — semua modul</td></tr>
            <tr><td><code>/roles</code> · <code>/permissions</code> · <code>/user-roles</code></td><td>Manajemen RBAC — roles, permissions, user-roles</td></tr>
            <tr><td><code>/krs</code> · <code>/jadwal</code></td><td>Kelola transaksi KRS &amp; jadwal kuliah (CRUD)</td></tr>
            <tr><td><code>/presensi</code> · <code>/nilai</code> · <code>/materi</code> · <code>/pengumuman</code></td><td>Kelola presensi, nilai, materi, pengumuman</td></tr>
            <tr><td><code>/kelayakan</code> · <code>/kelayakan/batch</code></td><td>Pantau &amp; proses evaluasi Fuzzy batch (semua mahasiswa)</td></tr>
          </tbody>
        </table>
      </div>

      <div class="doc-sub reveal" style="margin:20px 0 12px">
        <span class="sub-num">2</span>
        <h3>👨‍🏫 Dosen</h3>
      </div>
      <div class="api-table-wrap reveal">
        <table class="api-table">
          <tbody>
            <tr><td><code>/dashboard</code></td><td>Dashboard utama</td></tr>
            <tr><td><code>/krs/{krs}/validasi</code></td><td>Validasi KRS mahasiswa (setujui / tolak + catatan)</td></tr>
            <tr><td><code>/jadwal</code></td><td>Kelola jadwal mengajar</td></tr>
            <tr><td><code>/presensi</code> · <code>/nilai</code> · <code>/materi</code> · <code>/pengumuman</code></td><td>Input presensi, nilai, unggah materi, buat pengumuman</td></tr>
            <tr><td><code>/kelayakan</code> · <code>/kelayakan/create</code></td><td>Proses evaluasi Fuzzy per mahasiswa / mata kuliah</td></tr>
          </tbody>
        </table>
      </div>

      <div class="doc-sub reveal" style="margin:20px 0 12px">
        <span class="sub-num">3</span>
        <h3>🎓 Mahasiswa (Portal Web)</h3>
      </div>
      <div class="api-table-wrap reveal">
        <table class="api-table">
          <tbody>
            <tr><td><code>/mahasiswa/dashboard</code></td><td>Dashboard pribadi (IPO, SKS, grafik)</td></tr>
            <tr><td><code>/mahasiswa/krs</code> · <code>/mahasiswa/krs/create</code></td><td>Lihat &amp; ajukan KRS (status pending)</td></tr>
            <tr><td><code>/mahasiswa/jadwal</code> · <code>/mahasiswa/presensi</code> · <code>/mahasiswa/nilai</code></td><td>Jadwal, presensi, nilai</td></tr>
            <tr><td><code>/mahasiswa/materi</code> · <code>/mahasiswa/pengumuman</code></td><td>Materi kuliah &amp; pengumuman</td></tr>
            <tr><td><code>/mahasiswa/kelayakan</code> · <code>/mahasiswa/kelayakan/create</code></td><td>Evaluasi kelayakan Fuzzy (analisis per mata kuliah)</td></tr>
            <tr><td><code>/mahasiswa/profile</code></td><td>Profil akun</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ─── Pengujian ─── -->
    <div class="doc-pane" id="pane-pengujian">
      <div class="reveal" style="text-align:center;margin-bottom:24px">
        <p class="section-desc" style="margin:0 auto">Hasil pengujian fungsional (black-box) dan verifikasi perhitungan manual Fuzzy Mamdani.</p>
      </div>

      <div class="test-table-wrap reveal">
        <table class="test-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Skenario</th>
              <th>Input / Langkah</th>
              <th>Hasil yang Diharapkan</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr class="mod-row"><td colspan="5">Modul Autentikasi</td></tr>
            <tr><td class="t-id">TC-A01</td><td>Login kredensial benar</td><td>POST /api/login email &amp; password valid</td><td>Status 200, token dibuat, data user mahasiswa</td><td><span class="t-status t-pass">PASS</span></td></tr>
            <tr><td class="t-id">TC-A02</td><td>Login password salah</td><td>POST /api/login dengan password keliru</td><td>Status 401, "Email atau password salah."</td><td><span class="t-status t-pass">PASS</span></td></tr>
            <tr><td class="t-id">TC-A03</td><td>Login akun non-mahasiswa</td><td>POST /api/login memakai akun admin/dosen</td><td>Status 403, "Aplikasi hanya untuk akun mahasiswa."</td><td><span class="t-status t-pass">PASS</span></td></tr>
            <tr><td class="t-id">TC-A04</td><td>Logout menghapus token</td><td>POST /api/logout memakai Bearer token aktif</td><td>Status 200, token terhapus, akses berikutnya 401</td><td><span class="t-status t-pass">PASS</span></td></tr>

            <tr class="mod-row"><td colspan="5">Modul KRS &amp; Validasi</td></tr>
            <tr><td class="t-id">TC-K01</td><td>Ajukan KRS baru</td><td>POST /api/krs { matakuliah_id, tahun_ajaran, semester }</td><td>Status 200, status_validasi = pending</td><td><span class="t-status t-pass">PASS</span></td></tr>
            <tr><td class="t-id">TC-K02</td><td>Ajukan mata kuliah duplikat</td><td>POST /api/krs matkul sama, TA &amp; semester sama</td><td>Ditolak oleh constraint krs_unique</td><td><span class="t-status t-pass">PASS</span></td></tr>
            <tr><td class="t-id">TC-K03</td><td>Validasi disetujui</td><td>PUT /api/krs/{id}/proses-validasi { status_validasi=disetujui }</td><td>Status validasi = disetujui, tgl_validasi terisi</td><td><span class="t-status t-pass">PASS</span></td></tr>
            <tr><td class="t-id">TC-K04</td><td>Validasi ditolak</td><td>PUT /api/krs/{id}/proses-validasi { status_validasi=ditolak, catatan }</td><td>Status validasi = ditolak beserta catatan</td><td><span class="t-status t-pass">PASS</span></td></tr>

            <tr class="mod-row"><td colspan="5">Modul Evaluasi Kelayakan (Fuzzy)</td></tr>
            <tr><td class="t-id">TC-F01</td><td>Analisis KRS belum disetujui</td><td>POST /api/kelayakan/proses, KRS pending/ditolak</td><td>Ditolak, pesan "KRS belum disetujui"</td><td><span class="t-status t-pass">PASS</span></td></tr>
            <tr><td class="t-id">TC-F02</td><td>Analisis data lengkap</td><td>POST /api/kelayakan/proses, KRS disetujui + data presensi/nilai</td><td>Skor &amp; hasil tersimpan, detail rule aktif tampil</td><td><span class="t-status t-pass">PASS</span></td></tr>
            <tr><td class="t-id">TC-F03</td><td>Input bernilai 0 (safety)</td><td>Kehadiran / nilai tugas / keaktifan diskusi = 0</td><td>Hasil otomatis tidak_lulus (skor 0)</td><td><span class="t-status t-pass">PASS</span></td></tr>
            <tr><td class="t-id">TC-F04</td><td>Verifikasi manual vs sistem</td><td>Bandingkan hasil sistem dengan perhitungan manual</td><td>Skor &amp; status identik dengan hitungan manual</td><td><span class="t-status t-pass">PASS</span></td></tr>

            <tr class="mod-row"><td colspan="5">Modul Otorisasi &amp; Data</td></tr>
            <tr><td class="t-id">TC-O01</td><td>Akses tanpa token</td><td>GET /api/krs tanpa Authorization header</td><td>Status 401 Unauthorized</td><td><span class="t-status t-pass">PASS</span></td></tr>
            <tr><td class="t-id">TC-O02</td><td>Token tidak valid</td><td>GET /api/krs memakai token salah/kadaluarsa</td><td>Status 401</td><td><span class="t-status t-pass">PASS</span></td></tr>
            <tr><td class="t-id">TC-O03</td><td>RBAC web admin</td><td>Login admin, akses menu master data &amp; RBAC</td><td>Menu sesuai permission, akses terlarang diblokir</td><td><span class="t-status t-pass">PASS</span></td></tr>
            <tr><td class="t-id">TC-O04</td><td>Input nilai dosen</td><td>Dosen input nilai tugas/UTS/UAS</td><td>nilai_akhir &amp; grade terhitung otomatis (30/30/40)</td><td><span class="t-status t-pass">PASS</span></td></tr>
          </tbody>
        </table>
      </div>

      <div class="fuzzy-calc reveal">
        <h3>Verifikasi Perhitungan Manual Fuzzy Mamdani</h3>
        <p class="sub">Contoh kasus: mahasiswa dengan kehadiran 80%, nilai tugas 75, keaktifan diskusi 70.</p>

        <div class="fc-grid">
          <div class="fc-card">
            <div class="fc-var">Kehadiran (80%)</div>
            <div class="fc-input">80</div>
            <div class="fc-membership">
              μ Rendah = 0<br>
              μ Sedang = <b>0.5</b> (85−80)/10<br>
              μ Tinggi = <b>0.5</b> (80−75)/10
            </div>
          </div>
          <div class="fc-card">
            <div class="fc-var">Nilai Tugas (75)</div>
            <div class="fc-input">75</div>
            <div class="fc-membership">
              μ Rendah = 0<br>
              μ Sedang = <b>1.0</b> (plateau 60–75)<br>
              μ Tinggi = 0
            </div>
          </div>
          <div class="fc-card">
            <div class="fc-var">Keaktifan Diskusi (70)</div>
            <div class="fc-input">70</div>
            <div class="fc-membership">
              μ Rendah = 0<br>
              μ Sedang = <b>1.0</b> (plateau 50–70)<br>
              μ Tinggi = 0
            </div>
          </div>
        </div>

        <div class="fc-table-wrap">
          <table class="fc-table">
            <thead>
              <tr><th>Rule</th><th>Kondisi</th><th>α (min)</th><th>Output</th></tr>
            </thead>
            <tbody>
              <tr><td>R14</td><td>Kehadiran Sedang &amp; Tugas Sedang &amp; Diskusi Sedang</td><td class="active">0.5</td><td>cukup</td></tr>
              <tr><td>R23</td><td>Kehadiran Tinggi &amp; Tugas Sedang &amp; Diskusi Sedang</td><td class="active">0.5</td><td>lulus</td></tr>
              <tr><td>lainnya</td><td>25 rule lainnya</td><td>0</td><td>&mdash;</td></tr>
            </tbody>
          </table>
        </div>

        <div class="fc-kehadiran">
          <b>Defuzzifikasi (Weighted Average):</b> &nbsp; Skor = (0.5 × 50 + 0.5 × 80) / (0.5 + 0.5) = (25 + 40) / 1 = <b>65</b><br>
          <b>Kesimpulan:</b> Skor 65 &ge; 60 &rarr; status <b>LULUS</b> (sesuai ambang: &ge;60 lulus, &ge;40 cukup, &lt;40 tidak lulus)
        </div>

        <div class="fc-result">
          <div>
            <div class="fr-lbl">Hasil Sistem</div>
            <div class="fr-score">65.00</div>
          </div>
          <span class="fr-status">✅ LULUS</span>
          <div class="fr-note">Hasil sistem identik dengan perhitungan manual — memvalidasi kebenaran implementasi fuzzifikasi, inferensi 27 rule, dan defuzzifikasi weighted average (centroid: tidak_lulus=20, cukup=50, lulus=80).</div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section" id="kebutuhan">
  <div class="container">
    <div class="reveal" style="text-align:center;margin-bottom:44px">
      <span class="section-label">Kebutuhan</span>
      <h2 class="section-title">Kebutuhan Sistem</h2>
      <p class="section-desc" style="margin:0 auto">Persyaratan minimum agar SIPRAKATA berjalan lancar — baik sebagai pengguna maupun sebagai server.</p>
    </div>
    <div class="stack-table-wrap reveal">
      <table class="stack-table" style="min-width:680px">
        <thead>
          <tr><th>Komponen</th><th>Kebutuhan Minimum</th></tr>
        </thead>
        <tbody>
          <tr><td>Perangkat Mahasiswa</td><td>Android 8.0+ &middot; RAM 2 GB &middot; ruang penyimpanan &plusmn;50 MB &middot; koneksi internet stabil</td></tr>
          <tr><td>Perangkat Dosen / Admin</td><td>Browser modern (Chrome, Edge, Firefox, Safari) &middot; akses internet &middot; resolusi layar min. 1024&times;768</td></tr>
          <tr><td>Server Backend</td><td>PHP 8.3+ &middot; MySQL 8.0+ / MariaDB 10.6+ &middot; Composer 2 &middot; Laravel 13 &middot; Apache / Nginx &middot; HTTPS</td></tr>
          <tr><td>Pengembangan (Opsional)</td><td>Flutter 3 SDK + Dart &middot; Android SDK &middot; Git &middot; Node.js 18+ (Vercel CLI untuk deploy panel web)</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</section>

<section class="section" id="keamanan">
  <div class="container">
    <div class="reveal" style="text-align:center;margin-bottom:44px">
      <span class="section-label">Keamanan</span>
      <h2 class="section-title">Keamanan Terjamin</h2>
      <p class="section-desc" style="margin:0 auto">Data akademik dilindungi berlapis — dari sisi autentikasi hingga penyimpanan.</p>
    </div>
    <div class="features-grid">
      <div class="feat reveal"><span class="f-icon">🔐</span><div><div class="f-txt">Autentikasi Token</div><div class="f-sub">Setiap request API diverifikasi token Bearer — sesi aman dan bisa dicabut</div></div></div>
      <div class="feat reveal" style="transition-delay:.05s"><span class="f-icon">🛡️</span><div><div class="f-txt">RBAC Granular</div><div class="f-sub">Peran Admin, Dosen, Mahasiswa dengan permission per modul</div></div></div>
      <div class="feat reveal" style="transition-delay:.1s"><span class="f-icon">🔑</span><div><div class="f-txt">Password Ter-Hash</div><div class="f-sub">Kredensial disimpan dengan bcrypt, tidak pernah plain-text</div></div></div>
      <div class="feat reveal" style="transition-delay:.15s"><span class="f-icon">🧱</span><div><div class="f-txt">Middleware Proteksi</div><div class="f-sub">api.auth &amp; api.role memfilter akses sebelum masuk controller</div></div></div>
      <div class="feat reveal" style="transition-delay:.2s"><span class="f-icon">🧼</span><div><div class="f-txt">Aman dari Injeksi</div><div class="f-sub">Eloquent ORM + query binding mencegah SQL injection</div></div></div>
      <div class="feat reveal" style="transition-delay:.25s"><span class="f-icon">🖥️</span><div><div class="f-txt">Web Terlindungi</div><div class="f-sub">CSRF protection dan validasi input untuk panel web Laravel</div></div></div>
      <div class="feat reveal" style="transition-delay:.3s"><span class="f-icon">🔒</span><div><div class="f-txt">Tanpa Secret di Repo</div><div class="f-sub">Konfigurasi rahasia via environment (.env), tidak pernah di-commit</div></div></div>
      <div class="feat reveal" style="transition-delay:.35s"><span class="f-icon">📦</span><div><div class="f-txt">APK Rilis Resmi</div><div class="f-sub">APK dari build resmi Flutter — tanpa sumber pihak ketiga</div></div></div>
    </div>
  </div>
</section>

<section class="section" id="performa">
  <div class="container">
    <div class="reveal" style="text-align:center;margin-bottom:44px">
      <span class="section-label">Performa</span>
      <h2 class="section-title">Performa Optimal</h2>
      <p class="section-desc" style="margin:0 auto">Didesain hemat sumber daya tanpa mengorbankan pengalaman pengguna.</p>
    </div>
    <div class="roles">
      <div class="role reveal"><span class="r-icon">⚡</span><h4>Startup Cepat</h4><p>Flutter release AOT — aplikasi terbuka dalam hitungan detik tanpa interpreter.</p></div>
      <div class="role reveal" style="transition-delay:.1s"><span class="r-icon">🚀</span><h4>API Ringan</h4><p>Response REST JSON tanpa beban HTML — hemat kuota, data, dan baterai.</p></div>
      <div class="role reveal" style="transition-delay:.2s"><span class="r-icon">🧩</span><h4>Query Optimal</h4><p>Eager loading mencegah N+1 pada daftar KRS, jadwal, presensi, dan nilai.</p></div>
      <div class="role reveal" style="transition-delay:.3s"><span class="r-icon">📊</span><h4>Fuzzy Instan</h4><p>27 rule inferensi Mamdani berjalan cepat bahkan untuk evaluasi batch.</p></div>
    </div>
  </div>
</section>

<section class="section" id="studi-kasus">
  <div class="container">
    <div class="reveal" style="text-align:center;margin-bottom:44px">
      <span class="section-label">Studi Kasus</span>
      <h2 class="section-title">Evaluasi Kelayakan Fuzzy Mamdani</h2>
      <p class="section-desc" style="margin:0 auto">Ilustrasi nyata bagaimana sistem menilai kelayakan semester seorang mahasiswa.</p>
    </div>
    <div class="guide-grid">
      <div class="guide-card reveal">
        <span class="g-role">SKENARIO</span>
        <h4>Mahasiswa Mengajukan KRS</h4>
        <ol>
          <li><strong>Mahasiswa semester 3</strong> mengajukan KRS dari aplikasi mobile.</li>
          <li><strong>Input evaluasi</strong>: kehadiran 85%, nilai tugas 78, keaktifan diskusi 90.</li>
          <li>Sistem memanggil endpoint kelayakan untuk menilai kesiapan semester.</li>
        </ol>
      </div>
      <div class="guide-card reveal" style="transition-delay:.1s">
        <span class="g-role">PENYELESAIAN</span>
        <h4>Inferensi Fuzzy Mamdani</h4>
        <ol>
          <li><strong>Fuzzifikasi</strong> tiap variabel ke himpunan fuzzy (rendah/sedang/tinggi).</li>
          <li><strong>27 rules</strong> dikombinasikan dengan operator AND &rarr; derajat keanggotaan.</li>
          <li><strong>Defuzzifikasi</strong> metode centroid/weighted average menghasilkan skor akhir.</li>
        </ol>
      </div>
      <div class="guide-card reveal" style="transition-delay:.2s">
        <span class="g-role">HASIL &amp; EVALUASI</span>
        <h4>Layak &amp; Tervalidasi</h4>
        <ol>
          <li><strong>Skor kelayakan</strong> di atas ambang &rarr; status <strong>Layak</strong>.</li>
          <li>Dosen <strong>memvalidasi KRS</strong> di panel web (Setuju/Tolak + catatan).</li>
          <li>Perhitungan manual terbukti identik dengan sistem &mdash; lihat tab <strong>Pengujian</strong>.</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="section" id="faq">
  <div class="container">
    <div class="reveal" style="text-align:center;margin-bottom:44px">
      <span class="section-label">FAQ</span>
      <h2 class="section-title">Pertanyaan Umum</h2>
      <p class="section-desc" style="margin:0 auto">Jawaban singkat atas pertanyaan yang paling sering diajukan.</p>
    </div>
    <div class="faq">
      <details class="reveal"><summary>Apa itu SIPRAKATA? <span class="chev">›</span></summary><div class="faq-a">Sistem Informasi Praktik Akademik — platform terintegrasi untuk layanan akademik kampus: aplikasi mobile untuk mahasiswa dan panel web untuk admin &amp; dosen.</div></details>
      <details class="reveal"><summary>Bagaimana cara login sebagai mahasiswa? <span class="chev">›</span></summary><div class="faq-a">Buka aplikasi mobile, masukkan akun terdaftar Anda, lalu login. Admin dan dosen login lewat panel web menggunakan tombol <code>Login Admin/Dosen</code>.</div></details>
      <details class="reveal"><summary>Bagaimana cara mengajukan KRS? <span class="chev">›</span></summary><div class="faq-a">Buka menu <strong>KRS</strong> di aplikasi, pilih matakuliah yang diambil, lalu ajukan. Anda bisa memantau statusnya (pending/disetujui/ditolak) secara real-time.</div></details>
      <details class="reveal"><summary>Apa arti status KRS saya? <span class="chev">›</span></summary><div class="faq-a"><code>pending</code> berarti menunggu validasi dosen/admin; <code>disetujui</code> berarti KRS diterima; <code>ditolak</code> berarti perlu revisi — lengkap dengan catatan dari validator.</div></details>
      <details class="reveal"><summary>Apa itu Fuzzy Logic Mamdani? <span class="chev">›</span></summary><div class="faq-a">Metode evaluasi kelayakan yang memproses 3 input (kehadiran, nilai tugas, keaktifan diskusi) melalui 27 rules dan menghasilkan skor kelayakan 0&ndash;1 yang objektif.</div></details>
      <details class="reveal"><summary>Apakah aplikasi mobile berbayar? <span class="chev">›</span></summary><div class="faq-a">Tidak. Aplikasi gratis — unduh file APK langsung dari tombol <strong>Download</strong> di halaman ini.</div></details>
      <details class="reveal"><summary>Bagaimana admin/dosen memvalidasi KRS? <span class="chev">›</span></summary><div class="faq-a">Login di panel web, buka menu <strong>Validasi KRS</strong>, pilih pengajuan dengan status pending, lalu klik <strong>Setuju</strong> atau <strong>Tolak</strong> beserta catatan.</div></details>
      <details class="reveal"><summary>Apakah data akademik saya aman? <span class="chev">›</span></summary><div class="faq-a">Aman. Semua akses dilindungi autentikasi token, otorisasi RBAC per modul, password ter-hash, dan tanpa secret tersimpan di repositori publik.</div></details>
    </div>
  </div>
</section>

<section class="section" id="lisensi">
  <div class="container">
    <div class="reveal" style="text-align:center;margin-bottom:44px">
      <span class="section-label">Lisensi</span>
      <h2 class="section-title">Lisensi Aplikasi</h2>
      <p class="section-desc" style="margin:0 auto">SIPRAKATA dirilis sebagai perangkat lunak sumber terbuka.</p>
    </div>
    <div class="license-box reveal">
      <div class="lic-head">
        <span class="lic-badge">MIT</span>
        <span class="lic-name">SIPRAKATA &mdash; Sistem Informasi Praktik Akademik</span>
      </div>
      <p>Proyek ini dilisensikan di bawah <a href="https://opensource.org/license/mit" target="_blank" rel="noopener">MIT License</a> — bebas digunakan, dimodifikasi, dan didistribusikan kembali selama salinan lisensi asli disertakan.</p>
      <ul>
        <li>Bebas dipakai untuk tujuan apa pun, termasuk komersial.</li>
        <li>Bebas dimodifikasi dan didistribusikan ulang.</li>
        <li>Perangkat lunak disediakan "sebagaimana adanya" tanpa jaminan apa pun.</li>
        <li>Sumber kode lengkap tersedia di <a href="https://github.com/YudhaMaulana18/Siprakata" target="_blank" rel="noopener">github.com/YudhaMaulana18/Siprakata</a>.</li>
      </ul>
    </div>
  </div>
</section>

<section class="section" id="kontak">
  <div class="container">
    <div class="reveal" style="text-align:center;margin-bottom:44px">
      <span class="section-label">Kontak</span>
      <h2 class="section-title">Hubungi Kami</h2>
      <p class="section-desc" style="margin:0 auto">Temukan informasi lebih lanjut, kunjungi repositori, atau laporkan kendala.</p>
    </div>
    <div class="roles">
      <div class="role reveal"><span class="r-icon">🐙</span><h4>GitHub</h4><p><a href="https://github.com/YudhaMaulana18" target="_blank" rel="noopener">@YudhaMaulana18</a></p></div>
      <div class="role reveal" style="transition-delay:.1s"><span class="r-icon">📦</span><h4>Repositori</h4><p><a href="https://github.com/YudhaMaulana18/Siprakata" target="_blank" rel="noopener">YudhaMaulana18/Siprakata</a></p></div>
      <div class="role reveal" style="transition-delay:.2s"><span class="r-icon">📸</span><h4>Instagram</h4><p><a href="https://www.instagram.com/yudhamlnaa_" target="_blank" rel="noopener">@yudhamlnaa_</a></p></div>
      <div class="role reveal" style="transition-delay:.3s"><span class="r-icon">🚩</span><h4>Laporkan Masalah</h4><p><a href="https://github.com/YudhaMaulana18/Siprakata/issues" target="_blank" rel="noopener">GitHub Issues</a></p></div>
    </div>
  </div>
</section>


<section class="section" id="download">
  <div class="container">
    <div class="cta reveal">
      <span style="display:inline-flex;align-items:center;gap:8px;padding:8px 18px;border-radius:8px;font-size:13px;font-weight:600;background:rgba(34,197,94,.1);color:#4ade80;border:1px solid rgba(34,197,94,.15);margin-bottom:20px">
        <span>📱</span> Gratis — Download Aplikasi Mobile
      </span>
      <h2>Siap untuk Transformasi Akademik?</h2>
      <p>Download SIPRAKATA Mobile sekarang dan akses semua layanan akademik langsung dari genggaman Anda.</p>
      <div class="cta-btns">
        <a href="/siprakata.apk" class="btn btn-primary" download>
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
          Download APK
        </a>
        <a href="/flutter" class="btn btn-gold">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 012 2v4m0 0v4m0-4h-4m-8 0h4m0 0v4m0-4V6a2 2 0 012-2h4"/></svg>
          Login Admin/Dosen
        </a>
      </div>
      <div class="cta-btns" style="margin-top:22px">
        <a href="https://github.com/YudhaMaulana18" target="_blank" rel="noopener" class="btn btn-secondary">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 .5C5.73.5.5 5.73.5 12a11.5 11.5 0 007.86 10.91c.58.1.79-.25.79-.56v-2.17c-3.2.7-3.87-1.37-3.87-1.37-.53-1.33-1.28-1.69-1.28-1.69-1.05-.71.08-.7.08-.7 1.16.08 1.77 1.19 1.77 1.19 1.03 1.77 2.7 1.26 3.36.96.1-.75.4-1.26.72-1.55-2.57-.29-5.27-1.28-5.27-5.7 0-1.26.45-2.29 1.19-3.1-.12-.29-.52-1.46.11-3.05 0 0 .97-.31 3.17 1.18a11 11 0 015.77 0c2.2-1.49 3.17-1.18 3.17-1.18.63 1.59.23 2.76.11 3.05.74.81 1.19 1.84 1.19 3.1 0 4.43-2.71 5.41-5.29 5.69.41.36.78 1.06.78 2.14v3.17c0 .31.21.67.8.56A11.5 11.5 0 0023.5 12C23.5 5.73 18.27.5 12 .5z"/></svg>
          @YudhaMaulana18
        </a>
      </div>
      <div class="note">versi android · file .apk · rilis 2026 · membutuhkan android 8+</div>
    </div>
  </div>
</section>

<footer>
  <div class="container">
    &copy; 2026 <a href="/">SIPRAKATA</a> &mdash; Sistem Informasi Praktik Akademik &bull; Built with Laravel + Flutter &bull; Lisensi MIT
  </div>
</footer>

<script>
// ─── Particles ───
const canvas = document.getElementById('particles-canvas');
const ctx = canvas.getContext('2d');
let particles = [], w, h;
function resize(){ w=canvas.width=innerWidth; h=canvas.height=innerHeight; }
resize(); addEventListener('resize', resize);
class Particle{
  constructor(){ this.reset(); }
  reset(){
    this.x=Math.random()*w; this.y=Math.random()*h;
    this.r=Math.random()*1.5+.3;
    this.dx=(Math.random()-.5)*.3; this.dy=(Math.random()-.5)*.3;
    this.o=Math.random()*.5+.1;
  }
  update(){ this.x+=this.dx; this.y+=this.dy; if(this.x<0||this.x>w||this.y<0||this.y>h) this.reset(); }
  draw(){ ctx.beginPath(); ctx.arc(this.x,this.y,this.r,0,Math.PI*2); ctx.fillStyle=`rgba(99,102,241,${this.o})`; ctx.fill(); }
}
for(let i=0;i<80;i++) particles.push(new Particle());
function animate(){
  ctx.clearRect(0,0,w,h);
  particles.forEach(p=>{ p.update(); p.draw(); });
  // lines
  for(let i=0;i<particles.length;i++){
    for(let j=i+1;j<particles.length;j++){
      const dx=particles[i].x-particles[j].x, dy=particles[i].y-particles[j].y;
      const dist=Math.sqrt(dx*dx+dy*dy);
      if(dist<130){
        ctx.beginPath(); ctx.moveTo(particles[i].x,particles[i].y); ctx.lineTo(particles[j].x,particles[j].y);
        ctx.strokeStyle=`rgba(99,102,241,${.08*(1-dist/130)})`; ctx.lineWidth=.5; ctx.stroke();
      }
    }
  }
  requestAnimationFrame(animate);
}
animate();

// ─── Navbar scroll ───
const navbar=document.getElementById('navbar');
let lastScroll=0;
addEventListener('scroll',()=>{
  const scrollY=scrollY;
  navbar.classList.toggle('scrolled',scrollY>60);
  lastScroll=scrollY;
},{passive:true});

// ─── Mobile nav ───
const navToggle=document.getElementById('nav-toggle');
const navLinks=document.getElementById('nav-links');
const overlay=document.getElementById('mobile-overlay');
function toggleNav(){ navLinks.classList.toggle('open'); navToggle.classList.toggle('open'); overlay.classList.toggle('show'); }
function closeNav(){ navLinks.classList.remove('open'); navToggle.classList.remove('open'); overlay.classList.remove('show'); }
navToggle.addEventListener('click',toggleNav);
overlay.addEventListener('click',closeNav);

// ─── Reveal on scroll ───
const revealEls=document.querySelectorAll('.reveal');
const revealIO=new IntersectionObserver((entries)=>{
  entries.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('show'); revealIO.unobserve(e.target); } });
},{threshold:.08,margin:'0px 0px -40px 0px'});
revealEls.forEach(el=>revealIO.observe(el));

// ─── Animated counters ───
const counters=document.querySelectorAll('[data-to]');
const countIO=new IntersectionObserver((entries)=>{
  entries.forEach(entry=>{
    if(!entry.isIntersecting) return;
    const el=entry.target; const target=+el.dataset.to; const suffix=el.dataset.suffix||'';
    let cur=0; const dur=1500; const step=Math.max(1,Math.floor(target/40));
    const start=performance.now();
    function tick(now){
      const t=Math.min(1,(now-start)/dur);
      cur=Math.round(t*target);
      el.textContent=cur+suffix;
      if(t<1) requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
    countIO.unobserve(el);
  });
},{threshold:.6});
counters.forEach(c=>countIO.observe(c));

// ─── Documentation tabs ───
function loadPaneImages(pane){
  pane.querySelectorAll('img[data-src]').forEach(img=>{
    img.classList.add('loading');
    img.addEventListener('load',()=>img.classList.remove('loading'));
    img.addEventListener('error',()=>img.classList.remove('loading'));
    img.src=img.dataset.src;
    img.removeAttribute('data-src');
  });
}
document.querySelectorAll('.doc-tab').forEach(tab=>{
  tab.addEventListener('click',()=>{
    const wasActive=tab.classList.contains('active');
    document.querySelectorAll('.doc-tab').forEach(t=>t.classList.remove('active'));
    document.querySelectorAll('.doc-pane').forEach(p=>p.classList.remove('active'));
    if(wasActive){
      return;
    }
    tab.classList.add('active');
    const pane=document.getElementById('pane-'+tab.dataset.tab);
    pane.classList.add('active');
    loadPaneImages(pane);
  });
});
document.querySelectorAll('.doc-pane.active').forEach(loadPaneImages);

// ─── Diagram lightbox ───
const lb=document.createElement('div');
lb.style.cssText='position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:9999;display:none;align-items:center;justify-content:center;cursor:zoom-out;';
lb.innerHTML='<img alt="Preview" style="max-width:95vw;max-height:95vh;border-radius:12px;box-shadow:0 20px 80px rgba(0,0,0,.6);">';
lb.addEventListener('click',()=>lb.style.display='none');
document.body.appendChild(lb);
function openDiagram(img){
  const big=lb.querySelector('img');
  big.src=img.getAttribute('src');
  lb.style.display='flex';
}
</script>
</body>
</html>