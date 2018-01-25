
jQuery(document).ready(function() {
    jQuery('.datepicker').datepicker({
        //comment the beforeShow handler if you want to see the ugly overlay
        beforeShow: function() {
            setTimeout(function(){
                jQuery('.ui-datepicker').css('z-index', 99999999999999);
            }, 0);
    }
    });

    jQuery("#rateYo").on("rateyo.init", function (e, data) {
        
                 console.log("RateYo initialized! with " + data.rating);
                 });

         jQuery("#rateYo").rateYo({
             rating: jQuery("#rating_value").val()!=""?jQuery("#rating_value").val():0,
             normalFill: "#A0A0FF",
             ratedFill: jQuery("#rateYo").data("color")!=""?jQuery("#rateYo").data("color"): "#E74C3C"
             
         });
        jQuery("#rateYo").rateYo({}).on("rateyo.set", function (e, data) {
        
                       var rating = data.rating;
                        jQuery("#rating_value").val(rating);
                     });
    
  
});

