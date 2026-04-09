<style>
    *, *::before, *::after { box-sizing: border-box; }

    :root {
        --bg:       #F7F8FA;
        --surface:  #FFFFFF;
        --border:   #EEF0F4;
        --border-2: #E4E7ED;
        --text-1:   #0F172A;
        --text-2:   #475569;
        --text-3:   #94A3B8;
        --text-4:   #CBD5E1;
        --accent:   #F97316;
        --accent-2: #FB923C;
        --accent-bg:#FFF7ED;
        --green:    #10B981;
        --amber:    #F59E0B;
        --blue:     #3B82F6;
        --violet:   #8B5CF6;
        --red:      #EF4444;
        --r-card:   18px;
        --r-inner:  12px;
        --shadow-sm: 0 2px 6px rgba(15,23,42,0.04), 0 1px 2px rgba(15,23,42,0.03);
        --shadow-md: 0 6px 18px rgba(15,23,42,0.06), 0 2px 4px rgba(15,23,42,0.03);
        --shadow-lg: 0 12px 40px rgba(15,23,42,0.08), 0 4px 8px rgba(15,23,42,0.04);
    }

    body {
        font-family: 'Outfit', sans-serif;
        background: var(--bg);
        color: var(--text-1);
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    .mono { font-family: 'JetBrains Mono', monospace; }

    /* ── Period Selector ── */
    .period-wrap {
        display: flex; gap: 4px; padding: 4px;
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 14px; box-shadow: var(--shadow-sm);
    }
    .period-pill {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 46px; padding: 6px 12px; border-radius: 10px;
        font-size: 11px; font-weight: 700; letter-spacing: 0.02em;
        color: var(--text-2); background: transparent;
        text-decoration: none; transition: all 0.2s ease; white-space: nowrap;
    }
    .period-pill:hover { background: var(--bg); color: var(--text-1); }
    .period-pill.active {
        background: var(--accent); color: #fff;
        box-shadow: 0 4px 10px rgba(249,115,22,0.25);
    }

    /* ── Cards ── */
    .card {
        background: var(--surface); border-radius: var(--r-card);
        border: 1px solid var(--border); box-shadow: var(--shadow-sm);
        transition: box-shadow 0.2s ease;
    }
    .card:hover { box-shadow: var(--shadow-md); }
    .card-inner { padding: 22px 24px; }

    .kpi-card {
        background: var(--surface); border-radius: var(--r-card);
        border: 1px solid var(--border); box-shadow: var(--shadow-sm);
        padding: 20px 22px; position: relative; overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }
    .kpi-accent-line {
        position: absolute; top: 0; left: 0; right: 0; height: 3px;
        border-radius: var(--r-card) var(--r-card) 0 0;
    }

    /* ── Revenue Cards ── */
    .rev-card {
        background: var(--surface); border-radius: var(--r-card);
        border: 1px solid var(--border); box-shadow: var(--shadow-sm);
        padding: 24px 26px; position: relative; overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .rev-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }

    /* ── Section Title ── */
    .section-title {
        font-size: 11px; font-weight: 800; letter-spacing: 0.12em;
        text-transform: uppercase; color: var(--text-3); margin-bottom: 0;
    }

    /* ── Badge ── */
    .badge {
        display: inline-flex; align-items: center;
        padding: 4px 10px; border-radius: 8px;
        font-size: 10px; font-weight: 700; letter-spacing: 0.03em;
        line-height: 1;
    }

    /* ── Progress ── */
    .prog-track {
        height: 5px; background: var(--bg); border-radius: 999px;
        overflow: hidden; margin-top: 6px;
    }
    .prog-fill {
        height: 100%; border-radius: 999px;
        transition: width 0.7s cubic-bezier(0.34,1.56,0.64,1);
    }

    /* ── Funnel ── */
    .funnel-row { display: flex; align-items: center; gap: 12px; }
    .funnel-bar-wrap {
        flex: 1; height: 32px; background: var(--bg); border-radius: 10px;
        overflow: hidden; position: relative;
    }
    .funnel-fill {
        height: 100%; border-radius: 10px;
        transition: width 0.8s cubic-bezier(0.34,1.56,0.64,1);
        display: flex; align-items: center; padding-left: 12px; min-width: fit-content;
    }
    .funnel-text {
        font-size: 11px; font-weight: 700; color: #fff; white-space: nowrap;
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    .funnel-pct {
        position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
        font-size: 11px; font-weight: 600; color: var(--text-3); pointer-events: none;
    }

    /* ── Status dot ── */
    .s-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }

    /* ── Order row ── */
    .order-row {
        display: flex; align-items: center; justify-content: space-between; gap: 12px;
        padding: 12px 14px; border-radius: var(--r-inner); background: var(--bg);
        transition: background 0.15s, transform 0.1s;
        border: 1px solid transparent;
    }
    .order-row:hover {
        background: #F8FAFC;
        border-color: var(--border);
        transform: scale(1.01);
    }

    /* ── Send button ── */
    .send-btn {
        display: inline-flex; align-items: center; gap: 8px;
        background: linear-gradient(145deg, #059669, #10B981);
        color: #fff; font-family: 'Outfit', sans-serif; font-weight: 700;
        font-size: 12px; padding: 10px 18px; border-radius: 12px;
        border: none; cursor: pointer; white-space: nowrap;
        box-shadow: 0 6px 14px rgba(5,150,105,0.25);
        transition: all 0.2s ease;
        letter-spacing: 0.3px;
    }
    .send-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(5,150,105,0.3);
    }
    .send-btn:active { transform: scale(0.97); }
    .send-btn:disabled { opacity: 0.55; cursor: not-allowed; transform: none; }

    /* ── Customer mini-card ── */
    .cust-card {
        border-radius: 14px; padding: 18px; text-align: center;
        border: 1px solid var(--border); background: #fff;
        box-shadow: var(--shadow-sm);
    }

    /* ── Empty state ── */
    .empty-state {
        display: flex; flex-direction: column; align-items: center;
        justify-content: center; padding: 44px 0; gap: 10px;
    }
    .empty-state .empty-icon { font-size: 36px; opacity: 0.4; }
    .empty-state p { font-size: 13px; font-weight: 600; color: var(--text-3); margin: 0; }

    /* ── Animate ── */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .au { animation: fadeUp 0.5s ease both; }
    .d1 { animation-delay: 0.05s; } .d2 { animation-delay: 0.10s; }
    .d3 { animation-delay: 0.15s; } .d4 { animation-delay: 0.20s; }
    .d5 { animation-delay: 0.25s; } .d6 { animation-delay: 0.30s; }

    /* ── Divider ── */
    .divider { height: 1px; background: var(--border); margin: 16px 0; }

    /* ── Chart tooltip style ── */
    .chartjs-tooltip { font-family: 'Outfit', sans-serif !important; }

    /* ── Scrollbar ── */
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-thumb { background: var(--border-2); border-radius: 5px; }

    /* ── Icon box ── */
    .icon-box {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; flex-shrink: 0;
        transition: transform 0.1s ease;
    }
    .card:hover .icon-box { transform: scale(1.02); }

    /* ── Trending tag ── */
    .trend-tag {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 9px; border-radius: 8px;
        font-size: 10px; font-weight: 700; letter-spacing: 0.3px;
        background: var(--accent-bg); color: var(--accent);
        border: 1px solid rgba(249,115,22,0.15);
    }

    /* ── Additional Polishing ── */
    .bg-slate-50 { background-color: #F8FAFC; }
    .rounded-2xl { border-radius: 18px; }
    .shadow-soft { box-shadow: var(--shadow-sm); }
    .hover-lift:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }

    /* Responsive fine-tuning */
    @media (max-width: 640px) {
        .card-inner { padding: 18px 16px; }
        .rev-card { padding: 20px 18px; }
        .kpi-card { padding: 16px 18px; }
        .period-pill { min-width: 40px; padding: 5px 8px; font-size: 10px; }
    }
</style>