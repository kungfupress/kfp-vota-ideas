jQuery( document ).on( 'click', '.click-vote-link', function(event) {
    event.preventDefault();
    var post_id = jQuery(this).data('vote-id');
    jQuery.ajax({
        type : 'post',
        data : {
            action : 'click_vote_process',
            nonce : votelink.ajax_nonce,
            post_id : post_id
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