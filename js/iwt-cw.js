$(document).ready(function (load) {
  $("#search-form").submit(function (event) {
    var formData = $("form#search-form").serialize();
    var request = $.getJSON('./iwt-cw.php?' + formData);
    $('#output').removeClass('hidden');
    $('#error-message').empty();
    $('#error-message').addClass('hidden');
    $('table#result-table tbody').empty();
    $('table#result-table').addClass('hidden');
    request.done(function (results) {
      if (results.error != undefined) {
        $('#error-message').removeClass('hidden');
        $('#error-message').append('<b>' + results.error + '</b>');
      } else {
        if (results.length == 0) {
          $('#error-message').removeClass('hidden');
          $('#error-message').append('<b> No results found.</b>');
        }
        $.each(results, function (index, resultItem) {
          $('table#result-table').removeClass('hidden');
          $('table#result-table tbody').append(
            '<tr>' +
            '<td>' +
            resultItem["year"] +
            '</td>' +
            '<td>' +
            resultItem["tournament"] +
            '</td>' +
            '<td>' +
            resultItem["winner"] +
            '</td>' +
            '<td>' +
            resultItem["runner-up"] +
            '</td>' +
            '</tr>');
        });
      }
    });
    event.preventDefault();
  });
});

function clearResult(elementID) {
  document.getElementById(elementID).innerHTML = "";
}
