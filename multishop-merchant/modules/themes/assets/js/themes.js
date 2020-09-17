function switchdevice(device) {
    var containter = $('.device-frame-containter');
    containter.removeClass();
    containter.addClass('device-frame-containter '+device);
    $('.preview-control li').removeClass('active');
    $('.preview-control li.'+device).addClass('active');
}