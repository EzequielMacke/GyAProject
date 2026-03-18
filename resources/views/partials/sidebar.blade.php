@if(session('sin_permiso'))
<div id="alerta-permiso" style="
    position: fixed; top: 1rem; right: 1rem; z-index: 9999;
    background: #fdeaea; border: 1.5px solid #f5bcbc; border-radius: 0.65rem;
    padding: 0.75rem 1rem; display: flex; align-items: center; gap: 0.6rem;
    font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.82rem;
    color: #d94040; font-weight: 600;
    box-shadow: 0 4px 18px rgba(217,64,64,0.15);
    animation: slideIn 0.2s ease;
    max-width: 320px;
">
    <i class="fas fa-lock" style="font-size: 0.85rem; flex-shrink: 0;"></i>
    <span>{{ session('sin_permiso') }}</span>
    <button onclick="document.getElementById('alerta-permiso').remove()" style="
        background: none; border: none; cursor: pointer; color: #d94040;
        font-size: 1rem; margin-left: auto; line-height: 1; padding: 0;
    ">×</button>
</div>
<style>
@keyframes slideIn {
    from { opacity: 0; transform: translateX(12px); }
    to   { opacity: 1; transform: none; }
}
</style>
<script>
setTimeout(() => { document.getElementById('alerta-permiso')?.remove(); }, 4000);
</script>
@endif

@php
    use App\Models\Modulo;
    use App\Models\Permiso;
    use App\Models\Pedido_para_obra;
    use App\Models\PresupuestoAprobado;
    $permisos = Permiso::where('area_id', session('usuario_area_id'))->get();
    $modulos = Modulo::all();
    $pedidosPendientes = Pedido_para_obra::where('estado', '1')->count();
    $presupuestoaprobados = PresupuestoAprobado::where('estado', '1')->count();
@endphp

