function toggleproduct(selection){
    $(selection).hover(        
        function(){
            if ($(this).find('.product-image').size()>1){
                $(this).find('.product-image.hide').show();
                $(this).find('.product-image.hide').removeClass('hide').addClass('hide-hover');
                $(this).find('.product-image.show').hide();
                $(this).find('.product-image.show').removeClass('show').addClass('show-hover');
            }
        },
        function(){
            //repeat above same code, to show back the original image
            $(this).find('.product-image.show-hover').show();
            $(this).find('.product-image.hide-hover').hide();
            $(this).find('.product-image.show-hover').removeClass('show-hover').addClass('show');
            $(this).find('.product-image.hide-hover').removeClass('hide-hover').addClass('hide');
        }
    );
} 