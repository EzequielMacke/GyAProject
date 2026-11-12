{{-- Descripción + fotografías de una sección del informe (los bloques de
     fotos funcionan como en Características de la obra). Los campos llegan
     como {seccion}_descripcion y {seccion}_fotos[k][ids|pie]; el JS del
     generador arma los bloques. Parámetros: $seccion, $placeholder y $ayuda. --}}
<div data-seccion-fotos="{{ $seccion }}"
     data-guardados="{{ json_encode(array_values((array) ($datos[$seccion . '_fotos'] ?? []))) }}">
    <div class="campo">
        <label for="{{ $seccion }}-descripcion">Descripción</label>
        <textarea class="form-control" id="{{ $seccion }}-descripcion" name="{{ $seccion }}_descripcion" rows="5" placeholder="{{ $placeholder }}">{{ $datos[$seccion . '_descripcion'] ?? '' }}</textarea>
        <small class="ayuda">{{ $ayuda }}</small>
    </div>
    <div class="grupo-titulo mt-4 mb-2">Fotografía</div>
    <div data-bloques-fotos></div>
    <button type="button" class="btn btn-sm mt-2" data-agregar-fotos>
        <i class="fas fa-image"></i> Agregar imagen
    </button>
    <small class="ayuda d-block mt-2">Cada vez que agregás imágenes se crea un bloque: si elegís varias fotos podés agregarlas juntas con un solo pie, o por separado (una por bloque, cada una con su pie).</small>
</div>
