/** js/vote-link.js */

jQuery(document).ready(function ($) {
    $('body').on('click', '.vote', function (event) {
        event.preventDefault();
        var $link = $(this);
        var $linkCell = $link.parents('td');
        $.post(ajax_object.ajax_url,
            {
                action: 'vti_idea_vote',
                nonce: ajax_object.ajax_nonce,
                idea_id: $link.data('idea-id')
            },
            function (response) {
                $linkCell.html(response);
            });
        return false;
    });
});