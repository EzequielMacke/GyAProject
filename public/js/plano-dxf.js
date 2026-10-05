/* Generador mínimo de DXF (AutoCAD 2013, AC1027) para la descarga del
   plano con sus anotaciones. No es una librería genérica: escribe solo
   lo que necesita esa descarga (capas, polilíneas, sombreados sólidos,
   textos, bloques/inserciones y una imagen de fondo vinculada como
   archivo externo).

   La estructura de tablas/objetos replica la que genera ezdxf para un
   documento R2013 vacío (lo mínimo que AutoCAD abre sin pedir
   recuperación). Se eligió AC1027 porque desde R2007 el DXF va en
   UTF-8, así que los textos con acentos/ñ no necesitan escaparse. */
(function (global) {
    'use strict';

    function numero(v) {
        if (!Number.isFinite(v)) return '0.0';
        const s = (Math.round(v * 1e6) / 1e6).toString();
        return s.includes('.') || s.includes('e') ? s : s + '.0';
    }

    /* Los nombres de capa/bloque no admiten < > / \ " : ; ? * | = ` */
    function nombreSeguro(nombre) {
        return String(nombre).replace(/[<>\/\\":;?*|=`,]+/g, '_').trim() || 'SIN_NOMBRE';
    }

    function colorVerdadero(hex) {
        const limpio = String(hex || '#000000').replace('#', '');
        const normalizado = limpio.length === 3
            ? limpio.split('').map(c => c + c).join('')
            : limpio.padStart(6, '0');
        return parseInt(normalizado, 16) || 0;
    }

    /* Partes fijas copiadas tal cual de un documento R2013 vacío generado
       con ezdxf (clases, tipos de línea, estilo de cota y los objetos
       base: diccionarios estándar, layouts, materiales, estilos de
       multilínea/directriz, placeholder de estilo de trazado). AutoCAD
       rechaza el archivo entero ("presione ENTRAR para continuar") si
       falta alguno de estos o si las capas no apuntan al estilo de
       trazado/material, así que no conviene recortarlas a mano.
       Formato: un objeto por línea, pares código|valor separados por |.
       Sus handles (1 a 2C) son los mismos que usa el objeto H de abajo. */
    const CLASES_BASE = `
        0|CLASS|1|ACDBDICTIONARYWDFLT|2|AcDbDictionaryWithDefault|3|ObjectDBX Classes|90|0|91|0|280|0|281|0
        0|CLASS|1|SUN|2|AcDbSun|3|SCENEOE|90|1153|91|0|280|0|281|0
        0|CLASS|1|VISUALSTYLE|2|AcDbVisualStyle|3|ObjectDBX Classes|90|4095|91|0|280|0|281|0
        0|CLASS|1|MATERIAL|2|AcDbMaterial|3|ObjectDBX Classes|90|1153|91|0|280|0|281|0
        0|CLASS|1|SCALE|2|AcDbScale|3|ObjectDBX Classes|90|1153|91|0|280|0|281|0
        0|CLASS|1|TABLESTYLE|2|AcDbTableStyle|3|ObjectDBX Classes|90|4095|91|0|280|0|281|0
        0|CLASS|1|MLEADERSTYLE|2|AcDbMLeaderStyle|3|ACDB_MLEADERSTYLE_CLASS|90|4095|91|0|280|0|281|0
        0|CLASS|1|DICTIONARYVAR|2|AcDbDictionaryVar|3|ObjectDBX Classes|90|0|91|0|280|0|281|0
        0|CLASS|1|CELLSTYLEMAP|2|AcDbCellStyleMap|3|ObjectDBX Classes|90|1152|91|0|280|0|281|0
        0|CLASS|1|MENTALRAYRENDERSETTINGS|2|AcDbMentalRayRenderSettings|3|SCENEOE|90|1024|91|0|280|0|281|0
        0|CLASS|1|ACDBDETAILVIEWSTYLE|2|AcDbDetailViewStyle|3|ObjectDBX Classes|90|1025|91|0|280|0|281|0
        0|CLASS|1|ACDBSECTIONVIEWSTYLE|2|AcDbSectionViewStyle|3|ObjectDBX Classes|90|1025|91|0|280|0|281|0
        0|CLASS|1|RASTERVARIABLES|2|AcDbRasterVariables|3|ISM|90|0|91|0|280|0|281|0
        0|CLASS|1|ACDBPLACEHOLDER|2|AcDbPlaceHolder|3|ObjectDBX Classes|90|0|91|0|280|0|281|0
        0|CLASS|1|LAYOUT|2|AcDbLayout|3|ObjectDBX Classes|90|0|91|0|280|0|281|0`;
    const OBJETOS_BASE = `
        0|DICTIONARY|5|B|330|A|100|AcDbDictionary|281|1
        0|DICTIONARY|5|C|330|A|100|AcDbDictionary|281|1
        0|DICTIONARY|5|D|330|A|100|AcDbDictionary|281|1|3|Model|350|1A|3|Layout1|350|1E
        0|DICTIONARY|5|E|330|A|100|AcDbDictionary|281|1|3|ByBlock|350|1F|3|ByLayer|350|20|3|Global|350|21
        0|DICTIONARY|5|F|330|A|100|AcDbDictionary|281|1|3|Standard|350|2C
        0|DICTIONARY|5|10|330|A|100|AcDbDictionary|281|1|3|Standard|350|22
        0|DICTIONARY|5|11|330|A|100|AcDbDictionary|281|1
        0|ACDBDICTIONARYWDFLT|5|12|330|A|100|AcDbDictionary|281|1|3|Normal|350|13|100|AcDbDictionaryWithDefault|340|13
        0|ACDBPLACEHOLDER|5|13|330|12
        0|DICTIONARY|5|14|330|A|100|AcDbDictionary|281|1
        0|DICTIONARY|5|15|330|A|100|AcDbDictionary|281|1
        0|DICTIONARY|5|16|330|A|100|AcDbDictionary|281|1
        0|LAYOUT|5|1A|330|D|100|AcDbPlotSettings|1||4|A3|6||40|7.5|41|20.0|42|7.5|43|20.0|44|420.0|45|297.0|46|0.0|47|0.0|48|0.0|49|0.0|140|0.0|141|0.0|142|1.0|143|1.0|70|1024|72|1|73|0|74|5|7||75|16|76|0|77|2|78|300|147|1.0|148|0.0|149|0.0|100|AcDbLayout|1|Model|70|1|71|0|10|0.0|20|0.0|11|420.0|21|297.0|12|0.0|22|0.0|32|0.0|14|1e+20|24|1e+20|34|1e+20|15|-1e+20|25|-1e+20|35|-1e+20|146|0.0|13|0.0|23|0.0|33|0.0|16|1.0|26|0.0|36|0.0|17|0.0|27|1.0|37|0.0|76|1|330|17
        0|LAYOUT|5|1E|330|D|100|AcDbPlotSettings|1||4|A3|6||40|7.5|41|20.0|42|7.5|43|20.0|44|420.0|45|297.0|46|0.0|47|0.0|48|0.0|49|0.0|140|0.0|141|0.0|142|1.0|143|1.0|70|0|72|1|73|0|74|5|7||75|16|76|0|77|2|78|300|147|1.0|148|0.0|149|0.0|100|AcDbLayout|1|Layout1|70|1|71|1|10|0.0|20|0.0|11|420.0|21|297.0|12|0.0|22|0.0|32|0.0|14|1e+20|24|1e+20|34|1e+20|15|-1e+20|25|-1e+20|35|-1e+20|146|0.0|13|0.0|23|0.0|33|0.0|16|1.0|26|0.0|36|0.0|17|0.0|27|1.0|37|0.0|76|1|330|1B
        0|MATERIAL|5|1F|102|{ACAD_REACTORS|330|E|102|}|330|E|100|AcDbMaterial|1|ByBlock|2||70|0|40|1.0|71|1|41|1.0|91|-1023410177|42|1.0|72|1|3||73|1|74|1|75|1|44|0.5|73|0|45|1.0|46|1.0|77|1|4||78|1|79|1|170|1|48|1.0|171|1|6||172|1|173|1|174|1|140|1.0|141|1.0|175|1|7||176|1|177|1|178|1|143|1.0|179|1|8||270|1|271|1|272|1|145|1.0|146|1.0|273|1|9||274|1|275|1|276|1|42|1.0|72|1|3||73|1|74|1|75|1|94|63
        0|MATERIAL|5|20|102|{ACAD_REACTORS|330|E|102|}|330|E|100|AcDbMaterial|1|ByLayer|2||70|0|40|1.0|71|1|41|1.0|91|-1023410177|42|1.0|72|1|3||73|1|74|1|75|1|44|0.5|73|0|45|1.0|46|1.0|77|1|4||78|1|79|1|170|1|48|1.0|171|1|6||172|1|173|1|174|1|140|1.0|141|1.0|175|1|7||176|1|177|1|178|1|143|1.0|179|1|8||270|1|271|1|272|1|145|1.0|146|1.0|273|1|9||274|1|275|1|276|1|42|1.0|72|1|3||73|1|74|1|75|1|94|63
        0|MATERIAL|5|21|102|{ACAD_REACTORS|330|E|102|}|330|E|100|AcDbMaterial|1|Global|2||70|0|40|1.0|71|1|41|1.0|91|-1023410177|42|1.0|72|1|3||73|1|74|1|75|1|44|0.5|73|0|45|1.0|46|1.0|77|1|4||78|1|79|1|170|1|48|1.0|171|1|6||172|1|173|1|174|1|140|1.0|141|1.0|175|1|7||176|1|177|1|178|1|143|1.0|179|1|8||270|1|271|1|272|1|145|1.0|146|1.0|273|1|9||274|1|275|1|276|1|42|1.0|72|1|3||73|1|74|1|75|1|94|63
        0|MLINESTYLE|5|22|102|{ACAD_REACTORS|330|10|102|}|330|10|100|AcDbMlineStyle|2|Standard|70|0|3||62|256|51|90.0|52|90.0|71|2|49|0.5|62|256|6|BYLAYER|49|-0.5|62|256|6|BYLAYER
        0|MLEADERSTYLE|5|2C|102|{ACAD_REACTORS|330|F|102|}|330|F|100|AcDbMLeaderStyle|179|2|170|2|171|1|172|0|90|2|40|0.0|41|0.0|173|1|91|-1056964608|92|-2|290|1|42|2.0|291|1|43|8.0|3|Standard|44|4.0|300||342|29|174|1|175|1|176|0|178|1|93|-1056964608|45|4.0|292|0|297|0|46|4.0|94|-1056964608|47|1.0|49|1.0|140|1.0|294|1|141|0.0|177|0|142|1.0|295|0|296|0|143|3.75|271|0|272|9|273|9`;
    const TIPOS_LINEA_BASE = `
        0|LTYPE|5|24|330|2|100|AcDbSymbolTableRecord|100|AcDbLinetypeTableRecord|2|ByBlock|70|0|3||72|65|73|0|40|0.0
        0|LTYPE|5|25|330|2|100|AcDbSymbolTableRecord|100|AcDbLinetypeTableRecord|2|ByLayer|70|0|3||72|65|73|0|40|0.0
        0|LTYPE|5|26|330|2|100|AcDbSymbolTableRecord|100|AcDbLinetypeTableRecord|2|Continuous|70|0|3||72|65|73|0|40|0.0`;
    const DIMSTYLE_BASE = `
        0|DIMSTYLE|105|2B|330|4|100|AcDbSymbolTableRecord|100|AcDbDimStyleTableRecord|2|Standard|70|0|40|1.0|41|2.5|42|0.625|43|3.75|44|1.25|45|0.0|46|0.0|47|0.0|48|0.0|49|2.5|140|2.5|141|2.5|142|0.0|143|0.03937007874|144|1.0|145|0.0|146|1.0|147|0.625|148|0.0|69|0|70|0|71|0|72|0|73|0|74|0|75|0|76|0|77|1|78|8|79|3|170|0|171|3|172|1|173|0|174|0|175|0|176|0|177|0|178|0|179|2|271|2|272|2|273|2|274|3|275|0|276|0|277|2|278|44|279|0|280|0|281|0|282|0|283|0|284|8|285|0|286|0|288|0|289|3|290|0|371|-2|372|-2`;

    function leerPlantilla(texto) {
        return texto.trim().split('\n').map(linea => {
            const partes = linea.trim().split('|');
            const pares = [];
            for (let i = 0; i < partes.length; i += 2) pares.push([partes[i], partes[i + 1]]);
            return pares;
        });
    }

    function crearDxf({ insunits = 4 } = {}) {
        let siguienteHandle = 0x30;
        const nuevoHandle = () => (siguienteHandle++).toString(16).toUpperCase();

        /* Handles fijos de la estructura base (tablas, diccionarios,
           layouts y espacios modelo/papel). */
        const H = {
            tablaVport: '8', tablaLtype: '2', tablaLayer: '1', tablaStyle: '5',
            tablaView: '7', tablaUcs: '6', tablaAppid: '3', tablaDimstyle: '4',
            tablaBlockRecord: '9',
            dicRaiz: 'A',
            brModelo: '17', bloqueModelo: '18', finModelo: '19', layoutModelo: '1A',
            brPapel: '1B', bloquePapel: '1C', finPapel: '1D', layoutPapel: '1E',
            materialGlobal: '21', placeholderNormal: '13',
            vport: '23', capa0: '27', capaDefpoints: '28', estiloStandard: '29', appAcad: '2A',
        };
        /* Entradas del diccionario raíz que apuntan a OBJETOS_BASE. */
        const DICCIONARIOS_BASE = [
            ['ACAD_COLOR', 'B'], ['ACAD_GROUP', 'C'], ['ACAD_LAYOUT', 'D'], ['ACAD_MATERIAL', 'E'],
            ['ACAD_MLEADERSTYLE', 'F'], ['ACAD_MLINESTYLE', '10'], ['ACAD_PLOTSETTINGS', '11'],
            ['ACAD_PLOTSTYLENAME', '12'], ['ACAD_SCALELIST', '14'], ['ACAD_TABLESTYLE', '15'],
            ['ACAD_VISUALSTYLE', '16'],
        ];
        const handleVariablesImagen = nuevoHandle();
        const handleDicImagenes = nuevoHandle();

        const capas = [];
        const capasPorNombre = {};
        const bloques = [];
        const entidades = [];
        const definicionesImagen = [];
        let extension = null;

        function agregarCapa(nombre, color) {
            const n = nombreSeguro(nombre);
            if (!capasPorNombre[n]) {
                capasPorNombre[n] = { nombre: n, color, handle: nuevoHandle() };
                capas.push(capasPorNombre[n]);
            }
            return n;
        }

        /* Entidades: cada constructor recibe el handle del dueño (espacio
           modelo o un bloque) y devuelve la lista de pares código/valor. */
        function encabezadoEntidad(tipo, dueno, { capa = '0', color, transparencia } = {}) {
            const pares = [[0, tipo], [5, nuevoHandle()], [330, dueno], [100, 'AcDbEntity'], [8, capa]];
            if (color != null) pares.push([420, colorVerdadero(color)]);
            /* 440: transparencia como 0x020000AA (AA = opacidad 0-255). */
            if (transparencia != null) pares.push([440, 0x02000000 | Math.round((1 - transparencia) * 255)]);
            return pares;
        }

        function polilinea(dueno, puntos, { cerrada = false, ancho = 0, ...estilo } = {}) {
            const pares = encabezadoEntidad('LWPOLYLINE', dueno, estilo);
            pares.push([100, 'AcDbPolyline'], [90, puntos.length], [70, cerrada ? 1 : 0], [43, numero(ancho)]);
            puntos.forEach(p => pares.push([10, numero(p.x)], [20, numero(p.y)]));
            return pares;
        }

        /* Sombreado sólido. "contornos" es una lista de polígonos: si
           son varios (p. ej. un anillo), la regla de paridad impar del
           HATCH deja el hueco del contorno interior. */
        function sombreado(dueno, contornos, estilo = {}) {
            const validos = contornos.filter(c => c.length >= 3);
            if (!validos.length) return null;
            const pares = encabezadoEntidad('HATCH', dueno, estilo);
            pares.push(
                [100, 'AcDbHatch'],
                [10, '0.0'], [20, '0.0'], [30, '0.0'],
                [210, '0.0'], [220, '0.0'], [230, '1.0'],
                [2, 'SOLID'], [70, 1], [71, 0], [91, validos.length]
            );
            validos.forEach((puntos, i) => {
                /* 92: 2 = contorno tipo polilínea (+1 = externo, el primero). */
                pares.push([92, i === 0 ? 3 : 2], [72, 0], [73, 1], [93, puntos.length]);
                puntos.forEach(p => pares.push([10, numero(p.x)], [20, numero(p.y)]));
                pares.push([97, 0]);
            });
            pares.push([75, 0], [76, 1], [98, 0]);
            return pares;
        }

        /* Texto de una línea anclado a la izquierda y centrado en
           vertical (igual que textBaseline 'middle' del canvas). */
        function texto(dueno, contenido, x, y, altura, estilo = {}) {
            const limpio = String(contenido ?? '').replace(/[\r\n]+/g, ' ');
            if (!limpio.trim()) return null;
            const pares = encabezadoEntidad('TEXT', dueno, estilo);
            pares.push(
                [100, 'AcDbText'],
                [10, numero(x)], [20, numero(y)], [30, '0.0'],
                [40, numero(altura)], [1, limpio], [7, 'Standard'], [72, 0],
                [11, numero(x)], [21, numero(y)], [31, '0.0'],
                [100, 'AcDbText'], [73, 2]
            );
            return pares;
        }

        function insercion(dueno, nombreBloque, x, y, escala, estilo = {}) {
            const pares = encabezadoEntidad('INSERT', dueno, estilo);
            pares.push(
                [100, 'AcDbBlockReference'], [2, nombreBloque],
                [10, numero(x)], [20, numero(y)], [30, '0.0'],
                [41, numero(escala)], [42, numero(escala)], [43, numero(escala)]
            );
            return pares;
        }

        /* Constructor que se le pasa a quien arma el contenido, ya atado a
           un dueño (espacio modelo o bloque). */
        function constructorPara(dueno, destino) {
            const agregar = pares => { if (pares) destino.push(pares); };
            return {
                polilinea: (puntos, opciones) => agregar(polilinea(dueno, puntos, opciones)),
                sombreado: (contornos, estilo) => agregar(sombreado(dueno, contornos, estilo)),
                texto: (contenido, x, y, altura, estilo) => agregar(texto(dueno, contenido, x, y, altura, estilo)),
                insercion: (bloque, x, y, escala, estilo) => agregar(insercion(dueno, bloque, x, y, escala, estilo)),
            };
        }

        const modelo = constructorPara(H.brModelo, entidades);

        function agregarBloque(nombre, armar) {
            const n = nombreSeguro(nombre);
            const bloque = { nombre: n, handleRecord: nuevoHandle(), handleInicio: nuevoHandle(), handleFin: nuevoHandle(), entidades: [] };
            armar(constructorPara(bloque.handleRecord, bloque.entidades));
            bloques.push(bloque);
            return n;
        }

        /* Imagen raster de fondo (archivo externo, el DXF no puede
           incrustarla): definición (IMAGEDEF, con la ruta y el tamaño en
           píxeles) + entidad IMAGE en el espacio modelo + el reactor que
           las vincula. A diferencia de un PDF underlay, el tamaño final
           lo fija el propio DXF (vectores U/V = tamaño de un píxel en
           unidades del dibujo), así que AutoCAD no interpreta ninguna
           escala: la imagen ocupa exactamente ancho x alto desde (x, y),
           su esquina inferior izquierda. */
        function imagen(archivo, { anchoPx, altoPx, x = 0, y = 0, ancho, alto, capa = '0' }) {
            const handleDef = nuevoHandle();
            const handleImagen = nuevoHandle();
            const handleReactor = nuevoHandle();
            definicionesImagen.push({ handleDef, handleImagen, handleReactor, archivo, anchoPx, altoPx });
            entidades.push([
                [0, 'IMAGE'], [5, handleImagen], [330, H.brModelo], [100, 'AcDbEntity'], [8, capa],
                [100, 'AcDbRasterImage'], [90, 0],
                [10, numero(x)], [20, numero(y)], [30, '0.0'],
                [11, numero(ancho / anchoPx)], [21, '0.0'], [31, '0.0'],
                [12, '0.0'], [22, numero(alto / altoPx)], [32, '0.0'],
                [13, numero(anchoPx)], [23, numero(altoPx)],
                [340, handleDef],
                /* 70: 1 = mostrar + 2 = mostrar aunque no esté alineada.
                   281/282/283: brillo/contraste/atenuación. */
                [70, 3], [280, 0], [281, 50], [282, 50], [283, 0],
                [360, handleReactor],
                [71, 1], [91, 2], [14, '-0.5'], [24, '-0.5'], [14, numero(anchoPx - 0.5)], [24, numero(altoPx - 0.5)],
                [290, 0],
            ]);
        }

        /* Rectángulo que se muestra al abrir el archivo (zoom inicial). */
        function fijarExtension(minX, minY, maxX, maxY) {
            extension = { minX, minY, maxX, maxY };
        }

        function serializar() {
            const lineas = [];
            const emitir = pares => pares.forEach(([c, v]) => { lineas.push(String(c), String(v)); });
            const seccion = (nombre, armar) => { emitir([[0, 'SECTION'], [2, nombre]]); armar(); emitir([[0, 'ENDSEC']]); };
            const tabla = (nombre, handle, cantidad, registros, extra = []) => {
                emitir([[0, 'TABLE'], [2, nombre], [5, handle], [330, 0], [100, 'AcDbSymbolTable'], [70, cantidad], ...extra]);
                registros.forEach(emitir);
                emitir([[0, 'ENDTAB']]);
            };
            const registro = (tipo, handle, dueno, subclase, campos) =>
                [[0, tipo], [5, handle], [330, dueno], [100, 'AcDbSymbolTableRecord'], [100, subclase], ...campos];

            const ext = extension || { minX: 0, minY: 0, maxX: 100, maxY: 100 };
            const centroX = (ext.minX + ext.maxX) / 2;
            const centroY = (ext.minY + ext.maxY) / 2;
            const altoVista = Math.max(ext.maxY - ext.minY, (ext.maxX - ext.minX) / 1.5) * 1.05 || 100;

            seccion('HEADER', () => emitir([
                [9, '$ACADVER'], [1, 'AC1027'],
                [9, '$DWGCODEPAGE'], [3, 'ANSI_1252'],
                [9, '$INSBASE'], [10, '0.0'], [20, '0.0'], [30, '0.0'],
                [9, '$EXTMIN'], [10, numero(ext.minX)], [20, numero(ext.minY)], [30, '0.0'],
                [9, '$EXTMAX'], [10, numero(ext.maxX)], [20, numero(ext.maxY)], [30, '0.0'],
                [9, '$LIMMIN'], [10, numero(ext.minX)], [20, numero(ext.minY)],
                [9, '$LIMMAX'], [10, numero(ext.maxX)], [20, numero(ext.maxY)],
                [9, '$INSUNITS'], [70, insunits],
                [9, '$MEASUREMENT'], [70, 1],
                [9, '$LUNITS'], [70, 2],
                [9, '$HANDSEED'], [5, siguienteHandle.toString(16).toUpperCase()],
            ]));

            seccion('CLASSES', () => emitir([
                ...leerPlantilla(CLASES_BASE).flat(),
                [0, 'CLASS'], [1, 'IMAGE'], [2, 'AcDbRasterImage'], [3, 'ISM'], [90, 2175], [91, 0], [280, 0], [281, 1],
                [0, 'CLASS'], [1, 'IMAGEDEF'], [2, 'AcDbRasterImageDef'], [3, 'ISM'], [90, 0], [91, 0], [280, 0], [281, 0],
                [0, 'CLASS'], [1, 'IMAGEDEF_REACTOR'], [2, 'AcDbRasterImageDefReactor'], [3, 'ISM'], [90, 1], [91, 0], [280, 0], [281, 0],
            ]));

            seccion('TABLES', () => {
                tabla('VPORT', H.tablaVport, 1, [registro('VPORT', H.vport, H.tablaVport, 'AcDbViewportTableRecord', [
                    [2, '*Active'], [70, 0],
                    [10, '0.0'], [20, '0.0'], [11, '1.0'], [21, '1.0'],
                    [12, numero(centroX)], [22, numero(centroY)],
                    [13, '0.0'], [23, '0.0'], [14, '10.0'], [24, '10.0'], [15, '10.0'], [25, '10.0'],
                    [16, '0.0'], [26, '0.0'], [36, '1.0'], [17, '0.0'], [27, '0.0'], [37, '0.0'],
                    [40, numero(altoVista)], [41, '1.5'], [42, '50.0'], [43, '0.0'], [44, '0.0'],
                    [50, '0.0'], [51, '0.0'], [71, 0], [72, 1000], [73, 1], [74, 3], [75, 0], [76, 0], [77, 0], [78, 0],
                    [281, 0], [65, 0], [146, '0.0'],
                ])]);

                tabla('LTYPE', H.tablaLtype, 3, leerPlantilla(TIPOS_LINEA_BASE));

                /* 390/347: estilo de trazado "Normal" y material "Global"
                   (de OBJETOS_BASE) — AutoCAD los exige en cada capa. */
                const capa = (handle, nombre, color, { trazable = true } = {}) => {
                    const campos = [[2, nombre], [70, 0], [62, 7]];
                    if (color) campos.push([420, colorVerdadero(color)]);
                    campos.push([6, 'Continuous']);
                    if (!trazable) campos.push([290, 0]);
                    campos.push([370, -3], [390, H.placeholderNormal], [347, H.materialGlobal]);
                    return registro('LAYER', handle, H.tablaLayer, 'AcDbLayerTableRecord', campos);
                };
                tabla('LAYER', H.tablaLayer, capas.length + 2, [
                    capa(H.capa0, '0'),
                    capa(H.capaDefpoints, 'Defpoints', null, { trazable: false }),
                    ...capas.map(c => capa(c.handle, c.nombre, c.color)),
                ]);

                tabla('STYLE', H.tablaStyle, 1, [registro('STYLE', H.estiloStandard, H.tablaStyle, 'AcDbTextStyleTableRecord', [
                    [2, 'Standard'], [70, 0], [40, '0.0'], [41, '1.0'], [50, '0.0'], [71, 0], [42, '2.5'], [3, 'arial.ttf'], [4, ''],
                ])]);
                tabla('VIEW', H.tablaView, 0, []);
                tabla('UCS', H.tablaUcs, 0, []);
                tabla('APPID', H.tablaAppid, 1, [registro('APPID', H.appAcad, H.tablaAppid, 'AcDbRegAppTableRecord', [[2, 'ACAD'], [70, 0]])]);
                tabla('DIMSTYLE', H.tablaDimstyle, 1, leerPlantilla(DIMSTYLE_BASE), [[100, 'AcDbDimStyleTable']]);

                const registroBloque = (handle, nombre, layout = '0') => {
                    const campos = [[2, nombre], [340, layout], [70, 0], [280, 1], [281, 0]];
                    return registro('BLOCK_RECORD', handle, H.tablaBlockRecord, 'AcDbBlockTableRecord', campos);
                };
                tabla('BLOCK_RECORD', H.tablaBlockRecord, bloques.length + 2, [
                    registroBloque(H.brModelo, '*Model_Space', H.layoutModelo),
                    registroBloque(H.brPapel, '*Paper_Space', H.layoutPapel),
                    ...bloques.map(b => registroBloque(b.handleRecord, b.nombre)),
                ]);
            });

            seccion('BLOCKS', () => {
                const bloque = (handleInicio, handleFin, dueno, nombre, contenido = []) => {
                    emitir([
                        [0, 'BLOCK'], [5, handleInicio], [330, dueno], [100, 'AcDbEntity'], [8, '0'],
                        [100, 'AcDbBlockBegin'], [2, nombre], [70, 0], [10, '0.0'], [20, '0.0'], [30, '0.0'], [3, nombre], [1, ''],
                    ]);
                    contenido.forEach(emitir);
                    emitir([[0, 'ENDBLK'], [5, handleFin], [330, dueno], [100, 'AcDbEntity'], [8, '0'], [100, 'AcDbBlockEnd']]);
                };
                bloque(H.bloqueModelo, H.finModelo, H.brModelo, '*Model_Space');
                bloque(H.bloquePapel, H.finPapel, H.brPapel, '*Paper_Space');
                bloques.forEach(b => bloque(b.handleInicio, b.handleFin, b.handleRecord, b.nombre, b.entidades));
            });

            seccion('ENTITIES', () => entidades.forEach(emitir));

            seccion('OBJECTS', () => {
                emitir([
                    [0, 'DICTIONARY'], [5, H.dicRaiz], [330, 0], [100, 'AcDbDictionary'], [281, 1],
                    ...DICCIONARIOS_BASE.flatMap(([nombre, handle]) => [[3, nombre], [350, handle]]),
                    [3, 'ACAD_IMAGE_VARS'], [350, handleVariablesImagen],
                    [3, 'ACAD_IMAGE_DICT'], [350, handleDicImagenes],
                ]);
                leerPlantilla(OBJETOS_BASE).forEach(emitir);

                emitir([
                    [0, 'RASTERVARIABLES'], [5, handleVariablesImagen],
                    [102, '{ACAD_REACTORS'], [330, H.dicRaiz], [102, '}'], [330, H.dicRaiz],
                    [100, 'AcDbRasterVariables'], [90, 0], [70, 0], [71, 1], [72, 3],
                    [0, 'DICTIONARY'], [5, handleDicImagenes], [330, H.dicRaiz], [100, 'AcDbDictionary'], [281, 1],
                    ...definicionesImagen.flatMap((d, i) => [[3, 'FONDO_' + (i + 1)], [350, d.handleDef]]),
                ]);
                definicionesImagen.forEach(d => emitir([
                    [0, 'IMAGEDEF'], [5, d.handleDef],
                    [102, '{ACAD_REACTORS'], [330, handleDicImagenes], [330, d.handleReactor], [102, '}'],
                    [330, handleDicImagenes], [100, 'AcDbRasterImageDef'], [90, 0], [1, d.archivo],
                    [10, numero(d.anchoPx)], [20, numero(d.altoPx)], [11, '0.01'], [21, '0.01'], [280, 1], [281, 0],
                    [0, 'IMAGEDEF_REACTOR'], [5, d.handleReactor], [330, d.handleImagen],
                    [100, 'AcDbRasterImageDefReactor'], [90, 2], [330, d.handleImagen],
                ]));
            });

            emitir([[0, 'EOF']]);
            return lineas.join('\r\n') + '\r\n';
        }

        return { agregarCapa, agregarBloque, modelo, imagen, fijarExtension, serializar };
    }

    global.PlanoDxf = { crearDxf, nombreSeguro };
})(typeof window !== 'undefined' ? window : globalThis);