<aside class="main-sidebar elevation-2" style="background: #f8fafc; border-right: 1.5px solid #d8e0ea;">
    {{-- Brand --}}
    <a href="{{ url('/home') }}" class="brand-link" style="text-decoration:none; border-bottom: 1.5px solid #d8e0ea; background: #f8fafc;">
        <span class="brand-text" style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 0.95rem; color: #1e2835; letter-spacing: -0.3px;">
            Gavilán <span style="color: #2a6fdb;">& Asoc.</span>
        </span>
    </a>

    <div class="sidebar" style="background: #f8fafc;">

        {{-- Usuario --}}
        <div style="padding: 1rem 1rem 0.75rem; border-bottom: 1px solid #edf1f6;">
            <div style="display: flex; align-items: center; gap: 0.65rem;">
                <div style="
                    width: 34px; height: 34px; border-radius: 50%;
                    background: #e8f0fc; border: 1.5px solid #c3d7f7;
                    display: flex; align-items: center; justify-content: center;
                    font-size: 0.78rem; font-weight: 700; color: #2a6fdb;
                    flex-shrink: 0; font-family: 'Plus Jakarta Sans', sans-serif;
                ">
                    {{ strtoupper(substr(session('usuario_nombre', 'U'), 0, 2)) }}
                </div>
                <div style="min-width: 0;">
                    <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.8rem; font-weight: 700; color: #1e2835; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ session('usuario_nombre') }}
                    </div>
                    <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.7rem; color: #8496aa; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ session('usuario_area') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Buscador global --}}
        <div style="padding: 0.9rem 0.85rem 0.5rem;">
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.65rem; font-weight: 700; color: #8496aa; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.5rem;">
                Búsqueda global
            </div>
            <div style="position: relative;">
                <i class="fas fa-search" style="position: absolute; left: 0.7rem; top: 50%; transform: translateY(-50%); color: #8496aa; font-size: 0.72rem; pointer-events: none;"></i>
                <input
                    type="text"
                    id="globalSearch"
                    placeholder="Obras, facturas, recibos…"
                    autocomplete="off"
                    style="
                        font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.78rem;
                        width: 100%; padding: 0.5rem 0.75rem 0.5rem 2rem;
                        border: 1.5px solid #d8e0ea; border-radius: 0.55rem;
                        background: #fff; color: #1e2835; outline: none;
                        transition: border-color 0.15s, box-shadow 0.15s;
                    "
                    onfocus="this.style.borderColor='#2a6fdb'; this.style.boxShadow='0 0 0 3px rgba(42,111,219,0.1)'"
                    onblur="this.style.borderColor='#d8e0ea'; this.style.boxShadow='none'"
                >
            </div>

            {{-- Resultados --}}
            <div id="searchResults" style="display: none; margin-top: 0.4rem; max-height: calc(100vh - 280px); overflow-y: auto;">
            </div>

            {{-- Estado vacío inicial --}}
            <div id="searchHint" style="margin-top: 0.6rem; text-align: center; padding: 1.5rem 0.5rem;">
                <i class="fas fa-search" style="font-size: 1.4rem; color: #d8e0ea; display: block; margin-bottom: 0.5rem;"></i>
                <span style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.73rem; color: #8496aa; line-height: 1.4;">
                    Escribí al menos 2 caracteres para buscar en obras, presupuestos, facturas y recibos
                </span>
            </div>
        </div>

        {{-- Footer: inicio + cerrar sesión --}}
        <div style="position: absolute; bottom: 0; left: 0; right: 0; border-top: 1.5px solid #edf1f6; background: #f8fafc;">
            <a href="{{ url('/home') }}" style="
                display: flex; align-items: center; gap: 0.55rem;
                padding: 0.7rem 1rem;
                font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.78rem; font-weight: 600;
                color: #445060; text-decoration: none;
                border-bottom: 1px solid #edf1f6;
                transition: background 0.14s, color 0.14s;
            "
            onmouseover="this.style.background='#edf1f6'; this.style.color='#1e2835'"
            onmouseout="this.style.background='transparent'; this.style.color='#445060'"
            >
                <i class="fas fa-home" style="font-size: 0.75rem; color: #2a6fdb; width: 16px; text-align: center;"></i>
                Menú Principal
            </a>
            <a href="{{ route('logout') }}" style="
                display: flex; align-items: center; gap: 0.55rem;
                padding: 0.7rem 1rem;
                font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.78rem; font-weight: 600;
                color: #d94040; text-decoration: none;
                transition: background 0.14s;
            "
            onmouseover="this.style.background='#fdeaea'"
            onmouseout="this.style.background='transparent'"
            onclick="event.preventDefault(); if(confirm('¿Cerrar sesión?')) { document.getElementById('logout-form').submit(); }"
            >
                <i class="fas fa-sign-out-alt" style="font-size: 0.75rem; width: 16px; text-align: center;"></i>
                Cerrar sesión
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
        </div>

    </div>
</aside>

