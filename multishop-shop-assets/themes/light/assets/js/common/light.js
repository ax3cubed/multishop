function enlargeproduct(selection){
    $(selection).hover(        
        function(){
            $(this).find('img').css({
                'width':'103%',
            });
        },
        function(){
            $(this).find('img').css({
                'width':'100%',
            });
        }
    );
} 

function opencartdrawer(){
    openoffcanvasmenu_overlay("offcanvas_cart_menu","300px");
}
