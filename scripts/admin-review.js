jQuery(document).ready(function() {
    jQuery('.datepicker').datepicker();

        jQuery('#testRater').rater({ postHref: 'http://jvance.com/TestRater.aspx' });
    
  
        jQuery('#errorRater').rater({ postHref: 'http://jvance.com/TestRater.aspx?error=true' });
    
});

