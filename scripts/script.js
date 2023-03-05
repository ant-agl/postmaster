async function api(file, method, params={}) {
  return new Promise((resolve, reject) => {
    let xhr = new XMLHttpRequest();

    if (method == 'POST') {
      xhr.open(method, 'https://port25.ru/toolbox/' + file);
      xhr.setRequestHeader('Content-type', 'application/json; charset=utf-8');

      let json = JSON.stringify(params);
      xhr.send(json);

    } else if (method == 'GET') {
      let get = '';

      for (let key in params) {
        get += (get == '') ? '?' : '&';
        get += key + '=' + params[key];
      }

      xhr.open(method, 'https://port25.ru/toolbox/' + file + get);
      xhr.send();
    }

    xhr.onload = function() {
      resolve(xhr);
    };
    xhr.onerror = function() {
      reject(xhr);
    }
  });
}

$('.filter .link').on('click', function() {
  let $filter = $(this).closest('.filter');
  $filter.find('.link').removeClass('active');
  $(this).addClass('active');
});

$('.dropdown').on('click', '.dropdown-item', function() {
  let $drop = $(this).closest('.dropdown');
  let val = $(this).text().trim();
  $drop.find('.dropbtn').text(val);
});

function getDate(date = new Date) {
  let d = date.getDate();
  if (d < 10) d = '0' + d;
  let m = date.getMonth() + 1;
  if (m < 10) m = '0' + m;
  let y = date.getFullYear();

  return `${y}-${m}-${d}`;
}

function getPeriod() {
  let page = get('page');
  if (!page) page = 1;
  let count = get('count');
  if (!count) count = 30;

  let period = get('period');
  switch (period) {
    case 'day':
      count = 1;
      break;
    case 'week':
      count = 7;
      break;
    case 'month':
      count = 30;
      break;
  }

  let fromDate = new Date;
  fromDate.setDate(fromDate.getDate() - page * count + 1);
  let from = getDate(fromDate);

  let toDate = new Date;
  toDate.setDate(toDate.getDate() - (page - 1) * count);
  let to = getDate(toDate);

  if ($('.filter .link.active').data('period') == 'day')
    $('.period').text(`(${formatDate(from)})`);
  else 
    $('.period').text(`(${formatDate(from)} - ${formatDate(to)})`);

  return {
    from, to
  };
}

function get(key) {
  let params = {};
  let search = decodeURIComponent(window.location.search.slice(1));
  search.split('&').forEach(el => {
    let t = el.split('=');
    params[t[0]] = t[1];
  })
  return params[key];
}

function formatDate(str) {
  let t = str.split('-');
  let d = t[2];
  let m = t[1];
  let y = t[0];

  return `${d}.${m}.${y}`;
}

function toPercent(num) {
  return Math.round(num * 100) / 100;
}

function echoError(text) {
  deleteError();
  $('.table').after(`
    <p class="echo-error">${text}</p>
  `);
}
function deleteError() {
  $('.echo-error').remove();
}

if ($('.pagination').length > 0) {
  let page = Number(get('page'));
  if (!page) page = 1;
  
  let $pagination = $('.pagination');
  if (page > 1) 
    $pagination.append(`<a href="?page=${page-1}"><</a>`);

  if (page > 2) 
    $pagination.append(`<a href="?page=1">1</a>`);
    
  $pagination.append(`<a href="?page=${page}">${page}</a>`);
  $pagination.append(`<a href="?page=${page+1}">></a>`);
}

function editGetParams(params={}) {
  const url = new URL(document.location);
  const searchParams = url.searchParams;
        for (let key in params) {
    searchParams.set(key, params[key]);
  };
  window.history.pushState({}, '', url.toString());
}

function deleteGetParams(params=[]) {
  const url = new URL(document.location);
  const searchParams = url.searchParams;
  params.forEach(param => {
    searchParams.delete(param);
  });
  window.history.pushState({}, '', url.toString());
}