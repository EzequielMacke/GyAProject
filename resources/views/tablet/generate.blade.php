<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Códigos QR de Tabletas</title>
    @include('partials.head')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <style>
        :root {
            --bg:       #f0f3f7;
            --bg2:      #e4e9f0;
            --surface:  #f8fafc;
            --surface2: #edf1f6;
            --border:   #d8e0ea;
            --border2:  #c4cfdc;
            --text:     #1e2835;
            --text2:    #445060;
            --muted:    #8496aa;
            --accent:   #2a6fdb;
            --accent-s: #e8f0fc;
            --accent-b: #1f5bbf;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        .content-wrapper { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg) !important; }

        /* ── Header ── */
        .ph {
            padding: 1.75rem 0 1.5rem;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }
        .ph-crumb {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.72rem;
            font-weight: 500;
            color: var(--muted);
            margin-bottom: 0.5rem;
        }
        .ph-crumb a { color: var(--muted); text-decoration: none; }
        .ph-crumb a:hover { color: var(--accent); }
        .ph-crumb i { font-size: 0.58rem; }
        .ph-title { font-size: 1.65rem; font-weight: 700; color: var(--text); letter-spacing: -0.4px; line-height: 1.1; }
        .ph-title em { font-style: normal; color: var(--accent); }
        .ph-sub { font-size: 0.8rem; color: var(--muted); margin-top: 0.3rem; }
        .ph-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

        /* ── Buttons ── */
        .btn {
            height: 38px;
            padding: 0 1rem;
            border-radius: 0.55rem;
            display: inline-flex;
            align-items: center;
            gap: 0.42rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.825rem;
            font-weight: 600;
            border: 1.5px solid var(--border);
            background: var(--surface);
            color: var(--text2);
            text-decoration: none;
            cursor: pointer;
            transition: all 0.14s;
            white-space: nowrap;
        }
        .btn:hover { background: var(--surface2); border-color: var(--border2); color: var(--text); }
        .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }
        .btn-primary:hover {
            background: var(--accent-b); border-color: var(--accent-b); color: #fff;
            box-shadow: 0 4px 14px rgba(42,111,219,0.3);
        }

        /* ── QR Grid ── */
        .qr-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
            gap: 1rem;
        }

        .qr-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 0.85rem;
            padding: 1.25rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            text-align: center;
        }

        .qr-canvas-wrap {
            background: #fff;
            border: 1.5px solid var(--border);
            border-radius: 0.55rem;
            padding: 0.6rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-label {
            font-family: 'DM Mono', monospace;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: 0.5px;
        }

        .qr-sublabel {
            font-size: 0.7rem;
            color: var(--muted);
            margin-top: -0.4rem;
        }

        .qr-admin .qr-canvas-wrap {
            border-color: var(--accent);
            background: var(--accent-s);
        }

        .qr-admin .qr-label { color: var(--accent); }

        /* ── Print ── */
        @media print {
            .no-print { display: none !important; }
            body { background: #fff; }
            .content-wrapper { background: #fff !important; }
            .qr-grid { gap: 0.5rem; }
            .qr-card {
                border: 1px solid #ccc;
                box-shadow: none;
                break-inside: avoid;
            }
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    @include('partials.navbar')
    @include('partials.sidebar')

    <div class="content-wrapper">
        <div class="content-header no-print">
            <div class="container-fluid">
                <div class="ph">
                    <div>
                        <div class="ph-crumb">
                            <i class="fas fa-home"></i> Inicio
                            <i class="fas fa-chevron-right"></i>
                            <a href="{{ route('tabletas.index') }}">Tabletas</a>
                            <i class="fas fa-chevron-right"></i> Códigos QR
                        </div>
                        <h1 class="ph-title">Códigos <em>QR</em></h1>
                        <p class="ph-sub">Imprimí los códigos QR de cada tableta</p>
                    </div>
                    <div class="ph-right">
                        <button id="btn-print" onclick="generarPDFs()" class="btn btn-primary">
                            <i class="fas fa-print"></i> Imprimir
                        </button>
                        <a href="{{ route('tabletas.index') }}" class="btn">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="qr-grid">

                    {{-- QR Administrador --}}
                    <div class="qr-card qr-admin">
                        <div class="qr-canvas-wrap">
                            <canvas id="qr-admin"></canvas>
                        </div>
                        <div class="qr-label">ADMINISTRADOR</div>
                        <div class="qr-sublabel">Acceso de administrador</div>
                    </div>

                    @foreach($qrs as $tableta)
                    <div class="qr-card">
                        <div class="qr-canvas-wrap">
                            <canvas id="qr-{{ $tableta->id }}"></canvas>
                        </div>
                        <div class="qr-label">{{ $tableta->clave ?? $tableta->id }}</div>
                        <div class="qr-sublabel">{{ $tableta->nombre ?? '' }}</div>
                    </div>
                    @endforeach

                </div>
            </div>
        </section>
    </div>

    @include('partials.footer')
</div>

<script>
// Paleta idéntica a los CSS variables de la vista
const CSS = {
    surface:  [248, 250, 252], // --surface:  #f8fafc
    border:   [216, 224, 234], // --border:   #d8e0ea
    text:     [30,  40,  53],  // --text:     #1e2835
    muted:    [132, 150, 170], // --muted:    #8496aa
    accent:   [42,  111, 219], // --accent:   #2a6fdb
    accentS:  [232, 240, 252], // --accent-s: #e8f0fc
};

document.addEventListener('DOMContentLoaded', function () {
    new QRious({ element: document.getElementById('qr-admin'), value: "9XQ2Z7LJ4B1V6KTP", size: 150, background: 'transparent', foreground: '#2a6fdb' });
    @foreach($qrs as $tableta)
    new QRious({ element: document.getElementById('qr-{{ $tableta->id }}'), value: @json($tableta->codigo_qr), size: 150, background: 'white', foreground: '#1e2835' });
    @endforeach
});

const QR_CARDS = [
    { value: "9XQ2Z7LJ4B1V6KTP", label: "ADMINISTRADOR", sublabel: "Acceso de administrador", isAdmin: true },
    @foreach($qrs as $tableta)
    { value: @json($tableta->codigo_qr), label: @json($tableta->clave ?? $tableta->id), sublabel: @json($tableta->nombre ?? ''), isAdmin: false },
    @endforeach
];

function makeQRDataURL(value, isAdmin) {
    const c = document.createElement('canvas');
    new QRious({ element: c, value: value, size: 400, background: 'white', foreground: isAdmin ? '#2a6fdb' : '#1e2835' });
    return c.toDataURL('image/png');
}

async function generarPDFs() {
    const { jsPDF } = window.jspdf;
    const btn = document.getElementById('btn-print');
    btn.disabled = true;

    const W = 57, H = 90;
    const zip = new JSZip();

    for (let i = 0; i < QR_CARDS.length; i++) {
        const card = QR_CARDS[i];
        btn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> ${i + 1} / ${QR_CARDS.length}`;

        const doc = new jsPDF({ orientation: 'portrait', unit: 'mm', format: [W, H] });

        // ── Fondo: --surface #f8fafc ──
        doc.setFillColor(...CSS.surface);
        doc.roundedRect(0, 0, W, H, 4, 4, 'F');

        // ── QR canvas wrap (replica el .qr-canvas-wrap) ──
        // Proporciones CSS: padding 1rem=4mm lateral, wrap padding 0.6rem≈2.5mm, radius 0.55rem≈2mm
        const cardPad = 4;
        const wrapPad = 2.5;
        const wrapX   = cardPad;
        const wrapW   = W - cardPad * 2;   // 49mm
        const wrapY   = 22;                 // desplazado abajo, deja espacio para perforación
        const wrapH   = wrapW;              // cuadrado

        // ── Círculo indicador de perforación ──
        doc.setFillColor(...CSS.accentS);
        doc.setDrawColor(...CSS.border);
        doc.setLineWidth(0.5);
        doc.circle(W / 2, 7, 3.5, 'FD');

        // Admin: fondo --accent-s + borde --accent | Regular: fondo blanco + borde --border
        doc.setFillColor(...(card.isAdmin ? CSS.accentS : [255, 255, 255]));
        doc.setDrawColor(...(card.isAdmin ? CSS.accent  : CSS.border));
        doc.setLineWidth(0.5);
        doc.roundedRect(wrapX, wrapY, wrapW, wrapH, 2, 2, 'FD');

        // QR dentro del wrap con padding interior
        const qrSize = wrapW - wrapPad * 2;   // 44mm
        doc.addImage(
            makeQRDataURL(card.value, card.isAdmin),
            'PNG',
            wrapX + wrapPad, wrapY + wrapPad,
            qrSize, qrSize
        );

        // ── Etiqueta: .qr-label (DM Mono → Courier bold, letter-spacing 0.5px) ──
        const gap    = 3;   // ≈ 0.75rem flex gap
        const labelY = wrapY + wrapH + gap;   // ≈ 67mm

        doc.setTextColor(...(card.isAdmin ? CSS.accent : CSS.text));
        doc.setFont('courier', 'bold');
        doc.setFontSize(9.5);
        doc.setCharSpace(0.4);
        doc.text(doc.splitTextToSize(card.label, wrapW), W / 2, labelY, { align: 'center' });
        doc.setCharSpace(0);

        // ── Sub-etiqueta: .qr-sublabel ──
        if (card.sublabel) {
            doc.setTextColor(...CSS.muted);
            doc.setFont('helvetica', 'normal');
            doc.setFontSize(8);
            doc.text(doc.splitTextToSize(card.sublabel, wrapW), W / 2, labelY + 4.5, { align: 'center' });
        }

        // ── Borde exterior: border 1.5px --border, border-radius 0.85rem≈4mm ──
        doc.setDrawColor(...CSS.border);
        doc.setLineWidth(0.5);
        doc.roundedRect(0.5, 0.5, W - 1, H - 1, 3.5, 3.5, 'S');

        zip.file(`QR_${card.label.replace(/[^a-zA-Z0-9_\-]/g, '_')}.pdf`, doc.output('arraybuffer'));
    }

    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Empaquetando…';
    const blob = await zip.generateAsync({ type: 'blob' });
    saveAs(blob, 'QR_Tabletas.zip');

    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-check"></i> Descargado';
    setTimeout(() => { btn.innerHTML = '<i class="fas fa-print"></i> Imprimir'; }, 3000);
}
</script>
</body>
</html>
