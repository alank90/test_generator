/**
 * @author akillian
 * See https://coding.abel.nu/2012/01/jquery-ui-replacement-for-alert/
 */
$.extend({ alert: function (message, title) {
  $("<div></div>").dialog( {
    buttons: { "Ok": function () { $(this).dialog("close"); } },
    close: function (event, ui) { $(this).remove(); },
    resizable: false,
    title: title,
    modal: true
  }).text(message);
 }
});
