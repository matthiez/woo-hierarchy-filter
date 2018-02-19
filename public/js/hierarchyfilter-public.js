(function ($) {
    'use strict';
    $(document).ready(function () {
        const $shortcode = {
            1: $('#hierarchyfilter-shortcode-level-1'),
            2: $('#hierarchyfilter-shortcode-level-2'),
            3: $('#hierarchyfilter-shortcode-level-3'),
            submit: $('#hierarchyfilter-shortcode-submit')
        };

        const $widget = {
            1: $('#hierarchyfilter-widget-level-1'),
            2: $('#hierarchyfilter-widget-level-2'),
            3: $('#hierarchyfilter-widget-level-3'),
            submit: $('#hierarchyfilter-widget-submit')
        };
        /*       SHORTCODE       */
        $shortcode[2].prop("disabled", true); // disable the field
        $shortcode[3].prop("disabled", true); // disable the field
        $shortcode.submit.hide(); // hide submit button
        $shortcode[1].change(function () {
            $shortcode[2].prop("disabled", false); // enable the field
            $('#hierarchyfilter-shortcode-level-1 option').each(function () {
                if ($(this).is(':selected')) $(this).attr('selected', 'selected');
            });
            $.post(ajaxurl, {
                    action: 'hierarchyfilter_load_level_2',
                    hierarchyfilter_level_1: $(this).val()
                },
                function (response) {
                    $shortcode[2].html(response);
                });
        });
        $shortcode[2].change(function () {
            $shortcode[3].prop("disabled", false); // enable the field
            $('#hierarchyfilter-shortcode-level-2 option').each(function () {
                if ($(this).is(':selected')) $(this).attr('selected', 'selected');
            });
            $.post(ajaxurl, {
                    action: 'hierarchyfilter_load_level_3',
                    hierarchyfilter_level_2: $(this).val()
                },
                function (response) {
                    $shortcode[3].html(response);
                });
        });
        $shortcode[3].change(function () {
            $shortcode.submit.prop("disabled", false); // enable the field
            $shortcode.submit.show(); // show submit button
        });
        /*       WIDGET       */
        $widget[2].prop("disabled", true); // disable the field
        $widget[3].prop("disabled", true); // disable the field
        $widget.submit.hide(); // hide submit button
        $widget[1].change(function () {
            $widget[2].prop("disabled", false); // enable the field
            $('#hierarchyfilter-widget-level-1 option').each(function () {
                if ($(this).is(':selected')) $(this).attr('selected', 'selected');
            });
            $.post(ajaxurl, {
                    action: 'hierarchyfilter_load_level_2',
                    hierarchyfilter_level_1: $(this).val()
                },
                function (response) {
                    $widget[2].html(response);
                });
        });
        $widget[2].change(function () {
            $widget[3].prop("disabled", false); // enable the field
            $('#hierarchyfilter-widget-level-2 option').each(function () {
                if ($(this).is(':selected')) $(this).attr('selected', 'selected');
            });
            $.post(ajaxurl, {
                    action: 'hierarchyfilter_load_level_3',
                    hierarchyfilter_level_2: $(this).val()
                },
                function (response) {
                    $widget[3].html(response);
                });
        });
        $widget[3].change(function () {
            $widget.submit.prop("disabled", false); // enable the field
            $widget.submit.show(); // show submit button
        });
    });
})(window.jQuery || window.$);