<style>
    /* Quitar el padding-bottom del sidebar para que el footer fijo funcione */
    .main-sidebar .sidebar { padding-bottom: 90px !important; }

    /* Tipo badge en resultados */
    .sr-tipo {
        font-size: 0.6rem; font-weight: 700; padding: 0.1rem 0.45rem;
        border-radius: 99px; white-space: nowrap; font-family: 'Plus Jakarta Sans', sans-serif;
        text-transform: uppercase; letter-spacing: 0.04em;
    }
    .sr-obra        { background: #eeecf9; color: #7c6fcd; }
    .sr-presupuesto { background: #fef9ec; color: #d4920a; }
    .sr-factura     { background: #e8f0fc; color: #2a6fdb; }
    .sr-recibo      { background: #e5f6f0; color: #1e9166; }
    .sr-tableta     { background: #fff0eb; color: #d9622a; }
    .sr-contacto    { background: #e5f7fa; color: #0891a8; }

    .sr-item {
        display: flex; align-items: flex-start; gap: 0.55rem;
        padding: 0.5rem 0.6rem; border-radius: 0.5rem;
        text-decoration: none; color: #1e2835;
        transition: background 0.12s;
        margin-bottom: 2px;
    }
    .sr-item:hover { background: #edf1f6; color: #1e2835; }
    .sr-item-icon {
        width: 28px; height: 28px; border-radius: 0.4rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.68rem; flex-shrink: 0; margin-top: 1px;
    }
    .sr-item-label {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.78rem; font-weight: 600; color: #1e2835;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .sr-item-sub {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.7rem; color: #8496aa;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        margin-top: 0.05rem;
    }
    .sr-empty {
        text-align: center; padding: 1rem 0.5rem;
        font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.73rem; color: #8496aa;
    }
    .sr-group-label {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.62rem; font-weight: 700; color: #8496aa;
        text-transform: uppercase; letter-spacing: 0.06em;
        padding: 0.35rem 0.6rem 0.2rem;
    }

    /* Scrollbar fino */
    #searchResults::-webkit-scrollbar { width: 4px; }
    #searchResults::-webkit-scrollbar-track { background: transparent; }
    #searchResults::-webkit-scrollbar-thumb { background: #d8e0ea; border-radius: 99px; }
</style>

<script>
(function () {
    const input   = document.getElementById('globalSearch');
    const results = document.getElementById('searchResults');
    const hint    = document.getElementById('searchHint');
    const url     = '{{ route("search.global") }}';

    const typeConfig = {
        'Obra':        { cls: 'sr-obra',        icon: 'fa-hard-hat',            iconBg: '#eeecf9', iconColor: '#7c6fcd' },
        'Presupuesto': { cls: 'sr-presupuesto', icon: 'fa-file-invoice-dollar', iconBg: '#fef9ec', iconColor: '#d4920a' },
        'Factura':     { cls: 'sr-factura',     icon: 'fa-receipt',             iconBg: '#e8f0fc', iconColor: '#2a6fdb' },
        'Recibo':      { cls: 'sr-recibo',      icon: 'fa-money-bill-wave',     iconBg: '#e5f6f0', iconColor: '#1e9166' },
        'Tableta':     { cls: 'sr-tableta',     icon: 'fa-tablet-alt',          iconBg: '#fff0eb', iconColor: '#d9622a' },
        'Contacto':    { cls: 'sr-contacto',    icon: 'fa-address-book',        iconBg: '#e5f7fa', iconColor: '#0891a8' },
    };

    let debounce;

    input.addEventListener('input', function () {
        clearTimeout(debounce);
        const q = this.value.trim();

        if (q.length < 2) {
            results.style.display = 'none';
            hint.style.display = 'block';
            return;
        }

        debounce = setTimeout(() => {
            fetch(`${url}?q=${encodeURIComponent(q)}`)
                .then(r => r.json())
                .then(data => render(data))
                .catch(() => {});
        }, 280);
    });

    function render(items) {
        hint.style.display = 'none';
        results.style.display = 'block';
        results.innerHTML = '';

        if (!items.length) {
            results.innerHTML = `<div class="sr-empty"><i class="fas fa-search-minus" style="display:block; font-size:1.1rem; color:#d8e0ea; margin-bottom:0.4rem;"></i>Sin resultados</div>`;
            return;
        }

        // Agrupar por tipo
        const groups = {};
        items.forEach(item => {
            if (!groups[item.tipo]) groups[item.tipo] = [];
            groups[item.tipo].push(item);
        });

        Object.entries(groups).forEach(([tipo, list]) => {
            const cfg = typeConfig[tipo] || { cls: 'sr-factura', icon: 'fa-circle', iconBg: '#edf1f6', iconColor: '#8496aa' };

            const groupLabel = document.createElement('div');
            groupLabel.className = 'sr-group-label';
            groupLabel.innerHTML = `<span class="sr-tipo ${cfg.cls}">${tipo}s</span>`;
            results.appendChild(groupLabel);

            list.forEach(item => {
                const a = document.createElement('a');
                a.href = item.url;
                a.className = 'sr-item';
                a.innerHTML = `
                    <div class="sr-item-icon" style="background:${cfg.iconBg}; color:${cfg.iconColor};">
                        <i class="fas ${cfg.icon}"></i>
                    </div>
                    <div style="min-width:0; flex:1;">
                        <div class="sr-item-label">${item.label}</div>
                        ${item.sub ? `<div class="sr-item-sub">${item.sub}</div>` : ''}
                    </div>
                `;
                results.appendChild(a);
            });
        });
    }

    // Cerrar resultados al hacer click fuera
    document.addEventListener('click', function (e) {
        if (!input.contains(e.target) && !results.contains(e.target)) {
            results.style.display = 'none';
            hint.style.display = 'block';
            input.value = '';
        }
    });
})();
</script>
