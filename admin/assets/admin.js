jQuery(document).ready(function($) {
    // Toggle active status via AJAX
    $('.dgt-toggle').on('change', function() {
        var checkbox = $(this);
        var id = checkbox.data('id');
        var status = checkbox.is(':checked') ? 1 : 0;

        $.ajax({
            url: dgt_cm.ajax_url,
            type: 'POST',
            data: {
                action: 'dgt_cm_toggle',
                id: id,
                status: status,
                nonce: dgt_cm.nonce
            },
            success: function(response) {
                if (!response.success) {
                    alert('Error updating status.');
                    checkbox.prop('checked', !status);
                }
            },
            error: function() {
                alert('Request failed.');
                checkbox.prop('checked', !status);
            }
        });
    });

    // Initialize CodeMirror if element exists
    if ($('#snippet_code').length) {
        var editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};
        editorSettings.codemirror = _.extend(
            {},
            editorSettings.codemirror,
            {
                indentUnit: 4,
                tabSize: 4,
                lineNumbers: true,
                mode: 'php'
            }
        );

        var editor = wp.codeEditor.initialize($('#snippet_code'), editorSettings);

        // Change mode based on type selection
        $('#snippet_type').on('change', function() {
            var type = $(this).val();
            var mode = 'php';
            
            if (type === 'css') mode = 'css';
            else if (type === 'javascript') mode = 'javascript';
            else if (type === 'html') mode = 'htmlmixed';

            editor.codemirror.setOption('mode', mode);
        });

        // Trigger change to set initial mode
        $('#snippet_type').trigger('change');
    }
});
