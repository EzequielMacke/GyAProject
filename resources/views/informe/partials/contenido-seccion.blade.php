{{-- Descripción + contenido (planos, fotos, planillas y textos en orden) de
     una sección del informe. Los campos llegan como {seccion}_descripcion y
     {seccion}_elementos[k][...]; el JS del generador arma los elementos.
     Parámetros: $seccion y, opcionales:
       - $trabajosWord: si la sección va al Word (va si alguno de esos
         trabajos está activado; también decide si se arman sus imágenes).
       - $placeholder y $ayuda de la descripción; sin $placeholder no se
         muestra la descripción (la sección la tiene en otro lado).
       - $botones: qué se puede agregar (plano, fotos, planilla, texto).
       - $planilla: qué planilla agrega el botón "planilla" (InformeTc::PLANILLAS).
       - $tituloContenido y $ayudaContenido. --}}
@php
    $botones ??= ['plano', 'fotos', 'texto'];
    $textosBotones = [
        'plano' => ['fa-map', 'Agregar plano'],
        'fotos' => ['fa-image', 'Agregar fotos'],
        'planilla' => ['fa-table', 'Agregar planilla'],
        'texto' => ['fa-paragraph', 'Agregar texto'],
    ];
@endphp
<div data-seccion="{{ $seccion }}"
     @isset($trabajosWord) data-trabajos-word="{{ implode(',', $trabajosWord) }}" @endisset
     @isset($planilla) data-planilla="{{ $planilla }}" @endisset
     data-guardados="{{ json_encode(array_values((array) ($datos[$seccion . '_elementos'] ?? []))) }}">
    @isset($placeholder)
        <div class="campo">
            <label for="{{ $seccion }}-descripcion">Descripción</label>
            <textarea class="form-control" id="{{ $seccion }}-descripcion" name="{{ $seccion }}_descripcion" rows="5" placeholder="{{ $placeholder }}">{{ $datos[$seccion . '_descripcion'] ?? '' }}</textarea>
            <small class="ayuda">{{ $ayuda }}</small>
        </div>
    @endisset
    <div class="grupo-titulo mt-4 mb-2">{{ $tituloContenido ?? 'Contenido' }}</div>
    <div data-elementos></div>
    <div class="rg-botones">
        @foreach ($botones as $boton)
            <button type="button" class="btn btn-sm" data-agregar="{{ $boton }}">
                <i class="fas {{ $textosBotones[$boton][0] }}"></i> {{ $textosBotones[$boton][1] }}
            </button>
        @endforeach
    </div>
    <small class="ayuda d-block mt-2">{{ $ayudaContenido ?? 'Van después de la descripción, en este orden (se puede cambiar con las flechas). Cada plano lleva su propio pie y sale con las capas que estén prendidas.' }}</small>
</div>
