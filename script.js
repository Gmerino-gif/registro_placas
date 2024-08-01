$(document).ready(function() {
    let placasRegistradas = [];

    function updateClock() {
        const now = new Date();
        const dateString = now.toLocaleDateString();
        const timeString = now.toLocaleTimeString();
        $('#clock').text(`${dateString} ${timeString}`);
    }

    setInterval(updateClock, 1000);

    function addRecord(type) {
        const placa = $('#placa').val().trim();
        if (placa === "") {
            alert("Por favor, ingrese una placa.");
            return;
        }

        if (!placasRegistradas.includes(placa)) {
            placasRegistradas.push(placa);
            $('#placas').append(new Option(placa, placa));
        }

        const now = new Date();
        const dateString = now.toLocaleDateString();
        const timeString = now.toLocaleTimeString();
        const rowClass = type === 'Ingreso' ? 'entrada' : 'salida';
        const tipoClass = type === 'Ingreso' ? 'tipo-entrada' : 'tipo-salida';

        const row = `<tr class="${rowClass}">
                        <td>${placa}</td>
                        <td>${dateString} ${timeString}</td>
                        <td><span class="${tipoClass}">${type}</span></td>
                     </tr>`;
        $('#registro').append(row);
        $('#placa').val('');
    }

    $('#ingreso').click(function() {
        addRecord('Ingreso');
    });

    $('#salida').click(function() {
        addRecord('Salida');
    });

    updateClock();
});


