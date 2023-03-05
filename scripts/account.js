$(document).ready(() => {
  api('showEmail.php', 'GET')
  .then(xhr => {
    let emails = JSON.parse(xhr.response);
    console.log(emails);
    emails.forEach(email => {
      $('.table tbody').append(`
        <tr>
          <td>${email}</td> 
          <td class="ta-right"><button type="submit" class="btn btn_red btn__delete-email" data-email="${email}">Удалить</button></td>
        </tr>
      `);
    });
  })
  .catch(xhr => {
    console.error(xhr);
  });
});

$('body').on('click', '.btn__delete-email', function() {
  let $row = $(this).closest('tr');
  let email = $(this).data('email');

  $row.addClass('remove');
  setTimeout(() => {
    $row.remove();
  }, 300);

  api('deleteEmail.php', 'POST', {email});
});