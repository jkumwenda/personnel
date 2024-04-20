import Swal from "sweetalert2";

$(document).ready(function (){
    $('.cancel-application').click(function (){
        let applicationId = $(this).attr('data-id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if(result.value){
                $.ajax({
                    type: 'POST',
                    url: '{{ route("applications.cancel", ":id") }}'.replace(':id', applicationId),
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        // Handle success, you can show another SweetAlert or redirect
                        Swal.fire('Cancelled!', 'Your application has been cancelled.', 'success');
                    },
                    error: function (xhr, status, error) {
                        // Handle error, you can show another SweetAlert or log the error
                        Swal.fire('Error!', 'An error occurred while cancelling the application.', 'error');
                    }
                });
            }

        });
    });
});
