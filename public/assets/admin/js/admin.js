var Admin  = function()
{
    return{
        call : function(object,event){
            event.preventDefault();
            var obj = $(object);
            var url = obj.attr("href");
            $('.ajax-loader').toggleClass();
            $.ajax({
                type: "GET",
                url: url,
                success: function(response){
                    $('.result').html(response);
                    $('#modal').modal({
                        backdrop: 'static',
                        keyboard: true,
                    });
                }
            });
        }
    }
}();