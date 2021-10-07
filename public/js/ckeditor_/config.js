/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */
var roxyFileman = '/js/ckeditor/fileman/index.html';

CKEDITOR.editorConfig = function( config ) {
    config.filebrowserBrowseUrl = roxyFileman;
    config.filebrowserImageBrowseUrl = roxyFileman+'?type=image';
    config.removeDialogTabs = 'link:upload;image:upload';
    config.height = 350;
    // config.extraPlugins = 'btgrid';
    // config.extraPlugins = 'uploadimage';

    config.toolbar = [
        { name: 'document',    items: [ 'Source', '-',   'Preview', 'Print', '-', 'Templates' ] },
        { name: 'clipboard',   items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-','Undo', 'Redo' ] },
        { name: 'editing',     items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
        { name: 'insert',      items: [ 'CreatePlaceholder', 'Image', 'Flash', 'Table', 'Iframe', 'InsertPre' ] },
        '/',
        { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', ] },
        { name: 'paragraph',   items: [ 'NumberedList', 'BulletedList', '-', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'BidiLtr', 'BidiRtl' ] },
        { name: 'links',       items: [ 'Link', 'Unlink', 'Anchor' ] },
        { name: 'tools',       items: [ 'UIColor', 'Maximize', 'ShowBlocks' ] },
        '/',
        { name: 'styles',      items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
        { name: 'colors',      items: [ 'TextColor', 'BGColor' ] },
    ];

};
