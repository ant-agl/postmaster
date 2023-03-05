let statList = {};

$(document).ready(async () => {
  let client_id;

  let period = get('period');
  if (!period) period = 'day';
  $('.filter .link').removeClass('active');
  $(`.filter .link[data-period="${[period]}"]`).addClass('active');

  await api('showEmail.php', 'GET')
    .then(xhr => {
      let data = JSON.parse(xhr.response);
      console.log(data);

      let $drop = $('.dropdown-mail');
      data.forEach((email, i) => {
        $drop.find('.dropdown-content').append(`
          <div class="dropdown-item">${email}</div>
        `);
      });

      let thisMail = get('mail');
      if (!thisMail || thisMail == 'Почта') thisMail = data[0];

      $drop.find('.dropbtn').text(thisMail);
      client_id = thisMail;
    });

  let params = {
    client_id,
    date_from: getPeriod()['from'],
    date_to: getPeriod()['to'],
  };
  
  updateStatList(params)
    .then(updateTable);
});


async function updateStatList(params) {
  return api('statList.php', 'GET', params)
    .then(xhr => {
      let data = JSON.parse(xhr.response);
      console.log(data);
      if (!data.ok) {
        if (data.detail) {
          echoError(data.detail);
          throw new Error(data.detail);
        } else if (data.error) {
          echoError(data.error);
          throw new Error(data.error);
        }
        return;
      }

      let client_id = params.client_id;
      statList[client_id] = data.data;
    })
    .catch(xhr => {
      console.error(xhr);
    });
}

function updateTable() {
  $('.table tbody').html('');
    
  let client_id = $('.dropdown-mail .dropbtn').text();
  if (client_id == 'Почта') {
    echoError('Пусто!');
    return;
  }

  if (statList[client_id].length == 0) {
    echoError('Пусто!');
    return;
  }

  statList[client_id].forEach(el => {
    let deliveredPercent = el.delivered / el.messages_sent;
    if (isNaN(deliveredPercent)) deliveredPercent = 100;
    else deliveredPercent *= 100;

    let color = 'green';
    let max = Math.max(deliveredPercent, el.probably_spam_percent, el.spam_percent);
    switch (max) {
      case deliveredPercent:
        color = 'green';
        break
      case el.probably_spam_percent:
        color = 'yellow';
        break
      case el.spam_percent:
        color = 'red';
        break
    }

    deleteError();
    $('.table tbody').append(`
      <tr>
        <td>${el.domain}</td>
        <td>${el.messages_sent}</td>
        <td>${el.complaints}</td>
        <td>${toPercent(el.reputation)}</td>
        <td>${toPercent(el.trend)}</td>
        <td><span class="color ${color}">${toPercent(deliveredPercent)}</span></td>
      </tr>
    `);
  });
}

$('.dropdown').on('click', '.dropdown-item', function() {
  let client_id = $('.dropdown-mail .dropbtn').text().trim();
  editGetParams({
    mail: client_id,
    page: 1
  });

  window.location.reload();
});

$('.filter .link').on('click', function() {
  let period = $(this).data('period');
  editGetParams({
    period,
    page: 1
  });
  window.location.reload();
});

$('.pagination').on('click', 'a', function(isPeriod=false) {
  let mail = $('.dropdown-mail .dropbtn').text().trim();
  let domain = $('.dropdown-domain .dropbtn').text().trim()

  let url = '';

  let period = get('period');
  if (!period) period = 'day';
  url += '&period=' + period;

  url += '&mail=' + mail;

  if ($('.dropdown-domain .dropbtn').length > 0)
    url += '&domain=' + domain;

  let href = $(this).attr('href');
  $(this).attr('href', href + url);
});