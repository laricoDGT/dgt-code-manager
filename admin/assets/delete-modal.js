(function ($) {
  "use strict";

  var pluginFile = codeweaveDeleteModal.plugin_file;
  var ajaxUrl = codeweaveDeleteModal.ajax_url;
  var nonce = codeweaveDeleteModal.nonce;

  // Find the table row for our plugin and its delete link
  var $row = $('tr[data-plugin="' + pluginFile + '"]');
  if (!$row.length) return;

  var $deleteLink = $row.find(
    'a[href*="delete-selected"], a[href*="action=delete"]',
  );
  if (!$deleteLink.length) return;

  var deleteHref = $deleteLink.attr("href");

  // ── Modal HTML ────────────────────────────────────────────────────────────
  var modal = [
    '<div id="codeweave-delete-overlay">',
    '  <div id="codeweave-delete-modal" role="dialog" aria-modal="true" aria-labelledby="codeweave-modal-title">',
    '    <h3 id="codeweave-modal-title">Delete CodeWeave</h3>',
    "    <p>Are you sure you want to delete <strong>CodeWeave</strong>?</p>",
    '    <div class="codeweave-modal-option">',
    "      <label>",
    '        <input type="checkbox" id="codeweave-also-delete-data">',
    "        <span><strong>Also delete all snippets from the database</strong></span>",
    "      </label>",
    '      <p class="description">',
    "        Leave unchecked (default) to keep your snippets safe.<br>",
    "        Check this box to <em>permanently remove</em> the snippets table.",
    "      </p>",
    "    </div>",
    '    <div class="codeweave-modal-footer">',
    '      <button id="codeweave-modal-confirm" class="button button-primary">Yes, delete plugin</button>',
    '      <button id="codeweave-modal-cancel"  class="button">Cancel</button>',
    "    </div>",
    "  </div>",
    "</div>",
  ].join("\n");

  $("body").append(modal);

  // ── Styles ────────────────────────────────────────────────────────────────
  var css = [
    "#codeweave-delete-overlay{",
    "  display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);",
    "  z-index:99999;align-items:center;justify-content:center;",
    "}",
    "#codeweave-delete-overlay.is-open{ display:flex; }",
    "#codeweave-delete-modal{",
    "  background:#fff;border-radius:8px;padding:30px 32px;max-width:480px;",
    "  width:90%;box-shadow:0 12px 40px rgba(0,0,0,.25);",
    "}",
    "#codeweave-modal-title{ margin:0 0 10px;font-size:18px;color:#1d2327; }",
    "#codeweave-delete-modal > p{ margin:0 0 20px;color:#50575e; }",
    ".codeweave-modal-option{",
    "  background:#fffbf0;border:1px solid #e6a817;border-radius:6px;",
    "  padding:14px 16px;margin-bottom:22px;",
    "}",
    ".codeweave-modal-option label{",
    "  display:flex;align-items:flex-start;gap:10px;cursor:pointer;",
    "}",
    ".codeweave-modal-option input[type=checkbox]{ margin-top:3px;flex-shrink:0; }",
    ".codeweave-modal-option .description{",
    "  margin:8px 0 0;font-size:12px;color:#646970;",
    "}",
    ".codeweave-modal-footer{ display:flex;gap:10px;justify-content:flex-end; }",
  ].join("");

  $("<style>").text(css).appendTo("head");

  // ── Open / close ─────────────────────────────────────────────────────────
  $deleteLink.on("click", function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    $("#codeweave-delete-overlay").addClass("is-open");
    $("#codeweave-modal-confirm").prop("disabled", false).text("Yes, delete plugin");
    $("#codeweave-also-delete-data").prop("checked", false);
  });

  $("#codeweave-delete-overlay").on("click", function (e) {
    if (e.target === this) closeModal();
  });

  $("#codeweave-modal-cancel").on("click", closeModal);

  function closeModal() {
    $("#codeweave-delete-overlay").removeClass("is-open");
  }

  // ── Confirm ───────────────────────────────────────────────────────────────
  $("#codeweave-modal-confirm").on("click", function () {
    var $btn = $(this);
    var deleteData = $("#codeweave-also-delete-data").is(":checked") ? 1 : 0;

    $btn.prop("disabled", true).text("Please wait…");

    $.post(
      ajaxUrl,
      {
        action: "codeweave_set_delete_preference",
        delete_data: deleteData,
        nonce: nonce,
      },
      function () {
        window.location.href = deleteHref;
      },
    ).fail(function () {
      // On AJAX failure still proceed (safe default = keep data)
      window.location.href = deleteHref;
    });
  });
})(jQuery);
