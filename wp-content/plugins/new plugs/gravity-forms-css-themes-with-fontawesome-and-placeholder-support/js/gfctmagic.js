var $j = jQuery.noConflict();
// $j is now an alias to the jQuery function; creating the new alias is optional.
 
$j(document).ready(function() {
    $j(".gfct_add").each(function(){
    var faclass = $j(this).attr('class'); 
    var startme = faclass.indexOf("gfct_add") + 9;
    var finishme =  faclass.indexOf("gfct_end") - 1;
    var faicon = faclass.slice(startme,finishme);
    $j(this).children( ".ginput_container").prepend( "<span class='gfct_fa_span'><i class='fa " + faicon + "'></i></span>" );
    $j(this).find(".ginput_container input").addClass( 'gfct-uses-fa' );
    $j(this).find(".ginput_container textarea").addClass( 'gfct-uses-fa' );
    });
    ///labels
    // gravity forms custom placeholders
    $j(".gfct_placeholder li.gfield .gfield_label").each(function(){
       //first, let's get the placeholder value
        var label = $j(this).text();
        //next, let's find some input fields, and add placeholder labe, add custom css and remove label
        if ($j(this).siblings('.ginput_container').children('input[type="text"]').length)
        {
            $j(this).siblings('.ginput_container').children('input[type="text"]').addClass('gfct_placeholder_active').attr( "placeholder", label );
            $j(this).hide();
            //console.log(label);
        }
        //do the same with textarea
        if ($j(this).siblings('.ginput_container').children('textarea').length)
        {
            $j(this).siblings('.ginput_container').children('textarea').addClass('gfct_placeholder_active').attr( "placeholder", label );
            $j(this).hide();
            //console.log(label);
        }
        //and do custom magic with the complex field
        if ( $j(this).siblings('.ginput_complex').length)
        {
            //console.log('exist' + label);
            //first, lets hide the input
            $j(this).hide();
            //adnd lets loop the small labels
            $j(this).siblings('.ginput_complex').addClass('gfct_placeholder_active').find('label').each(function(){
                //$j(this).siblings('.ginput_container').find('label').text();
                //get the value of complex label and add them
                complex = $j(this).text();
                twolabels = label + ': ' + complex;
                //add class and placeholder attribute
                $j(this).siblings('input').addClass('gfct_placeholder_active').attr( "placeholder", twolabels );
                $j(this).hide();
            
             });
        };
    });
});