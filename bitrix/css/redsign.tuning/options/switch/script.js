$(document).ready(function(){

    $(document).on('change', 'input', function(){
        $input = $(this);

        if ($input.is(':checked')) {
            $input.closest('.switch').addClass('active');
        } else {
            $input.closest('.switch').removeClass('active');
        }
    });

});
