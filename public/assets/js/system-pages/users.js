
// AJAX UPDATE USER
$(document).on('click', '.btnUpdateUser', function(){ //De esta manera se ejecutará así la clase no se haya cargado aún

    //Vaciar el input file
    $('.photo').val('');
    //Vaciar form
    $('form')[1].reset();

    const id = $(this).attr('user-id');

    $.ajax({
        url: path + '/users/getById/' + id,
        method: "GET",
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        beforeSend: function() {
            Swal.fire({
                icon: 'info',
                title: '<strong>Procesando...</strong>',
                text: 'Por favor, espera unos segundos',
                showConfirmButton: false,
                didOpen: function() {
                    Swal.showLoading();
                }
            });
        },
        success: function (data) {
            $('#updateId').val(data[0].id);
            $('#updateCi').val(data[0].ci);
            $('#updateName').val(data[0].name);
            $('#updateEmail').val(data[0].email);
            $('#updatePasswordPreview').val(data[0].password);
            $('#updatePrivilege').val(data[0].privilege);
            $('#updatePhotoPreview').val(data[0].photo);
            $('#updatePhoto').attr('src', path + "/assets/images/users/anonymous.png");
            if(data[0].photo != null && data[0].photo != ''){
                $('#updatePhoto').attr('src', data[0].photo);
            }
            Swal.close();

        },
        error: function (data) {
            console.log(data);
            Swal.close();
            $('#updateUserModal').modal('hide');
            
            Swal.fire({
               title: '¡Oops!',
               text: 'Ocurrió un error',
               icon: 'error'
            });
        }
    });
    return false;
});

// AJAX DELETE USER
$(document).on('click', '.btnDeleteUser', function(){ 
    
    var response = $('.response');
    var id = $(this).attr('user-id');
    var photo = $(this).attr('photo');
    var data = new FormData();
    data.append('id', id);
    data.append('ci', ci);
    
    Swal.fire({
       
       title: '¿Está seguro de eliminar el usuario #'+id+'?',
       text: 'Si no está seguro, puede cancelar la operación',
       icon: 'warning',
       showCancelButton: true,
       cancelButtonColor: '#D33',
       confirmButtonText: 'Sí',
       cancelButtonText: 'Cancelar'

    }).then((result) => {
        if(result.value){

            //AJAX
            $.ajax({
                url: path + '/users/delete',
                method: "POST",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    Swal.fire({
                        icon: 'info',
                        title: '<strong>Procesando...</strong>',
                        text: 'Por favor, espera unos segundos',
                        showConfirmButton: false,
                        didOpen: function() {
                            Swal.showLoading();
                        }
                    });
                },
                success: function (data) {
                    response.html(data);
                },
                error: function (data) {
                    
                    console.log(data);
                    Swal.fire({
                       title: 'Ha ocurrido un error',
                       text: 'Intente nuevamente',
                       icon: 'error'
                    });

                }
            });
        }
    });
});