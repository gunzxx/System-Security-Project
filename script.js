$(document).ready(()=>{
    var regex = /^[a-zA-Z]+$/;
    $(".form-container").submit(function(e){
        e.preventDefault()
        if(!regex.test($('#key').val())){
            Swal.fire({
                text : "Input tidak valid.",
                confirmButtonColor: 'var(--r2)',
            })
        }
        else{
            $(this).unbind("submit");
            $(this).submit();
        }
    })
})