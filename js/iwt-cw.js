$("#search-form").submit(function () {
  var formData = $("form#search-form").serialize();
  var request = $.getJSON('./iwt-cw.php?' + formData);
  request.done(function (results) {
    console.log(results)
    if (results.error != undefined) {
      alert(results.error);
    } else {
      alert(results[0].year)
      // $.each(results, function (i, item) {
      //   var $tr = $('<tr>').append(
      //     $('<td>').text(item.rank),
      //     $('<td>').text(item.content),
      //     $('<td>').text(item.UID)
      //   ).appendTo('#result-table');
      //   console.log($tr.wrap('<p>').html());
    }
  });
});

function clearResult(elementID) {
  document.getElementById(elementID).innerHTML = "";
}
