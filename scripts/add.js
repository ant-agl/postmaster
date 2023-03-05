$('.btn__add-email').on('click', function() {
  let $message = $('.message');
  $message.removeClass('message_success message_error');
  $message.text('');

  let $email = $('[name="email"]');
  let $tokens_json = $('[name="tokens_json"]');

  let errors = false;

  if (!$email.val().trim()) {
    errors = true;
    $email.addClass('error');
  }

  if (!$tokens_json.val().trim()) {
    errors = true;
    $tokens_json.addClass('error');
  }

  if (errors) return;

  let params = {
    email: $email.val().trim(),
    tokens_json: $tokens_json.val().trim()
  };
  
  api('form.php', 'POST', params)
  .then(xhr => {
    let data = JSON.parse(xhr.response);

    if (data.ok) {
      $message.addClass('message_success');
      $message.text(data.message);
    } else {
      $message.addClass('message_error');
      $message.text(data.error_description);
    }
  })
  .catch(xhr => {
    console.error(xhr);
  });
});

$('[name="email"]').on('input change', notError);
$('[name="tokens_json"]').on('input change', notError);

function notError() {
  $(this).removeClass('error');
}