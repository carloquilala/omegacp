function setImageValue(url){
  $('.mce-btn.mce-open').parent().find('.mce-textbox').val(url);
}

$(document).ready(function(){

  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  tinymce.init({
    menubar: false,
    selector:'textarea.richTextBox',
    skin: 'omega',
    plugins: 'link, image, code, youtube, giphy, table, textcolor',
    extended_valid_elements : 'input[onclick|value|style|type]',
    file_browser_callback: function(field_name, url, type, win) {
            if(type =='image'){
              $('#upload_file').trigger('click');
            }
        },
    toolbar: 'styleselect bold italic underline | forecolor backcolor | alignleft aligncenter alignright | bullist numlist outdent indent | link image table youtube giphy | code',
    convert_urls: false,
    image_caption: true,
    image_title: true
  });

});
