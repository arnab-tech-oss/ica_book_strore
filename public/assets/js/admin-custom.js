$(document).ready(function (){
   $("#adminSearch").on('keypress keyup click',function () {
       let val = $(this).val().trim();
       let element = $("#admin-search-content");
       if(val.length > 2){
           $.ajax({
               url: "/admin/admin-search",
               data: {
                   search : val
               },
               success: function(response){
                   console.log(response.status);
                   if(response.status == 'success'){
                       element.hasClass('p-3') ? element.removeClass('p-3') : '';
                       element.html(response.data).show();
                   }else{
                       element.addClass('p-3');
                       element.html(response.msg).show();
                   }
               }
           });
       }else{
           element.hide();
       }
   });
});
