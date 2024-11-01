(function($) {
    $(document).ready(function() {
        var typeaheadPosts = new Bloodhound({
            "datumTokenizer": Bloodhound.tokenizers.obj.whitespace("post_title"),
            "queryTokenizer": Bloodhound.tokenizers.whitespace,
            "limit": typeahead_settings.number,
            "remote": typeahead_settings.ajaxurl + "?action=typeahead&s=%QUERY&nonce=" + typeahead_settings.nonce
        });
        typeaheadPosts.initialize(), $('input[name="s"]').typeahead({
            "hint": false
        }, {
            "name": "typeahead",
            "displayKey": "post_title",
            "source": typeaheadPosts.ttAdapter()
        });
    });
})(jQuery);