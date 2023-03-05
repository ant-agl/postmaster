let statList = {};

$(document).ready(async () => {
  let client_id;

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
      editGetParams({
        mail: client_id
      });
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
  return api('statListDetailed.php', 'GET', params)
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

      let $drop = $('.dropdown-domain');
      $drop.find('.dropbtn').text('Домен');
      $drop.find('.dropdown-content').html('');
      if (data.data.length == 0) {
        deleteGetParams(['domain']);
        return;
      }

      let client_id = params.client_id;
      if (!statList[client_id]) statList[client_id] = {};
      data.data.forEach((el, i) => {
        statList[client_id][el.domain] = el.data;

        if (i == 0) {
          let thisDomain = get('domain');
          if (!thisDomain || thisDomain == 'Домен') thisDomain = el.domain;
          $drop.find('.dropbtn').text(thisDomain);
          editGetParams({
            domain: thisDomain
          });
        }
        $drop.find('.dropdown-content').append(`
          <div class="dropdown-item">${el.domain}</div>
        `);
      });
    })
    .catch(xhr => {
      console.error(xhr);
    });
}

function updateTable() {
  $('.table tbody').html('');
    
  let client_id = $('.dropdown-mail .dropbtn').text();
  let domain = $('.dropdown-domain .dropbtn').text();
  if (client_id == 'Почта' || domain == 'Домен') {
    echoError('Пусто!');
    return;
  }
  console.log(statList[client_id][domain]);

  if (statList[client_id][domain].length == 0) {
    echoError('Пусто!');
  }

  statList[client_id][domain].forEach(el => {
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
        <td>${formatDate(el.date)}</td>
        <td>${el.messages_sent}</td>
        <td>${el.complaints}</td>
        <td>${toPercent(el.reputation)}</td>
        <td>${toPercent(el.trend)}</td>
        <td>${el.read}</td>
        <td>${el.deleted_read}</td>
        <td>${el.deleted_unread}</td>
        <td><span class="color ${color}">${toPercent(deliveredPercent)}</span></td>
      </tr>
    `);
  });
}

$('.dropdown').on('click', '.dropdown-item', function() {
  let client_id = $('.dropdown-mail .dropbtn').text().trim();
  let domain = $('.dropdown-domain .dropbtn').text().trim();
  editGetParams({
    mail: client_id,
    domain,
    page: 1
  });

  window.location.reload();
});

$('.filter .link').on('click', function() {
  let period = $(this).data('period');
  let client_id = $('.dropdown-mail .dropbtn').text();
  window.location.href = `domain.php?mail=${client_id}&period=${period}`;
});

$('.pagination').on('click', 'a', function() {
  let count = Number(get('count'));
  let mail = $('.dropdown-mail .dropbtn').text().trim();
  let domain = $('.dropdown-domain .dropbtn').text().trim()

  let url = '';
  if (count)
    url += '&count=' + count;

  url += '&mail=' + mail;

  if ($('.dropdown-domain .dropbtn').length > 0)
    url += '&domain=' + domain;

  let href = $(this).attr('href');
  $(this).attr('href', href + url);
});