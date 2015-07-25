function getModal() {
    var method = {},
        $overlay = $('<div id="overlay"></div>'),
        $modal = $('<div id="modal"></div>'),
        $content = $('<div id="content"></div>'),
        $close = $('<a id="close" href="#">close</a>');
    
    $modal.hide();
    $overlay.hide();
    $modal.append($content, $close);
    $("body").append($overlay, $modal);
    
    method.center = function() {
        var top, left;
        
        
        top = Math.max($(window).height() - $modal.outerHeight(), 0) / 2;
        left = Math.max($(window).width() - $modal.outerWidth(), 0) / 2;
        
        $modal.css({
            top: top + $(window).scrollTop(),
            left: left + $(window).scrollLeft()
        });
    };
    
    method.open = function(settings) {
        $content.empty().append('<div id="modalcontent"></div>').children("#modalcontent").append(settings.content);
        
        $modal.css({
            width: settings.width || "auto",
            height: settings.height || "auto"
        });
        
        method.center();
        
        $(window).bind('resize.modal', method.center);
        
        $modal.show();
        $overlay.show();
    };
    
    method.close = function() {
        $modal.hide();
        $overlay.hide();
        $content.empty();
        $(window).unbind('resize.modal');
    };
    
    $close.click(function(e){
        e.preventDefault();
        method.close();
    });
    
    return method;
}
