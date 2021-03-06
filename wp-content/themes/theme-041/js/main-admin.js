(function($) {
	
	$(document).ready(function() {

        if($('#_project041_featured_authors').length > 0 ) {
            var select2_options = {
                language: 'es',
                width: '100%',
                minimumInputLength: 5,
                data: project041_featured_authors,
                ajax: {
                    url: ajaxurl + '?action=project041_get_users',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data) {
                        return data;
                    },
                    cache: true
                },
                placeholder: 'Introduce el nombre de un colaborador'
            }
            $('#_project041_featured_authors').select2(select2_options);        
        }        

        if($('#_project041_featured_events').length > 0 ) {
            var select2_options = {
                language: 'es',
                width: '100%',
                minimumInputLength: 5,
                data: project041_featured_events,
                ajax: {
                    url: ajaxurl + '?action=project041_get_events',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data) {
                        return data;
                    },
                    cache: true
                },
                placeholder: 'Introduce el nombre de un evento'
            }
            $('#_project041_featured_events').select2(select2_options);        
        }  

        $('.download-event-users').click(function(e) {
            e.preventDefault();
            var eid = $(this).data('event');
            $.ajax({
                url: ajaxurl + '?action=project041-download-event-users',
                data: {'eid': eid},
                cache: false,
                type: 'GET',
                dataType: 'json',
                success: function(data){
                    if(data.response == 'OK') {
                        //document.location = data.file;
                        var csv = data.csv;
                        var hiddenElement = document.createElement('a');
                        hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
                        hiddenElement.target = '_blank';
                        hiddenElement.download = 'usuarios.csv';
                        hiddenElement.click();                        
                    } else {
                        alert('Ha ocurrido un error al generar el fichero');
                    }
                }
            });	            
        });        

    });

})(jQuery);