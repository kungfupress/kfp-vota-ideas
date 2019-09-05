/*
jQuery( document ).on( 'click', '.click-vote-link', function(event) {
    event.preventDefault();
    var idea_id = jQuery(this).data('idea-id');
    jQuery.ajax({
        type : 'post',
        data : {
            url: 'http://wordpress.local/wp-admin/admin-ajax.php',
            action : 'vti_idea_vote',
            nonce : 'pepe',
            idea_id : idea_id
        },
        success : function( response ) {
            if (response == "pass") {
                // Incrementa el contador
            } else {
                alert(response);
            }
        }
    });
})
*/

//             url: votelink.ajax_url,
//             nonce : votelink.ajax_nonce,


jQuery(document).ready(function($){
    $('.click-vote-link').click(function(event){
        event.preventDefault();
        var redirect = $(this).attr('href');
        $.post(ajax_object.ajax_url, 
            {
                action:'vti_idea_vote', 
                idea_id:$(this).data('idea-id')
            }, 
            function(response) {
                alert(response);
            });
        return false;
    });
});