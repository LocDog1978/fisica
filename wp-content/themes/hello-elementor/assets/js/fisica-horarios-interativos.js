( function () {
    'use strict';

    const root = document.querySelector( '[data-fisica-horarios]' );
    if ( ! root ) {
        return;
    }

    const dias = [ 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira' ];
    const controls = Object.fromEntries(
        [ 'q', 'periodo', 'dia', 'turno' ].map( ( id ) => [ id, root.querySelector( '[data-horarios-control="' + id + '"]' ) ] )
    );
    const results = root.querySelector( '[data-horarios-results]' );
    const count = root.querySelector( '[data-horarios-count]' );
    const esc = ( value ) => String( value ?? '' ).replace( /[&<>"']/g, ( character ) => ( {
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[ character ] ) );

    function card( lesson ) {
        return `<article class="lesson wp-exclude-emoji"><h3>${esc( lesson.disciplina )}</h3><span class="pill">${esc( lesson.periodo )}</span> ${lesson.turma ? `<span class="pill">Turma ${esc( lesson.turma )}</span>` : ''}<p><b>🕒 ${dias[ lesson.dia ]} · ${esc( lesson.inicio )}–${esc( lesson.fim )}</b></p><p>📍 ${esc( lesson.sala || 'Sala não informada' )}</p><p>👤 ${esc( lesson.professor || 'Professor não informado' )}</p></article>`;
    }

    async function load() {
        try {
            const response = await fetch( root.dataset.source, { credentials: 'same-origin' } );
            if ( ! response.ok ) {
                throw new Error( 'Timetable request failed' );
            }
            const data = await response.json();
            if ( ! Array.isArray( data ) ) {
                throw new Error( 'Invalid timetable data' );
            }

            const periods = [ ...new Set( data.map( ( lesson ) => lesson.periodo ) ) ];
            controls.periodo.innerHTML = '<option value="">Todos os períodos e grupos</option>' + periods.map( ( period ) => `<option value="${esc( period )}">${esc( period )}</option>` ).join( '' );
            controls.dia.innerHTML = '<option value="">Todos os dias</option>' + dias.map( ( day, index ) => `<option value="${index}">${day}</option>` ).join( '' );

            function render() {
                const query = controls.q.value.toLocaleLowerCase( 'pt-BR' ).trim();
                const period = controls.periodo.value;
                const day = controls.dia.value;
                const shift = controls.turno.value;
                const lessons = data.filter( ( lesson ) =>
                    ( ! period || lesson.periodo === period ) &&
                    ( ! day || lesson.dia === Number( day ) ) &&
                    ( ! shift || lesson.slot.startsWith( shift ) ) &&
                    ( ! query || [ lesson.disciplina, lesson.professor, lesson.sala, lesson.turma, lesson.periodo ].some( ( value ) => String( value ).toLocaleLowerCase( 'pt-BR' ).includes( query ) ) )
                ).sort( ( first, second ) => first.dia - second.dia || first.inicio.localeCompare( second.inicio ) || first.disciplina.localeCompare( second.disciplina ) );

                count.textContent = `${lessons.length} registros de horário encontrados`;
                results.innerHTML = ! lessons.length
                    ? '<div class="empty">Nenhuma aula encontrada. Experimente alterar os filtros.</div>'
                    : '<div class="days">' + dias.map( ( name, index ) => `<div class="day"><h3>${name}</h3>${lessons.filter( ( lesson ) => lesson.dia === index ).map( card ).join( '' ) || '<p class="count">Sem aulas</p>'}</div>` ).join( '' ) + '</div>';
            }

            Object.values( controls ).forEach( ( control ) => control.addEventListener( 'input', render ) );
            root.querySelector( '[data-horarios-clear]' ).addEventListener( 'click', () => {
                Object.values( controls ).forEach( ( control ) => { control.value = ''; } );
                render();
            } );
            render();
        } catch ( error ) {
            count.textContent = '';
            results.innerHTML = '<div class="empty">Não foi possível carregar os horários. Recarregue a página.</div>';
        } finally {
            root.setAttribute( 'aria-busy', 'false' );
        }
    }

    load();
}() );
