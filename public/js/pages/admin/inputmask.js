$(document).ready(function() {
    $("#inputNumber").on("click", function(e) {
      $('#inputNumber').inputmask({
        'mask': '(999) 999 9999',
        'oncomplete': function() { 
        },
          clearMaskOnLostFocus: false,
          onBeforeMask: function (value, opts) {
          var inp = document.getElementById('inputNumber');
          if (inp.createTextRange) {
              var part = inp.createTextRange();
              part.move("character", 0);
              part.select();
          }
          else if (inp.setSelectionRange) {
              inp.setSelectionRange(0, 0);
          }
          inp.focus();
        }
      })
    });
});

  $(function() {
    $("#phone_no").
    on("click", function(e) {
    }).
    on('focus', function() {
      $('#phone_no').inputmask({
      'mask': '(999) 999 9999',
      'oncomplete': function() { 
      },
        clearMaskOnLostFocus: false,
        onBeforeMask: function (value, opts) {
        var inp = document.getElementById('phone_no');
        if (inp.createTextRange) {
            var part = inp.createTextRange();
            part.move("character", 0);
            part.select();
        }
        else if (inp.setSelectionRange) {
            inp.setSelectionRange(0, 0);
        }
        inp.focus();
      }
    });
    });
  });
  $(document).on("click", function(e) {
    if ($(e.target).is("#phone_no") === false) {
      if($('#phone_no').val()==''){
        console.log($('#phone_no').val());
          $('#phone_no').inputmask({ 'mask': '' });
        }
    }
  }).on('focusout', function() {
    if($('#phone_no').val()==''){
      $('#phone_no').inputmask({ 'mask': '' });
    }
  }).on('blur', function() {
    if($('#phone_no').val()==''){
      $('#phone_no').inputmask({'mask': ''});
    }
  });